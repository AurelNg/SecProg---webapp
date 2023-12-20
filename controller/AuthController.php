<?php
    session_start();
    require "../database/connection.php";

    if(isset($_POST["register"])){
        $username = htmlspecialchars($_POST["username"]);
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $valid = 1;

        if(strlen($username) == 0){
            $valid = 0;
            $error = "Please input username";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $valid = 0;
            $error = "Please enter valid email address";
        } else if (strlen($password) <= 8){
            $valid = 0;
            $error = "Password must be more than 8 character";
        } else if ($password !== $confirm_password){
            $valid = 0;
            $error = "Password and Confirm Password must be the same";
        }

        if($valid == 1){
            $sql = "SELECT * from users where username = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $error = "Username is already taken";
            } else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users(user_id, username, email, `password`, profile_img) VALUES (NULL, ?, ?, ?, 'default.jpg');";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                $stmt->execute();
                $db->close();
                header("Location: ../login.php");
                exit();
            }
        }
        $_SESSION["error-message"] = $error;
        header("Location: ../register.php");
    }
    else if(isset($_POST["login"])){
        $username = htmlspecialchars($_POST["username"]);
        $password = $_POST["password"];
        $valid = 1;

        if(strlen($username) == 0){
            $valid = 0;
            $error = "Must input username";
        } else if(strlen($password) == 0){
            $valid = 0;
            $error = "Must input password";
        }

        if($valid == 1){
            $sql = "SELECT user_id, username, `password` FROM users WHERE username = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $db->close();
            if($result){
                $row = $result->fetch_assoc();
                $storedPassword = $row['password'];
                if(password_verify($password, $storedPassword)){
                    $_SESSION['isLoggedIn'] = TRUE;
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['prof_img'] = $row['prof_img'];
                    header('Location: ../home.php');
                    exit();
                }
                $error = "Wrong username and/or password";
            }
        }
        $_SESSION["error-message"] = $error;
        header("Location: ../login.php");
    }
    else if(isset($_GET['logout'])){
        session_unset();
        session_destroy();
        header('Location: ../index.php');
    }
?>