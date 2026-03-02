<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: mainApp/home.php");
    exit;
}

require_once "mainApp/mySQLConnect.php"; 
// Assuming the file above contains: $db = new SQLConnect();
$db = new SQLConnect(); 

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        // USE THE CLASS METHOD HERE
        $userData = $db->verifyLogin($username, $password);

        if($userData){
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $userData['id'];
            $_SESSION["username"] = $userData['username'];                            
            
            header("location: mainApp/home.php");
            exit;
        } else {
            $login_err = "Invalid username or password.";
        }
    }
}
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- CRITICAL: Makes the page scale to fit phone screens -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Flexbox centers the card on larger screens */
        body { 
            font: 14px sans-serif; 
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .wrapper { 
            width: 100%; 
            max-width: 400px; /* Limits width on desktop */
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        /* Make inputs larger for fingers (tappable area) */
        .form-control {
            height: 45px;
            font-size: 16px; /* Prevents iOS from zooming in on focus */
        }
        .btn-primary {
            width: 100%; /* Full width button is easier to hit on mobile */
            height: 45px;
            font-weight: bold;
        }
        @media (max-width: 480px) {
            .wrapper {
                box-shadow: none; /* Flatten UI on small phones */
                background: transparent;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="text-center">Login</h2>
        <p class="text-center text-muted">Please enter your credentials.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <!-- added autocomplete for better mobile experience -->
                <input type="text" name="username" autocomplete="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" autocomplete="current-password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group mt-4">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
