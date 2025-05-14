<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_data.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION["user_id"];

// Delete the record
$sql = "DELETE FROM rainfall_data WHERE id='$id' AND user_id='$user_id'";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success_message'] = "Record deleted successfully!";
} else {
    $_SESSION['error_message'] = "Error deleting record: " . $conn->error;
}

header("Location: view_data.php");
exit();
?>