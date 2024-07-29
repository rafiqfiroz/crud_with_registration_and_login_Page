<?php
include "config.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_owner = mysqli_real_escape_string($link, $_POST['property_owner']);
    $asset_number = mysqli_real_escape_string($link, $_POST['asset_number']);
    $po_number = mysqli_real_escape_string($link, $_POST['po_number']);
    $warranty_expiry = mysqli_real_escape_string($link, $_POST['warranty_expiry']);
    $asset_location = mysqli_real_escape_string($link, $_POST['asset_location']);

    $sql = "INSERT INTO assets (property_owner, asset_number, po_number, warranty_expiry, asset_location) 
            VALUES ('$property_owner', '$asset_number', '$po_number', '$warranty_expiry', '$asset_location')";

    if (mysqli_query($link, $sql)) {
        $_SESSION['success'] = "New asset created successfully!";
        header("Location: index_assets.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .fas { font-size: 20px; }
        .fa-edit:hover { color: green; }
        .fa-trash:hover { color: red; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Asset</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="property_owner" class="form-label">Property Owner:</label>
                <input type="text" class="form-control" name="property_owner" id="property_owner" required>
            </div>
            <div class="mb-3">
                <label for="asset_number" class="form-label">Asset Number:</label>
                <input type="text" class="form-control" name="asset_number" id="asset_number" required>
            </div>
            <div class="mb-3">
                <label for="po_number" class="form-label">PO Number:</label>
                <input type="text" class="form-control" name="po_number" id="po_number" required>
            </div>
            <div class="mb-3">
                <label for="warranty_expiry" class="form-label">Warranty Expiry:</label>
                <input type="date" class="form-control" name="warranty_expiry" id="warranty_expiry" required>
            </div>
            <div class="mb-3">
                <label for="asset_location" class="form-label">Asset Location:</label>
                <input type="text" class="form-control" name="asset_location" id="asset_location" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Asset</button>
            <a href="index_assets.php" class="btn btn-secondary ms-2">Back to list</a>
        </form>
    </div>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
