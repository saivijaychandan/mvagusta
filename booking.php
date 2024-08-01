<?php
$conn = new mysqli('localhost', 'root', '', 'bike_company');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input values
    $model_id = isset($_POST['model_id']) ? intval($_POST['model_id']) : 0; // Ensure model_id is an integer
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $dl_info = $conn->real_escape_string($_POST['dl_info']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);

    // Validate model_id
    if ($model_id <= 0) {
        echo "";
        exit();
    }

    // Insert booking into the database
    $sql = "INSERT INTO bookings (model_id, name, email, dl_info, phone_number) VALUES ($model_id, '$name', '$email', '$dl_info', '$phone_number')";
    if ($conn->query($sql) === TRUE) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve model_id from GET request
$model_id = isset($_GET['model_id']) ? intval($_GET['model_id']) : 0;

// Validate model_id
if ($model_id <= 0) {
    echo "";
    exit();
}

// Fetch model details from the database
$sql = "SELECT * FROM models WHERE id = $model_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Model not found.";
    exit();
}

$model = $result->fetch_assoc();
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo htmlspecialchars($model['model_name']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Book <?php echo htmlspecialchars($model['model_name']); ?></h1>
        <form action="booking.php" method="post">
            <input type="hidden" name="model_id" value="<?php echo htmlspecialchars($model['id']); ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="dl_info">DL Info</label>
                <input type="text" class="form-control" id="dl_info" name="dl_info" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <button type="submit" class="btn btn-primary">Book</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>