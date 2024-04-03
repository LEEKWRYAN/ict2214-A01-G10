<!DOCTYPE html>
<html lang="en">

<!--Common Headers-->
<?php 
    require_once 'common_header.php'; 
?>

<?php 
    require_once 'common_session_yes.php'; 
?>

<head>
    <!-- CSS -->
    <link rel="stylesheet" href="style/login.css" />
    <title>Login | Protect The Flag</title>
</head>

<body>

    <!----------------------- Main Container -------------------------->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!----------------------- Login Container -------------------------->
        <div class="row border rounded-5 p-3 bg-white shadow box-area">

            <!--------------------------- Left Box ----------------------------->
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #1097c0">

                <div class="featured-image mb-3">
                    <img src="image/logo.png" class="img-fluid" style="width: 250px" />
                </div>

                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace;font-weight: 600;">
                    Be Defensive
                </p>

                <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace">
                    Join experienced Security Professionals on this platform.
                </small>

            </div>

            <!-------------------- ------ Right Box ---------------------------->
            <div class="col-md-6 right-box">

                <div class="row align-items-center" >

                <?php

                    if (isset($_POST["login"])) 
                    {
                        $email = $_POST["email"];
                        $password = $_POST["password"];

                        require_once "database.php"; // Make sure the database connection is established

                        // Check if the email exists in the database
                        $sql_check_email = "SELECT * FROM users WHERE email = ?";
                        $stmt_check_email = mysqli_stmt_init($conn);

                        if (mysqli_stmt_prepare($stmt_check_email, $sql_check_email)) 
                        {
                            mysqli_stmt_bind_param($stmt_check_email, "s", $email);
                            mysqli_stmt_execute($stmt_check_email);
                            $result_check_email = mysqli_stmt_get_result($stmt_check_email);
                            $user = mysqli_fetch_array($result_check_email, MYSQLI_ASSOC);

                            if ($user) 
                            {
                                // Check if the account is locked
                                if ($user['locked'] == 1) 
                                {
                                    // Check if the lock period has expired
                                    $locked_time = strtotime($user['locked_time']);
                                    $current_time = time();
                                    $lock_period = 60; // 1 minute 

                                    if (($current_time - $locked_time) >= $lock_period) 
                                    {
                                        echo "<div class='alert alert-danger'>Your account is locked. Please try again later.</div>";
                                        exit();
                                    } 
                                    else 
                                    {
                                        // If the lock period has expired, unlock the account
                                        $sql_unlock_account = "UPDATE users SET locked = 0, attempts = 3, locked_time = NULL WHERE email = ?";
                                        $stmt_unlock_account = mysqli_stmt_init($conn);

                                        if (mysqli_stmt_prepare($stmt_unlock_account, $sql_unlock_account)) 
                                        {
                                            mysqli_stmt_bind_param($stmt_unlock_account, "s", $email);
                                            
                                            if(mysqli_stmt_execute($stmt_unlock_account)) 
                                            {
                                                echo "Account unlocked successfully.";
                                            } 
                                            else 
                                            {
                                                echo "Error: " . mysqli_error($conn);
                                            }
                                        }
                                    }
                                }

                                // Check if the password matches
                                if (password_verify($password, $user["password"])) 
                                {
                                    // Reset the failed login attempts counter
                                    $sql_reset_attempts = "UPDATE users SET attempts = 3 WHERE email = ?";
                                    $stmt_reset_attempts = mysqli_stmt_init($conn);

                                    if (mysqli_stmt_prepare($stmt_reset_attempts, $sql_reset_attempts)) 
                                    {
                                        mysqli_stmt_bind_param($stmt_reset_attempts, "s", $email);
                                        mysqli_stmt_execute($stmt_reset_attempts);
                                    }

                                    // Start the session and redirect to index.php
                                    session_start();
                                    $_SESSION["user"] = "yes";
                                    header("Location: ./index.php");

                                    // Assuming login is successful
                                    $cookie_value = bin2hex(random_bytes(16));

                                    // Setting the cookie to expire at the end of the session
                                    setcookie("PTF_cookie", $cookie_value, 0, "/");

                                    $nonce_value = bin2hex(random_bytes(16)); // Generate a random value (16 bytes in this case)
                                    
                                    // Store the nonce value in the session
                                    $_SESSION['nonce'] = $nonce_value;

                                    die();
                                } 
                                else 
                                {
                                    // Increment the failed login attempts counter
                                    $failed_attempts = $user['attempts'] - 1;
                                    $sql_update_attempts = "UPDATE users SET attempts = ? WHERE email = ?";
                                    $stmt_update_attempts = mysqli_stmt_init($conn);

                                    if (mysqli_stmt_prepare($stmt_update_attempts, $sql_update_attempts)) 
                                    {
                                        mysqli_stmt_bind_param($stmt_update_attempts, "is", $failed_attempts, $email);
                                        mysqli_stmt_execute($stmt_update_attempts);
                                    }

                                    // Check if the user has reached the maximum allowed attempts
                                    if ($failed_attempts == 0) 
                                    {
                                        // Lock the account
                                        $current_timestamp = date('Y-m-d H:i:s');
                                        $sql_lock_account = "UPDATE users SET locked = 1, locked_time = ? WHERE email = ?";
                                        $stmt_lock_account = mysqli_stmt_init($conn);

                                        if (mysqli_stmt_prepare($stmt_lock_account, $sql_lock_account))
                                        {
                                            mysqli_stmt_bind_param($stmt_lock_account, "ss", $current_timestamp, $email);
                                            mysqli_stmt_execute($stmt_lock_account);
                                        }

                                        echo "<div class='alert alert-danger'>Your account has been locked after 3 failed login attempts. Please contact support.</div>";
                                        exit();
                                    }

                                    echo "<div class='alert alert-danger'>Password is incorrect. You got $failed_attempts attempts left.</div>";
                                }
                            } 
                            else 
                            {
                                echo "<div class='alert alert-danger'>Email doesn't exist</div>";
                            }
                        }
                    }
                ?>

                    <form action="login.php" method="post">

                        <div class="header-text mb-4">
                            <h2>Hello, Again</h2>
                            <p>We are happy to have you back.</p>
                        </div>

                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email address" id="inputEmail" name="email" autocomplete="off"/>
                        </div>

                        <div class="input-group mb-1">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" id="inputPassword" name="password" autocomplete="off"/>
                        </div>
                        
                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-primary w-100 fs-6" style="background: #1097c0" name="login" id="btnLogin">Login</button>
                        </div>

                        <div class="row">
                            <small>Don't have account? <a href="./register.php">Sign Up</a></small>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>

</html>