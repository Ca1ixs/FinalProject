<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
require_once '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Trip Planner</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    .trip-planner-container {
      display: flex;
      flex-direction: column;
      gap: 30px;
      align-items: center;
    }

    .map-and-activities {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .world-map svg {
      width: 100%;
      max-width: 900px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .trip-planner-form { max-width: 900px; width: 100%; }

    .trip-planner-form form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px 40px;
    }

    .trip-planner-form h1 {
      grid-column: span 2;
      font-size: 2rem;
    }

    .trip-planner-form label { display: block; margin-bottom: 6px; font-weight: 600; color: #004d66; }

    .trip-planner-form input,
    .trip-planner-form select,
    .trip-planner-form textarea {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1.5px solid #ccc;
      font-size: 1rem;
    }

    .trip-planner-form textarea {
      min-height: 100px;
      grid-column: span 2;
    }

    .trip-planner-form button {
      grid-column: span 2;
      padding: 14px;
      background-color: #006699;
      color: white;
      font-size: 1.2rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .trip-planner-form button:hover {
      background-color: #004d66;
    }

    .checkbox-group {
      grid-column: span 2;
    }

    .checkbox-group h2 {
      font-size: 1.3rem;
      color: #004d66;
      margin-bottom: 10px;
    }

    .checkbox-options {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .checkbox-options label {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .saved-trips-list {
      max-width: 900px;
    }

    .saved-trips-list .trip {
      margin-bottom: 20px;
      padding: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    svg [id]:hover {
      fill: #ffcc00 !important;
      cursor: pointer;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <section class="trip-planner-container">

    <div class="map-and-activities">
      <div class="world-map">
        <?php include 'Compass_Site/Assets/images/interactive_map_updated.svg'; ?>
      </div>
    </div>

    <div class="trip-planner-form">
      <h1>Plan Your Next Adventure</h1>
      <form method="post" action="save_trip.php">
        <div>
          <label for="tripName">Trip Name:</label>
          <input type="text" id="tripName" name="tripName" required />
        </div>

        <div>
          <label for="region">Country/Region:</label>
          <select id="region" name="region" required>
            <option value="">-- Select Region --</option>
            <option value="North America">North America</option>
            <option value="Europe">Europe</option>
            <option value="Asia">Asia</option>
            <option value="South America">South America</option>
            <option value="Africa">Africa</option>
            <option value="Australia">Australia</option>
          </select>
        </div>

        <div>
          <label for="city">Closest City:</label>
          <select id="city" name="city" required>
            <option value="">Select a region first</option>
          </select>
        </div>

        <div>
          <label for="destination">Destination:</label>
          <input type="text" id="destination" name="destination" required />
        </div>

        <div>
          <label for="startDate">Start Date:</label>
          <input type="text" id="startDate" name="startDate" required />
        </div>

        <div>
          <label for="endDate">End Date:</label>
          <input type="text" id="endDate" name="endDate" required />
        </div>

        <div class="checkbox-group">
          <h2>Choose Your Activities:</h2>
          <div style="margin-bottom: 10px;">
            <label><input type="checkbox" class="category-filter" value="land"> Land Adventures</label>
            <label><input type="checkbox" class="category-filter" value="water"> Water Adventures</label>
            <label><input type="checkbox" class="category-filter" value="air"> Air Adventures</label>
            <label><input type="checkbox" class="category-filter" value="snow"> Snow & Ice</label>
            <label><input type="checkbox" class="category-filter" value="wilderness"> Wilderness</label>
            <label><input type="checkbox" class="category-filter" value="specialty"> Specialty</label>
          </div>

          <div class="checkbox-options">
            <div class="activity-group" data-category="land" style="display: none;">
              <label><input type="checkbox" name="activities[]" value="Hiking">Hiking</label>
              <label><input type="checkbox" name="activities[]" value="Rock Climbing">Rock Climbing</label>
              <label><input type="checkbox" name="activities[]" value="Mountain Biking">Mountain Biking</label>
              <label><input type="checkbox" name="activities[]" value="Caving">Caving</label>
            </div>

            <div class="activity-group" data-category="water" style="display: none;">
              <label><input type="checkbox" name="activities[]" value="Kayaking">Kayaking</label>
              <label><input type="checkbox" name="activities[]" value="Scuba Diving">Scuba Diving</label>
              <label><input type="checkbox" name="activities[]" value="Surfing">Surfing</label>
              <label><input type="checkbox" name="activities[]" value="Fly Fishing">Fly Fishing</label>
            </div>

            <div class="activity-group" data-category="air" style="display: none;">
              <label><input type="checkbox" name="activities[]" value="Paragliding">Paragliding</label>
              <label><input type="checkbox" name="activities[]" value="Skydiving">Skydiving</label>
              <label><input type="checkbox" name="activities[]" value="Hot Air Balloon">Hot Air Balloon</label>
            </div>

            <div class="activity-group" data-category="snow" style="display: none;">
              <label><input type="checkbox" name="activities[]" value="Snowboarding">Snowboarding</label>
              <label><input type="checkbox" name="activities[]" value="Skiing">Skiing</label>
              <label><input type="checkbox" name="activities[]" value="Ice Climbing">Ice Climbing</label>
            </div>

            <div class="activity-group" data-category="wilderness" style="display: none;">
              <label><input type="checkbox" name="activities[]" value="Wildlife Safari">Wildlife Safari</label>
              <label><input type="checkbox" name="activities[]" value="Jungle Trekking">Jungle Trekking</label>
              <label><input type="checkbox" name="activities[]" value="Forest Camping">Forest Camping</label>
            </div>

            <div class="activity-group" data-category="specialty" style="display: none;">
              <label><input type="checkbox" name="activities[]" value="Photography Tour">Photography Tour</label>
              <label><input type="checkbox" name="activities[]" value="Cultural Exploration">Cultural Exploration</label>
              <label><input type="checkbox" name="activities[]" value="Survival Training">Survival Training</label>
            </div>
          </div>
        </div>

        <div>
          <label for="notes">Notes:</label>
          <textarea id="notes" name="notes"></textarea>
        </div>

        <button type="submit">Save Trip</button>
      </form>
    </div>

    <div class="saved-trips-list">
      <h2>Your Saved Trips</h2>
      <?php
      $stmt = $conn->prepare("SELECT * FROM trips WHERE user_id = ? ORDER BY create_at DESC");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows === 0) {
          echo "<p>No trips saved yet.</p>";
      } else {
          while ($trip = $result->fetch_assoc()) {
              echo "<div class='trip'>";
              echo "<h3>" . htmlspecialchars($trip['tripName']) . "</h3>";
              echo "<p><strong>Region:</strong> " . htmlspecialchars($trip['region']) . "</p>";
              echo "<p><strong>City:</strong> " . htmlspecialchars($trip['city']) . "</p>";
              echo "<p><strong>Activity:</strong> " . htmlspecialchars($trip['activity']) . "</p>";
              echo "<p><strong>Destination:</strong> " . htmlspecialchars($trip['destination']) . "</p>";
              echo "<p><strong>Dates:</strong> " . htmlspecialchars($trip['start_date']) . " to " . htmlspecialchars($trip['end_date']) . "</p>";
              echo "<p><strong>Notes:</strong> " . nl2br(htmlspecialchars($trip['notes'])) . "</p>";
              echo "<p><a href='delete_trip.php?id=" . $trip['id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a></p>";
              echo "</div>";
          }
      }
      ?>
    </div>

  </section>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  const citiesByRegion = {
    "North America": ["New York", "Los Angeles", "Toronto", "Vancouver", "Chicago", "San Francisco", "Mexico City", "Montreal", "Houston", "Boston"],
    "Europe": ["London", "Paris", "Berlin", "Rome", "Amsterdam", "Madrid", "Vienna", "Prague", "Athens", "Lisbon"],
    "Asia": ["Tokyo", "Bangkok", "Seoul", "Singapore", "Jakarta", "Mumbai", "Beijing", "Shanghai", "Kuala Lumpur", "Manila"],
    "South America": ["Rio de Janeiro", "Buenos Aires", "Lima", "Bogotá", "Santiago", "Quito", "Caracas", "Montevideo", "La Paz", "Asunción"],
    "Africa": ["Cape Town", "Nairobi", "Cairo", "Marrakech", "Lagos", "Johannesburg", "Accra", "Algiers", "Addis Ababa", "Dakar"],
    "Australia": ["Sydney", "Melbourne", "Brisbane", "Perth", "Adelaide", "Canberra", "Gold Coast", "Hobart", "Darwin", "Newcastle"]
  };

  const countryToRegionMap = {
    "US": "North America", "CA": "North America", "MX": "North America",
    "FR": "Europe", "DE": "Europe", "IT": "Europe", "UK": "Europe",
    "JP": "Asia", "CN": "Asia", "IN": "Asia", "TH": "Asia",
    "BR": "South America", "AR": "South America", "CL": "South America",
    "ZA": "Africa", "EG": "Africa", "NG": "Africa",
    "AU": "Australia", "NZ": "Australia"
  };

  document.getElementById("region").addEventListener("change", function () {
    const selectedRegion = this.value;
    const citySelect = document.getElementById("city");
    citySelect.innerHTML = "";

    if (selectedRegion && citiesByRegion[selectedRegion]) {
      citySelect.innerHTML = `<option value="">-- Select City --</option>`;
      citiesByRegion[selectedRegion].forEach(city => {
        const opt = document.createElement("option");
        opt.value = city;
        opt.textContent = city;
        citySelect.appendChild(opt);
      });
    } else {
      citySelect.innerHTML = `<option value="">Select a region first</option>`;
    }
  });

  flatpickr("#startDate", { dateFormat: "Y-m-d", minDate: "today" });
  flatpickr("#endDate", { dateFormat: "Y-m-d", minDate: "today" });

  document.querySelectorAll("svg [id]").forEach(country => {
    country.style.cursor = "pointer";
    country.addEventListener("click", () => {
      const region = countryToRegionMap[country.id];
      if (region) {
        const regionSelect = document.getElementById("region");
        regionSelect.value = region;
        regionSelect.dispatchEvent(new Event("change"));
      }
    });
  });

  document.querySelectorAll('.category-filter').forEach(filter => {
    filter.addEventListener('change', () => {
      const selected = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);
      document.querySelectorAll('.activity-group').forEach(group => {
        const category = group.getAttribute('data-category');
        group.style.display = selected.includes(category) ? 'flex' : 'none';
      });
    });
  });
</script>

</body>
</html>
