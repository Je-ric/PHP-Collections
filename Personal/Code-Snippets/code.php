<?php 
include 'config.php';


$htmlCode = '';
$cssCode = '';
$jsCode = '';
$error_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
    $html = $conn->real_escape_string($_POST['html']);
    $css = $conn->real_escape_string($_POST['css']);
    $js = $conn->real_escape_string($_POST['js']);
    
    if(isset($_POST['save'])) {
        
        if ($id > 0) {
            $sql = "UPDATE code_snippets SET title='$title', html='$html', css='$css', js='$js' WHERE id=$id";
        } else {
            $sql = "INSERT INTO code_snippets (title, html, css, js) VALUES ('$title', '$html', '$css', '$js')";
        }

        if ($conn->query($sql) === TRUE) {
            if ($id == 0) {
                $id = $conn->insert_id; 
            }
            $success_message = "Code saved successfully with ID: $id";
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Error saving code: " . $conn->error;
        }
    }
}
 else {

    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        $sql = "SELECT html, css, js FROM code_snippets WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $htmlCode = $row['html'];
            $cssCode = $row['css'];
            $jsCode = $row['js'];
        } else {
            echo "Code snippet not found.";
            exit;
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Previewer</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        #editor {
            display: flex;
        }
        textarea {
            width: 300px;
            height: 200px;
            margin: 10px;
            resize: none;
        }
        iframe {
            width: 100%;
            height: 400px;
        }
        .message {
            margin-top: 20px;
            color: green;
        }
        .error {
            margin-top: 20px;
            color: red;
        }
        #fullScreenBtn {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div id="editor">
    <form method="POST" id="codeForm">
        <input type="text" id="title" name="title" placeholder="Title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
        <textarea id="htmlCode" name="html" placeholder="HTML"><?php echo htmlspecialchars($htmlCode); ?></textarea>
        <textarea id="cssCode" name="css" placeholder="CSS"><?php echo htmlspecialchars($cssCode); ?></textarea>
        <textarea id="jsCode" name="js" placeholder="JavaScript"><?php echo htmlspecialchars($jsCode); ?></textarea>
        <input type="hidden" name="id" id="codeId" value="<?php echo isset($id) ? $id : 0; ?>">
        <button type="submit" name="save">Save</button>
        <button type="button" onclick="window.history.back()">Back</button>
    </form>

    </div>
    <button id="fullScreenBtn" onclick="toggleFullScreen()">Toggle Fullscreen</button>
    <iframe id="preview"><?php echo $htmlCode . '<style>' . $cssCode . '</style>' . '<script>' . $jsCode . '</script>'; ?></iframe>
    
    <?php if (isset($success_message)): ?>
        <div class="message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <script>
        function updatePreview() {
            const htmlCode = document.getElementById('htmlCode').value;
            const cssCode = `<style>${document.getElementById('cssCode').value}</style>`;
            const jsCode = `<script>${document.getElementById('jsCode').value}<\/script>`;
            const previewFrame = document.getElementById('preview');
            const preview = previewFrame.contentDocument || previewFrame.contentWindow.document;
            preview.open();
            preview.write(htmlCode + cssCode + jsCode);
            preview.close();
        }

        document.getElementById('htmlCode').addEventListener('input', updatePreview);
        document.getElementById('cssCode').addEventListener('input', updatePreview);
        document.getElementById('jsCode').addEventListener('input', updatePreview);

        window.addEventListener('load', updatePreview);

        function toggleFullScreen() {
            const editor = document.getElementById('editor');
            const preview = document.getElementById('preview');
            const fullScreenBtn = document.getElementById('fullScreenBtn');
            if (editor.style.display !== 'none') {
                editor.style.display = 'none';
                preview.style.width = '100%';
                preview.style.height = '100vh'; 
                fullScreenBtn.textContent = 'Exit Fullscreen';
            } else {
                editor.style.display = 'flex';
                preview.style.width = '100%';
                preview.style.height = '400px'; 
                fullScreenBtn.textContent = 'Toggle Fullscreen';
            }
        }
    </script>

</body>
</html>
