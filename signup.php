<?php
session_start();
include "db.php";

$message = '';

if(isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password === $confirm_password){
        // تحقق إذا البريد موجود مسبقاً
        $check = "SELECT * FROM users WHERE email='$email'";
        $res = $conn->query($check);
        if($res && $res->num_rows>0){
            $message = "Email already exists!";
        } else {
            // إدخال المستخدم الجديد
            $sql = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
            if($conn->query($sql) === TRUE){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $message = "Sign up successful! Welcome, $name.";
            } else {
                $message = "Error: ".$conn->error;
            }
        }
    } else {
        $message = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - My Cake Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container mt-5">
  <h2>Sign Up</h2>
  <?php if($message != '') echo "<p>$message</p>"; ?>
  <form action="signup.php" method="POST">
    <div class="mb-3">
      <label for="name">Name:</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email">Email:</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password">Password:</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="confirm_password">Confirm Password:</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Sign Up</button>
  </form>
  <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>