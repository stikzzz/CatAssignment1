<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Convert TXT to PDF</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1 class="titleheader">TXT to PDF Converter</h1>
    <div class="container">
        <h1>Convert TXT to PDF</h1>
        <form id="txtToPdfForm" action="ttp.php" method="post" enctype="multipart/form-data">
            <label for="txtFile">Choose TXT file(s):</label>
            <input type="file" id="txtFile" name="txtFile[]" accept=".txt" multiple>
            <!-- <span id="file-chosen">No file chosen</span> -->
            <button type="submit">Convert</button>
        </form>
    </div>
    <p class="text3">Select more than one file when browsing for files to upload multiple files (max 3)</p>
    <!-- Table to display uploaded files -->
    <table>
        <thead>
            <tr>
                <th>File Name</th>
                <th>File Size (KB)</th>
            </tr>
        </thead>
        <tbody id="fileTableBody">
            <tr class="empty-row">
                <td></td>
                <td></td>
            </tr>
            <!-- Other empty rows if needed -->
        </tbody>
    </table>
    <!-- End of table -->

    <a href="index2.0.html" class="back-button">Back to Home</a>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('txtFile').addEventListener('change', function() {
                const fileList = this.files; // Get the list of uploaded files
                const tableBody = document.getElementById('fileTableBody'); // Get the table body element

                // Remove empty rows if present
                const emptyRows = document.querySelectorAll('#fileTableBody .empty-row');
                emptyRows.forEach(row => {
                    tableBody.removeChild(row);
                });

                // Loop through each uploaded file
                for (let i = 0; i < fileList.length; i++) {
                    const file = fileList[i];
                    const fileSize = Math.round(file.size / 1024); // Convert file size to KB (rounding off)

                    // Create a new table row and populate it with file details
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${file.name}</td>
                        <td>${fileSize} KB</td>
                    `;

                    // Check if the filename is too long and add a line break in the same cell
                    if (file.name.length > 20) { // Set your desired character limit for the line break
                        newRow.querySelector('td:first-child').style.whiteSpace = 'pre-wrap';
                    }

                    // Append the new row to the table body
                    tableBody.appendChild(newRow);
                }
            });
        });
    </script>
</body>
</html>
