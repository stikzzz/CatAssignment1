<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Convert PDF to TXT</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1 class="titleheader">Conversion Result</h1>
    <main>
        <div class="content">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["pdfFile"])) {
            $upload_dir = "uploads/"; // Relative path to the upload directory
            $output_dir = "converted/"; // Relative path to the output directory
            $txtFiles = glob("uploaded/*.txt");
            foreach ($txtFiles as $txtFile) {
                unlink($txtFile);
            }
            $txtFiles = glob("converted/*.txt");
            foreach ($txtFiles as $txtFile) {
                unlink($txtFile);
            }

            $script_dir = dirname(__FILE__);

            // Combine script directory with relative paths
            $upload_dir = $script_dir . '/' . $upload_dir;
            $output_dir = $script_dir . '/' . $output_dir;

            // Check if the upload directory exists, create it if not
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Check if the output directory exists, create it if not
            if (!is_dir($output_dir)) {
                mkdir($output_dir, 0755, true);
            }

            foreach ($_FILES["pdfFile"]["tmp_name"] as $key => $tmp_name) {
                $file_name = $_FILES["pdfFile"]["name"][$key];
                $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                // Validate if the file is a PDF
                if ($file_type != "pdf" || $_FILES["pdfFile"]["type"][$key] != "application/pdf") {
                    echo "Only PDF files are allowed.";
                    exit();
                }

                // Sanitize the filename
                $file_name = preg_replace("/[^a-zA-Z0-9\._]/", "", basename($file_name));

                // Move the uploaded file to the upload directory
                $target_file = $upload_dir . $file_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $outputFilePath = $output_dir . basename($target_file, ".pdf") . ".txt";

                    $jar_path = "D:\Users\Chin Zhen Ang\Desktop\New folder\htdocs\pdfbox-app-2.0.30.jar";
                    $command = escapeshellcmd("java -jar \"$jar_path\" ExtractText \"$target_file\" \"$outputFilePath\"");

                    // Execute the command
                    exec($command . ' 2>&1', $output, $return);

                    if ($return === 0) {
                        $txt_filename = basename($target_file, ".pdf") . ".txt";
                        $txt_file_path = $output_dir . $txt_filename;

                    } else {
                        echo "Conversion failed! Error: " . implode("<br>", $output);
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            echo "<br><br><br><br><br>";
            echo "<h2><br><br><br><re>Conversion completed</h2>";
            echo "<h3><br><br>Download TXT files:</h3>";

            // Start the table structure
            echo "<table>";
            echo "<thead><tr><th>Download</th></tr></thead>";
            echo "<tbody>";

            foreach (glob($output_dir . "*.txt") as $txtFile) {
                $txt_filename = basename($txtFile);

                // Output each file as a table row with a download link
                echo "<tr><td><a href='{$output_dir}{$txt_filename}' download>{$txt_filename}</a></td></tr>";
            }

            // Close the table
            echo "</tbody>";
            echo "</table>";
        }
        echo "<br>";
        echo "<a href='index2.0.html' class='back-button' >Return to Home</a>";
        ?>
        </div>
    </main>
        <!-- JavaScript for tracking downloads -->
    <script>
        function trackDownload(fileUrl) {
            // Create an anchor element
            const anchor = document.createElement('a');
            anchor.href = fileUrl;
            anchor.download = fileUrl.split('/').pop(); // Set the download filename

            // Simulate a click on the anchor to start the download
            anchor.click();

            // Track download progress (This varies between browsers and might not be fully accurate)
            anchor.addEventListener('progress', function() {
                // Perform actions to track download progress
                console.log('Downloading...', anchor.download);
            });
        }
    </script>
</body>
</html>



