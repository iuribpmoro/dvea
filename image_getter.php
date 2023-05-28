<?php

    if(isset($_GET["filename"])) {
        // Get the requested file name and sanitize it to prevent path traversal
        $filename = $_GET["filename"];
        // Concatenate the sanitized filename with the path
        $file = $filename;

        // Check if the file exists, and if it does, send it to the client
        if(file_exists($file)) {
            // Set the content type header based on the file extension
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            switch($extension){
                case "jpg":
                    header("Content-Type: image/jpg");
                    break;
                case "png":
                    header("Content-Type: image/png");
                    break;
                case "gif":
                    header("Content-Type: image/gif");
                    break;
                default:
                    break;
            }

            // Send the file to the client
            readfile($file);
            exit();
        } else {
            // File not found
            header("HTTP/1.0 404 Not Found");
            echo "File not found.";
            exit();
        }
    }

?>