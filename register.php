<?php

require_once "connect.php";
 
// Declare variables and initialize with empty values
$fullname = $password = $repeat_password = "";
$email = $password = $repeat_password = "";
$username = $password = $repeat_password = "";

// Post method for form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // validate empty input fields
    if(empty(trim($_POST["fullname"])) && empty(trim($_POST["email"])) && empty(trim($_POST["username"]))){
        $fullname_error = "Please enter a Fullname.";
		$email_error = "Please enter a Email.";
        $username_error = "Please enter a UserName.";   
        
    } 
	 
	else{
      // Using Prepared Statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($statement = mysqli_prepare($connect, $sql)){
            // Bind parameters
            mysqli_stmt_bind_param($statement, "s", $username_parameter);
            
           
            $firstname_parameter = trim($_POST["fullname"]);
			$email_parameter = trim($_POST["email"]);
            $username_parameter = trim($_POST["username"]);
            
             // Execute statement
            if(mysqli_stmt_execute($statement)){
               // store result values
                mysqli_stmt_store_result($statement);
                // validate existing data
                if(mysqli_stmt_num_rows($statement) == 1){
                    $fullname_error = "This Name already exists";
					$email_error = "This Email already exists.";
                    $username_error = "This Username already exists.";    
                } 
				else{
                    $fullname = trim($_POST["fullname"]);
					$email = trim($_POST["email"]);
                    $username = trim($_POST["username"]);
                    
                    
                }
            } else{
                echo "Wrong Credentials. Try again later.";
            }

            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_error = "Password must have at least 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $repeat_password_error = "Please confirm password.";     
    } else{
        $repeat_password = trim($_POST["confirm_password"]);
        if(empty($password_error) && ($password != $repeat_password)){
            $repeat_password_error = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($fullname_error) && empty($email_error) && empty($username_error) && empty($password_error) && empty($repeat_password_error)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
         
        if($statement = mysqli_prepare($connect, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($statement, "ssss",$firstname_parameter,$email_parameter,$username_parameter,$password_parameter);
            
            // Set parameters
            $firstname_parameter = $fullname;
			$email_parameter = $email;
            $username_parameter = $username;
            $password_parameter = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($statement)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "sorry, you probably did something wrong, try again later.";
            }

            // Close statement
            mysqli_stmt_close($statement);
        }
    }
    
    // Close connection
    mysqli_close($connect);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
    <div class="container w-50 mt-5 bg-dark text-white p-5 opacity-50">
        <h1>Sign Up</h1>
        <h4>Create Account.</h4>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
		 <div class="form-group">
                <label class="fw-bolder">Fullname</label>
                <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>">
                <span class="invalid-feedback"><?php echo $fullname_error; ?></span>
            </div> 
			
			 <div class="form-group">
                <label class="fw-bolder">Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_error; ?></span>
            </div> 
			
            <div class="form-group">
                <label class="fw-bolder">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_error; ?></span>
            </div>    
            <div class="form-group">
                <label class="fw-bolder">Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_error; ?></span>
            </div>
            <div class="form-group">
                <label class="fw-bolder">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $repeat_password; ?>">
                <span class="invalid-feedback"><?php echo $repeat_password_error; ?></span>
            </div>
            <div class="form-group mt-2">
                <input type="submit" class="btn btn-success" value="Sign Up">
                <input type="reset" class="btn btn-warning ml-2" value="Reset">
            </div>
            <p class="fw-bolder">Already have an account? <a href="login.php" class="text-decoration-none">Sign In</a></p>
			
        </form>
    </div> 
<div class="container">
<h6 class="text-center text-white fw-bolder">Designed & Developed By Mike Obi</h6> 
</div> 
</body>
</html>