<?php
if (isset($_POST['html'])) {
    // Get the HTML content of the table
    $html = $_POST['html'];

    // Set headers for Excel file download
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=exported_table.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output the HTML content directly
    echo $html;
    exit();
} else {
    // Handle the case when HTML content is not received
    echo "Error: HTML content not found.";
}
?>
