<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../db.php'; 

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title']) && isset($_POST['content'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $photo_filename = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $tmp_name = $_FILES['photo']['tmp_name'];
        $original = basename($_FILES['photo']['name']);
        $safe_name = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", $original);
        move_uploaded_file($tmp_name, $upload_dir . $safe_name);
        $photo_filename = $safe_name;
    }

    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO travel_logs (user_id, title, content, photo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $content, $photo_filename);
        $stmt->execute();
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_log_id'])) {
    $log_id = intval($_POST['delete_log_id']);

    $stmt = $conn->prepare("SELECT photo FROM travel_logs WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $log_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM travel_logs WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $log_id, $user_id);
    $stmt->execute();
    $stmt->close();

    if ($photo && file_exists("uploads/$photo")) {
        unlink("uploads/$photo");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Travel Log</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
      margin: 0;
      padding: 0;
      color: #1f2937;
    }

    main {
      max-width: 900px;
      margin: 2rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    form label {
      font-weight: 600;
      display: block;
      margin-top: 1rem;
    }

    input[type="text"], textarea, input[type="file"] {
      width: 100%;
      padding: 0.5rem;
      margin-top: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    button {
      margin-top: 1rem;
      padding: 0.5rem 1.25rem;
      background-color: #2563eb;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .delete-btn {
      background-color: #dc2626;
      margin-top: 1rem;
    }

    article {
      margin-bottom: 2rem;
      border-bottom: 1px solid #e5e7eb;
      padding-bottom: 1.5rem;
    }

    article h3 {
      font-size: 1.5rem;
      color: #111827;
    }

    article p {
      color: #4b5563;
      margin: 0.5rem 0;
      line-height: 1.6;
    }

    .travel-img {
      max-width: 100%;
      border-radius: 12px;
      margin: 1rem 0;
    }

    @media (max-width: 600px) {
      main {
        margin: 1rem;
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <section aria-label="Add Travel Log">
    <h2>Add a New Travel Log</h2>
    <form method="POST" enctype="multipart/form-data">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" required>

      <label for="content">Log Details</label>
      <textarea id="content" name="content" rows="4" required></textarea>

      <label for="photo">Upload Photo (optional)</label>
      <input type="file" name="photo" id="photo" accept="image/*">

      <button type="submit">Submit</button>
    </form>
  </section>

  <section aria-label="Travel Log Entries">
    <h2>Community Travel Logs</h2>
    <?php
    $sql = "SELECT tl.id, tl.title, tl.content, tl.photo, tl.created_at, u.username 
            FROM travel_logs tl 
            JOIN users u ON tl.user_id = u.id 
            ORDER BY tl.created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
      <article>
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><strong>By:</strong> <?php echo htmlspecialchars($row['username']); ?> on <?php echo date("F j, Y", strtotime($row['created_at'])); ?></p>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

        <?php if (!empty($row['photo'])): ?>
          <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Travel photo" class="travel-img">
        <?php endif; ?>

        <?php if ($row['username'] === $_SESSION['username']): ?>
          <form method="POST">
            <input type="hidden" name="delete_log_id" value="<?php echo $row['id']; ?>">
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        <?php endif; ?>
      </article>
    <?php endwhile;
    else: ?>
      <p>No travel logs yet. Be the first to add one!</p>
    <?php endif; ?>
  </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
