<?php
session_start();
if (!isset($_SESSION["User"])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Profile</title>
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
                <li class="active"><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="login-box">
                <div class="form-group">
                    <h1>Account Information</h1>
                </div>
                <div class="form-group">
                    <h2>Name:</h2>
                    <p><?php echo $_SESSION["FullName"]; ?></p>
                </div>
                <div class="form-group">
                    <h2>Email:</h2>
                    <p><?php echo $_SESSION["Email"]; ?></p>
                </div>
                <div class="form-group">
                    <a href="change_password.php">Change Password</a>
                </div>
            </div>
    </div>
</body>

</html>
