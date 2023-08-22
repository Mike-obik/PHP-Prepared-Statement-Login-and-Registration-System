<?php
// start your session
session_start();
 
// Redirect user to Login Page if not logged in 
if(!isset($_SESSION["user"]) || $_SESSION["user"] !== true){
    header("location: login.php");
    exit;
}
 
// require your connection file
require_once "connect.php";
 
// Declare variables and initialize with empty values
$new_password = $repeat_password = "";
$new_password_error = $repeat_password_error = "";
 
// Post method for form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // new password validation
    if(empty(trim($_POST["new_password"]))){
        $new_password_error = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 8){
        $new_password_error = "Password must have atleast 8 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    //  Confirm password Validation
    if(empty(trim($_POST["repeat_password"]))){
        $repeat_password_error = "Please confirm the password.";
    } else{
        $repeat_password = trim($_POST["repeat_password"]);
        if(empty($new_password_error) && ($new_password != $repeat_password)){
            $repeat_password_error = "Password don't match.";
        }
    }
        
    // validate inputs
    if(empty($new_password_error) && empty($repeat_password_error)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($statement = mysqli_prepare($connect, $sql)){
            // Bind parameters
            mysqli_stmt_bind_param($statement, "si", $password_parameter, $parameter_id);
            
            // Set parameters
            $password_parameter = password_hash($new_password, PASSWORD_DEFAULT);
            $parameter_id = $_SESSION["id"];
            
            //execute statement
            if(mysqli_stmt_execute($statement)){
                // update password and destroy session
                session_destroy();
				// redirect to Login Page
                header("location: login.php");
                exit();
            } else{
                echo "sorry, you probably did something wrong, try again later.";
            }

            // End statement
            mysqli_stmt_close($statement);
        }
    }
    
    // End connection
    mysqli_close($connect);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
body{
	background-image:url('img/sea.jpeg');
	background-repeat:no-repeat;
	background-position: center;
	background-size:cover;
}
</style>
</head>
<body>
    <div class="container w-50 mt-5 bg-dark text-white fw-bolder p-5 opacity-50">
        <h2>Reset Password</h2>
        <p>Enter reset password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label class="fw-bolder">New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_error; ?></span>
            </div>
            <div class="form-group">
                <label class="fw-bolder">Repeat Password</label>
                <input type="password" name="repeat_password" class="form-control <?php echo (!empty($repeat_password_error)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $repeat_password_error; ?></span>
            </div>
            <div class="form-group mt-2">
                <input type="submit" class="btn btn-success" value="Submit">
                <a class="btn btn-link ml-2 text-danger fw-bolder" href="dashboard.php">Cancel</a>
            </div>
        </form>
    </div>
<div class="container">
<h6 class="text-center text-white fw-bolder">Designed & Developed By Mike Obi</h6> 
</div>     
</body>
</html>