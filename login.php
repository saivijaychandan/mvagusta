<?php
session_start();
include 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to check the credentials
    $sql = "SELECT id FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Successful login
        $_SESSION['admin_id'] = $username;
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        // Failed login
        echo "Invalid username or password.";
    }
    $stmt->close();
}
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <h2 class="text-center">Login</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

