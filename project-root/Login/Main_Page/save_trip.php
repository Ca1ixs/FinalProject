<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tripName = $_POST['tripName'];
    $destination = $_POST['destination'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $notes = $_POST['notes'];
    $region = $_POST['region'];
    $city = $_POST['city'];

$activityArray = isset($_POST['activities']) ? $_POST['activities'] : [];
if (!is_array($activityArray)) {
    $activityArray = [$activityArray]; 
}
if (empty($activityArray) && isset($_POST['from_featured'])) {
    $activityArray = ['Planned Destination']; 
}
    $activity = implode(', ', $activityArray);

    $stmt = $conn->prepare("INSERT INTO trips (user_id, tripName, destination, start_date, end_date, notes, region, city, activity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $user_id, $tripName, $destination, $startDate, $endDate, $notes, $region, $city, $activity);

    if ($stmt->execute()) {
        header("Location: TripPlanner.php");
        exit;
    } else {
        echo "Error saving trip: " . $stmt->error;
    }
}
?>
