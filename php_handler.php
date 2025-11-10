<?php
// Simple PHP code execution handler for local development
// ⚠️ WARNING: This executes arbitrary PHP code submitted by the client.
// Only use this on a local development machine you trust.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Safety: only allow requests from localhost (development only)
$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
$allowed = ['127.0.0.1', '::1', '::ffff:127.0.0.1'];
if (!in_array($remoteAddr, $allowed, true)) {
    http_response_code(403);
    echo 'Forbidden: php_handler is restricted to localhost.';
    exit;
}

// Optional: enable CORS if your frontend runs on another port (e.g., localhost:5173)
// header('Access-Control-Allow-Origin: http://localhost:5173');

$code = $_POST['code'] ?? '';
if (trim($code) === '') {
    echo '';
    exit;
}

// Show errors directly (development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add basic safety limits
set_time_limit(3); // Limit execution time (seconds)
ini_set('memory_limit', '64M'); // Limit memory usage

ob_start();

// Create a temporary file for isolated execution
$tmpFile = tempnam(sys_get_temp_dir(), 'phprun_');
if ($tmpFile === false) {
    http_response_code(500);
    echo 'Failed to create temporary file.';
    exit;
}

// Ensure .php extension so include works properly
$tmpPhp = $tmpFile . '.php';

// Determine if code should be wrapped in PHP tags
$trim = ltrim($code);
if (stripos($code, '<?') !== false || strpos($trim, '<') === 0) {
    $fileContent = $code;
} else {
    $fileContent = "<?php\n" . $code . "\n?>";
}

// Write the code to temporary file
file_put_contents($tmpPhp, $fileContent);

try {
    include $tmpPhp;
} catch (Throwable $e) {
    echo "Fatal error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
} finally {
    // Cleanup temp files even if execution fails
    @unlink($tmpPhp);
    @unlink($tmpFile);
}

$output = ob_get_clean();

// Return plain text output for easy display
header('Content-Type: text/plain; charset=utf-8');
echo $output;
?>
