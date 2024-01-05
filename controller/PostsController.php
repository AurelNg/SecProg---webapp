<?php
session_start();
require "../database/connection.php";

if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST["submit_post"])) {
    $user_id = $_SESSION["user_id"];
    $text_content = htmlspecialchars($_POST["text_content"]);

    // Filetype validation
    $allowedImageMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $allowedVideoMimeTypes = ['video/mp4', 'video/avi', 'video/quicktime'];

    if(isset($_FILES["attachment"])){
        $attachment = $_FILES["attachment"];
        $fileinfo = pathinfo($attachment["name"]);

        if(in_array($attachment["type"], $allowedImageMimeTypes)){
            $newfilename = time().$_SESSION["username"].".".$fileinfo["extension"];
            $target = "../assets/uploads/".$newfilename;
            if(!move_uploaded_file($attachment["tmp_name"], $target)){
                // Failed to move file
                $_SESSION["error-message"] = "Failed to move the uploaded file.";
                header("Location: ../home.php");
                exit();
            }
        } else {
            // Invalid file type
            $_SESSION["error-message"] = "Invalid file type. Allowed types are JPEG, PNG, GIF, MP4, AVI, QuickTime.";
            header("Location: ../home.php");
            exit();
        }
    } else {
        // No attachment provided
        $newfilename = null;
    }


    // Insert the post into the database
    $sql = "INSERT INTO posts (user_id, text_content, attachment, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iss", $user_id, $text_content, $newfilename);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Successful post insertion
        $_SESSION["success-message"] = "Post successfully created!";
    } else {
        // Failed to insert post
        $_SESSION["error-message"] = "Failed to create post. Please try again.";
    }

    $stmt->close();
}

$db->close();
header("Location: ../home.php");
exit();
?>
