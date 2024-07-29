<?php
include "config.php";
$id = intval($_GET['id']);

$sql = "DELETE FROM assets WHERE id=$id";
if (mysqli_query($link, $sql)) {
    $_SESSION['success'] = "Asset deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting record: " . mysqli_error($link);
}
header("Location: index.php");
exit();
?>
