<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$destinations = [
  [
    'title' => 'California Surf Safari',
    'price' => 960,
    'duration' => 5,
    'type' => 'Surfing',
    'image' => 'Compass_Site/Assets/images/surfing.jpg',
    'detailsPage' => 'details_surf.php',
    'description' => 'Get ready for hurricane-inspired swells and 5 mornings of surf bliss! Choose between beach breaks and long point breaks. Stay in Newport Beach and ride world-class waves at Trestles, The Wedge, and more.',
    'region' => 'North America',
    'city' => 'Los Angeles'
  ],
  [
    'title' => 'Devil’s Tower Getaway',
    'price' => 740,
    'duration' => 5,
    'type' => 'Climbing',
    'image' => 'Compass_Site/Assets/images/DevilTower.jfif',
    'detailsPage' => 'details_climb.php',
    'description' => 'Climb the legendary 865 ft tower in Wyoming with over 200 challenging routes. This 5-day trip is perfect for adventurous climbers looking for a national monument experience.',
    'region' => 'North America',
    'city' => 'Denver'
  ],
  [
    'title' => 'Karapoti Trail Adventure (NZ)',
    'price' => 1490,
    'duration' => 4,
    'type' => 'Biking',
    'image' => 'Compass_Site/Assets/images/Karapoti_2.jpg',
    'detailsPage' => 'details_karapoti.php',
    'description' => 'Take on the 31-mile Karapoti Trail with technical terrain and scenic mountain views. Ideal for mountain bikers who want both beauty and burn.',
    'region' => 'Australia',
    'city' => 'Wellington'
  ],
  [
    'title' => 'Cycling the Irma Coastline',
    'price' => 1490,
    'duration' => 4,
    'type' => 'Biking',
    'image' => 'Compass_Site/Assets/images/Cycling.jpg',
    'detailsPage' => 'details_cycling.php',
    'description' => 'Steep cliffs, fast roads, and beautiful coastal scenery await. This route is ideal for experienced cyclists who crave adrenaline and ocean views.',
    'region' => 'Europe',
    'city' => 'Naples'
  ],
  [
    'title' => 'Rutan Islands Kayaking',
    'price' => 1200,
    'duration' => 6,
    'type' => 'Kayaking',
    'image' => 'Compass_Site/Assets/images/kayaker.jfif',
    'detailsPage' => 'details_rutan.php',
    'description' => 'Rush through 50 mph rapids and cascading waterfalls before unwinding in serene local villages. This is our craziest kayaking experience yet.',
    'region' => 'Asia',
    'city' => 'Manila'
  ]
];


$filtered = [];
foreach ($destinations as $index => $dest) {
  $matches = true;
  $min = isset($_GET['min_price']) ? (int)$_GET['min_price'] : null;
  $max = isset($_GET['max_price']) ? (int)$_GET['max_price'] : null;

  if (!empty($min) && $dest['price'] < $min) $matches = false;
  if (!empty($max) && $dest['price'] > $max) $matches = false;
  if (!empty($_GET['duration']) && $dest['duration'] != $_GET['duration']) $matches = false;
  if (!empty($_GET['type']) && $dest['type'] != $_GET['type']) $matches = false;

  if ($matches) $filtered[] = array_merge($dest, ['id' => $index]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Featured Destinations</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f6f6f6;
      margin: 0;
      padding: 0;
    }

    .trip-planner-container {
      display: flex;
      flex-direction: column;
      gap: 40px;
      align-items: center;
      padding: 40px 20px;
    }

    .featured-destinations {
      max-width: 1000px;
      width: 100%;
    }

    .featured-destinations h2 {
      font-size: 2.2rem;
      color: #004d66;
      margin-bottom: 30px;
      text-align: center;
    }

    .destination {
      display: flex;
      gap: 30px;
      margin-bottom: 40px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      align-items: flex-start;
    }

    .destination-text {
      flex: 1;
    }

    .destination h3 {
      margin-top: 0;
      color: #006699;
      font-size: 1.5rem;
    }

    .destination p {
      margin: 8px 0;
      line-height: 1.5;
      font-size: 0.95rem;
    }

    .destination-image {
      width: 250px;
      text-align: center;
    }

    .destination-image img {
      max-width: 100%;
      max-height: 200px;
      object-fit: cover;
      border-radius: 8px;
    }

    .price-tag {
      margin-top: 10px;
      font-weight: bold;
      color: #333;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 100;
      padding-top: 100px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: white;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 90%;
      max-width: 600px;
      border-radius: 10px;
      position: relative;
    }

    .close {
      color: #aaa;
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .destination {
        flex-direction: column;
        align-items: center;
      }

      .destination-text, .destination-image {
        width: 100%;
        text-align: center;
      }

      .destination-text {
        margin-bottom: 20px;
      }
    }

    form.destination-filters {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 30px;
    }

    form.destination-filters label {
      display: flex;
      flex-direction: column;
      font-weight: 600;
      color: #004d66;
    }

    form.destination-filters input,
    form.destination-filters select,
    form.destination-filters button {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    form.destination-filters button {
      background-color: #006699;
      color: white;
      cursor: pointer;
    }

    form.destination-filters button:hover {
      background-color: #004d66;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <section class="trip-planner-container">
    <div class="featured-destinations">
      <h2>Featured Destinations</h2>

      <form method="GET" class="destination-filters">
        <label>
          Min Price:
          <input type="number" name="min_price" min="0" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
        </label>
        <label>
          Max Price:
          <input type="number" name="max_price" min="0" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
        </label>
        <label>
          Duration:
          <select name="duration">
            <option value="">Any</option>
            <option value="3" <?= ($_GET['duration'] ?? '') === '3' ? 'selected' : '' ?>>3 Days</option>
            <option value="4" <?= ($_GET['duration'] ?? '') === '4' ? 'selected' : '' ?>>4 Days</option>
            <option value="5" <?= ($_GET['duration'] ?? '') === '5' ? 'selected' : '' ?>>5 Days</option>
            <option value="6" <?= ($_GET['duration'] ?? '') === '6' ? 'selected' : '' ?>>6 Days</option>
          </select>
        </label>
        <label>
          Experience Type:
          <select name="type">
            <option value="">Any</option>
            <option value="Surfing" <?= ($_GET['type'] ?? '') === 'Surfing' ? 'selected' : '' ?>>Surfing</option>
            <option value="Climbing" <?= ($_GET['type'] ?? '') === 'Climbing' ? 'selected' : '' ?>>Climbing</option>
            <option value="Biking" <?= ($_GET['type'] ?? '') === 'Biking' ? 'selected' : '' ?>>Biking</option>
            <option value="Kayaking" <?= ($_GET['type'] ?? '') === 'Kayaking' ? 'selected' : '' ?>>Kayaking</option>
          </select>
        </label>
        <button type="submit">Apply Filters</button>
      </form>

      <?php if (count($filtered) === 0): ?>
        <p style="text-align: center; color: #666;">No destinations match your filters.</p>
      <?php else: ?>
        <?php foreach ($filtered as $dest): ?>
          <div class="destination">
            <div class="destination-text">
              <h3><?= htmlspecialchars($dest['title']) ?></h3>
              <p><?= htmlspecialchars($dest['description']) ?></p>
            </div>
            <div class="destination-image">
              <a class="more-details-btn" href="javascript:void(0);" onclick="openModal(<?= $dest['id'] ?>)">
                <img src="Compass_Site/Assets/images/MoreDetails.gif" alt="More Details">
              </a>
              <img src="<?= htmlspecialchars($dest['image']) ?>" alt="<?= htmlspecialchars($dest['title']) ?>">
              <div class="price-tag">$<?= number_format($dest['price']) ?> — Includes lodging, food, and airfare</div>
            </div>
          </div>

          <div id="modal-<?= $dest['id'] ?>" class="modal">
            <div class="modal-content">
              <span class="close" onclick="closeModal(<?= $dest['id'] ?>)">&times;</span>
              <h2><?= htmlspecialchars($dest['title']) ?></h2>
              <p><strong>Description:</strong> <?= htmlspecialchars($dest['description']) ?></p>
              <p><strong>Price:</strong> $<?= number_format($dest['price']) ?></p>
              <p><strong>Duration:</strong> <?= $dest['duration'] ?> nights</p>
              <ul>
                <li>✔️ Airfare Included</li>
                <li>✔️ Lodging</li>
                <li>✔️ Food</li>
                <li>✔️ Local Guide</li>
              </ul>
<form method="POST" action="save_trip.php" style="margin-top: 20px;">
  <input type="hidden" name="tripName" value="<?= htmlspecialchars($dest['title']) ?>">
<input type="hidden" name="region" value="<?= htmlspecialchars($dest['region']) ?>">
<input type="hidden" name="city" value="<?= htmlspecialchars($dest['city']) ?>">
  <input type="hidden" name="destination" value="<?= htmlspecialchars($dest['title']) ?>">
  <input type="hidden" name="startDate" value="<?= date('Y-m-d') ?>">
  <input type="hidden" name="endDate" value="<?= date('Y-m-d', strtotime('+'.intval($dest['duration']).' days')) ?>">
  <input type="hidden" name="notes" value="<?= htmlspecialchars($dest['description']) ?>">
  <input type="hidden" name="from_featured" value="1">
  <button type="submit" style="margin-top: 10px; padding: 10px 20px; background-color: #006699; color: white; border: none; border-radius: 6px;">Book This Trip</button>
</form>

            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<script>
  function openModal(id) {
    document.getElementById('modal-' + id).style.display = 'block';
  }
  function closeModal(id) {
    document.getElementById('modal-' + id).style.display = 'none';
  }
  window.onclick = function(event) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (event.target === modal) modal.style.display = "none";
    });
  };
</script>

<?php include 'footer.php'; ?>
</body>
</html>
