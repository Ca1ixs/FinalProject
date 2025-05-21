<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, tripName, destination, start_date, end_date FROM trips WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$savedTrips = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compass Home</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9fafb;
      margin: 0;
      padding: 0;
      color: #1f2937;
    }

    main {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
    }

    .left-section {
      flex: 2;
    }

    .right-section {
      flex: 1;
      background-color: #f1f5f9;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .left-section h1 {
      font-size: 2.25rem;
      margin-bottom: 1rem;
      color: #004d66;
    }

    .learn-more h2 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: #006699;
    }

    .highlight-card {
      background-color: #fff8ee;
      padding: 1rem;
      border-left: 5px solid #ffcc66;
      margin-bottom: 1rem;
      border-radius: 8px;
    }

    .highlight-card h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      color: #cc6600;
    }

    .highlight-card p {
      font-size: 0.95rem;
      color: #333;
    }

    .right-section h2 {
      font-size: 1.4rem;
      border-bottom: 2px solid #006699;
      padding-bottom: 0.5rem;
      margin-bottom: 1rem;
      color: #004d66;
    }

    .trip-box {
      background-color: #ffffff;
      padding: 1rem;
      margin-bottom: 1rem;
      border: 1px solid #d1d5db;
      border-radius: 10px;
    }

    .trip-box h3 {
      color: #006699;
      margin-bottom: 0.3rem;
    }

    .no-trips-msg {
      font-size: 0.95rem;
      color: #444;
    }

    .no-trips-msg a {
      color: #006699;
      font-weight: bold;
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      main {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <section class="left-section">
    <h1>Welcome to Compass Adventures!</h1>

    <div class="learn-more">
      <h2>Learn More About:</h2>

      <div class="highlight-card">
        <h3>Level 5 Rapids!</h3>
        <p>Put on your helmet and grab your wetsuit — it's time to conquer Siberia’s roaring waters.</p>
      </div>

      <div class="highlight-card">
        <h3>Fly Fishing in the Rockies</h3>
        <p>Catch trout with a seasoned guide while enjoying gourmet camp meals in the wild.</p>
      </div>

      <div class="highlight-card">
        <h3>Puget Sound Kayaking</h3>
        <p>One week of ocean kayaking through calm waters and vibrant marine life.</p>
      </div>
    </div>
  </section>

  <aside class="right-section">
    <h2>Your Planned Trips</h2>

    <?php if (count($savedTrips) > 0): ?>
      <?php foreach ($savedTrips as $trip): ?>
        <div class="trip-box">
          <h3><?= htmlspecialchars($trip['tripName']) ?></h3>
          <p><strong>Destination:</strong> <?= htmlspecialchars($trip['destination']) ?></p>
          <p><strong>Dates:</strong> <?= htmlspecialchars($trip['start_date']) ?> to <?= htmlspecialchars($trip['end_date']) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="no-trips-msg">You have no planned trips yet.<br><a href="TripPlanner.php">Plan your first trip</a></p>
    <?php endif; ?>
  </aside>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
