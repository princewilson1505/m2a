<?php require_once __DIR__ . '/config.php'; ?>
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
        <nav id="navbar-example2" class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">
      <div class="navbar-brand h1 mb-0" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">
        <i class="bi bi-braces-asterisk"></i> <span class="text-danger">M3a</span>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCompilers" aria-controls="navbarCompilers" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

        <div class="title container">
            <h1 class="p-4" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">M3a Compiler</h1>
        </div>

        <div class="card container shadow-lg bg-light p-4">
            <div class="row mb-3">
                <div class="col">Select Your Language:</div>
                    <select class="form-select" id="languageSelect" onchange="changeLanguage()">
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="javascript">JavaScript</option>
                        <option value="php">PHP</option>
                        <option value="svelte" disabled>Svelte</option>
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


