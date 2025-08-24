<link rel="stylesheet" href="toolbar.css">

<div id="toolbar">
    <!-- HOME TAB -->
    <div class="toolbar-section">
        <h3>🏠 Home</h3>

        <!-- Clipboard -->
        <div class="toolbar-group">
            <button onclick="formatDoc('cut')">✂ Cut</button>
            <button onclick="formatDoc('copy')">📋 Copy</button>
            <button onclick="formatDoc('paste')">📄 Paste</button>
            <button onclick="formatDoc('removeFormat')">🧹 Clear Formatting</button>
        </div>

        <!-- Font Formatting -->
        <div class="toolbar-group">
            <select onchange="formatDoc('fontName', this.value)">
                <option value="Arial">Arial</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Calibri">Calibri</option>
                <option value="Verdana">Verdana</option>
            </select>

            <select onchange="formatDoc('fontSize', this.value)">
                <option value="1">Small</option>
                <option value="3">Normal</option>
                <option value="5">Large</option>
                <option value="7">Huge</option>
            </select>

            <input type="color" onchange="formatDoc('foreColor', this.value)">
            <input type="color" onchange="formatDoc('hiliteColor', this.value)">
        </div>

        <!-- Text Formatting -->
        <div class="toolbar-group">
            <button onclick="formatDoc('bold')"><b>B</b></button>
            <button onclick="formatDoc('italic')"><i>I</i></button>
            <button onclick="formatDoc('underline')"><u>U</u></button>
            <button onclick="formatDoc('strikeThrough')"><s>S</s></button>
            <button onclick="formatDoc('subscript')">X₂</button>
            <button onclick="formatDoc('superscript')">X²</button>
        </div>

        <!-- Paragraph Formatting -->
        <div class="toolbar-group">
            <button onclick="formatDoc('justifyLeft')">⬅ Left</button>
            <button onclick="formatDoc('justifyCenter')">🔳 Center</button>
            <button onclick="formatDoc('justifyRight')">➡ Right</button>
            <button onclick="formatDoc('justifyFull')">📖 Justify</button>
        </div>

        <div class="toolbar-group">
            <button onclick="formatDoc('insertOrderedList')">1️⃣ Ordered List</button>
            <button onclick="formatDoc('insertUnorderedList')">🔘 Bullet List</button>
            <button onclick="formatDoc('outdent')">↩ Decrease Indent</button>
            <button onclick="formatDoc('indent')">➡ Increase Indent</button>
        </div>

        <!-- Styles -->
        <div class="toolbar-group">
            <select onchange="formatDoc('formatBlock', this.value)">
                <option value="p">Normal</option>
                <option value="h1">Heading 1</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="blockquote">Quote</option>
            </select>
        </div>
    </div>

    <!-- INSERT TAB -->
    <div class="toolbar-section">
        <h3>📄 Insert</h3>

        <div class="toolbar-group">
            <button onclick="insertCoverPage()">📘 Cover Page</button>
            <button onclick="insertBlankPage()">📄 Blank Page</button>
            <button onclick="formatDoc('insertParagraph')">📑 Page Break</button>
        </div>

        <div class="toolbar-group">
            <button onclick="insertTable()">📊 Insert Table</button>
            <button onclick="insertImage()">🖼 Insert Image</button>
            <button onclick="insertShape()">⬛ Insert Shape</button>
            <button onclick="insertIcon()">🎨 Insert Icon</button>
            <button onclick="insert3DModel()">📦 Insert 3D Model</button>
            <button onclick="insertChart()">📈 Insert Chart</button>
            <button onclick="insertScreenshot()">📷 Screenshot</button>
        </div>

        <div class="toolbar-group">
            <button onclick="insertHeader()">🔝 Header</button>
            <button onclick="insertFooter()">🔻 Footer</button>
            <button onclick="insertPageNumber()">🔢 Page Number</button>
            <button onclick="insertTextBox()">✏ Text Box</button>
        </div>
    </div>

    <!-- LAYOUT TAB -->
    <div class="toolbar-section">
        <h3>📑 Layout</h3>

        <div class="toolbar-group">
            <button onclick="setMargins()">📏 Margins</button>
            <button onclick="setOrientation()">↕ Orientation</button>
            <button onclick="setPageSize()">📏 Paper Size</button>
            <button onclick="setColumns()">📖 Columns</button>
            <button onclick="setBreaks()">✂ Breaks</button>
            <button onclick="toggleLineNumbers()">🔢 Line Numbers</button>
            <button onclick="toggleHyphenation()">➖ Hyphenation</button>
        </div>

        <div class="toolbar-group">
            <button onclick="increaseIndent()">➡ Increase Indent</button>
            <button onclick="decreaseIndent()">⬅ Decrease Indent</button>
            <button onclick="setSpacing()">📏 Adjust Spacing</button>
        </div>

        <div class="toolbar-group">
            <button onclick="setPosition()">📍 Position</button>
            <button onclick="wrapText()">🌀 Wrap Text</button>
            <button onclick="bringForward()">⬆ Bring Forward</button>
            <button onclick="sendBackward()">⬇ Send Backward</button>
            <button onclick="alignObjects()">📐 Align</button>
            <button onclick="groupObjects()">📦 Group</button>
            <button onclick="rotateObject()">🔄 Rotate</button>
        </div>
    </div>
</div>



<script>
    // 🔹 GLOBAL FUNCTION TO FORMAT DOCUMENT
    function formatDoc(cmd, value = null) {
        document.execCommand(cmd, false, value);
    }

    // ───────────────────────────────────────────────────────────
    // 🟢 1. PAGE SETUP FUNCTIONS
    // ───────────────────────────────────────────────────────────

    function setMargins() {
        let margin = prompt("Enter margin size in pixels (e.g., 20):");
        if (margin && !isNaN(margin)) {
            document.getElementById("editor").contentWindow.document.body.style.margin = margin + "px";
        } else {
            alert("Invalid input. Please enter a number.");
        }
    }

    function setOrientation() {
        let orientation = prompt("Enter orientation: portrait or landscape?");
        let editor = document.getElementById("editor").contentWindow.document.body;

        if (orientation === "landscape") {
            editor.style.width = "100vh";
            editor.style.height = "70vw";
        } else {
            editor.style.width = "70vw";
            editor.style.height = "100vh";
        }
    }

    function setPageSize() {
        let size = prompt("Enter paper size: Letter, A4, or Legal?");
        let editor = document.getElementById("editor").contentWindow.document.body;

        switch (size.toLowerCase()) {
            case "letter":
                editor.style.width = "8.5in";
                editor.style.height = "11in";
                break;
            case "a4":
                editor.style.width = "8.27in";
                editor.style.height = "11.69in";
                break;
            case "legal":
                editor.style.width = "8.5in";
                editor.style.height = "14in";
                break;
            default:
                alert("Invalid size. Please enter Letter, A4, or Legal.");
        }
    }

    function setColumns() {
        let cols = prompt("Enter number of columns (1-3):");
        if (cols && cols >= 1 && cols <= 3) {
            document.getElementById("editor").contentWindow.document.body.style.columnCount = cols;
        } else {
            alert("Invalid number. Enter between 1 and 3.");
        }
    }

    function setBreaks() {
        document.execCommand("insertHTML", false, "<hr style='page-break-before: always;'>");
    }

    function toggleLineNumbers() {
        let editor = document.getElementById("editor").contentWindow.document;
        let lines = editor.body.innerHTML.split("\n").map((line, index) => `<span>${index + 1}.</span> ${line}`).join("<br>");
        editor.body.innerHTML = lines;
    }

    function toggleHyphenation() {
        document.getElementById("editor").contentWindow.document.body.style.hyphens = "auto";
    }

    // ───────────────────────────────────────────────────────────
    // 🟢 2. PARAGRAPH FORMATTING
    // ───────────────────────────────────────────────────────────

    function increaseIndent() {
        document.execCommand("indent");
    }

    function decreaseIndent() {
        document.execCommand("outdent");
    }

    function setSpacing() {
        let space = prompt("Enter paragraph spacing (e.g., 10px):");
        if (space && !isNaN(space)) {
            document.getElementById("editor").contentWindow.document.body.style.lineHeight = space;
        } else {
            alert("Invalid input. Please enter a number.");
        }
    }

    // ───────────────────────────────────────────────────────────
    // 🟢 3. ARRANGING OBJECTS
    // ───────────────────────────────────────────────────────────

    function setPosition() {
        let position = prompt("Enter position: absolute, relative, fixed, or static?");
        if (["absolute", "relative", "fixed", "static"].includes(position)) {
            document.execCommand("insertHTML", false, `<div style="position:${position};">Positioned Element</div>`);
        } else {
            alert("Invalid position value.");
        }
    }

    function wrapText() {
        let wrap = prompt("Enter wrap type: square, tight, behind, in front?");
        let wrapStyle = wrap === "behind" ? "z-index: -1;" : "z-index: 1;";
        document.execCommand("insertHTML", false, `<div style="display:inline-block;${wrapStyle}">Wrapped Element</div>`);
    }

    function bringForward() {
        document.execCommand("insertHTML", false, `<div style="position:absolute;z-index:2;">Brought Forward</div>`);
    }

    function sendBackward() {
        document.execCommand("insertHTML", false, `<div style="position:absolute;z-index:0;">Sent Backward</div>`);
    }

    function alignObjects() {
        let align = prompt("Enter alignment: left, center, right?");
        if (["left", "center", "right"].includes(align)) {
            document.execCommand("insertHTML", false, `<div style="text-align:${align};">Aligned Object</div>`);
        } else {
            alert("Invalid alignment.");
        }
    }

    function groupObjects() {
        document.execCommand("insertHTML", false, `<div style="display:flex;gap:10px;">Grouped Objects</div>`);
    }

    function rotateObject() {
        let degrees = prompt("Enter rotation degrees (e.g., 45):");
        if (degrees && !isNaN(degrees)) {
            document.execCommand("insertHTML", false, `<div style="transform:rotate(${degrees}deg);">Rotated Object</div>`);
        } else {
            alert("Invalid input. Please enter a number.");
        }
    }

    // ───────────────────────────────────────────────────────────
    // 🟢 4. INSERT FUNCTIONS
    // ───────────────────────────────────────────────────────────

    function insertCoverPage() {
        let editor = document.getElementById("editor").contentWindow.document;
        editor.body.innerHTML = `<div style="width:100%;height:100vh;background:#ddd;display:flex;align-items:center;justify-content:center;">
                                    <h1>Cover Page</h1>
                                </div>` + editor.body.innerHTML;
    }

    function insertBlankPage() {
        let editor = document.getElementById("editor").contentWindow.document;
        editor.body.innerHTML += `<div style="height:100vh;"></div>`;
    }

    function insertTable() {
        let rows = prompt("Enter number of rows:", "3");
        let cols = prompt("Enter number of columns:", "3");
        let table = "<table border='1' style='width:100%;border-collapse:collapse;'>";
        for (let i = 0; i < rows; i++) {
            table += "<tr>";
            for (let j = 0; j < cols; j++) {
                table += "<td contenteditable='true' style='padding:10px;'>Cell</td>";
            }
            table += "</tr>";
        }
        table += "</table>";
        document.execCommand("insertHTML", false, table);
    }

    function insertImage() {
        let url = prompt("Enter image URL:");
        if (url) document.execCommand("insertImage", false, url);
    }

    // ───────────────────────────────────────────────────────────
    // 🟢 5. INITIALIZE THE EDITOR ON PAGE LOAD
    // ───────────────────────────────────────────────────────────
    
    document.addEventListener("DOMContentLoaded", function () {
        let editor = document.getElementById("editor");
        editor.contentWindow.document.designMode = "On";
        editor.contentWindow.document.body.style.fontFamily = "Arial, sans-serif";
    });

</script>
