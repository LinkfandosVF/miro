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
        #sidebar {width: 300px; display: flex; flex-direction: column; height: 100vh; z-index: 10;}
        .sidebar-footer {position: flex; flex-direction: row;}
        .sidebar-top-group {
    flex-shrink: 0;
}
        .wawascroll {flex-grow: 1; overflow-y: auto;}
        #help-modal { 
    display: none; 
    position: fixed; 
    /* Le secret est ici pour le centrage */
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%); 
    
    padding: 25px; 
    border-radius: 8px; 
    z-index: 10000; 
    width: 400px; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.4); 
    border-style: solid; 
    border-width: 1px; 
    max-height: 80vh;
    overflow-y: auto; 
    backdrop-filter: blur(8px);
}
#help-modal::-webkit-scrollbar {
    width: 6px;
}
#help-modal::-webkit-scrollbar-thumb {
    background: rgba(128, 128, 128, 0.3);
    border-radius: 10px;
}
        body.theme-light { background: #ffffff; color: #1e1e1e; }
        body.theme-light #sidebar { background: #f3f3f3; border-right: 1px solid #ccc; }
        body.theme-light .file-item { color: #1e1e1e; }
        body.theme-light .folder { color: #555; }
        body.theme-light .sidebar-brand { color: #005fb8; }
        body.theme-light .sidebar-footer { border-top: 1px solid #ccc; color: #1e1e1e; }
        body.theme-light #help-modal { background: #ffffff; color: #1e1e1e; border-color: #ccc; }
        body.theme-light .CodeMirror-cursor { border-left: 2px solid #27beff; visibility: visible; }
        body.theme-light #ctx-menu { background: #ffffff; border-color: #ccc; }
        body.theme-light #ctx-menu div { color: #1e1e1e; }
        body.theme-dark { background: #1e1e1e; color: #d4d4d4; }
        body.theme-dark #sidebar { background: #252526; border-right: 1px solid #333; }
        body.theme-dark .file-item { color: #d4d4d4; }
        body.theme-dark .folder { color: #888; }
        body.theme-dark .CodeMirror-cursor { border-left: 2px solid #27beff; visibility: visible; }
        body.theme-dark #help-modal { background: #252526; color: #d4d4d4; border-color: #444; }
        body.theme-dark #ctx-menu { background: #252526; border-color: #444; }
        body.theme-dark #ctx-menu div { color: #d4d4d4; }
        #splash {backdrop-filter: blur(8px);position: fixed; top:0; left:0; width:100%; height:100%; background: #1e1e1e; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; }
        .logo-splash { width: 200px; object-fit: contain; margin-bottom: 20px; animation: boing 2.5s infinite ease-in-out; }
        #sidebar { width: 300px; display: flex; flex-direction: column; justify-content: space-between; z-index: 10; }
        .sidebar-brand { padding: 15px; font-size: 1.1rem; font-weight: bold; color: #007acc; border-bottom: 1px solid rgba(128,128,128,0.2); display: flex; align-items: center; gap: 10px; }
        .sidebar-header { padding: 15px; border-bottom: 1px solid rgba(128,128,128,0.2); display: flex; flex-direction: column; gap: 8px; }
        #file-tree {flex-grow: 1; overflow-y: auto !important;min-height: 0;padding: 10px;}
       .sidebar-footer {flex-shrink: 0;padding: 12px;display: flex;justify-content: space-between;align-items: center;border-top: 1px solid rgba(128,128,128,0.2);}
        .github-link a { color: inherit; opacity: 0.7; text-decoration: none; font-size: 12px; transition: opacity 0.2s; display: flex; align-items: center; gap: 5px; }
        .github-link a:hover { opacity: 1; }
        .help-btn { background: #007acc; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; border: none; font-weight: bold; }
        #sidebar-mode-toggle { background: #007acc; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; border: none; font-weight: bold; }
        #help-modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 25px; border-radius: 8px; z-index: 10000; width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,0.4); border-style: solid; border-width: 1px; }
        #help-overlay {backdrop-filter: blur(8px);display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); z-index: 9999; }
        #restore-overlay { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); z-index: 10003; align-items: center; justify-content: center; }
        #restore-overlay.show { display: flex; }
        #restore-modal { background: #252526; color: #d4d4d4; border: 1px solid #444; border-radius: 12px; padding: 30px; width: 340px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.5); }
        body.theme-light #restore-modal { background: #ffffff; color: #1e1e1e; border-color: #ccc; }
        #restore-modal .restore-icon { font-size: 40px; margin-bottom: 12px; }
        #restore-modal h3 { margin: 0 0 8px; color: #007acc; font-size: 1.1rem; }
        #restore-modal p { margin: 0 0 20px; font-size: 13px; opacity: 0.7; line-height: 1.5; }
        #restore-modal .restore-btn { width: 100%; padding: 12px; background: #007acc; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: bold; cursor: pointer; margin-bottom: 8px; }
        #restore-modal .restore-btn:hover { background: #005f9e; }
        #restore-modal .restore-skip { width: 100%; padding: 8px; background: transparent; color: inherit; border: 1px solid rgba(128,128,128,0.3); border-radius: 8px; font-size: 13px; cursor: pointer; opacity: 0.6; }
        #restore-modal .restore-skip:hover { opacity: 1; }
        #notif { position: fixed; bottom: 30px; left: 50%; transform: translate(-50%, 100px); padding: 10px 25px; background: #007acc; color: white; border-radius: 30px; font-size: 13px; font-weight: bold; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10001; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        #notif.show { transform: translate(-50%, 0); }
        #editor-container { flex: 1; display: flex; flex-direction: column; padding: 10px; background: transparent; position: relative; min-width: 0; }
        .editor-toolbar button { color: inherit !important; }
        .editor-toolbar {border: none !important;}.CodeMirror {border: none;}.EasyMDEContainer { border: none !important;}
        .CodeMirror { background: transparent !important; height: calc(100vh - 150px) !important; flex: 1; border: inherit; color: inherit !important; font-size: 16px; width: 100%; }
        button, select { background: rgba(128,128,128,0.2); color: inherit; border: none; padding: 8px; cursor: pointer; border-radius: 4px; }
        .file-item { cursor: pointer; padding: 6px 10px; border-radius: 4px; font-size: 13px; margin-bottom: 2px; }
        .file-item:hover { background: rgba(128,128,128,0.1); }
        .file-active { background: #007acc; color: white; }
        .folder { font-weight: bold; margin-top: 10px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; padding: 4px; border-radius: 4px; }
        .folder:hover { background: rgba(128,128,128,0.1); }
        .folder.drag-over { background: rgba(0, 122, 204, 0.3); border: 1px dashed #007acc; }
        .CodeMirror-wrap pre.CodeMirror-line {word-break: normal; overflow-wrap: break-word;}
        .editor-preview, .editor-preview-side { background-color: var(--dynamic-bg) !important; color: inherit !important; height: 100% !important; border: none !important; position: absolute !important; top: 0 !important; left: 0 !important; right: 0 !important; z-index: 10; padding: 15px; box-sizing: border-box; }
        .editor-preview *, .editor-preview-side * { color: inherit !important; background-color: transparent !important; }
        #ctx-menu {display: none; position: fixed;border: 1px solid;border-radius: 6px;z-index: 10002;box-shadow: 0 4px 12px rgba(0,0,0,0.2);font-size: 13px;font-weight: bold;overflow: hidden;transform-origin: center;}
@keyframes popIn {
    0% { transform: scale(0.5); opacity: 0; }
    70% { transform: scale(1.05); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes boing-scale { 
    0% { transform: scale(0.5); opacity: 0; } 
    70% { transform: scale(1.05); opacity: 1; } 
    100% { transform: scale(1); opacity: 1; } 
}


.modal-boing {
    animation: boing-centered 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.music-boing {
    animation: boing-scale 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes boing-centered { 
    0% { transform: translate(-50%, -50%) scale(0.5); opacity: 0; } 
    50% { transform: translate(-50%, -50%) scale(1.05); opacity: 1; } 
    100% { transform: translate(-50%, -50%) scale(1); opacity: 1; } 
}
#ctx-menu.animate {
    display: block; /* On s'assure qu'il est visible pour l'anim */
    animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
        #ctx-menu div { padding: 8px 12px; cursor: pointer; }
        #ctx-menu div:hover { background: #007acc; color: white; }
        #ctx-delete { color: #ff4d4d !important; }
        #ctx-delete:hover { background: #ff4d4d !important; color: white !important; }
        .EasyMDEContainer .CodeMirror {border: inherit;}

        
    /* solid snake ass */
    .CodeMirror-line .cm-formatting-header, 
    .CodeMirror-line .cm-formatting-em, 
    .CodeMirror-line .cm-formatting-strong {
        display: inline-block !important;
        opacity: 0 !important;
        width: 0 !important;
        letter-spacing: -1em !important;
        transition: all 0.2s ease-in-out !important;
        pointer-events: none;
        overflow: hidden;
        vertical-align: middle;
    }

    .CodeMirror-line .cm-formatting-list {
        opacity: 0.8 !important;
        width: auto !important;
        letter-spacing: normal !important;
    }

    .CodeMirror-line.show-markers .cm-formatting-header,
    .CodeMirror-line.show-markers .cm-formatting-em,
    .CodeMirror-line.show-markers .cm-formatting-strong {
        opacity: 0.6 !important;
        width: auto !important;
        letter-spacing: normal !important;
        pointer-events: auto;
    }

    .cm-s-easymde .cm-link {
    color: inherit !important;
    text-decoration: underline;
    cursor: pointer;
}
.cm-s-easymde .cm-url {
    color: inherit !important;
    opacity: 0.5;
    font-size: 0.9em;
}

.CodeMirror-line:not(.show-markers) .cm-formatting-link,
.CodeMirror-line:not(.show-markers) .cm-url {
    display: none;
}



/* ============================= */
/* FLOATING SIDEBAR MODE */
/* ============================= */

#sidebar {
    transition: all .35s cubic-bezier(.4,0,.2,1);
}

body.sidebar-floating #sidebar {

    position: fixed;

    left: 10px;
    top: 10px;
    bottom: 10px;

    width: 280px;

    border-radius: 24px;

    border: 1px solid rgba(0,0,0,0.1);

    box-shadow:
        0 20px 50px rgba(0,0,0,0.25),
        0 2px 10px rgba(0,0,0,0.15);

    backdrop-filter: blur(14px);

    z-index: 1000;
}

/* hidden state */

body.sidebar-floating.sidebar-hidden #sidebar {

    transform: translateX(-110%);
}

/* hover edge */

#sidebar-edge-trigger {

    position: fixed;

    left: 0;
    top: 0;
    bottom: 0;

    width: 6px;

    z-index: 999;
}

/* mobile button */

#sidebar-touch-button {
    position: fixed;
    top: 14px;
    left: 14px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(0,0,0,0.35);
    color: white;
    display: none;
    z-index: 1200;
}

/* show mobile */

@media (pointer: coarse) {

    #sidebar-touch-button {
        display: block;
    }

}






/* the music bar thing */

.musicbar {
        display: flex; align-items: center; gap: 12px; padding: 10px 16px;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 50px; backdrop-filter: blur(20px);
        min-width: 500PX; max-width: 60vw; box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        flex-shrink: 1;
    }

    /* Mode flottant : la musicbar redevient un blob fixed centré en haut */
    body.sidebar-floating .musicbar {
        position: fixed;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1001;
        min-width: 500px;
        max-width: 90vw;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
    }
    .track-info { flex: 1; min-width: 0; position: relative; height: 40px; display: flex; align-items: center; overflow: hidden; }
    .track-title-view, .track-controls-view {
        position: absolute; width: 100%; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex; flex-direction: column; justify-content: center;
    }
    .track-controls-view { opacity: 0; transform: translateY(20px); pointer-events: none; flex-direction: row; align-items: center; gap: 8px; }
    .musicbar:hover .track-title-view { opacity: 0; transform: translateY(-20px); }
    .musicbar:hover .track-controls-view { opacity: 1; transform: translateY(0); pointer-events: auto; }
    .track-title { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; }
    .track-index { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; }
    .slider-box { flex: 1; display: flex; align-items: center; gap: 8px; }
    input[type=range] { flex: 1; height: 4px; cursor: pointer; accent-color: #4a7cf7; }
    .time-txt { font-size: 10px; font-family: monospace; opacity: 0.7; min-width: 32px; }
    .mode-btn { background: none; border: none; color: white; opacity: 0.5; cursor: pointer; font-size: 12px; }
    .mode-btn:hover { opacity: 1; }
    .musicbar-btn { background: none; border: none; color: rgba(255,255,255,0.8); cursor: pointer; font-size: 18px; padding: 4px 6px; border-radius: 6px; transition: 0.2s; line-height: 1; }
    .musicbar-btn:hover { color: #fff; background: rgba(255,255,255,0.1); }
    .musicbar-btn:disabled { opacity: 0.2; cursor: default; }
    .musicbar-btn.settings { font-size: 14px; border: 1px solid rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; }

    /* Musicbar — thème clair */
    body.theme-light .musicbar {
        background: rgba(0,0,0,0.06);
        border-color: rgba(0,0,0,0.12);
    }
    body.theme-light .track-title { color: #1e1e1e; }
    body.theme-light .track-index { color: rgba(0,0,0,0.45); }
    body.theme-light .time-txt { color: #1e1e1e; }
    body.theme-light .mode-btn { color: #1e1e1e; }
    body.theme-light .musicbar-btn { color: rgba(0,0,0,0.75); }
    body.theme-light .musicbar-btn:hover { color: #1e1e1e; background: rgba(0,0,0,0.08); }
    body.theme-light .musicbar-btn.settings { border-color: rgba(0,0,0,0.2); }

    /* Thèmes custom : mix-blend-mode comme fallback si le thème ne définit pas les couleurs */
    body.theme-custom .musicbar {
        background: rgba(128,128,128,0.15);
        border-color: rgba(128,128,128,0.2);
    }
    body.theme-custom .track-title,
    body.theme-custom .track-index,
    body.theme-custom .time-txt,
    body.theme-custom .mode-btn,
    body.theme-custom .musicbar-btn {
        color: inherit;
    }

    .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); justify-content: center; align-items: center; z-index: 2000; }
    .overlay.active { display: flex; }
    .musicpopup-card { background: #16213e; border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; width: 420px; max-width: 95vw; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; }
    .musicpopup-header { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; }
    .musicpopup-body { padding: 16px 20px; overflow-y: auto; flex: 1; }
    .url-row { display: flex; gap: 8px; margin-bottom: 16px; }
    .url-input { flex: 1; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; padding: 9px; color: white; outline: none; }
    .load-btn { background: #4a7cf7; border: none; border-radius: 8px; color: white; padding: 9px 16px; font-weight: 600; cursor: pointer; }
    .playlist-section { display: none; margin-top: 10px; }
    .playlist-section.visible { display: block; }
    .playlist-item { display: flex; align-items: center; gap: 10px; padding: 9px; border-radius: 8px; cursor: pointer; border: none; background: none; color: rgba(255,255,255,0.7); font-size: 13px; width: 100%; text-align: left; }
    .playlist-item:hover { background: rgba(255,255,255,0.07); color: white; }
    .playlist-item.active { background: rgba(74,124,247,0.2); color: #7eaaff; }
    .item-index { font-size: 11px; opacity: 0.4; min-width: 20px; }

</style>


</head>
<body class="theme-dark">
<div id="sidebar-edge-trigger"></div>

<div id="splash">
    <img id="splash-logo" src="logos/dark.png" class="logo-splash" onerror="this.src='logos/dark.png'">
    <div id="splash-title" style="color: white; font-weight: bold; letter-spacing: 1px;">INITIALISATION...</div>
    <div id="splash-subtitle" style="color: #888; margin-top: 10px; font-style: italic;">Bon retour parmi nous...</div>
</div>
<div id="player-wrap" style="display:none"><div id="youtube-player"></div></div>

<div id="help-overlay" onclick="toggleHelp()"></div>
<div id="help-modal">
    <h2 style="margin-top:0; color:#007acc;" data-i18n="settings">Préférences</h2>
    <p data-i18n="opsettings"><b>⌘</b> Ouvre les réglages</p>
    <p data-i18n="opfloating"><b>⎋</b> Active le flotteur</p>
    <p data-i18n="opburry">opburry</p>
    <p data-i18n="cmdclick">Maintenez Contrôle (ou CMD sur mac) et cliquez sur un lien pour l'ouvrir.</p>
    <h2 style="margin-top:0; color:#007acc;">---</h2>
    <label style="font-size: 15px; cursor:pointer; opacity: 100%; align-items: center; display: flex;">
        <input type="checkbox" id="autosave-toggle" checked>
        <p data-i18n="autosavefeature" style="margin-left: 8px;">Sauvegarde Auto</p>
    </label>
    <p data-i18n="autosavetooltip" style="font-size: 11px">tooltip</p>
    <label style="font-size: 15px; cursor:pointer; align-items: center; display: flex;">
        <input type="checkbox" id="notif-toggle" checked>
        <p style="margin-left: 8px;" data-i18n="savenotif">Notifications De Sauvegarde</p>
    </label>
    <p data-i18n="savenotiftooltip" style="font-size: 11px">tooltip</p>
    <label style="font-size: 15px; cursor:pointer; align-items: center; display: flex;">
        <input type="checkbox" id="sound-toggle" checked>
        <p style="margin-left: 8px;" data-i18n="soundtoggle">Sons</p>
    </label>
    <p data-i18n="soundtooltip" style="font-size: 11px">tooltip3</p>
    <label style="font-size: 15px; cursor:pointer; align-items: center; display: flex;">
    <input type="checkbox" id="music-hide-toggle">
    <p style="margin-left: 8px;" data-i18n="disablemusic">Masquer le lecteur musique</p>
    </label>
    <p data-i18n="tooltip4" style="font-size: 11px">tooltip4</p>
<br>
    
    <br>
    <br>
    <p data-i18n="themes" style="font-weight: bold; color:#007acc">themes</p>
    <label></label> 
    <select id="theme-selector" onchange="changeTheme(this.value)">
                    <option value="light">☀️ Clair</option>
                    <option value="auto">🌓 Auto</option>  
                    <option value="dark">🌙 Obscur</option>
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
            <p data-i18n="checkoutgit">Allez voir le github pour apprendre à créer vos propres thèmes!</p>
    <p style="font-weight: bold; color:#007acc"><b>Langue</b></p>
    <div class="language-selector">
    <label for="language-select"></label>
    <select id="language-select">
        <option value="fr">French - Français (Par défaut)</option>
        <option value="en">English - Anglais</option>
    </select>
    </div>
    <button onclick="toggleHelp()" style="width: 100%; margin-top: 10px; background: #007acc; color: white;">Let's Boogie!</button>
    <p style="font-size: 11px; text-align: center" data-i18n="madewithlove">Fait avec amour par aalllaaasss & friends...</p>
</div>

<div id="musicpopupOverlay" class="overlay">
    <div class="musicpopup-card">
        <div class="musicpopup-header"><h2  data-i18n="music">Lecteur YouTube</h2><button class="musicbar-btn" onclick="togglemusicpopup()">&#x2715;</button></div>
        <div class="musicpopup-body">
            <div class="url-row">
                <input class="url-input" type="text" id="yt-url" placeholder="URL vidéo ou playlist...">
                <button class="load-btn" onclick="loadMedia()">Yo!</button>
            </div>
            <div class="playlist-section" id="playlist-section">
                <div style="font-size:11px; opacity:0.4; margin-bottom:10px; text-transform:uppercase;">Playlist</div>
                <div id="playlist-list"></div>
            </div>
        </div>
    </div>
</div>

<div id="restore-overlay">
    <div id="restore-modal">
        <div class="restore-icon">📁</div>
        <h3 id="restore-folder-name">Mon Espace</h3>
        <p>Chromium a besoin de votre autorisation pour accéder à nouveau au dossier.</p>
        <button class="restore-btn" onclick="restoreSpace()">🔓 Réautoriser l'accès</button>
        <button class="restore-skip" onclick="dismissRestore()">Ignorer</button>
    </div>
</div>

<div id="notif">Sauvegarde...</div>
<div id="ctx-menu">
    <div id="ctx-rename">✏️ Rename</div>
    <div id="ctx-delete">🗑️ Delete</div>
</div>

<div id="sidebar">
    <div class="sidebar-top-group"> 
        <div class="sidebar-brand">
            <img id="sidebar-logo" src="logos/dark.png" style="width:250px; object-fit:contain;">
        </div>
        <div class="sidebar-header">
            <button id="btn-open-dir" onclick="pickDirectory()" data-i18n="opspace">📁 Ouvrir Un Espace</button>
            <button onclick="createNewFile()" data-i18n="newfile">📄 Nouveau Fichier</button>
            <button onclick="createNewFolder()" data-i18n="newdir">📂 Nouveau Dossier</button>
            <button class="help-btn" onclick="collapseAllFolders()">⛺︎</button>
        </div>
    </div>

    <div id="file-tree"></div>

    <div class="sidebar-footer">
        <div class="github-link">
            <a href="https://github.com/LinkfandosVF/miro" target="_blank">
                <i class="fa fa-github"></i> GitHub Repo
            </a>
        </div>
        <div style="display:flex; gap:6px;">
            <button id="sidebar-mode-toggle" title="Sidebar">⎋</button>
            <button class="help-btn" onclick="toggleHelp()">⌘</button>
        </div>
    </div>
</div>

<div id="editor-container">
    <div>
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom:10px;">
        <span id="current-filename" style="opacity: 0.6; font-size: 14px;">📍 No file to save to.</span>
        <div class="musicbar">
    <button class="musicbar-btn" id="btn-prev" onclick="prevTrack()" disabled>&#9198;</button>
    <button class="musicbar-btn" id="btn-playpause" onclick="playPause()">&#9654;</button>
    <button class="musicbar-btn" id="btn-next" onclick="nextTrack()" disabled>&#9197;</button>
    
    <div class="track-info">
        <div class="track-title-view">
            <div class="track-title" id="track-title"  data-i18n="nomedia">No media</div>
            <div class="track-index" id="track-index"></div>
        </div>

        <div class="track-controls-view">
            <div class="slider-box" id="box-time">
                <span class="time-txt" id="time-cur">0:00</span>
                <input type="range" id="progress-musicbar" value="0" min="0" step="1" oninput="seek(this.value)">
                <span class="time-txt" id="time-total">0:00</span>
            </div>
            <div class="slider-box" id="box-vol" style="display:none">
                <span style="font-size:12px">🔊</span>
                <input type="range" id="volume-musicbar" value="100" min="0" max="100" oninput="setVolume(this.value)">
            </div>
            <button class="mode-btn" id="btn-mode-toggle" onclick="toggleCtrlMode()">&#128266;</button>
            
        </div>
    </div>

    <button class="musicbar-btn settings" onclick="togglemusicpopup()">&#9881;</button>
</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <button id="btn-save" onclick="saveCurrentFile()" disabled>💾</button>
        </div>
    </div>
    <textarea id="my-editor"></textarea>
</div>

<script src="https://cdn.jsdelivr.net/npm/idb-keyval@6/dist/umd/idb-keyval-iife.min.js"></script>
<script>

    // ===========================
    // TRADUCTIONS (global, en premier)
    // ===========================
    const allTranslations = {
        en: {
            'settings': 'Preferences',
            'opsettings': '⌘ Open Settings',
            'opfloating': '⎋ Enable Floating mode',
            'checkoutgit': 'Check out the github to learn how to create your own themes!',
            'madewithlove': 'Made with love by aalllaaass and friends...',
            'newfile': '📄 New file',
            'newdir': '📂 New Folder',
            'opspace': '📁 Open Space',
            'themes': 'Themes',
            'lezgo': "Let's boogie!",
            'renamecont': '✏️ Rename',
            'deletecont': '🗑️ Delete',
            'autosavefeature': 'Automatic save',
            'autosavetooltip': 'Saves your document when you stop writing.',
            'cmdclick': 'Hold Control (or CMD on mac) and click a link to open it.',
            'savenotiftooltip': 'Displays a notification when you save the document.',
            'savenotif': 'Save notification',
            'notif_saving': 'Saving...',
            'notif_saved': 'Document saved.',
            'notif_autosaved': 'Document auto-saved.',
            'err': 'ERR: Check JS Console.',
            'notif_collapsed': 'Space collapsed.',
            'notif_newfolder': 'Folder created.',
            'notif_openaspace': 'Open a space first!',
            'notif_deleted': 'Item deleted',
            'notif_delete_err': 'Delete error',
            'notif_renamed': 'File renamed',
            'notif_rename_err': 'Rename error',
            'notif_rename_dir': 'Cannot rename a folder... Yet :C',
            'notif_move_err': 'Move error',
            'opburry': '⛺︎ Closes folders in space.',
            'notif_restored': 'Space restored.',
            'soundtooltip' : 'Toggle funny sounds for the app. (They‘re from Animal Crossing.)',
            'nomedia' : 'No media loaded.',
            'music' : 'Music',
            'disablemusic': 'Hide Music Player',
            'tooltip4': 'Better on mobile.'
        },
        fr: {
            'settings': 'Réglages',
            'opsettings': '⌘ Ouvrir Les Réglages',
            'opfloating': '⎋ Activer le flotteur',
            'opburry': '⛺︎ Replier les dossiers.',
            'checkoutgit': 'Allez voir le github pour apprendre à créer vos propres thèmes!',
            'madewithlove': 'Fait avec amour par aalllaaasss & friends...',
            'newfile': '📄 Nouveau Fichier',
            'newdir': '📂 Nouveau Dossier',
            'opspace': '📁 Ouvrir Un Espace',
            'themes': 'Thèmes',
            'lezgo': "Let's boogie!",
            'renamecont': '✏️ Renommer',
            'deletecont': '🗑️ Supprimer',
            'autosavefeature': 'Sauvegarde auto',
            'autosavetooltip': "Sauvegarde votre document quand vous arrêtez d'écrire.",
            'cmdclick': "Maintenez Contrôle (ou CMD sur mac) et cliquez sur un lien pour l'ouvrir.",
            'savenotiftooltip': 'Vous notifie lorsque le document est sauvegardé.',
            'savenotif': 'Notifications de Sauvegarde',
            'notif_saving': 'Sauvegarde...',
            'notif_saved': 'Document enregistré.',
            'notif_autosaved': 'Document auto-enregistré.',
            'err': 'ERR ❌ - Verifiez la console.',
            'notif_collapsed': 'Espace replié.',
            'notif_newfolder': 'Dossier créé.',
            'notif_openaspace': 'Ouvrez un espace en premier !',
            'notif_deleted': 'Élément supprimé',
            'notif_delete_err': 'Erreur de suppression... :C',
            'notif_renamed': 'Fichier renommé',
            'notif_rename_err': 'Erreur de renommage',
            'notif_rename_dir': 'Impossible de renommer un dossier pour l‘instant... :C',
            'notif_move_err': 'Erreur de déplacement',
            'notif_restored': 'Espace restauré!',
            'soundtooltip' : 'Active des sons rigolos pour l‘application.. (Ils viennent de Animal Crossing.)',
            'nomedia' : 'Pas de média chargé.',
            'music' : 'Musique',
            'disablemusic': 'Masquer le lecteur',
            'tooltip4': 'C‘est mieux sur mobile.',
        }
    };

    function getTranslatedText(key) {
        const lang = localStorage.getItem('selectedLanguage') || 'fr';
        return (allTranslations[lang] && allTranslations[lang][key]) ? allTranslations[lang][key] : key;
    }

    function showNotif(textOrKey, duration = 2000) {
        const n = document.getElementById('notif');
        n.innerText = getTranslatedText(textOrKey);
        n.classList.add('show');
        if (duration > 0) setTimeout(() => n.classList.remove('show'), duration);
    }

    function updateLanguage(language) {
        const elementsToUpdate = document.querySelectorAll('[data-i18n]');
        elementsToUpdate.forEach((element) => {
            const key = element.getAttribute('data-i18n');
            if (allTranslations[language] && allTranslations[language][key]) {
                element.textContent = allTranslations[language][key];
            }
        });
    }


    let easyMDE, rootHandle, currentFileHandle, autoSaveTimeout;
    let collapsedFolders = new Set();
    let draggedItem = null;
    let ctxTarget = null;
    const db = window.idbKeyval || window.idb_keyval;
    const loadingTexts = ["ON PRÉPARE LES TRUCS BIEN...", "OUVERTURE DE C'TRUC...", "ON NÉTTOIE LES PINCEAUX...", "OUVERTURE DE MIRO...", "ENTRÉE DANS LA PEINTURE...", "RÉCUPÉRATION DE MONOCO...", "APPLICATIONS DES DROITS TRANS...", "ON BRANDIS LES DRAPEAUX TRANS...", "ON BRANDIS LES DRAPEAUX LGBTQ+...", "ON RÉVEILLE LE CAMÉLÉON...", "ON RÉVEILLE LEXULATHU'AL...", "DES BISOUX POUR LES SUPPORTERS...", "ON NETTOIE LES DOSSIERS...", "PEINDRE L'AMOUR...", "PEINDRE LA VIE...", "PLEURER EN COULEUR...", "SUR LA TOILE NOTRE VIE S'ÉCRIT...", "ON RÉCUP LA HOMIE SALOMÉ..."];
    const loadingTexts2 = ["Mirqo c'est le nom d'un très bon chien!", "Bientôt on ferra bien plus que jeter des cailloux.", "J'avais beaucoup de temps à perdre!", "Rebonjour!", "Merci de t'amuser!", "Hein? C'est quoi deez?", "On respectera toujours votre vie privée!", "Cryptid crush c'était mieux avant.", "Salut Romy!", "Salut Dominik!", "Salut Craze!", "Salut Sim!", "La paix pour tous... C'est quand?", "Continuer à t'aimer, continuer de peindre...", "Tout ça dans un seul fichier php?","Hi Grey!!!!!! :33","J'ai plus d'idée de sous titre.","SALOMÉ COUCOUUUUU!!!!!!!"];


    // INIT + SOUNDS

    const ctx = new AudioContext();

    async function loadSound(url) {
    const response = await fetch(url);
    const buffer = await response.arrayBuffer();
    return await ctx.decodeAudioData(buffer);
    }

    function playBuffer(buffer, { volume = 1, playbackRate = 1 } = {}) {
    const source = ctx.createBufferSource();
    const gain = ctx.createGain();
    
    source.buffer = buffer;
    source.playbackRate.value = playbackRate; // 0.5 = lent, 2 = rapide
    gain.gain.value = volume;
    
    source.connect(gain);
    gain.connect(ctx.destination);
    source.start();
    }


    function playErrorSound(audiotoplay) {
    // On vérifie l'état du toggle dans le localStorage ou directement sur l'élément
    const soundsEnabled = localStorage.getItem('sound-enabled') !== 'false';
    
    if (soundsEnabled) {
        const audio = new Audio(audiotoplay);
        audio.play().catch(e => console.log("Invalid AUDIO url! (or blocked lmao)", e));
    }
}
    window.onload = async () => {
        document.getElementById('splash-title').innerText = loadingTexts[Math.floor(Math.random() * loadingTexts.length)];
        document.getElementById('splash-subtitle').innerText = loadingTexts2[Math.floor(Math.random() * loadingTexts2.length)];

        // 1. Initialisation du toggle des notifications
        const notifToggle = document.getElementById('notif-toggle');
        notifToggle.checked = localStorage.getItem('notif-enabled') !== 'false';
        notifToggle.addEventListener('change', (e) => {
            localStorage.setItem('notif-enabled', e.target.checked);
        }); // <-- Il manquait cette fermeture !

        // 2. Initialisation du toggle des sons
        const soundToggle = document.getElementById('sound-toggle');
        soundToggle.checked = localStorage.getItem('sound-enabled') !== 'false';
        soundToggle.addEventListener('change', (e) => {
            localStorage.setItem('sound-enabled', e.target.checked);
        }); // <-- Il manquait cette fermeture !

        // 3. Initialisation de EasyMDE
        easyMDE = new EasyMDE({ 
            element: document.getElementById('my-editor'),
            spellChecker: false, 
            autofocus: true, 
            forceSync: true,
            styleActiveLine: true,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "preview", "|", "guide"]
        });

        if (easyMDE && easyMDE.codemirror) {
            const cm = easyMDE.codemirror;
            
            cm.getWrapperElement().addEventListener("mousedown", (e) => {
                if (e.ctrlKey || e.metaKey) {
                    const pos = cm.coordsChar({ left: e.clientX, top: e.clientY });
                    const token = cm.getTokenAt(pos);
                    if (token.type && (token.type.includes("url") || token.type.includes("link"))) {
                        let url = token.string.replace(/[()\[\]]/g, "").trim();
                        if (url) {
                            const finalUrl = url.startsWith("http") ? url : "https://" + url;
                            window.open(finalUrl, "_blank");
                        }
                    }
                }
            }, true);
        }

        easyMDE.codemirror.on("cursorActivity", (cm) => {
            cm.eachLine(line => {
                const info = cm.lineInfo(line);
                if (info.handle.widgets) return;
                cm.removeLineClass(line, "text", "show-markers");
            });
            const currentLine = cm.getCursor().line;
            cm.addLineClass(currentLine, "text", "show-markers");
        });

        easyMDE.codemirror.on("change", () => {
            if (document.getElementById('autosave-toggle').checked && currentFileHandle) {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => saveCurrentFile(true), 1200);
            }
        });

        document.addEventListener('click', () => {
    const m = document.getElementById('ctx-menu');
    m.style.display = 'none';
    m.classList.remove('animate'); // On nettoie pour la prochaine fois
});
        
        document.getElementById('ctx-delete').addEventListener('click', async (ev) => {
            ev.stopPropagation();
            document.getElementById('ctx-menu').style.display = 'none';
            if (ctxTarget) {
                try {
                    await ctxTarget.parent.removeEntry(ctxTarget.name, { recursive: true });
                    if (currentFileHandle && currentFileHandle.name === ctxTarget.name) {
                        easyMDE.value("");
                        document.getElementById('current-filename').innerText = "📍 Ready.";
                        currentFileHandle = null;
                    }
                    showNotif('notif_deleted');
                    renderTree();
                } catch (e) { showNotif('notif_delete_err'); }
                document.addEventListener('click', () => playBuffer(clickSound));
            }
        });

        document.getElementById('ctx-rename').addEventListener('click', async (ev) => {
            ev.stopPropagation();
            document.getElementById('ctx-menu').style.display = 'none';
            if (ctxTarget) {
                if (ctxTarget.kind === 'directory') return showNotif('notif_rename_dir');
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
                            document.getElementById('current-filename').innerText = "➤ " + newHandle.name;
                        }
                        showNotif('notif_renamed');
                        renderTree();
                    } catch (e) { showNotif('notif_rename_err'); }
                    document.addEventListener('click', () => playBuffer(clickSound));
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
                    // Affiche le nom du dossier dans le popup
                    document.getElementById('restore-folder-name').innerText = '📂 ' + storedHandle.name;
                    document.getElementById('restore-overlay').classList.add('show');
                }
            }
        } catch (e) {}

        setTimeout(() => {
            document.getElementById('splash').classList.add('fade-out');
            setTimeout(() => document.getElementById('splash').style.display = 'none', 800);
        }, 1800);
    };

    // ===========================
    // THEME
    // ===========================
    function changeTheme(val) {
        const body = document.body;
        const link = document.getElementById('theme-link');
        const sLogo = document.getElementById('sidebar-logo');
        const spLogo = document.getElementById('splash-logo');
        body.classList.remove('theme-light', 'theme-dark', 'theme-custom');
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
            body.classList.add('theme-dark', 'theme-custom');
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

// 1. On récupère les éléments
const musicHideToggle = document.getElementById('music-hide-toggle');
const musicBar = document.querySelector('.musicbar');

// 2. Fonction pour appliquer l'état
const applyMusicVisibility = (isHidden) => {
    if (musicBar) {
        // On utilise !important en JS pour être sûr de gagner contre le CSS
        musicBar.style.setProperty('display', isHidden ? 'none' : 'flex', 'important');
    }
};

// 3. Initialisation au chargement
const isMusicHidden = localStorage.getItem('music-hidden') === 'true';
if (musicHideToggle) {
    musicHideToggle.checked = isMusicHidden;
    applyMusicVisibility(isMusicHidden);

    // 4. L'écouteur de changement
    musicHideToggle.addEventListener('change', (e) => {
        const hidden = e.target.checked;
        localStorage.setItem('music-hidden', hidden);
        applyMusicVisibility(hidden);
        playErrorSound('sounds/UICheck.wav');
    });
}

    // ===========================
    // FICHIERS
    // ===========================
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

    async function restoreSpace() {
        document.getElementById('restore-overlay').classList.remove('show');
        if (!rootHandle) return;
        try {
            const status = await rootHandle.requestPermission({ mode: 'readwrite' });
            if (status === 'granted') {
                await renderTree();
                showNotif('notif_restored');
            }
        } catch (e) {}
    }

    function dismissRestore() {
        document.getElementById('restore-overlay').classList.remove('show');
        rootHandle = null;
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
    
    // Positionnement
    m.style.left = ev.pageX + 'px';
    m.style.top = ev.pageY + 'px';
    
    // On retire la classe au cas où elle y était déjà pour reset l'anim
    m.classList.remove('animate');
    // Forcer un "reflow" pour que le navigateur voit le retrait de la classe
    void m.offsetWidth; 

    
    // On affiche et on joue le son
    m.style.display = 'block';
    m.classList.add('animate');
    playErrorSound('./sounds/UIOpen1.wav'); // Ou un autre son de ton choix :3
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
                        } catch (err) { showNotif('notif_move_err'); }
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
        document.getElementById('current-filename').innerText = "► " + f.name;
        document.getElementById('btn-save').disabled = false;
        easyMDE.codemirror.focus();
    }

    async function saveCurrentFile(silent = false) {
        if (!currentFileHandle) return;
        
        const showNotifsEnabled = document.getElementById('notif-toggle').checked;

        if (!silent && showNotifsEnabled) showNotif('notif_saving');

        try {
            const w = await currentFileHandle.createWritable();
            await w.write(easyMDE.value());
            await w.close();
            if (showNotifsEnabled) {
                showNotif(silent ? 'notif_autosaved' : 'notif_saved');
            }
            if (!silent) {
                const b = document.getElementById('btn-save'); 
                b.innerText = "✨ OK";
                setTimeout(() => b.innerText = "💾", 1500);
            }
        } catch (err) { 
            showNotif('err'); 
        }
    }

    async function createNewFile() {
        if (!rootHandle) {showNotif('notif_openaspace'); playErrorSound('sounds/UIWarning.wav')}
        let n = prompt("File Name: ");
        if (n) { 
            if (!n.includes('.')) n += '.md';
            await rootHandle.getFileHandle(n, { create: true }); 
            renderTree(); 
        }
    }

async function createNewFolder() {
        if (!rootHandle) {
            playErrorSound('sounds/UIWarning.wav')
            return showNotif('notif_openaspace');
        }

        const name = prompt("Folder Name:");
        if (name) {
            try {
                await rootHandle.getDirectoryHandle(name, { create: true });
                renderTree(); 
                showNotif('notif_newfolder');
            } catch (err) {
                showNotif('err');
                const errorAudio = new Audio('https://interactive-examples.mdn.mozilla.net/media/cc0-audio/t-rex-roar.mp3');
                errorAudio.play().catch(e => console.log("Audio bloqué", e));
            }
        }
    }
            
    


    async function collapseAllFolders() {
        if (!rootHandle) return;
        playErrorSound('sounds/UIUndo.wav');
        const fillCollapsed = async (handle, path = "") => {
            for await (const entry of handle.values()) {
                if (entry.kind === 'directory') {
                    const currentPath = path + "/" + entry.name;
                    collapsedFolders.add(currentPath); 
                    await fillCollapsed(entry, currentPath);
                }
            }
        };

        await fillCollapsed(rootHandle);
        renderTree();
        showNotif('notif_collapsed');
    }

    function toggleHelp() {
        const m = document.getElementById('help-modal');
        const o = document.getElementById('help-overlay');
        const isVisible = m.style.display === 'block';

        if (!isVisible) {
            playErrorSound('sounds/UIOpen2.wav');
            m.style.display = 'block';
            o.style.display = 'block';
            m.classList.add('modal-boing');
        } else {
            playErrorSound('sounds/UiClose.wav');
            m.style.display = 'none';
            o.style.display = 'none';
            m.classList.remove('modal-boing');
    }
}

    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); saveCurrentFile(); }
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') { e.preventDefault(); createNewFile(); }
    });

    // ===========================
    // SIDEBAR FLOATING MODE
    // ===========================
    window.addEventListener("load", () => {
        const sidebarToggle = document.getElementById("sidebar-mode-toggle");
        const sidebarEdge = document.getElementById("sidebar-edge-trigger");
        const sidebarTouch = document.getElementById("sidebar-touch-button");
        const sidebar = document.getElementById("sidebar");

       if (!sidebar || !sidebarEdge) return;

if (sidebarToggle) {
    sidebarToggle.onclick = () => {
        document.body.classList.toggle("sidebar-floating");
        // On joue le son de toggle systématiquement car c'est l'action qui active/désactive le mode
        playErrorSound('sounds/UICheck.wav');
    };
}

function showSidebar() {
    document.body.classList.remove("sidebar-hidden");
    // On vérifie si on est en mode floating avant de jouer le son
    if (document.body.classList.contains("sidebar-floating")) {
        playErrorSound('sounds/UIDragStart.wav');
    }
}

function hideSidebar() {
    if (document.body.classList.contains("sidebar-floating")) {
        document.body.classList.add("sidebar-hidden");
        // Le son ne joue que si on est en mode floating (vu la condition au-dessus)
        playErrorSound('sounds/UIDragEnd.wav');
    }
}

sidebarEdge.addEventListener("mouseenter", showSidebar);
sidebar.addEventListener("mouseleave", hideSidebar);

if (sidebarTouch) {
    sidebarTouch.onclick = () => {
        document.body.classList.toggle("sidebar-hidden");
        // Optionnel : ajouter un son ici aussi si tu veux un retour tactile
        if (document.body.classList.contains("sidebar-floating")) {
            playErrorSound('sounds/UICheck.wav');
        }
    };
}
    });

    document.addEventListener('DOMContentLoaded', () => {
        const languageSelect = document.getElementById('language-select');

        const savedLanguage = localStorage.getItem('selectedLanguage');
        if (savedLanguage) {
            languageSelect.value = savedLanguage;
            updateLanguage(savedLanguage);
        }

        languageSelect.addEventListener('change', (event) => {
            const selectedLanguage = event.target.value;
            updateLanguage(selectedLanguage);
            localStorage.setItem('selectedLanguage', selectedLanguage);
        });
    });






</script>

<script>
var player, isPlaylist = false, playlistData = [], currentIndex = 0, playerReady = false, isLive = false, ctrlMode = 'time';
var ORIGIN = location.protocol + '//' + location.host;

// API Init
var tag = document.createElement('script');
tag.src = 'https://www.youtube.com/iframe_api';
document.head.appendChild(tag);

function onYouTubeIframeAPIReady() { createPlayer(null, null); }

function createPlayer(videoId, listId) {
    playerReady = false;
    if (player && player.destroy) player.destroy();
    document.getElementById('player-wrap').innerHTML = '<div id="youtube-player"></div>';

    player = new YT.Player('youtube-player', {
        height: '0', width: '0',
        playerVars: { origin: ORIGIN, autoplay: 1, enablejsapi: 1 },
        events: {
            onReady: (e) => {
                playerReady = true;
                if (videoId) e.target.loadVideoById(videoId);
                else if (listId) {
                    e.target.loadPlaylist({ list: listId, listType: 'playlist', index: 0 });
                    waitForPlaylistLoad();
                }
            },
            onStateChange: onPlayerStateChange
        }
    });
}

// --- TES FONCTIONS PLAYLIST REPRISES ---
function waitForPlaylistLoad() {
    var iv = setInterval(() => {
        var list = player.getPlaylist ? player.getPlaylist() : null;
        if (list && list.length > 0) { clearInterval(iv); buildPlaylistUI(list); }
    }, 200);
}

function buildPlaylistUI(videoIds) {
    playlistData = videoIds.map(id => ({ videoId: id, title: null }));
    document.getElementById('playlist-section').classList.add('visible');
    renderPlaylistSkeleton();
    updateNavButtons();

    videoIds.forEach((id, i) => {
        fetch('https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=' + id + '&format=json')
            .then(r => r.json()).then(data => {
                playlistData[i].title = data.title;
                updatePlaylistItem(i);
            }).catch(() => {
                playlistData[i].title = 'Vidéo ' + (i + 1);
                updatePlaylistItem(i);
            });
    });
}

function renderPlaylistSkeleton() {
    var list = document.getElementById('playlist-list');
    list.innerHTML = '';
    playlistData.forEach((item, i) => {
        var el = document.createElement('button');
        el.className = 'playlist-item' + (i === currentIndex ? ' active' : '');
        el.id = 'pitem-' + i;
        el.onclick = () => jumpTo(i);
        el.innerHTML = `<span class="item-index">${i+1}</span><span class="item-title">${item.title || '...'}</span>`;
        list.appendChild(el);
    });
}

function updatePlaylistItem(i) {
    var el = document.getElementById('pitem-' + i);
    if (el) el.querySelector('.item-title').textContent = playlistData[i].title;
    if (i === currentIndex) document.getElementById('track-title').textContent = playlistData[i].title;
}

function jumpTo(index) {
    currentIndex = index;
    player.playVideoAt(index);
    updatePlaylistUI();
}

function updatePlaylistUI() {
    document.querySelectorAll('.playlist-item').forEach((el, i) => el.classList.toggle('active', i === currentIndex));
    document.getElementById('track-index').textContent = isPlaylist ? (currentIndex + 1) + ' / ' + playlistData.length : '';
}

// --- NAVIGATION & CONTROLES ---
function prevTrack() { if(isPlaylist && playerReady) player.previousVideo(); }
function nextTrack() { if(isPlaylist && playerReady) player.nextVideo(); }

function onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.PLAYING) {
        isLive = player.getDuration() === 0;
        isPlaylist = !!player.getPlaylist();
        
        if (isPlaylist) {
            currentIndex = player.getPlaylistIndex();
            updatePlaylistUI();
        }
        
        // Auto-switch mode Volume si Live
        if (isLive) {
            setCtrlMode('volume');
            document.getElementById('btn-mode-toggle').style.display = 'none';
        } else {
            document.getElementById('btn-mode-toggle').style.display = 'block';
            setCtrlMode(ctrlMode);
        }

        updateNavButtons();
        updateMetadata();
    }
    document.getElementById('btn-playpause').textContent = (event.data === 1) ? '⏸' : '▶';
}

function updateMetadata() {
    var data = player.getVideoData();
    document.getElementById('track-title').textContent = data.title || (playlistData[currentIndex] ? playlistData[currentIndex].title : "En cours...");
}

function updateNavButtons() {
    document.getElementById('btn-prev').disabled = !isPlaylist;
    document.getElementById('btn-next').disabled = !isPlaylist;
}

// Logic Sliders
setInterval(() => {
    if (playerReady && player.getPlayerState() === 1 && !isLive) {
        const cur = player.getCurrentTime();
        const dur = player.getDuration();
        document.getElementById('progress-musicbar').max = dur;
        document.getElementById('progress-musicbar').value = cur;
        document.getElementById('time-cur').textContent = formatTime(cur);
        document.getElementById('time-total').textContent = formatTime(dur);
    }
}, 1000);

function toggleCtrlMode() { setCtrlMode(ctrlMode === 'time' ? 'volume' : 'time'); }
function setCtrlMode(m) {
    ctrlMode = m;
    const isVol = m === 'volume';
    document.getElementById('box-time').style.display = isVol ? 'none' : 'flex';
    document.getElementById('box-vol').style.display = isVol ? 'flex' : 'none';
    document.getElementById('btn-mode-toggle').textContent = isVol ? '⏳' : '🔊';
}

function formatTime(s) {
    let m = Math.floor(s / 60);
    s = Math.floor(s % 60);
    return m + ":" + (s < 10 ? '0' : '') + s;
}

function seek(v) { player.seekTo(v); }
function setVolume(v) { player.setVolume(v); }
function playPause() { player.getPlayerState() === 1 ? player.pauseVideo() : player.playVideo(); }
function togglemusicpopup() {
    const overlay = document.getElementById('musicpopupOverlay');
    const card = overlay.querySelector('.musicpopup-card');
    const isOpening = !overlay.classList.contains('active');

    if (isOpening) {
        overlay.style.display = 'flex'; // On force le flex pour le centrage
        overlay.classList.add('active');
        card.classList.add('music-boing');
        playErrorSound('sounds/UIOpen2.wav');
    } else {
        overlay.classList.remove('active');
        overlay.style.display = 'none';
        card.classList.remove('music-boing');
        playErrorSound('sounds/UiClose.wav');
    }
}

function loadMedia() {
    var url = document.getElementById('yt-url').value.trim();
    if (!url) return;
    var videoRe = /(?:v=|\/be\/|embed\/)([^?&]+)/;
    var listRe = /[?&]list=([^#&?]+)/;
    var vid = url.match(videoRe), lid = url.match(listRe);
    
    if (lid) { isPlaylist = true; createPlayer(null, lid[1]); }
    else if (vid) { isPlaylist = false; document.getElementById('playlist-section').classList.remove('visible'); createPlayer(vid[1], null); }
    togglemusicpopup();
}
</script>
</body>
</html>
