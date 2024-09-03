import html2canvas from 'html2canvas';

const htmlEditor = document.getElementById('html-editor');
const cssEditor = document.getElementById('css-editor');
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

document.getElementById('submit').addEventListener('click', function () {
  const preview = document.getElementById('preview');
  const previewDocument = preview.contentDocument || preview.contentWindow.document;

  // Make sure the iframe's content is fully loaded before capturing
  html2canvas(previewDocument.body).then(function (canvas) {
    const img = canvas.toDataURL('image/png');
    const imgTag = document.getElementById('image');
    imgTag.value = img;
    document.getElementById('form').submit();
    // フォームデータを作成
    const formData = new FormData();
    formData.append('image', img);
    formData.append('html', htmlEditor.value);
    formData.append('css', cssEditor.value);

    
  }).catch(function (error) {
    console.error('Error capturing the screenshot:', error);
  });
});

