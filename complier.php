<?php require_once __DIR__ . '/config.php';
// Check for local Svelte compiler and show a helpful message when missing
$localCompiler = __DIR__ . '/assets/svelte/compiler.js';
$showCompilerAlert = !file_exists($localCompiler);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Multi-Language Code Editor</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/editor.js?v=2"></script>
        <style>
            .title { text-align: center; margin-top: 20px; }
            .content { justify-content: center; align-items: center; margin-top: 20px; }
            .CodeMirror { height: 300px; border: 1px solid #ced4da; font-size: 14px; font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', monospace; }
            .output-container { display: flex; flex-direction: column; gap: 10px; }
            .output-frame { width: 100%; height: 300px; border: 1px solid #ced4da; background: white; }
            .console-output { width: 100%; height: 100px; background: #1e1e1e; color: #fff; padding: 10px; font-family: monospace; overflow-y: auto; border: 1px solid #ced4da; }
            .greeting { padding: 1em; text-align: center; }
        </style>
    </head>
    <body>
        <div class="title container">
            <h1 class="p-2" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">M2a Compiler</h1>
        </div>

        <div class="card container-fluid shadow-lg bg-light p-4">
            <?php if (!empty($showCompilerAlert)): ?>
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">Svelte compiler not found locally</h5>
                    <p>The in-browser Svelte compiler is not available as a local file. Edge's Tracking Prevention may block loading the compiler from third-party CDNs. To ensure Svelte runs reliably in this editor, download the compiler to <code>/assets/svelte/compiler.js</code>.</p>
                    <hr>
                    <p class="mb-0"><strong>Quick PowerShell command (run in project root):</strong></p>
                    <pre class="small">New-Item -ItemType Directory -Force -Path .\assets\svelte\; Invoke-WebRequest 'https://cdn.jsdelivr.net/npm/svelte@4/compiler.js' -OutFile .\assets\svelte\compiler.js</pre>
                </div>
            <?php endif; ?>
            <div class="row mb-3">
                <div class="col">Select Your Language:</div>
                    <select class="form-select" id="languageSelect" onchange="changeLanguage()">
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="javascript">JavaScript</option>
                        <option value="php">PHP</option>
                        <option value="svelte">Svelte</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" onclick="runCode()">
                        Run <i class="bi bi-play-fill"></i>
                    </button>
                </div>
            </div>

            <div class="row align-items-center m-5 border rounded p-3 bg-dark">
                <div class="col-md-6">
                    <h4 class="mb-3 text-light">Input:</h4>
                    <textarea id="codeEditor"></textarea>
                </div>
                <div class="col-md-6">
                    <h4 class="mb-3 text-light">Output:</h4>
                    <div class="output-container">
                        <iframe id="output" class="output-frame"></iframe>
                    <h5 class="mb-2 text-light">Console:</h5>
                        <div id="console" class="console-output"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>


