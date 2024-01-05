<?php
    session_start();
    require "./database/connection.php";
    if (!isset($_SESSION["isLoggedIn"])) {
        header("Location: ../index.php");
        exit();
    }
    
    $user_id = $_SESSION["user_id"];
    
    $sql = "SELECT a.user_id, a.username, a.profile_img, b.post_id, b.text_content, b.attachment, b.created_at FROM users a JOIN posts b ON a.user_id = b.user_id WHERE a.user_id = ? ORDER BY created_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        die("Query failed: " . $db->error);
    }
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
    
    $stmt->close();
    $db->close();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Witter - Profile</title>
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
        <h2>Your Profile, <?php echo $_SESSION["username"]; ?>!</h2>
        <p><strong>Username:</strong> <?php echo $_SESSION["username"]; ?></p>
        <p><strong>E-mail:</strong> <?php echo $_SESSION["email"]; ?></p>
        <img src="./assets/<?=$_SESSION["profile_img"]?>" alt="Profile Picture">
    </div>

    <!-- success message -->
    <?php if(isset($_SESSION["success-message"])){ ?>
        <p style="color:green; text-align:center; margin:auto; padding:20px;"><?=$_SESSION["success-message"]?></p>
    <?php unset($_SESSION["success-message"]); } ?>

    <!-- error message -->
    <?php if(isset($_SESSION["error-message"])){ ?>
        <p style="color:red; text-align:center; margin:auto; padding:20px;"><?=$_SESSION["error-message"]?></p>
    <?php unset($_SESSION["error-message"]); } ?>

    <!-- display all personal posts -->
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
</body>
</html>
