<?php
include 'config.php';

// Fetch models from the database
$sql = "SELECT * FROM models";
$result = $conn->query($sql);
include 'includes/header.php'
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Models</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h1 style="font-family: 'Playwrite BE VLG';">Our Models</h1>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <img src="<?php echo $row['image_path']; ?>" class="card-img-top" alt="<?php echo $row['model_name']; ?>">
                            <div class="card-body">
                                <h5 style="font-family: 'Playwrite BE VLG';" class="card-title"><?php echo $row['model_name']; ?></h5>
                                <p style="font-family: 'Playwrite BE VLG';" class="card-text"><?php echo $row['model_details']; ?></p>
                                <a href="model.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </body>
</html>