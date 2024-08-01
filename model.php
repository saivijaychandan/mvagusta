<?php
include 'config.php';

// Get the model ID from the URL and sanitize it
$model_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($model_id <= 0) {
    echo "Invalid model ID.";
    exit();
}

// Fetch the model details from the database
$sql = "SELECT * FROM models WHERE id = $model_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Model not found.";
    exit();
}

$model = $result->fetch_assoc();

// Fetch engine specifications
$sql_specs = "SELECT * FROM engine_specs WHERE model_id = $model_id";
$result_specs = $conn->query($sql_specs);
$specs = $result_specs->fetch_assoc();
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($model['model_name']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Playwrite+BE+VLG:wght@100..400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .model-image {
            max-width: 100%;
            height: auto;
        }
        .specs-table th, .specs-table td {
            text-align: left;
            padding: 8px;
        }
        .specs-table {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .specs-header {
            background-color: #343a40;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($model['image_path']); ?>" alt="<?php echo htmlspecialchars($model['model_name']); ?>" class="model-image img-fluid">
            </div>
            <div class="col-md-6">
                <h1 style="font-family: 'Playwrite BE VLG';"><?php echo htmlspecialchars($model['model_name']); ?></h1>
                <p style="font-family: 'Playwrite BE VLG';"><?php echo htmlspecialchars($model['model_details']); ?></p>
                <div class="specs-header">
                    <h2>Engine Specifications</h2>
                </div>
                <table class="table specs-table">
                    <tr><th>CC</th><td><?php echo htmlspecialchars($specs['cc'] ?? 'N/A'); ?></td></tr>
                    <tr><th>BHP</th><td><?php echo htmlspecialchars($specs['bhp'] ?? 'N/A'); ?></td></tr>
                    <tr><th>Torque</th><td><?php echo htmlspecialchars($specs['torque'] ?? 'N/A'); ?></td></tr>
                    <tr><th>No of Cylinders</th><td><?php echo htmlspecialchars($specs['cylinders'] ?? 'N/A'); ?></td></tr>
                    <tr><th>Transmission</th><td><?php echo htmlspecialchars($specs['transmission'] ?? 'N/A'); ?></td></tr>
                    <tr><th>Fuel Tank Capacity</th><td><?php echo htmlspecialchars($specs['fuel_tank_capacity'] ?? 'N/A'); ?></td></tr>
                    <tr><th>Kerb Weight</th><td><?php echo htmlspecialchars($specs['kerb_weight'] ?? 'N/A'); ?></td></tr>
                </table>
                <!-- Booking Button -->
                <div class="mt-4">
                    <a href="booking.php?model_id=<?php echo htmlspecialchars($model['id']); ?>" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>