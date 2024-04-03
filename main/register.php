<!doctype html>
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
    <link rel="stylesheet" href="style/register.css" />
    <title>Registration | Protect The Flag</title>
</head>

<body>

    <div class="container">

        <!-- Container for the entire page content -->
        <div class="logo-container">
            <img src="image/logo.png" class="logo img-fluid" /> <!-- Logo image -->
        </div>

        <!-- Container for the registration form -->
        <div class="form-container">

            <?php

                if (isset($_POST["submit"]))
                {
                    $username = $_POST["username"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $confirm_password = $_POST["confirm_password"];

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $errors = array();
                    
                    if (empty($username) OR empty($email) OR empty($password) OR empty($confirm_password)) 
                    {
                    array_push($errors,"All fields are required");
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                    {
                    array_push($errors, "Email is not valid");
                    }

                   // Password requirements check - 12 or more
                    if (strlen($password) < 12) 
                    {
                        array_push($errors,"Password must be at least 12 characters long");
                    }

                    // Password requirements check - uppercase letter
                    if (!preg_match("/[A-Z]/", $password)) 
                    {
                        array_push($errors, "Password must contain at least one uppercase letter");
                    }

                    // Password requirements check - contains 1 number
                    if (!preg_match("/[0-9]/", $password)) 
                    {
                        array_push($errors, "Password must contain at least one number");
                    }

                    // Password requirements check - contain at least 1 special character
                    if (!preg_match("/[^a-zA-Z0-9]/", $password)) 
                    {
                        array_push($errors, "Password must contain at least one special character");
                    }

                    // If there are any errors, display them
                    if (!empty($errors)) 
                    {
                        foreach ($errors as $error) 
                        {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        echo "<script>setTimeout(function(){ window.location.href = 'register.php'; }, 3000);</script>"; // Redirect back to the registration page after 3 seconds
                        exit(); // Stop further execution if there are errors
                    }

                    if ($password!==$confirm_password) 
                    {
                        array_push($errors,"Password does not match");
                    }

                    require_once "database.php"; // Make sure the database connection is established

                    // Check if the email already exists in the database
                    $sql_check_email = "SELECT * FROM users WHERE email = ?";
                    $stmt_check_email = mysqli_stmt_init($conn);

                    if (mysqli_stmt_prepare($stmt_check_email, $sql_check_email)) 
                    {
                        mysqli_stmt_bind_param($stmt_check_email, "s", $email);
                        mysqli_stmt_execute($stmt_check_email);
                        $result_check_email = mysqli_stmt_get_result($stmt_check_email);
                        $rowCount = mysqli_num_rows($result_check_email);

                        if ($rowCount > 0) 
                        {
                            echo "<div class='alert alert-danger'>Email already exists!</div>";
                            exit(); // Stop further execution if email exists
                        }
                    }
                    
                    // Prepare INSERT statement to insert new user data
                    $sql_insert_user = "INSERT INTO users (username, email, password, locked) VALUES (?, ?, ?, ?)";
                    $stmt_insert_user = mysqli_stmt_init($conn);

                    if (mysqli_stmt_prepare($stmt_insert_user, $sql_insert_user)) 
                    {
                        // Set default value for locked column
                        $locked = 0;
                        
                        // Hash the password
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        
                        // Bind parameters and execute the statement
                        mysqli_stmt_bind_param($stmt_insert_user, "sssi", $username, $email, $passwordHash, $locked);
                        mysqli_stmt_execute($stmt_insert_user);
                        
                        // JavaScript redirect after 1 second
                        echo "<script>setTimeout(function(){ window.location.href = './login.php'; }, 1000);</script>";
                    }
                    else
                    {
                        echo "<div class='alert alert-danger'>Something went wrong: " . mysqli_error($conn) . "</div>";
                    }
                    
                }

            ?>

            <form class="row g-3" action="register.php" method="post">

                <!-- Assign name attribute for username -->
                <div class="col-12">
                    <label for="inputUsername" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="inputUsername" autocomplete="off"> 
                </div>

                 <!-- Assign name attribute for email -->
                <div class="col-12">
                    <label for="inputEmail" class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" id="inputEmail" autocomplete="off">
                </div>

                <!-- Assign name attribute for password -->
                <div class="col-12">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword" autocomplete="off"> 
                </div>

                 <!-- Assign name attribute for confirm password -->
                <div class="col-12">
                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" id="inputConfirmPassword" autocomplete="off">
                </div>

                <!-- Sign up button -->
                <div class="btn-container">
                    <button class="btn btn-primary" name="submit" id="btnSubmit">Sign up</button> 
                </div>

            </form>
</body>

</html>
