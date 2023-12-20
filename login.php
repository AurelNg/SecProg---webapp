<?php
    session_start();
    if (isset($_SESSION["isLoggedIn"])){
        header("Location: ../home.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witter - Sign in</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <form class="index-box" action="./controller/AuthController.php" method="POST">
        <img src="./assets/witter logo - fontbolt.png" id="logo">
        <p>Sign In</p>
        <div class="form-bar">

            <input type="text" placeholder="Username" name="username">
            <input type="password" placeholder="Password" name="password">

        </div>
        <div class="form-buttons">
            <a href="./index.php" class="button">Return</a>
            <button type="submit" name="login"><b>Sign In</b></button>
        </div>
    </form>

    <!-- add error message -->
    <?php if(isset($_SESSION["error-message"])){ ?>
          <p style="color:red; text-align:center; margin:auto; padding:20px;"><?=$_SESSION["error-message"]?></p>  
    <?php unset($_SESSION["error-message"]); } ?>
</body>
</html>