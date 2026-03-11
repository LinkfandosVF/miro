<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Miro</title>
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <link id="theme-link" rel="stylesheet" href="">
    <style>
        :root { --dynamic-bg: #1e1e1e; }
         @keyframes boing { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.12); } }
        .fade-out { opacity: 0; transition: opacity 0.8s ease-out; pointer-events: none; }
        body { display: flex; height: 100vh; margin: 0; font-family: 'Segoe UI', sans-serif; transition: background 0.3s, color 0.3s; overflow: hidden; }
        body.theme-light { background: #ffffff; color: #1e1e1e; }
        body.theme-light #sidebar { background: #f3f3f3; border-right: 1px solid #ccc; }
        body.theme-light .file-item { color: #1e1e1e; }
        body.theme-light .folder { color: #555; }
        body.theme-light .sidebar-brand { color: #005fb8; }
        body.theme-light .sidebar-footer { border-top: 1px solid #ccc; color: #1e1e1e; }
        body.theme-light #help-modal { background: #ffffff; color: #1e1e1e; border-color: #ccc; }
        body.theme-light .CodeMirror-cursor { border-left: 2px solid #27beff !important; visibility: visible !important; }
        body.theme-light #ctx-menu { background: #ffffff; border-color: #ccc; }
        body.theme-light #ctx-menu div { color: #1e1e1e; }
        body.theme-dark { background: #1e1e1e; color: #d4d4d4; }
        body.theme-dark #sidebar { background: #252526; border-right: 1px solid #333; }
        body.theme-dark .file-item { color: #d4d4d4; }
        body.theme-dark .folder { color: #888; }
        body.theme-dark .CodeMirror-cursor { border-left: 2px solid #27beff !important; visibility: visible !important; }
        body.theme-dark #help-modal { background: #252526; color: #d4d4d4; border-color: #444; }
        body.theme-dark #ctx-menu { background: #252526; border-color: #444; }
        body.theme-dark #ctx-menu div { color: #d4d4d4; }
        #splash { position: fixed; top:0; left:0; width:100%; height:100%; background: #1e1e1e; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; }
        .logo-splash { width: 200px; object-fit: contain; margin-bottom: 20px; animation: boing 2.5s infinite ease-in-out; }
        #sidebar { width: 300px; display: flex; flex-direction: column; justify-content: space-between; z-index: 10; }
        .sidebar-brand { padding: 15px; font-size: 1.1rem; font-weight: bold; color: #007acc; border-bottom: 1px solid rgba(128,128,128,0.2); display: flex; align-items: center; gap: 10px; }
        .sidebar-header { padding: 15px; border-bottom: 1px solid rgba(128,128,128,0.2); display: flex; flex-direction: column; gap: 8px; }
        #file-tree { flex: 1; overflow-y: auto; padding: 10px; }
        .sidebar-footer { padding: 12px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(128,128,128,0.2); }
        .github-link a { color: inherit; opacity: 0.7; text-decoration: none; font-size: 12px; transition: opacity 0.2s; display: flex; align-items: center; gap: 5px; }
        .github-link a:hover { opacity: 1; }
        .help-btn { background: #007acc; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; border: none; font-weight: bold; }
        #help-modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 25px; border-radius: 8px; z-index: 10000; width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,0.4); border-style: solid; border-width: 1px; }
        #help-overlay { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); z-index: 9999; }
        #notif { position: fixed; bottom: 30px; left: 50%; transform: translate(-50%, 100px); padding: 10px 25px; background: #007acc; color: white; border-radius: 30px; font-size: 13px; font-weight: bold; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10001; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        #notif.show { transform: translate(-50%, 0); }
        #editor-container { flex: 1; display: flex; flex-direction: column; padding: 10px; background: transparent; position: relative; min-width: 0; }
        .editor-toolbar button { color: inherit !important; }
        .editor-toolbar {border: none !important;}.CodeMirror {border: none !important;}.EasyMDEContainer { border: none !important;}
        .CodeMirror { background: transparent !important; height: calc(100vh - 150px) !important; flex: 1; border: none !important; color: inherit !important; font-size: 16px; width: 100%; }
        button, select { background: rgba(128,128,128,0.2); color: inherit; border: none; padding: 8px; cursor: pointer; border-radius: 4px; }
        .file-item { cursor: pointer; padding: 6px 10px; border-radius: 4px; font-size: 13px; margin-bottom: 2px; }
        .file-item:hover { background: rgba(128,128,128,0.1); }
        .file-active { background: #007acc !important; color: white !important; }
        .folder { font-weight: bold; margin-top: 10px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; padding: 4px; border-radius: 4px; }
        .folder:hover { background: rgba(128,128,128,0.1); }
        .folder.drag-over { background: rgba(0, 122, 204, 0.3); border: 1px dashed #007acc; }
        .CodeMirror-wrap pre.CodeMirror-line {word-break: break-all; overflow-wrap: break-word;}
        .editor-preview, .editor-preview-side { background-color: var(--dynamic-bg) !important; color: inherit !important; height: 100% !important; border: none !important; position: absolute !important; top: 0 !important; left: 0 !important; right: 0 !important; z-index: 10; padding: 15px; box-sizing: border-box; }
        .editor-preview *, .editor-preview-side * { color: inherit !important; background-color: transparent !important; }
        #ctx-menu { display: none; position: fixed; border: 1px solid; border-radius: 6px; z-index: 10002; box-shadow: 0 4px 12px rgba(0,0,0,0.2); font-size: 13px; font-weight: bold; overflow: hidden; }
        #ctx-menu div { padding: 8px 12px; cursor: pointer; }
        #ctx-menu div:hover { background: #007acc; color: white; }
        #ctx-delete { color: #ff4d4d !important; }
        #ctx-delete:hover { background: #ff4d4d !important; color: white !important; }
    </style>
</head>
<body class="theme-dark">

<div id="splash">
    <img id="splash-logo" src="logos/dark.png" class="logo-splash" onerror="this.src='logos/dark.png'">
    <div id="splash-title" style="color: white; font-weight: bold; letter-spacing: 1px;">INITIALISATION...</div>
    <div id="tip" style="color: #888; margin-top: 10px; font-style: italic;">Bon retour parmi nous...</div>
</div>

<div id="help-overlay" onclick="toggleHelp()"></div>
<div id="help-modal">
    <h2 style="margin-top:0; color:#007acc;">Bienvenu sur Miro!</h2>
    <p><b>📁 Dossier :</b> Ouvrez votre répertoire. L'accès est mémorisé au prochain démarrage !</p>
    <p><b>💾 Sauvegarde :</b> Automatique ou <code>CTRL + S</code>.</p>
    <p><b>🌓 Thèmes :</b> Customizable à souhait! Vos préférences sont sauvegardées automatiquement.</p>
    <button onclick="toggleHelp()" style="width: 100%; margin-top: 10px; background: #007acc; color: white;">C'est parti !</button>
</div>

<div id="notif">Saving...</div>
<div id="ctx-menu">
    <div id="ctx-rename">✏️ Renommer</div>
    <div id="ctx-delete">🗑️ Supprimer</div>
</div>

<div id="sidebar">
    <div>
        <div class="sidebar-brand">
            <img id="sidebar-logo" src="logos/dark.png" style="width:250px; object-fit:contain;" onerror="this.src='logos/dark.png'">
        </div>
        <div class="sidebar-header">
            <button id="btn-open-dir" onclick="pickDirectory()">📁 Ouvrir Un Espace</button>
            <button onclick="createNewFile()">📄 Nouveau Fichier</button>
            <button onclick="createNewFolder()">📂 Nouveau Dossier</button>
            <select id="theme-selector" onchange="changeTheme(this.value)">
                    <option value="light">☀️ Clair</option>
                    <option value="auto">🌓 Auto</option>  
                    <option value="dark">🌙 Sombre</option>
                <optgroup label="Thèmes Officiels">
                    <?php foreach(glob('themes/official/*.css') as $t) echo "<option value='$t'>⭐ ".basename($t, '.css')."</option>"; ?>
                </optgroup>
                <optgroup label="Communauté">
                    <?php foreach(glob('themes/commu/*.css') as $t) echo "<option value='$t'>👥 ".basename($t, '.css')."</option>"; ?>
                </optgroup>
                <optgroup label="Personnel">
                    <?php foreach(glob('themes/custom/*.css') as $t) echo "<option value='$t'>☮ ".basename($t, '.css')."</option>"; ?>
                </optgroup>
            </select>
        </div>
        <div id="file-tree"></div>
    </div>
    <div class="sidebar-footer">
        <div class="github-link"><a href="https://github.com/LinkfandosVF/miro" target="_blank"><i class="fa fa-github"></i> GitHub Repo</a></div>
        <button class="help-btn" onclick="toggleHelp()">?</button>
    </div>
</div>

<div id="editor-container">
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom:10px;">
        <span id="current-filename" style="opacity: 0.6; font-size: 14px;">📍 Prêt pour la rédaction</span>
        <div style="display: flex; align-items: center; gap: 15px;">
            <label style="font-size: 12px; cursor:pointer; opacity: 0.8;"><input type="checkbox" id="autosave-toggle" checked> Auto-save</label>
            <button id="btn-save" onclick="saveCurrentFile()" disabled>💾 Sauvegarder</button>
        </div>
    </div>
    <textarea id="my-editor"></textarea>
</div>

<script src="https://cdn.jsdelivr.net/npm/idb-keyval@6/dist/umd/idb-keyval-iife.min.js"></script>
<script>
    let easyMDE, rootHandle, currentFileHandle, autoSaveTimeout;
    let collapsedFolders = new Set();
    let draggedItem = null;
    let ctxTarget = null;
    const db = window.idbKeyval || window.idb_keyval;
    const loadingTexts = ["RESTAURATION DE LA SESSION...", "OUVERTURE DU GRIMOIRE...", "CHARGEMENT DE L'UNIVERS...", "OUVERTURE DE MIRO...", "ENTRÉE DANS LA PEINTURE...", "RÉCUPÉRATION DE MONOCO..."];

    window.onload = async () => {
        document.getElementById('splash-title').innerText = loadingTexts[Math.floor(Math.random() * loadingTexts.length)];
        
        easyMDE = new EasyMDE({ 
            element: document.getElementById('my-editor'),
            spellChecker: false, autofocus: true, forceSync: true,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "preview", "|", "guide"]
        });

        easyMDE.codemirror.on("change", () => {
            if (document.getElementById('autosave-toggle').checked && currentFileHandle) {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => saveCurrentFile(true), 1200);
            }
        });

        document.addEventListener('click', () => document.getElementById('ctx-menu').style.display = 'none');
        
        document.getElementById('ctx-delete').addEventListener('click', async (ev) => {
            ev.stopPropagation();
            document.getElementById('ctx-menu').style.display = 'none';
            if (ctxTarget) {
                try {
                    await ctxTarget.parent.removeEntry(ctxTarget.name, { recursive: true });
                    if (currentFileHandle && currentFileHandle.name === ctxTarget.name) {
                        easyMDE.value("");
                        document.getElementById('current-filename').innerText = "📍 Prêt pour la rédaction";
                        currentFileHandle = null;
                    }
                    showNotif("Élément supprimé");
                    renderTree();
                } catch (e) { showNotif("Erreur de suppression"); }
            }
        });

        document.getElementById('ctx-rename').addEventListener('click', async (ev) => {
            ev.stopPropagation();
            document.getElementById('ctx-menu').style.display = 'none';
            if (ctxTarget) {
                if (ctxTarget.kind === 'directory') return showNotif("Impossible de renommer un dossier.");
                let newName = prompt("Nouveau nom :", ctxTarget.name);
                if (newName && newName !== ctxTarget.name) {
                    if (!newName.includes('.')) newName += '.md';
                    try {
                        const file = await ctxTarget.handle.getFile();
                        const content = await file.text();
                        const newHandle = await ctxTarget.parent.getFileHandle(newName, { create: true });
                        const w = await newHandle.createWritable();
                        await w.write(content);
                        await w.close();
                        await ctxTarget.parent.removeEntry(ctxTarget.name);
                        if (currentFileHandle && currentFileHandle.name === ctxTarget.name) {
                            currentFileHandle = newHandle;
                            document.getElementById('current-filename').innerText = "📍 " + newHandle.name;
                        }
                        showNotif("Fichier renommé");
                        renderTree();
                    } catch (e) { showNotif("Erreur de renommage"); }
                }
            }
        });

        const savedTheme = localStorage.getItem('obsidian-theme') || 'dark';
        document.getElementById('theme-selector').value = savedTheme;
        changeTheme(savedTheme);

        try {
            if (db) {
                const storedHandle = await db.get('root-handle');
                if (storedHandle) {
                    rootHandle = storedHandle;
                    document.getElementById('file-tree').innerHTML = `
                        <div style='padding:15px; text-align:center;'>
                            <button onclick="verifyPermission()" style="width:100%; background:#007acc; color:white; font-weight:bold; padding:12px;">🔓 Restaurer l'espace</button>
                            <p style='font-size:11px; opacity:0.6; margin-top:8px;'>Requis par le navigateur pour l'accès local.</p>
                        </div>`;
                }
            }
        } catch (e) {}

        setTimeout(() => {
            document.getElementById('splash').classList.add('fade-out');
            setTimeout(() => document.getElementById('splash').style.display = 'none', 800);
        }, 1800);
    };

    function changeTheme(val) {
        const body = document.body;
        const link = document.getElementById('theme-link');
        const sLogo = document.getElementById('sidebar-logo');
        const spLogo = document.getElementById('splash-logo');
        body.classList.remove('theme-light', 'theme-dark');
        link.href = "";
        let logoPath = "logos/dark.png";
        if (val === "light") {
            body.classList.add('theme-light');
            logoPath = "logos/light.png";
        } else if (val === "dark") {
            body.classList.add('theme-dark');
            logoPath = "logos/dark.png";
        } else if (val === "auto") {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            body.classList.add(isDark ? 'theme-dark' : 'theme-light');
            logoPath = isDark ? "logos/dark.png" : "logos/light.png";
        } else {
            link.href = val;
            body.classList.add('theme-dark');
            logoPath = val.replace('themes/', 'logos/').replace('.css', '.png');
        }
        sLogo.src = logoPath;
        spLogo.src = logoPath;

        setTimeout(() => {
            const bg = window.getComputedStyle(document.body).backgroundColor;
            document.documentElement.style.setProperty('--dynamic-bg', bg);
        }, 150);

        localStorage.setItem('obsidian-theme', val);
    }

    async function pickDirectory() {
        try { 
            rootHandle = await window.showDirectoryPicker();
            if (db) await db.set('root-handle', rootHandle);
            await renderTree(); 
        } catch (err) {}
    }

    async function verifyPermission() {
        if (!rootHandle) return;
        const status = await rootHandle.requestPermission({mode: 'readwrite'});
        if (status === 'granted') await renderTree();
    }

    async function renderTree() {
        const c = document.getElementById('file-tree');
        c.innerHTML = ""; 
        if (rootHandle) await listFiles(rootHandle, c, rootHandle, "");
    }

    async function listFiles(h, c, parentH, path) {
        const entries = [];
        for await (const entry of h.values()) entries.push(entry);
        entries.sort((a, b) => (b.kind === 'directory') - (a.kind === 'directory'));
        
        for (const e of entries) {
            const d = document.createElement('div');
            const currentPath = path + "/" + e.name;
            
            d.oncontextmenu = (ev) => {
                ev.preventDefault();
                ev.stopPropagation();
                ctxTarget = { parent: h, handle: e, name: e.name, kind: e.kind };
                const m = document.getElementById('ctx-menu');
                m.style.left = ev.pageX + 'px';
                m.style.top = ev.pageY + 'px';
                m.style.display = 'block';
            };

            if (e.kind === 'directory') {
                d.className = "folder";
                const icon = collapsedFolders.has(currentPath) ? "📁 " : "📂 ";
                d.innerHTML = icon + e.name;
                const s = document.createElement('div'); 
                s.style.paddingLeft = "12px"; 
                s.style.borderLeft = "1px solid rgba(128,128,128,0.3)"; 
                s.style.marginLeft = "6px";
                s.style.display = collapsedFolders.has(currentPath) ? "none" : "block";
                
                d.onclick = (ev) => {
                    ev.stopPropagation();
                    if (collapsedFolders.has(currentPath)) {
                        collapsedFolders.delete(currentPath);
                        s.style.display = "block";
                        d.innerHTML = "📂 " + e.name;
                    } else {
                        collapsedFolders.add(currentPath);
                        s.style.display = "none";
                        d.innerHTML = "📁 " + e.name;
                    }
                };

                d.ondragover = (ev) => { ev.preventDefault(); d.classList.add('drag-over'); };
                d.ondragleave = () => d.classList.remove('drag-over');
                d.ondrop = async (ev) => {
                    ev.preventDefault();
                    d.classList.remove('drag-over');
                    if (draggedItem && draggedItem.parent !== e) {
                        try {
                            const file = await draggedItem.handle.getFile();
                            const content = await file.text();
                            const newHandle = await e.getFileHandle(draggedItem.name, { create: true });
                            const writable = await newHandle.createWritable();
                            await writable.write(content);
                            await writable.close();
                            await draggedItem.parent.removeEntry(draggedItem.name);
                            if (currentFileHandle && currentFileHandle.name === draggedItem.name) currentFileHandle = newHandle;
                            renderTree();
                        } catch (err) { showNotif("Erreur de déplacement"); }
                    }
                };

                c.appendChild(d); 
                c.appendChild(s); 
                await listFiles(e, s, h, currentPath);

            } else if (e.name.match(/\.(md|txt)$/)) {
                d.className = "file-item"; 
                d.innerHTML = "📄 " + e.name;
                if (currentFileHandle && currentFileHandle.name === e.name) d.classList.add('file-active');
                
                d.draggable = true;
                d.ondragstart = (ev) => {
                    ev.stopPropagation();
                    draggedItem = { handle: e, parent: h, name: e.name };
                };

                d.onclick = (ev) => {
                    ev.stopPropagation();
                    openFile(e, d);
                };
                c.appendChild(d);
            }
        }
    }

    async function openFile(h, el) {
        document.querySelectorAll('.file-item').forEach(i => i.classList.remove('file-active'));
        el.classList.add('file-active');
        currentFileHandle = h;
        const f = await h.getFile();
        easyMDE.value(await f.text());
        document.getElementById('current-filename').innerText = "📍 " + f.name;
        document.getElementById('btn-save').disabled = false;
        easyMDE.codemirror.focus();
    }

    async function saveCurrentFile(silent = false) {
        if (!currentFileHandle) return;
        if (!silent) showNotif("Sauvegarde...");
        try {
            const w = await currentFileHandle.createWritable();
            await w.write(easyMDE.value());
            await w.close();
            showNotif(silent ? "Document auto-enregistré ✨" : "Document enregistré ! ✨");
            if (!silent) {
                const b = document.getElementById('btn-save'); b.innerText = "✨ OK";
                setTimeout(() => b.innerText = "💾 Sauvegarder", 1500);
            }
        } catch (err) { showNotif("Erreur ! ❌"); }
    }

    async function createNewFile() {
        if (!rootHandle) return showNotif("Ouvrez d'abord un espace.");
        let n = prompt("Nom du nouveau fichier :");
        if (n) { 
            if (!n.includes('.')) n += '.md';
            await rootHandle.getFileHandle(n, { create: true }); 
            renderTree(); 
        }
    }

    async function createNewFolder() {
        if (!rootHandle) return showNotif("Ouvrez d'abord un espace.");
        const name = prompt("Nom du nouveau dossier :");
        if (name) {
            try {
                await rootHandle.getDirectoryHandle(name, { create: true });
                renderTree(); 
                showNotif("Repertoire créé.");
            } catch (err) {
                alert("Erreur de création. Le nom existe déjà ou est invalide.");
            }
        }
    }

    function showNotif(text, duration = 2000) {
        const n = document.getElementById('notif');
        n.innerText = text; n.classList.add('show');
        if (duration > 0) setTimeout(() => n.classList.remove('show'), duration);
    }

    function toggleHelp() {
        const m = document.getElementById('help-modal');
        const o = document.getElementById('help-overlay');
        const isVisible = m.style.display === 'block';
        m.style.display = isVisible ? 'none' : 'block';
        o.style.display = isVisible ? 'none' : 'block';
    }

    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); saveCurrentFile(); }
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') { e.preventDefault(); createNewFile(); }
    });
</script>
</body>
</html>