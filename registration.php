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
    <title>Registration Form</title>
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
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                $errors["fields"] = "Error: All fields are required";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Error: Email is not valid";
            }
            if (strlen($password)<8) {
                $errors["password"] = "Error: Password must be at least 8 characters long";
            }
            if ($password!==$passwordRepeat) {
                $errors["repeat"] = "Error: Password does not match";
            }

            require_once "database.php";
            $sql = "SELECT * FROM Users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount>0) {
                $errors["email"] = "Error: Email already exists";
            }

            if (count($errors)==0) {
                $sql = "INSERT INTO Users (FullName, Email, Password) VALUES ( ?, ?, ? )";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                if($prepareStmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    $_SESSION["User"] = "yes";
                    $_SESSION["FullName"] = $fullName;
                    $_SESSION["Email"] = $email;
                    $_SESSION["Password"] = $password;
                    header("Location: index.php"); 
                 } else {
                    die("Something went wrong while attempting to register");
                }
            }
        }
        ?>
        <div class="login-box">
            <h2>Register</h2>
            <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
                    <?php if(isset($errors["fields"])): ?>
                        <div class="error-message"><?php echo $errors["fields"]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="email" placeholder="Email:">
                    <?php if(isset($errors["email"])): ?>
                        <div class="error-message"><?php echo $errors["email"]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password:">
                    <?php if(isset($errors["password"])): ?>
                        <div class="error-message"><?php echo $errors["password"]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
                    <?php if(isset($errors["repeat"])): ?>
                        <div class="error-message"><?php echo $errors["repeat"]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-btn">
                    <input type="submit" value="Register" name="submit">
                </div>
            </form>
            <div>
                <p>Already a User? <a href="login.php">Login here</a></p>
            <div>
        </div>    
    </div>
</body>

</html>
