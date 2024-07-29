<?php
session_start();
include "config.php"; // Ensure this file sets up `$link` for database connection

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;
// Use prepared statements for security
$sql = "SELECT * FROM assets LIMIT ? OFFSET ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$query = $stmt->get_result();

// Get total number of records
$total_sql = "SELECT COUNT(*) FROM assets";
$total_result = mysqli_query($link, $total_sql);
$total_row = mysqli_fetch_array($total_result);
$total_record = $total_row[0];
$total_page = ceil($total_record / $limit);
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
        <h2>Assets List</h2>
        <a href="create_assets.php" class="btn btn-success mb-3">Add New Asset</a>
        <?php if(isset($_SESSION['success'])) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php } ?>
        <?php if(isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php } ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Property Owner</th>
                    <th>Asset Number</th>
                    <th>PO Number</th>
                    <th>Warranty Expiry</th>
                    <th>Asset Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $query->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['property_owner']); ?></td>
                        <td><?php echo htmlspecialchars($row['asset_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['po_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['warranty_expiry']); ?></td>
                        <td><?php echo htmlspecialchars($row['asset_location']); ?></td>
                        <td>
                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <li class="page-item <?php echo ($page > 1) ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?php echo ($page < $total_page) ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
