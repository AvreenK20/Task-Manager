<?php
session_start();
if (isset($_SESSION["User"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="top-nav">
        <div>
            <img src="./icon.png" alt="" class="logo">
        </div>
        <div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <li class="active"><a href="login.php">Account</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <?php 
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM Users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if(password_verify($password, $user["Password"])) {
                    $_SESSION["User"] = "yes";
                    $_SESSION["UserID"] = $user["UserID"];
                    $_SESSION["FullName"] = $user["FullName"];
                    $_SESSION["Email"] = $user["Email"];
                    header("Location: index.php");
                    die();
                } else {
                    $errors["password"] = "Error: Password does not match";
                }
            } else {
                $errors["email"] = "Error: Email does not exist";

            }
        }
        ?>
        <div class="login-box">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control">
                    <?php if(isset($errors["email"])): ?>
                        <div class="error-message"><?php echo $errors["email"]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter Password:" name="password" class="form-control">
                    <?php if(isset($errors["password"])): ?>
                        <div class="error-message"><?php echo $errors["password"]; ?></div>
                    <?php endif; ?>
                </div>
                <p>Forgot Password? <a href="forgot_password.php">Reset your password</a></p>
                <div class="form-btn">
                    <input type="submit" value="Login" name="login" class="btn btn-primary">
                </div>
            </form>
            <div>
                <p>Not a user? <a href="registration.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html>