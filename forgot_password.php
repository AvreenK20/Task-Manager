<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        require_once "vendor/autoload.php";

        if (isset($_POST["reset"])) {
            $email = $_POST["email"];
            require_once "database.php";
            $sql = "SELECT * FROM Users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                $token = bin2hex(random_bytes(16));
                $token_hash = hash("sha256", $token);
        
                $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
        
                $sql2 = "UPDATE Users
                         SET reset_token_hash = ?,
                             reset_token_expires_at = ?
                         WHERE email = ?";
        
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql2)) {
                    mysqli_stmt_bind_param($stmt, "sss", $token_hash, $expiry, $email);
                    mysqli_stmt_execute($stmt);
        
                    // Check if the query updated the token and expiry
                    if (mysqli_stmt_affected_rows($stmt) > 0) {
                        // Send the email with the reset link containing the token

                        $mail = new PHPMailer(true);

                        $mail->isSMTP();
                        $mail->SMTPAuth = true;

                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->Username = "taskmanager967@gmail.com";                        ;
                        $mail->Password = "iuum ocop tzcq hlfo";

                        $mail->isHtml(true);

                        $mail->setFrom("noreply@gmail.com");
                        $mail->addAddress($email);
                        $mail->Subject = "Password Reset";
                        $mail->Body = <<<END
                        Click <a href="http://localhost/task/reset_password.php?token=$token">here</a>
                        END;

                        try {
                            $mail->send();
                            $success_message = "An email has been sent to $email. Please check your inbox to reset your password.";
                        } catch (Exception $e) {
                            $errors["email"] = "Message could not be sent. Mailer error: ($mail->ErrorInfo}";
                        }

                    } else {
                        $errors["email"] = "Error updating token. Please try again.";
                    }
                } else {
                    $errors["email"] = "Error preparing update query.";
                }
            } else {
                $errors["email"] = "Error: Email $email does not exist";
            }
        }
        ?>
        <div class="login-box">
            <h2>Reset Password</h2>
            <form action="forgot_password.php" method="post">
                <div class="form-group">
                    <input type="email" placeholder="Enter Email:" name="email" class="form-control">
                    <?php if(isset($errors["email"])): ?>
                        <div class="error-message"><?php echo $errors["email"]; ?></div>
                    <?php endif; ?>
                </div>
                <?php if(isset($success_message)): ?>
                    <div class="success-message"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <div class="form-btn">
                    <input type="submit" value="Reset" name="reset" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</body>
</html>