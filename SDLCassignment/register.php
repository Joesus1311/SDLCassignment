<?php
include "connect.php"; 

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['name'];
    $password = $_POST['password']; 
    $email = $_POST['email'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $role = $_POST['role'];

  
    $stmt = $conn->prepare("SELECT UserID FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "This name already taken please use another username";
    } else {
       
        if (empty($role)) {
            $error = "Please select a role.";
        } else {
            
            $stmt = $conn->prepare("INSERT INTO users (Username, Password, Role, Email, Address, Birthday) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $password, $role, $email, $address,  $birthday);

            if ($stmt->execute()) {
               
                header("Location: login.php"); 
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h1 class="text-center">Register Form</h1>
    <br>
    <form method="post">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="birthday">Date of Birth:</label>
            <input type="date" class="form-control" id="birthday" name="birthday">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <select name="role" required="">
                <option value="">Select Role</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <button type="submit" class="btn btn-success">Register</button>
    </form>
</div>


</body>
</html>