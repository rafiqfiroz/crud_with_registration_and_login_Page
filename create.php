<?php 
session_start();
include "authentication.php";
include "config.php";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // More secure password encryption
    $photo = $_FILES['photo'];
    $photo_name = $photo['name'];
    $photo_temp = $photo['tmp_name'];

    // File upload validation
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $file_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
    $max_size = 2 * 1024 * 1024; // 2 MB

    if ($photo['size'] > $max_size) {
        $_SESSION['error'] = "File size exceeds 2 MB.";
        header('Location: create.php');
        exit;
    }

    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
        header('Location: create.php');
        exit;
    }

    // Move the uploaded photo to the 'uploads' directory
    if (!empty($photo_name)) {
        if (move_uploaded_file($photo_temp, 'uploads/' . $photo_name)) {
            $photo_name = $photo_name;
        } else {
            $_SESSION['error'] = "Failed to upload photo.";
            header('Location: create.php');
            exit;
        }
    } else {
        $photo_name = 'avatar.png'; // Default photo if no photo is uploaded
    }

    // Insert data into the database
    $sql = "INSERT INTO users (name, sex, phone, email, password, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $sex, $phone, $email, $password, $photo_name);

    if (mysqli_stmt_execute($stmt)) {
        $log = getHostByName($_SERVER['HTTP_HOST']) . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
            "Record created_" . time() . PHP_EOL .
            "---------------------------------------" . PHP_EOL;
        file_put_contents('logs/log_' . date("j-n-Y") . '.log', $log, FILE_APPEND);

        $_SESSION['success'] = "User registered successfully";
        header('Location: success.php'); // Redirect to a success page or dashboard
        exit;
    } else {
        $_SESSION['error'] = "Something went wrong, Record not inserted: " . mysqli_error($link);
        header('Location: create.php'); // Redirect to the form page with error
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Register User</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name"><strong>Name</strong></label>
                <input type="text" class="form-control" placeholder="Enter full name" name="name" required>
            </div>
            <div class="form-group">
                <label for="sex"><strong>Sex</strong></label><br>
                <input type="radio" name="sex" value="male" required> Male &nbsp;
                <input type="radio" name="sex" value="female" required> Female
            </div>
            <div class="form-group">
                <label for="phone"><strong>Phone</strong></label>
                <input type="text" class="form-control" placeholder="Enter phone number" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email"><strong>Email</strong></label>
                <input type="email" class="form-control" placeholder="Enter email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password"><strong>Password</strong></label>
                <input type="password" class="form-control" placeholder="Enter password" name="password" required>
            </div>
            <div class="form-group">
                <label for="photo"><strong>Photo</strong></label><br>
                <input type="file" name="photo">
            </div>
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
