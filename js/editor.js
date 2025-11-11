let editor;
let currentLanguage = 'html';

// Initialize CodeMirror safely
function initializeEditor() {
    if (typeof CodeMirror === 'undefined') {
        console.error('CodeMirror not loaded.');
        return;
    }

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
        extraKeys: { "Ctrl-Space": "autocomplete" }
    });

    // Wait for the editor to fully render
    setTimeout(initializeLanguage, 100);
}

// Use DOMContentLoaded only (avoid double init)
document.addEventListener('DOMContentLoaded', initializeEditor);

// Detect language from URL or set default
function initializeLanguage() {
    const urlParams = new URLSearchParams(window.location.search);
    const langParam = urlParams.get('lang');
    const languageSelect = document.getElementById('languageSelect');

    if (langParam && languageSelect) {
        languageSelect.value = langParam;
        changeLanguage();
    } else {
        setDefaultContent();
    }
}

// Switch language
function changeLanguage() {
    const languageSelect = document.getElementById('languageSelect');
    if (!languageSelect) return;

    currentLanguage = languageSelect.value;
    const modeMap = {
        html: 'text/html',
        css: 'text/css',
        javascript: 'text/javascript',
        php: 'application/x-httpd-php',
        svelte: 'text/html'
    };

    editor.setOption('mode', modeMap[currentLanguage] || 'text/plain');
    toggleSvelteUI(currentLanguage === 'svelte');
    setDefaultContent();
}

// Toggle Svelte-specific UI
function toggleSvelteUI(isSvelte) {
    const testBtn = document.getElementById('testSvelteBtn');
    const statusBadge = document.getElementById('svelteStatus');
    const helpText = document.getElementById('svelteHelp');

    [testBtn, statusBadge, helpText].forEach(el => {
        if (el) el.style.display = isSvelte ? 'inline-block' : 'none';
    });

    if (isSvelte && statusBadge) {
        if (typeof svelte !== 'undefined') {
            statusBadge.className = 'ms-2 badge bg-success';
            statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Svelte Ready';
        } else {
            statusBadge.className = 'ms-2 badge bg-warning';
            statusBadge.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Loading...';
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/svelte@4.2.8/compiler.js';
            script.onload = () => {
                statusBadge.className = 'ms-2 badge bg-success';
                statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Svelte Ready';
            };
            script.onerror = () => {
                statusBadge.className = 'ms-2 badge bg-danger';
                statusBadge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Load Error';
            };
            document.head.appendChild(script);
        }
    }
}

// Default code templates
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
</script>

<button on:click={() => count++}>Clicked {count} times</button>`
    };
    editor.setValue(defaults[currentLanguage] || '');
}

// --- MAIN RUN FUNCTION ---
function runCode() {
    const code = editor.getValue();
    const outputFrame = document.getElementById('output');
    const consoleBox = document.getElementById('console');

    if (!outputFrame || !consoleBox) return;
    consoleBox.innerHTML = '';

    switch (currentLanguage) {
        case 'html':
            showOutput(code);
            break;

        case 'css':
            showOutput(`<style>${code}</style><h1>CSS Preview</h1><p>Sample content</p>`);
            break;

        case 'javascript':
            runJS(code);
            break;

        case 'php':
            runPHP(code);
            break;

        case 'svelte':
            runSvelte(code);
            break;
    }
}

// --- Helper functions for each language ---
function showOutput(html) {
    const iframe = document.getElementById('output');
    const consoleBox = document.getElementById('console');
    iframe.style.display = 'block';
    consoleBox.style.display = 'none';
    iframe.contentWindow.document.open();
    iframe.contentWindow.document.write(html);
    iframe.contentWindow.document.close();
}

function runJS(code) {
    const consoleBox = document.getElementById('console');
    consoleBox.style.display = 'block';
    document.getElementById('output').style.display = 'none';

    try {
        const sandbox = {
            console: {
                log: (...args) => {
                    consoleBox.innerHTML += args.map(a =>
                        typeof a === 'object' ? JSON.stringify(a, null, 2) : String(a)
                    ).join(' ') + '<br>';
                }
            },
            Math, Date, Array, Object, String, Number, Boolean
        };
        new Function('sandbox', `with (sandbox) { ${code} }`)(sandbox);
    } catch (err) {
        consoleBox.innerHTML = `<span style="color:red">Error: ${err.message}</span>`;
    }
}

function runPHP(code) {
    const consoleBox = document.getElementById('console');
    consoleBox.style.display = 'block';
    document.getElementById('output').style.display = 'none';
    consoleBox.innerHTML = 'Executing PHP code...';

    // Use relative path so it works in XAMPP (htdocs/project/php_handler.php)
    fetch('./php_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'code=' + encodeURIComponent(code)
    })
        .then(res => {
            if (!res.ok) throw new Error('PHP handler not reachable. HTTP ' + res.status);
            return res.text();
        })
        .then(result => {
            consoleBox.innerHTML = result.trim()
                ? result.replace(/\n/g, '<br>')
                : '✅ Code executed successfully but produced no output.';
        })
        .catch(err => {
            consoleBox.innerHTML = `
                <span style="color:red;">Error executing PHP:</span><br>${err.message}<br><br>
                <b>Troubleshooting:</b><br>
                1. Make sure Apache in XAMPP is running.<br>
                2. Verify php_handler.php is in the same folder.<br>
                3. Check XAMPP logs if issue persists.
            `;
        });
}

function runSvelte(code) {
    const iframe = document.getElementById('output');
    const consoleBox = document.getElementById('console');
    iframe.style.display = 'block';
    consoleBox.style.display = 'none';

    // Build iframe content as a Blob (avoids srcdoc escaping issues and accidental </script> termination)
    const encoded = btoa(unescape(encodeURIComponent(code)));
    const iframeHtml = `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Svelte Output</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;margin:0;padding:20px} .error{color:#a00;background:#ffe6e6;padding:10px;border-radius:4px;font-family:monospace;}</style>
</head>
<body>
  <div id="root">Loading...</div>
  <script>
  (function(){
    const encoded = '${encoded}';
    const source = decodeURIComponent(escape(atob(encoded)));

    function loadScript(src){
      return new Promise((resolve,reject)=>{
        const s = document.createElement('script'); s.src = src; s.async = true;
        s.onload = () => resolve(); s.onerror = () => reject(new Error('Failed to load ' + src));
        document.head.appendChild(s);
      });
    }

    (async function(){
      const root = document.getElementById('root');
      try {
        // Try local compiler first, then CDNs
        const candidates = ['/assets/svelte/compiler.js', 'https://cdn.jsdelivr.net/npm/svelte@4/compiler.js', 'https://unpkg.com/svelte@4/compiler.js'];
        let loaded = false;
        for (const c of candidates) {
          try { await loadScript(c); if (typeof svelte !== 'undefined') { loaded = true; break; } } catch(e) { /* ignore */ }
        }
        if (!loaded || typeof svelte === 'undefined') throw new Error('Svelte compiler not available (tried local + CDNs)');

        const result = svelte.compile(source, { dev: true, format: 'iife', name: 'App' });
        const fn = new Function('exports', result.js.code + '; return App;');
        const App = fn({});
        root.innerHTML = '';
        new App({ target: root });
      } catch (err) {
        root.innerHTML = '<div class="error"><strong>Svelte Error:</strong><br>' + (err.message || err.toString()).replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</div>';
        console.error('Svelte Error:', err);
      }
    })();
  })();
  </script>
</body>
</html>`;

    const blob = new Blob([iframeHtml], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    iframe.src = url;
    // revoke the blob after it loads to free memory
    iframe.onload = () => { try { URL.revokeObjectURL(url); } catch(e){} };
}
