<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    </head>
        <body>
            <button id="capture">Capture Iframe</button>
            <div style="display: flex;">
                <textarea id="html-editor" placeholder="Enter HTML here..." style="width: 50%; height: 300px;"></textarea>
                <textarea id="css-editor" placeholder="Enter CSS here..." style="width: 50%; height: 300px;"></textarea>
            </div>
            <iframe id="preview" style="width: 100%; height: 300px; border: 1px solid #ccc;"></iframe>

            <div class="footer">
                <a href="/">戻る</a>
            </div>
        </body>
        <script>
            const htmlEditor = document.getElementById('html-editor');
            const cssEditor = document.getElementById('css-editor');
            const preview = document.getElementById('preview');
            function updatePreview() {
                const htmlContent = htmlEditor.value;
                const cssContent = cssEditor.value;

                const previewDocument = preview.contentDocument || preview.contentWindow.document;
                previewDocument.open();
                previewDocument.write(`
                    <style>${cssContent}</style>
                    ${htmlContent}
                `);
                previewDocument.close();
            }
            htmlEditor.addEventListener('input', updatePreview);
            cssEditor.addEventListener('input', updatePreview);
            document.getElementById('capture').addEventListener('click', function() {
                const previewDocument = preview.contentDocument || preview.contentWindow.document;
                
                // Make sure the iframe's content is fully loaded before capturing
                html2canvas(previewDocument.body).then(function(canvas) {
                    const img = canvas.toDataURL('image/png');
                    const link = document.createElement('a');
                    link.href = img;
                    link.download = 'iframe-screenshot.png';
                    link.click();
                }).catch(function(error) {
                    console.error('Error capturing the screenshot:', error);
                });
            });
        </script>
    
</html>