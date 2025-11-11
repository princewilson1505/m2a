// compile.js - fixed
const fs = require('fs');
const path = require('path');

if (process.argv.length < 3) {
  console.error('Usage: node compile.js Component.svelte');
  process.exit(1);
}

const filename = process.argv[2];
const source = fs.readFileSync(filename, 'utf8');

// Helpers to extract parts
const extractTag = (tag) => {
  const re = new RegExp(`<${tag}([\\s\\S]*?)>([\\s\\S]*?)<\\/${tag}>`, 'i');
  const m = source.match(re);
  return m ? m[2].trim() : '';
};

const scriptContent = extractTag('script');
const styleContent = extractTag('style');
let template = source.replace(/<script[\s\S]*?<\/script>/i, '')
                     .replace(/<style[\s\S]*?<\/style>/i, '')
                     .trim();

// 1) find export let declarations (props)
const propRe = /export\s+let\s+([A-Za-z0-9_$]+)(\s*=\s*([^;]+))?;/g;
let props = [];
let propMatch;
while ((propMatch = propRe.exec(scriptContent)) !== null) {
  const name = propMatch[1];
  const defaultVal = propMatch[3] ? propMatch[3].trim() : 'undefined';
  props.push({name, defaultVal});
}

// 2) normalize `on:event="{handler}"` to data attributes so we can attach later
//    and transform {expr} interpolations into template placeholders
function escapeTemplateLiteral(str) {
  // escape backticks and escape `${` so outer template doesn't interpolate during generation
  return str.replace(/`/g, '\\`').replace(/\$\{/g, '\\${');
}

// convert on:click="{handler}" -> data-on-click="handler"
template = template.replace(/on:([a-zA-Z0-9_-]+)\s*=\s*"\{([^}]+)\}"/g, (m, ev, handler) => {
  return `data-on-${ev}="${handler.trim()}"`;
});

// convert {expr} to ${ctx.expr} for the generated template literal (will be evaluated at runtime)
template = template.replace(/\{([^}]+)\}/g, (_, expr) => {
  return '${' + 'ctx.' + expr.trim().replace(/^\$?/, '') + '}';
});

// escape template for safe embedding in generator output
const templateForOut = escapeTemplateLiteral(template);
const scriptForOut = escapeTemplateLiteral(scriptContent);
const styleForOut = escapeTemplateLiteral(styleContent);

// Build props defaults object from collected props
const defaultsObj = props.length
  ? props.map(p => `${p.name}: ${p.defaultVal}`).join(', ')
  : '';

const componentName = path.basename(filename, path.extname(filename));

// Generate output JS module
const out = `// Compiled from ${filename} (toy svelte-compiler)
${styleContent ? `// style extracted
(function(){
  if (!document.getElementById('style-${componentName}')){
    const s = document.createElement('style'); s.id='style-${componentName}'; s.textContent = \`${styleForOut}\`; document.head.appendChild(s);
  }
})();
` : ''}

const script = (function(){
${scriptForOut}
return (typeof exports !== 'undefined' && exports) || (typeof module !== 'undefined' && module.exports) || {};
})();

export default function create_${componentName}(initial = {}) {
  const defaults = { ${defaultsObj} };
  const ctx = Object.assign({}, defaults, initial);

  const scriptObj = {};
  (function(exports) {
${scriptForOut.split('\n').map(l => '    ' + l).join('\n')}
  })(scriptObj);

  function render() {
    const container = document.createElement('div');
    container.innerHTML = \`${templateForOut}\`;
    // attach event listeners for data-on-*
    container.querySelectorAll('[data-on-click],[data-on-change],[data-on-input],[data-on-submit]').forEach(el => {
      Array.from(el.attributes).forEach(attr => {
        if (attr.name.startsWith('data-on-')) {
          const ev = attr.name.slice('data-on-'.length);
          const handlerName = attr.value.trim();
          const handler = (scriptObj && scriptObj[handlerName]) || (window && window[handlerName]);
          if (typeof handler === 'function') {
            el.addEventListener(ev, handler.bind(null, { ctx }));
          } else {
            // No handler found — ignore
          }
        }
      });
    });
    return container.firstElementChild || container;
  }

  let root = null;

  function mount(target) {
    if (!target) throw new Error('mount target required');
    root = render();
    target.appendChild(root);
  }

  function update(newProps = {}) {
    Object.assign(ctx, newProps);
    if (root && root.parentNode) {
      const parent = root.parentNode;
      parent.removeChild(root);
      root = render();
      parent.appendChild(root);
    }
  }

  function destroy() {
    if (root && root.parentNode) root.parentNode.removeChild(root);
    root = null;
  }

  return { mount, update, destroy, ctx, script: scriptObj };
}
`;

// write debug output and emit compiled module to stdout
try {
  fs.writeFileSync('__last_compiled_debug.js', out, 'utf8');
} catch(e) {
  // ignore write errors for environments where write is not allowed
}
process.stdout.write(out);
