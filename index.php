<?php 
session_start();
include "authentication.php";
include "config.php";

// Initialize page number and limit
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

// Ensure page number is positive
if ($page < 1) {
    $page = 1;
}

// Fetch user data with pagination
$sql = "SELECT * FROM users LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

if (!$query) {
    die('Query Error: ' . mysqli_error($link));
}

// Count total records for pagination
$psql = "SELECT COUNT(*) AS total FROM users";
$pquery = mysqli_query($link, $psql);

if (!$pquery) {
    die('Query Error: ' . mysqli_error($link));
}

$total_row = mysqli_fetch_assoc($pquery);
$total_record = $total_row['total'];
$total_page = ceil($total_record / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
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
    <nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
        <p class="navbar-brand">
            <?php echo isset($_SESSION['id']) ? 'Welcome ' . htmlspecialchars($_SESSION['name']) : ''; ?> |
        </p>
        <p class="nav-item text-white">
            <?php echo isset($_SESSION['login_at']) ? 'Login at: ' . htmlspecialchars($_SESSION['login_at']) : ''; ?> | &nbsp;&nbsp;
        </p>
        <p class="float-right">
            <a href="logout.php" class="btn btn-danger"><i class='fas fa-sign-out-alt'></i> Logout</a>
        </p>
    </nav>
    <div class="container mt-4">
        <h1 class="text-center">User List</h1>

        <!-- Buttons aligned equally to the left -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="create.php" class="btn btn-success"><i class='fas fa-plus'></i> Add User</a>
            </div>
            <div class="col-md-6 text-end">
                <a href="create_assets.php" class="btn btn-success"><i class='fas fa-plus'></i> Add Assets</a>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php } ?>
        <?php if (isset($_SESSION['warning'])) { ?>
            <div class="alert alert-warning"><?php echo htmlspecialchars($_SESSION['warning']); unset($_SESSION['warning']); ?></div>
        <?php } ?>

        <table class="table table-bordered table-striped table-hover">
            <thead class="bg-dark text-center text-white">
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Sex</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if (mysqli_num_rows($query) == 0) { ?>
                    <tr><td colspan="7" class="text-center">No record found</td></tr>
                <?php } else { ?>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>
                                <?php if ($row['sex'] == 'male') { ?>
                                    <i class='fas fa-male'></i> Male
                                <?php } ?>
                                <?php if ($row['sex'] == 'female') { ?>
                                    <i class='fas fa-female'></i> Female
                                <?php } ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="100" height="125" alt="User Photo"></td>
                            <td>
                                <a href="update.php?id=<?php echo urlencode($row['id']); ?>" class="text-dark"><i class='fas fa-edit'></i></a>&nbsp;&nbsp;
                                <a href="delete.php?id=<?php echo urlencode($row['id']); ?>" class="text-dark"><i class='fas fa-trash'></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <ul class="pagination">
            <li class="page-item <?php echo ($page > 1) ? '' : 'disabled'; ?>"><a class="page-link" href="index.php?page=<?php echo ($page - 1); ?>">Previous</a></li>
            <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php } ?>
            <li class="page-item <?php echo ($total_page > $page) ? '' : 'disabled'; ?>"><a class="page-link" href="index.php?page=<?php echo ($page + 1); ?>">Next</a></li>
        </ul>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
