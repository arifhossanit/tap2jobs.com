const appTextEditorConfig = {
    base_url: '/tinymce',
    suffix: '.min',
    license_key: 'gpl',
    height: 360,
    menubar: false,
    plugins: 'link lists table code',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | bullist numlist | link table | removeformat code',
    branding: false,
    promotion: false,
};

function appEditorText(html) {
    const node = document.createElement('div');
    node.innerHTML = html || '';
    return node.textContent || '';
}

function appEditorEscape(text) {
    const node = document.createElement('div');
    node.textContent = text || '';
    return node.innerHTML;
}

class AppTextEditor {
    constructor(selector, options = {}) {
        const source = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!source) throw new Error(`Editor element not found: ${selector}`);

        this.editor = null;
        this.callbacks = [];
        this.html = source.value || source.innerHTML || '';
        this.container = this.prepareTarget(source);
        this.root = {};
        Object.defineProperty(this.root, 'innerHTML', {
            get: () => this.getHtml(),
            set: (html) => this.setHtml(html),
        });
        this.clipboard = { dangerouslyPasteHTML: (...args) => this.setHtml(args.at(-1)) };

        tinymce.init({
            ...appTextEditorConfig,
            target: this.container,
            placeholder: options.placeholder || '',
            setup: (editor) => {
                this.editor = editor;
                editor.on('init', () => editor.setContent(this.html));
                editor.on('change input undo redo', () => {
                    this.html = editor.getContent();
                    this.container.value = this.html;
                    this.callbacks.forEach((callback) => callback(null, null, 'user'));
                });
            },
        });
    }

    prepareTarget(source) {
        if (source.tagName === 'TEXTAREA') return source;
        const textarea = document.createElement('textarea');
        [...source.attributes].forEach((attribute) => textarea.setAttribute(attribute.name, attribute.value));
        textarea.value = this.html;
        source.replaceWith(textarea);
        return textarea;
    }

    getHtml() { return this.editor ? this.editor.getContent() : this.html; }
    setHtml(html) {
        this.html = html || '';
        this.container.value = this.html;
        if (this.editor) this.editor.setContent(this.html);
    }
    getText() { return this.editor ? this.editor.getContent({format: 'text'}) : appEditorText(this.html); }
    setContents(contents = []) {
        const text = Array.isArray(contents) ? contents.map((item) => item.insert || '').join('').replace(/^\n$/, '') : '';
        this.setHtml(appEditorEscape(text));
    }
    on(event, callback) {
        if (event === 'text-change' && typeof callback === 'function') this.callbacks.push(callback);
        return this;
    }
    focus() { this.editor ? this.editor.focus() : this.container.focus(); }
    deleteText(index) { this.setHtml(appEditorEscape(this.getText().slice(0, index))); }
}

window.AppTextEditor = AppTextEditor;
window.syncAppTextEditors = () => tinymce.triggerSave();
