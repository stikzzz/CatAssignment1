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
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["txtFile"])) {
            $upload_dir = "uploads/";
            $output_dir = "converted/";
            $pdfFiles = glob("uploads/*.pdf");
            foreach ($pdfFiles as $pdfFile) {
                unlink($pdfFile);
            }
            $pdfFiles = glob("converted/*.pdf");
            foreach ($pdfFiles as $pdfFile) {
                unlink($pdfFile);
            }


            // Get the absolute path of the script
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

            // Process each uploaded file
            foreach ($_FILES["txtFile"]["tmp_name"] as $key => $tmp_name) {
                $target_file = $upload_dir . basename($_FILES["txtFile"]["name"][$key]);

                // Move the uploaded file to the upload directory
                if (move_uploaded_file($tmp_name, $target_file)) {
                    // Output file path for PDF creation
                    $pdfFilePath = $output_dir . pathinfo($target_file, PATHINFO_FILENAME) . ".pdf";

                    // Corrected paths for the PDFBox command
                    $jar_path = "D:\Users\Chin Zhen Ang\Desktop\New folder\htdocs\pdfbox-app-2.0.30.jar";
                    $input_file = $target_file;  // Use the uploaded text file as input
                    $output_file = $pdfFilePath;

                    // Corrected command construction
                    $command = "java -cp \"$jar_path\" TextToPDFConverter.java \"$input_file\" \"$output_file\"";

                    // Execute the command and capture output, error, and return value
                    exec($command, $output, $return);

                    if ($return === 0 && file_exists($pdfFilePath)) {
                    // $pdfFileName = basename($pdfFilePath);
                        //echo "<h2>Conversion successful</h2>";
                        //echo "<a href='{$output_dir}{$pdfFileName}' download>{$pdfFileName}</a><br>";
                    } else {
                        echo "Conversion failed!";
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            

            // Optionally, provide links for each converted PDF file
            echo "<br><br><br><br><br>";
            echo "<h2><br><br><br><re>Conversion completed</h2>";
            echo "<h3><br><br>Download PDF files:</h3>";

            // Start the table structure
            echo "<table>";
            echo "<thead><tr><th>File Name</th><th>Download</th></tr></thead>";
            echo "<tbody>";

            foreach (glob($output_dir . "*.pdf") as $pdfFile) {
                $pdfFileName = basename($pdfFile);
                echo "<tr><td>$pdfFileName</td><td><a href='{$output_dir}{$pdfFileName}' download>{$pdfFileName}</a></td></tr>";
            }
            // Close the table
            echo "</tbody>";
            echo "</table>";

        }
        echo "<br>";
        echo "<a href='index2.0.html' class='back-button'>Return to Home</a>";
        ?>
        </div>
    </main>
</body>
</html>