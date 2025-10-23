<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    // Security: disable dangerous functions and tags
    $disabled = ['shell_exec','exec','system','passthru','proc_open','popen','curl','fopen','file_put_contents','unlink','rename','require','include'];
    foreach ($disabled as $func) {
        if (stripos($code, $func) !== false) {
            exit("⚠️ Function '$func' is not allowed for security reasons.");
        }
    }

    // Create a temporary PHP file
    $tmpFile = tempnam(sys_get_temp_dir(), 'phpcode_') . '.php';
    file_put_contents($tmpFile, "" . $code);

    // Capture output safely
    ob_start();
    include $tmpFile;
    $output = ob_get_clean();

    // Delete temp file
    unlink($tmpFile);

    // Return result
    echo $output ?: "(No output)";
}
?>
