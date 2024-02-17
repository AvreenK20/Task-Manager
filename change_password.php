<?php
session_start();
if (!isset($_SESSION["User"])) {
    header("Location: login.php");
    exit(); 
}

if (isset($_POST["submit"])) {
    $errors = [];
    $email = $_SESSION["Email"];

    require_once "database.php"; 

    // Retrieve user's current password from the database
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if (strlen($currentPassword) === 0 || strlen($newPassword) === 0 || strlen($confirmPassword) === 0) {
        $errors["fields"] = "All fields must be filled";
    }

    if(strlen($newPassword) < 8) {
        $errors["length"] = "Password must be at least 8 characters";
    }

    // Validate current password against the one stored in the database
    $sql = "SELECT Password FROM Users WHERE email = ?"; 

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userData = mysqli_fetch_assoc($result);

    
    if (!password_verify($currentPassword, $userData['Password'])) {
        $errors["current"] = "Current password is incorrect";
    } elseif ($newPassword === $currentPassword) {
        $errors["same"] = "New password must be different than old password";
    }    

    if ($newPassword !== $confirmPassword) {
        $errors["match"] = "New passwords do not match";
    }

    if (empty($errors)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $updateSql = "UPDATE Users SET Password = ? WHERE email = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ss", $hashedPassword, $email);
        mysqli_stmt_execute($updateStmt);

        header("Location: reset_password_success.php");
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
                <?php
                    // Display the Log Out button
                    echo '<li class="active"><a href="profile.php">Profile</a></li>';
                    echo '<li><a href="logout.php">Log Out</a></li>'; 
                ?>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="login-box">
            <h1>Change Password</h1>
            <form method="post" action="">
                <div class="form-group">
                    <input type="password" placeholder="Enter Current Password:" name="current_password" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Enter New Password:" name="new_password" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Confirm New Password:" name="confirm_password" class="form-control">
                </div>
                <?php if(isset($errors["fields"])): ?>
                    <div class="error-message"><?php echo $errors["fields"]; ?></div>
                <?php endif; ?>
                <?php if(isset($errors["current"])): ?>
                     <div class="error-message"><?php echo $errors["current"]; ?></div>
                <?php endif; ?>
                <?php if(isset($errors["same"])): ?>
                     <div class="error-message"><?php echo $errors["same"]; ?></div>
                <?php endif; ?>
                <?php if(isset($errors["length"])): ?>
                     <div class="error-message"><?php echo $errors["length"]; ?></div>
                <?php endif; ?>
                <?php if(isset($errors["match"])): ?>
                     <div class="error-message"><?php echo $errors["match"]; ?></div>
                <?php endif; ?>
                <div class="form-btn">
                    <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
