<?php
include "connect.php";


$username = $password = "";
$username_err = $password_err = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        
        $sql = "SELECT UserID, Username, Password FROM users WHERE Username = ?";
       
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
          
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            
            $param_username = $username;
           
            if (mysqli_stmt_execute($stmt)) {
                
                mysqli_stmt_store_result($stmt);

                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $password_db);

                    
                    if (mysqli_stmt_fetch($stmt)) {
                        
                        if ($password === $password_db) {
                           
                            header("location: main.php");
                            exit();
                        }else{
                           
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            
            mysqli_stmt_close($stmt);
        }
    }
}


mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Login form</h2>
  <form action="login.php" method="POST">
    <div class="mb-3 mt-3">
      <label for="username">Username:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter name" name="username">
      <span class="text-danger"><?php echo $username_err; ?></span>
    </div>
    <div class="mb-3">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
      <span class="text-danger"><?php echo $password_err; ?></span>
    </div>
    <div class="form-check mb-3">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <p>Dont have a account?<a href="register.php" target="_blank">Sign up now</a></p> 
</div>

</body>
</html>