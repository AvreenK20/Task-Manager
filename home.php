<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Home</title>
</head>

<body>
    <div class="top-nav">
        <div>
            <img src="./icon.png" alt="" class="logo">
        </div>
        <div>
            <ul class="nav-links">
                <li class="active"><a href="home.php">Home</a></li>
                <li><a href="index.php">Dashboard</a></li>
                <?php
                    // Check if the user is logged in
                    if (isset($_SESSION["User"])) {
                        // Display the Log Out button
                         echo '<li><a href="profile.php">Profile</a></li>';
                         echo '<li><a href="logout.php">Log Out</a></li>'; 
                    } else {
                        // Display the Account link
                        echo '<li><a href="login.php">Account</a></li>'; 
                    }
                ?>
            </ul>
        </div>
    </div>

    <div class="mid-nav">
        <div class="section-container">
            <h1>Welcome to Task Manager! A simple tool to manage your tasks efficiently.</h1>
        </div>
    </div>
</body>

</html>