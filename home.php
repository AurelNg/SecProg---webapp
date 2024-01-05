<?php
    session_start();
    if (!isset($_SESSION["isLoggedIn"])){
        header("Location: ../index.php");
    }
    require "./database/connection.php";

    $sql = "SELECT a.user_id, a.username, a.profile_img, b.post_id, b.text_content, b.attachment, b.created_at FROM users a JOIN posts b ON a.user_id = b.user_id ORDER BY created_at DESC";
    $result = $db->query($sql);

    if (!$result) {
        die("Query failed: " . $db->error);
    }
    $data = [];
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
    $db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Witter - Home</title>
</head>
<body>
    <header>
        <img src="./assets/witter logo - fontbolt.png" id="logo">
        <nav>
            <ul>
                <li><a href="./home.php">Home</a></li>
                <li><a href="./profile.php">Profile</a></li>
            </ul>
        </nav>
        <div class="nav-prof">
            <a href="./controller/AuthController.php?logout=1">Logout</a>    
            <img src="./assets/<?=$_SESSION["profile_img"]?>" alt="Profile Picture">
        </div>
    </header>

    <div class="content">
        <h2>Welcome to Witter, <?=$_SESSION["username"]; ?>!</h2>

        <!-- success message -->
        <?php if(isset($_SESSION["success-message"])){ ?>
            <p style="color:green; text-align:center; margin:auto; padding:20px;"><?=$_SESSION["success-message"]?></p>
        <?php unset($_SESSION["success-message"]); } ?>

        <!-- error message -->
        <?php if(isset($_SESSION["error-message"])){ ?>
            <p style="color:red; text-align:center; margin:auto; padding:20px;"><?=$_SESSION["error-message"]?></p>
        <?php unset($_SESSION["error-message"]); } ?>

        <!-- add post -->
        <form class="createpost" action="./controller/PostsController.php" method="POST" enctype="multipart/form-data">
            <h3 style="margin: auto;">Create New Post</h3>
            <label for="text_content">Write your post:</label>
            <textarea name="text_content" class="form-control" placeholder="Write your thoughts..." rows="5" required style="border-radius:5px; resize:none;"></textarea>

            <label for="attachment">Attachment(optional):</label>
            <input type="file" name="attachment" class="form-control">

            <button class="button" type="submit" name="submit_post" style="margin: auto; font-size: 1em;">Post</button>
        </form>

        <!-- display all posts -->
        <div class="tweet-container">
            <?php foreach ($data as $d) { ?>
                <div class="tweet">
                    <img src="./assets/<?=$d["profile_img"]; ?>" alt="Profile Picture" class="profileimg">
                    <div class="tweet-content">
                        <p><strong><?=$d["username"]; ?></strong></p>
                        <p><?=$d["text_content"]; ?></p>
                        <?php if ($d["attachment"] !== null) { ?>
                            <img src="./assets/uploads/<?=$d["attachment"]; ?>" alt="Picture" width = "100px">
                        <?php } ?>
                        <p style="font-size:12px; color:grey"><?=$d["created_at"]?></p>
                    </div>
                </div>
            <?php } ?>
        </div> 
    </div>
</body>
</html>
