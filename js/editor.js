let editor;
let currentLanguage = 'html';

// Initialize CodeMirror
function initializeEditor() {
    editor = CodeMirror.fromTextArea(document.getElementById("codeEditor"), {
        lineNumbers: true,
        theme: 'dracula',
        mode: 'text/html',
        indentUnit: 4,
        tabSize: 4,
        lineWrapping: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        matchBrackets: true,
        extraKeys: {"Ctrl-Space": "autocomplete"}
    });

    // Initialize language after CodeMirror is set up
    setTimeout(initializeLanguage, 50);
}

// Wait for both DOM and window load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEditor);
} else {
    window.addEventListener('load', initializeEditor);
}

// Function to initialize language based on URL parameter
function initializeLanguage() {
    // Check for language parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const langParam = urlParams.get('lang');
    
    if (langParam) {
        // Set the language select to the parameter value
        const languageSelect = document.getElementById('languageSelect');
        
        if (languageSelect) {
            languageSelect.value = langParam;
            // Change to the specified language
            changeLanguage();
        } else {
            // If element not found, set default content and try again after a short delay
            setDefaultContent();
            setTimeout(() => {
                const retrySelect = document.getElementById('languageSelect');
                if (retrySelect) {
                    retrySelect.value = langParam;
                    changeLanguage();
                }
            }, 100);
        }
    } else {
        // Set default content
        setDefaultContent();
    }
}

// Change language mode
function changeLanguage() {
    currentLanguage = document.getElementById('languageSelect').value;
    
    // Update CodeMirror mode
    switch(currentLanguage) {
        case 'html':
            editor.setOption('mode', 'text/html');
            break;
        case 'css':
            editor.setOption('mode', 'text/css');
            break;
        case 'javascript':
            editor.setOption('mode', 'text/javascript');
            break;
        case 'php':
            editor.setOption('mode', 'application/x-httpd-php');
            break;
        case 'svelte':
            editor.setOption('mode', 'text/html');
            break;
    }
    
    // Show/hide Svelte test button and status
    const testBtn = document.getElementById('testSvelteBtn');
    const statusBadge = document.getElementById('svelteStatus');
    const helpText = document.getElementById('svelteHelp');
    
    if (currentLanguage === 'svelte') {
        if (testBtn) testBtn.style.display = 'inline-block';
        if (helpText) helpText.style.display = 'block';
        if (statusBadge) {
            statusBadge.style.display = 'inline-block';
            // Check if Svelte is available
            if (typeof svelte !== 'undefined') {
                statusBadge.className = 'ms-2 badge bg-success';
                statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Svelte Ready';
            } else {
                statusBadge.className = 'ms-2 badge bg-warning';
                statusBadge.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Svelte Loading...';
                // Try to load Svelte
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/svelte@4.2.8/compiler.js';
                script.onload = () => {
                    statusBadge.className = 'ms-2 badge bg-success';
                    statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Svelte Ready';
                };
                script.onerror = () => {
                    statusBadge.className = 'ms-2 badge bg-danger';
                    statusBadge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Svelte Error';
                };
                document.head.appendChild(script);
            }
        }
    } else {
        if (testBtn) testBtn.style.display = 'none';
        if (statusBadge) statusBadge.style.display = 'none';
        if (helpText) helpText.style.display = 'none';
    }
    
    setDefaultContent();
}

// Set default content based on selected language
function setDefaultContent() {
    const defaults = {
        html: `<!DOCTYPE html>
<html>
<head>
    <title>Hello World</title>
</head>
<body>
    <h1>Hello, World!</h1>
</body>
</html>`,
        css: `body {
    background-color: #f0f0f0;
    font-family: Arial, sans-serif;
}

h1 {
    color: blue;
    text-align: center;
}`,
        javascript: `console.log("Hello, World!");`,
        php: `<?php
echo "Hello, World!";
?>`,
        svelte: `<script>
    let count = 0;
    let name = 'World';
    
    function increment() {
        count += 1;
    }
    
    function reset() {
        count = 0;
    }
</script>

<div class="greeting">
    <h1>Hello {name}!</h1>
    <p>Welcome to Svelte</p>
    
    <div class="counter">
        <p>Count: {count}</p>
        <button on:click={increment}>Increment</button>
        <button on:click={reset}>Reset</button>
    </div>
    
    <div class="input-section">
        <label for="name">Your name:</label>
        <input id="name" bind:value={name} placeholder="Enter your name">
    </div>
</div>

<style>
    .greeting {
        padding: 2em;
        text-align: center;
        font-family: Arial, sans-serif;
        max-width: 500px;
        margin: 0 auto;
    }
    
    h1 {
        color: #ff3e00;
        margin-bottom: 0.5em;
    }
    
    p {
        color: #333;
        margin-bottom: 1em;
    }
    
    .counter {
        margin: 2em 0;
        padding: 1em;
        background: #f5f5f5;
        border-radius: 8px;
    }
    
    button {
        background: #ff3e00;
        color: white;
        border: none;
        padding: 0.5em 1em;
        margin: 0 0.5em;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1em;
    }
    
    button:hover {
        background: #e03500;
    }
    
    .input-section {
        margin-top: 2em;
    }
    
    label {
        display: block;
        margin-bottom: 0.5em;
        color: #333;
    }
    
    input {
        padding: 0.5em;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1em;
        width: 200px;
    }
    
    input:focus {
        outline: none;
        border-color: #ff3e00;
    }
</style>`
    };

    editor.setValue(defaults[currentLanguage] || '');
}

// Run the code
function runCode() {
    const code = editor.getValue();
    const output = document.getElementById('output');
    const console = document.getElementById('console');
    
    // Clear previous output
    console.innerHTML = '';
    
    switch(currentLanguage) {
        case 'html':
            output.style.display = 'block';
            console.style.display = 'none';
            output.contentWindow.document.open();
            output.contentWindow.document.write(code);
            output.contentWindow.document.close();
            break;
            
        case 'css':
            output.style.display = 'block';
            console.style.display = 'none';
            output.contentWindow.document.open();
            output.contentWindow.document.write(`
                <style>${code}</style>
                <div class="preview">
                    <h1>Sample Heading</h1>
                    <p>This is a paragraph to preview your CSS.</p>
                    <button>Sample Button</button>
                </div>
            `);
            output.contentWindow.document.close();
            break;
            
        case 'javascript':
            output.style.display = 'none';
            console.style.display = 'block';
            try {
                // Create a sandboxed environment
                const sandbox = {
                    console: {
                        log: (...args) => {
                            document.getElementById('console').innerHTML += 
                                args.map(arg => 
                                    typeof arg === 'object' ? JSON.stringify(arg, null, 2) : String(arg)
                                ).join(' ') + '<br>';
                        }
                    },
                    // Add other safe globals as needed
                    Math: Math,
                    Date: Date,
                    Array: Array,
                    Object: Object,
                    String: String,
                    Number: Number,
                    Boolean: Boolean
                };

                // Create a function from the code
                const fn = new Function('sandbox', `
                    with (sandbox) {
                        ${code}
                    }
                `);

                // Execute in sandbox
                fn(sandbox);
            } catch (error) {
                document.getElementById('console').innerHTML = 
                    `<span style="color: red">Error: ${error.message}</span>`;
            }
            break;
            
        case 'php':
            output.style.display = 'none';
            console.style.display = 'block';
            document.getElementById('console').innerHTML = 'Executing PHP code...';
            
            // Send to PHP handler using absolute path
            // send to local php handler in this project
            fetch('php_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'code=' + encodeURIComponent(code)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(result => {
                if (result.trim() === '') {
                    document.getElementById('console').innerHTML = 'Code executed successfully but produced no output.';
                } else {
                    document.getElementById('console').innerHTML = result.replace(/\n/g, '<br>');
                }
            })
            .catch(error => {
                document.getElementById('console').innerHTML = `Error executing PHP code:<br>
${error.message}<br><br>
Troubleshooting steps:<br>
1. Make sure XAMPP Apache is running (check XAMPP Control Panel)<br>
2. Try accessing <a href="/capstone/test.php" target="_blank">test.php</a> to verify PHP is working<br>
3. Check XAMPP error logs for more details<br>
                4. Verify the URL: ${window.location.origin}/m2a/php_handler.php`;
            });
            break;
            
        case 'svelte':
            output.style.display = 'block';
            console.style.display = 'none';

            const sourceB64 = btoa(unescape(encodeURIComponent(code)));
            output.contentWindow.document.open();
            output.contentWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Svelte Output</title>
                    <script>
                        window.loadSvelteCompiler = function() {
                            return new Promise((resolve, reject) => {
                                const sources = [
                                    'https://unpkg.com/svelte@4.2.8/compiler.js',
                                    'https://cdn.jsdelivr.net/npm/svelte@4.2.8/compiler.js',
                                    'https://cdnjs.cloudflare.com/ajax/libs/svelte/4.2.8/compiler.js'
                                ];
                                let i = 0;
                                function next() {
                                    if (i >= sources.length) return reject(new Error('Failed to load Svelte compiler'));
                                    const s = document.createElement('script');
                                    s.src = sources[i++];
                                    s.onload = () => typeof svelte !== 'undefined' ? resolve() : next();
                                    s.onerror = next;
                                    document.head.appendChild(s);
                                }
                                next();
                            });
                        };
                    </script>
                    <style>
                        .error { color: red; padding: 10px; border: 1px solid red; margin: 10px; background: #fff0f0; font-family: monospace; white-space: pre-wrap; }
                        .loading { color: #888; padding: 10px; margin: 10px; }
                        .success { color: #4CAF50; padding: 10px; margin: 10px; background: #f0fff0; border: 1px solid #4CAF50; }
                    </style>
                </head>
                <body>
                    <div id="app" class="loading">Loading Svelte compiler...</div>
                    <script type="module">
                        const source = decodeURIComponent(escape(atob('${sourceB64}')));
                        const appDiv = document.getElementById('app');
                        try {
                            if (typeof svelte === 'undefined') {
                                appDiv.textContent = 'Loading Svelte compiler...';
                                await window.loadSvelteCompiler();
                            }
                            const result = svelte.compile(source, { dev: true, format: 'esm', name: 'SvelteApp' });
                            if (result.css && result.css.code) {
                                const style = document.createElement('style');
                                style.textContent = result.css.code;
                                document.head.appendChild(style);
                            }
                            let jsCode = result.js.code;
                            jsCode = jsCode
                                .replace(/from ['\"]svelte\\/internal['\"]/g, 'from "https://cdn.jsdelivr.net/npm/svelte@4.2.8/internal/index.mjs"')
                                .replace(/from ['\"]svelte['\"]/g, 'from "https://cdn.jsdelivr.net/npm/svelte@4.2.8/index.mjs"');

                            const moduleCode = jsCode;
                            const blobUrl = URL.createObjectURL(new Blob([moduleCode], { type: 'text/javascript' }));

                            appDiv.innerHTML = '';
                            appDiv.className = '';

                            const mod = await import(blobUrl);
                            const Component = mod.default || mod.SvelteApp || mod.App;
                            if (!Component) throw new Error('Failed to load compiled component');
                            new Component({ target: document.getElementById('app') });
                        } catch (error) {
                            appDiv.className = 'error';
                            appDiv.textContent = 'Compilation Error:\n' + error.toString();
                        }
                    </script>
                </body>
                </html>
            `);
            output.contentWindow.document.close();
            break;
    }
}

// Test Svelte compiler functionality
function testSvelteCompiler() {
    const console = document.getElementById('console');
    const output = document.getElementById('output');
    
    // Show console and hide output for test results
    output.style.display = 'none';
    console.style.display = 'block';
    console.innerHTML = '<span style="color: #ffd700;">Testing Svelte compiler...</span><br>';
    
    // Test Svelte compiler with a simple component
    const testCode = `
<script>
    let count = 0;
    let name = 'Test';
</script>

<div class="test-component">
    <h2>Hello {name}!</h2>
    <p>Count: {count}</p>
    <button on:click={() => count++}>Increment</button>
</div>

<style>
    .test-component {
        padding: 20px;
        text-align: center;
        background: #f0f0f0;
        border-radius: 8px;
    }
    button {
        background: #ff3e00;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>`;

    try {
        // Check if Svelte compiler is available
        if (typeof svelte === 'undefined') {
            console.innerHTML += '<span style="color: red;">❌ Svelte compiler not loaded</span><br>';
            console.innerHTML += '<span style="color: #ffa500;">Attempting to load Svelte compiler...</span><br>';
            
            // Try to load Svelte compiler
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/svelte@4.2.8/compiler.js';
            script.onload = () => {
                console.innerHTML += '<span style="color: green;">✅ Svelte compiler loaded successfully</span><br>';
                runSvelteTest();
            };
            script.onerror = () => {
                console.innerHTML += '<span style="color: red;">❌ Failed to load Svelte compiler</span><br>';
                console.innerHTML += '<span style="color: #ffa500;">Please check your internet connection</span><br>';
            };
            document.head.appendChild(script);
        } else {
            console.innerHTML += '<span style="color: green;">✅ Svelte compiler already loaded</span><br>';
            runSvelteTest();
        }
        
        function runSvelteTest() {
            try {
                // Test compilation
                const result = svelte.compile(testCode, {
                    dev: true,
                    format: 'esm',
                    name: 'TestComponent'
                });
                
                console.innerHTML += '<span style="color: green;">✅ Svelte compilation successful</span><br>';
                console.innerHTML += `<span style="color: #87ceeb;">📊 Compilation stats:</span><br>`;
                console.innerHTML += `<span style="color: #87ceeb;">   - JavaScript: ${result.js ? result.js.code.length : 0} characters</span><br>`;
                console.innerHTML += `<span style="color: #87ceeb;">   - CSS: ${result.css ? result.css.code.length : 0} characters</span><br>`;
                
                // Test if the compiled code has the expected structure
                if (result.js && result.js.code.includes('createEventDispatcher')) {
                    console.innerHTML += '<span style="color: green;">✅ Compiled code structure looks correct</span><br>';
                } else {
                    console.innerHTML += '<span style="color: orange;">⚠️ Compiled code structure may be incomplete</span><br>';
                }
                
                console.innerHTML += '<br><span style="color: #32cd32;">🎉 Svelte compiler test completed successfully!</span><br>';
                console.innerHTML += '<span style="color: #87ceeb;">You can now write and run Svelte components.</span><br>';
                
            } catch (error) {
                console.innerHTML += `<span style="color: red;">❌ Compilation failed: ${error.message}</span><br>`;
                console.innerHTML += `<span style="color: #ffa500;">Stack trace: ${error.stack}</span><br>`;
            }
        }
        
    } catch (error) {
        console.innerHTML += `<span style="color: red;">❌ Test failed: ${error.message}</span><br>`;
    }
}