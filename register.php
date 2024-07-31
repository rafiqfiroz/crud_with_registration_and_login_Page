<?php
session_start();
include "config.php";

if (isset($_POST['register'])) {
    $name = $link->real_escape_string($_POST['name']);
    $username = $link->real_escape_string($_POST['username']);
    $password = $link->real_escape_string($_POST['password']);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if the username already exists
    $check_username_query = "SELECT * FROM login WHERE username='$username'";
    $result = mysqli_query($link, $check_username_query);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Username already exists. Please choose another one.";
    } else {
        $sql = "INSERT INTO login (name, username, password) VALUES ('$name', '$username', '$hashed_password')";
    
        if ($link->query($sql) === TRUE) {
            $_SESSION['success'] = "Registration successful!";
            header('location:login.php');
        } else {
            $_SESSION['error'] = "Error: " . $sql . "<br>" . $link->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
  <div id="register">
    <div class="mb-4"><h1 class="text-center">Register</h1></div>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <form action="" method="POST">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class='fas fa-user'></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Enter Name" id="name" name="name" required>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class='fas fa-user'></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Enter Username" id="username" name="username" required>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class='fas fa-key'></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Enter Password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
    </form>
  </div>
</div>
</body>
</html>
