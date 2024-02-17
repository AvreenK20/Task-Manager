<?php
if (isset($_GET["token"])) {
    $token = $_GET["token"];

    $token_hash = hash("sha256", $token);

    require_once "database.php";

    $sql = "SELECT * FROM Users WHERE reset_token_hash = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token_hash);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($user === null) {
            die("Token not found");
        }

        if (strtotime($user["reset_token_expires_at"]) <= time()) {
            die("Token has expired");
        }
        
    } else {
        die("Error preparing statement");
    }
}    

if (isset($_POST["token"])) {
    $token = $_POST["token"];

$token_hash = hash("sha256", $token);

require_once "database.php";

$sql = "SELECT * FROM Users WHERE reset_token_hash = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $token_hash);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $errors = [];

    if ($user === null) {
        die("Token is invalid");
    }

    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        die("Token has expired");
    }

    if (strlen($_POST["password"]) < 8) {
        $errors["length"] = "Password must be at least 8 characters";
    }

    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        $errors["match"] = "Passwords must match";
    }

    if (empty($errors)) {
        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $sql = "UPDATE Users
                SET Password = ?,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                WHERE UserID = ?";
        
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                    
        mysqli_stmt_bind_param($stmt, "ss", $password_hash, $user["UserID"]);
        mysqli_stmt_execute($stmt);
        header("Location: reset_password_success.php");
    } 

}      
     else {
        die("Error preparing statement");
    }
} 
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Reset Password</title>
            <meta charset="UTF-8">
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
                <div class="login-box">
                    <h2>Reset Password</h2>
                    <form action="" method="post">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <div class="form-group">
                            <input type="password" placeholder="Enter Password:" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Confirm Password:" name="password_confirmation" class="btn btn-primary">
                            <?php if(isset($errors["match"])): ?>
                                <div class="error-message"><?php echo $errors["match"]; ?></div>
                            <?php endif; ?>
                            <?php if(isset($errors["length"])): ?>
                                <div class="error-message"><?php echo $errors["length"]; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-btn">
                            <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </body>
    </html>

