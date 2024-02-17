<!DOCTYPE html>
    <html>
        <head>
            <title>Password</title>
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
                    <h2>Success!</h2>
                        <div class="form-group">
                            <div class="success-message"><?php echo "Password has been changed!"; ?></div>
                        </div>  
                        <div>
                        <p>Want to log in? <a href="logout.php">Click Here</a></p>
                    </div>  
                </div>
            </div>
        </body>
    </html>