<?php

// Required extensions
$requiredExtensions = ['mysqlnd', 'curl', 'imagick', 'gd', 'simplexml'];

// Get loaded extensions
$loadedExtensions = get_loaded_extensions();

// Check if required extensions are loaded
foreach ($requiredExtensions as $extension) {
    if (in_array($extension, $loadedExtensions)) {
        echo "$extension extension is enabled.\n";
        
    } else {
        echo "$extension extension is not enabled.\n";
    }
}

?>
