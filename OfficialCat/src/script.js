document.getElementById('pdfFile').addEventListener('change', function() {
    var fileName = this.files.length > 0 ? this.files[0].name : "No file chosen";
    document.getElementById('file-chosen').textContent = fileName;
});


