<?php
session_start();
include 'config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include 'includes/header.php';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    $model_name = $_POST['model_name'] ?? '';
    $model_details = $_POST['model_details'] ?? '';
    $image_path = $_POST['image_path'] ?? '';
    $cc = $_POST['cc'] ?? 0;
    $bhp = $_POST['bhp'] ?? 0;
    $cylinders = $_POST['cylinders'] ?? 0;
    $fuel_tank_capacity = $_POST['fuel_tank_capacity'] ?? 0;
    $kerb_weight = $_POST['kerb_weight'] ?? 0;
    $torque = $_POST['torque'] ?? 0;
    $transmission = $_POST['transmission'] ?? '';

    if ($action == 'add_model') {
        $sql = "INSERT INTO models (model_name, model_details, image_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $model_name, $model_details, $image_path);
        $stmt->execute();
        $model_id = $stmt->insert_id;

        $sql = "INSERT INTO engine_specs (model_id, cc, bhp, torque, cylinders, transmission, fuel_tank_capacity, kerb_weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiiiisii', $model_id, $cc, $bhp, $torque, $cylinders, $transmission, $fuel_tank_capacity, $kerb_weight);
        $stmt->execute();

    } elseif ($action == 'update_model') {
        $id = $_POST['id'];
        $sql = "UPDATE models SET model_name = ?, model_details = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $model_name, $model_details, $image_path, $id);
        $stmt->execute();

        $sql = "UPDATE engine_specs SET cc = ?, bhp = ?, torque = ?, cylinders = ?, transmission = ?, fuel_tank_capacity = ?, kerb_weight = ? WHERE model_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiiiisii', $cc, $bhp, $torque, $cylinders, $transmission, $fuel_tank_capacity, $kerb_weight, $id);
        $stmt->execute();

    } elseif ($action == 'delete_model') {
        $id = $_POST['id'];

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Delete from engine_specs first
            $sql = "DELETE FROM engine_specs WHERE model_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();

            // Delete from models
            $sql = "DELETE FROM models WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();
        } catch (mysqli_sql_exception $exception) {
            // Rollback the transaction in case of error
            $conn->rollback();
            echo "Error deleting model: " . $exception->getMessage();
        }
    }
}

// Fetch models and their engine specifications from the database
$sql = "SELECT m.*, e.cc, e.bhp, e.torque, e.cylinders, e.transmission, e.fuel_tank_capacity, e.kerb_weight 
        FROM models m 
        LEFT JOIN engine_specs e ON m.id = e.model_id";
$result = $conn->query($sql);

// Fetch bookings from the database
$sql_bookings = "SELECT b.id, b.name, b.email, b.dl_info, b.phone_number, m.model_name 
                 FROM bookings b 
                 JOIN models m ON b.model_id = m.id";
$result_bookings = $conn->query($sql_bookings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Control</h1>
        <form action="logout.php" method="post">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <!-- Add Model Form -->
        <form action="admin.php" method="post">
            <input type="hidden" name="action" value="add_model">
            <div class="form-group">
                <label for="model_name">Model Name</label>
                <input type="text" class="form-control" id="model_name" name="model_name" required>
            </div>
            <div class="form-group">
                <label for="model_details">Model Details</label>
                <textarea class="form-control" id="model_details" name="model_details" required></textarea>
            </div>
            <div class="form-group">
                <label for="image_path">Image Path</label>
                <input type="text" class="form-control" id="image_path" name="image_path" required>
            </div>
            <div class="form-group">
                <label for="cc">CC</label>
                <input type="number" class="form-control" id="cc" name="cc" required>
            </div>
            <div class="form-group">
                <label for="bhp">BHP</label>
                <input type="number" class="form-control" id="bhp" name="bhp" required>
            </div>
            <div class="form-group">
                <label for="torque">Torque</label>
                <input type="number" class="form-control" id="torque" name="torque" required>
            </div>
            <div class="form-group">
                <label for="cylinders">Cylinders</label>
                <input type="number" class="form-control" id="cylinders" name="cylinders" required>
            </div>
            <div class="form-group">
                <label for="transmission">Transmission</label>
                <input type="text" class="form-control" id="transmission" name="transmission" required>
            </div>
            <div class="form-group">
                <label for="fuel_tank_capacity">Fuel Tank Capacity</label>
                <input type="number" class="form-control" id="fuel_tank_capacity" name="fuel_tank_capacity" required>
            </div>
            <div class="form-group">
                <label for="kerb_weight">Kerb Weight</label>
                <input type="number" class="form-control" id="kerb_weight" name="kerb_weight" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Model</button>
        </form>
        <hr>
        <!-- Update and Delete Models -->
        <h2>Manage Models</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Model Name</th>
                    <th>Details</th>
                    <th>Image Path</th>
                    <th>CC</th>
                    <th>BHP</th>
                    <th>Torque</th>
                    <th>Cylinders</th>
                    <th>Transmission</th>
                    <th>Fuel Tank Capacity</th>
                    <th>Kerb Weight</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['model_name'] ?? ''; ?></td>
                        <td><?php echo $row['model_details'] ?? ''; ?></td>
                        <td><?php echo $row['image_path'] ?? ''; ?></td>
                        <td><?php echo $row['cc'] ?? ''; ?></td>
                        <td><?php echo $row['bhp'] ?? ''; ?></td>
                        <td><?php echo $row['torque'] ?? ''; ?></td>
                        <td><?php echo $row['cylinders'] ?? ''; ?></td>
                        <td><?php echo $row['transmission'] ?? ''; ?></td>
                        <td><?php echo $row['fuel_tank_capacity'] ?? ''; ?></td>
                        <td><?php echo $row['kerb_weight'] ?? ''; ?></td>
                        <td>
                            <!-- Update Model Form -->
                            <form action="admin.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="action" value="update_model">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="model_name" value="<?php echo $row['model_name'] ?? ''; ?>" required>
                                <input type="text" name="model_details" value="<?php echo $row['model_details'] ?? ''; ?>" required>
                                <input type="text" name="image_path" value="<?php echo $row['image_path'] ?? ''; ?>" required>
                                <input type="number" name="cc" value="<?php echo $row['cc'] ?? ''; ?>" required>
                                <input type="number" name="bhp" value="<?php echo $row['bhp'] ?? ''; ?>" required>
                                <input type="number" name="torque" value="<?php echo $row['torque'] ?? ''; ?>" required>
                                <input type="number" name="cylinders" value="<?php echo $row['cylinders'] ?? ''; ?>" required>
                                <input type="text" name="transmission" value="<?php echo $row['transmission'] ?? ''; ?>" required>
                                <input type="number" name="fuel_tank_capacity" value="<?php echo $row['fuel_tank_capacity'] ?? ''; ?>" required>
                                <input type="number" name="kerb_weight" value="<?php echo $row['kerb_weight'] ?? ''; ?>" required>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </form>
                            <!-- Delete Model Form -->
                            <form action="admin.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="action" value="delete_model">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <hr>

        <!-- Display Bookings -->
        <h2>Manage Bookings</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>DL Info</th>
                    <th>Phone</th>
                    <th>Model</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['dl_info']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><?php echo $row['model_name']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
