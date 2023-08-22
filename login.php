<?php
// start your session
session_start();
 
// Redirect User to dashboard if logged in 
if(isset($_SESSION["user"]) && $_SESSION["user"] === true){
    header("location: dashboard.php");
    exit;
}
 

require_once "connect.php";
 
// Declare variables and initialize with empty values
$username = $password = "";
$username_error = $password_error = $login_error = "";
 
// Post method for form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // validate empty username
    if(empty(trim($_POST["username"]))){
        $username_error = "Username is Requires.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // validate empty password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    if(empty($username_error) && empty($password_error)){
     
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        // Using Prepared Statement
        if($statement = mysqli_prepare($connect, $sql)){
            // Bind Parameters
            mysqli_stmt_bind_param($statement, "s", $username_parameter);
            
            
            $username_parameter = $username;
            
            // Execute statement
            if(mysqli_stmt_execute($statement)){
               // store result values
                mysqli_stmt_store_result($statement);
                
                
                if(mysqli_stmt_num_rows($statement) == 1){                    
                   
                    mysqli_stmt_bind_result($statement, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($statement)){
                        if(password_verify($password, $hashed_password)){
                          
                            
                            // session for data storage
                            $_SESSION["user"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // user redirect to dashboard page
                            header("location: dashboard.php");
                        } else{
                            // error variable and value for wrong password input
                            $error_login = "Wrong username or password.";
                        }
                    }
                } else{
                    // error variable and value for wrong username input
                    $error_login = "Wrong username or password.";
                }
            } else{
                echo "Wrong Credentials, try again later.";
            }

         
            mysqli_stmt_close($statement);
        }
    }
    

    mysqli_close($connect);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<style>
body{
	background-image:url('img/sea.jpeg');
	background-repeat:no-repeat;
	background-position: center;
	background-size:cover;
}
</style>
<body>
    <div class="container w-50 mt-5 bg-dark text-white fw-bolder p-5 opacity-50">
        <h1>Sign In</h1>
        <h4>Enter Login Credentials</h4>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label class="fw-bolder">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label class="fw-bolder">Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group mt-2">
                <input type="submit" class="btn btn-success" value="Login">
            </div>
            <p class="fw-bolder">Don't have an account? <a href="register.php" class="text-decoration-none">Sign up now</a></p>
        </form>
    </div>
	<div class="container">
<h6 class="text-center text-white fw-bolder">Designed & Developed By Mike Obi</h6> 
</div> 
</body>
</html>