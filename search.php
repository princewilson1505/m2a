<?php
// search.php - Search lessons by title/category
session_start();
require_once __DIR__ . '/config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get search query and format
$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$format = isset($_POST['format']) ? strtolower($_POST['format']) : 'json';

// Validate query
if ($q === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Query parameter "q" is required']);
    exit;
}

// Sanitize and prepare search term
$searchTerm = '%' . $conn->real_escape_string($q) . '%';

// Search lessons and any matching sections (heading/content/code_block).
// We use a correlated subquery to pull a single matching section snippet per lesson when available.
$query = "
SELECT l.id, l.title, l.category, l.date_created,
    (
        SELECT CONCAT(ls.id, '::', COALESCE(NULLIF(ls.heading, ''), NULLIF(ls.content, ''), NULLIF(ls.code_block, '')))
        FROM lesson_sections ls
        WHERE ls.lesson_id = l.id AND (ls.heading LIKE ? OR ls.content LIKE ? OR ls.code_block LIKE ?)
        LIMIT 1
    ) AS snippet_with_id
FROM lessons l
WHERE l.title LIKE ? OR l.category LIKE ? OR EXISTS (
  SELECT 1 FROM lesson_sections s WHERE s.lesson_id = l.id AND (s.heading LIKE ? OR s.content LIKE ? OR s.code_block LIKE ?)
)
ORDER BY l.date_created DESC
LIMIT 20
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
    exit;
}

// Bind parameters: snippet subquery (3), title & category (2), exists subquery (3) => total 8
$stmt->bind_param('ssssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Build results array
$results = [];
while ($row = $result->fetch_assoc()) {
    $snippet = '';
    $section_id = null;

    if (!empty($row['snippet_with_id'])) {
        // We stored "id::snippet" in the subquery; parse it
        $parts = explode('::', $row['snippet_with_id'], 2);
        if (count($parts) === 2) {
            $section_id = (int)$parts[0];
            $snippet = $parts[1];
        } else {
            $snippet = $row['snippet_with_id'];
        }
    } else {
        $snippet = 'Category: ' . $row['category'];
    }

    // Trim snippet to 200 chars
    $snippet = mb_substr(strip_tags($snippet), 0, 50);

    $results[] = [
        'id' => (int)$row['id'],
        'section_id' => $section_id,
        'title' => htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'),
        'category' => htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'),
        'created_at' => $row['date_created'],
        'snippet' => $snippet
    ];
}

$stmt->close();

// Return JSON response
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => true,
    'query' => htmlspecialchars($q, ENT_QUOTES, 'UTF-8'),
    'total' => count($results),
    'results' => $results
], JSON_UNESCAPED_UNICODE);

?>
