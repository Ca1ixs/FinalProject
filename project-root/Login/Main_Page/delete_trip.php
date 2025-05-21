<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $trip_id = (int) $_GET['id'];
    $user_id = $_SESSION['user_id'];

    require_once '../db.php'; 

    $stmt = $conn->prepare("DELETE FROM trips WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $trip_id, $user_id);

    if ($stmt->execute()) {
        header("Location: TripPlanner.php");
        exit;
    } else {
        echo "Error deleting trip: " . $conn->error;
    }
} else {
    echo "No valid trip ID specified.";
}
?>
