<?php
$username = $_SESSION['username'] ?? 'Guest';
$email = $_SESSION['email'] ?? ''; 
?>
<style>
.user-info {
  position: relative;
  cursor: pointer;
}

#dropdownMenu {
  display: none;
  position: absolute;
  right: 0;
  background: white;
  border: 1px solid #ccc;
  padding: 10px;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  pointer-events: auto;
}

.user-info.active #dropdownMenu {
  display: block;
}

#dropdownMenu a {
  display: block;
  padding: 5px;
  text-decoration: none;
  color: black;
}

#dropdownMenu a:hover {
  background-color: #f0f0f0;
}

.profile-modal {
  display: none;
  position: fixed;
  z-index: 2000;
  left: 0; top: 0;
  width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.25);
}
.profile-modal-content {
  background: #fff;
  margin: 8% auto;
  padding: 32px 36px 24px 36px;
  border-radius: 14px;
  max-width: 350px;
  min-width: 260px;
  position: relative;
  box-shadow: 0 8px 24px rgba(0,0,0,0.10);
  color: #222;
  text-align: center;
}
.profile-modal-content h2 {
  color: #cc6600;
  margin-bottom: 18px;
  font-weight: 800;
  font-size: 1.5rem;
}
.profile-modal-content .close {
  position: absolute;
  top: 14px;
  right: 22px;
  font-size: 1.7rem;
  color: #888;
  cursor: pointer;
}
.profile-modal-content .profile-label {
  font-weight: bold;
  color: #004d66;
  margin-top: 10px;
}
.profile-modal-content .profile-value {
  margin-bottom: 10px;
  color: #222;
}
.profile-modal-content .logout-btn {
  margin-top: 18px;
  padding: 10px 0;
  width: 100%;
  background: linear-gradient(90deg, #fcd835 60%, #ffcc66 100%);
  color: #1f2937;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.profile-modal-content .logout-btn:hover {
  background: linear-gradient(90deg, #ffcc66 0%, #fcd835 100%);
  color: #cc6600;
}

@media (max-width: 700px) {
  header {
    flex-direction: column;
    align-items: flex-start;
    padding: 10px 5px;
  }
  .logo img {
    max-width: 120px;
    width: 100%;
    height: auto;
    display: block;
  }
  nav ul {
    flex-direction: column;
    gap: 8px;
    padding-left: 0;
  }
  .user-info {
    margin-top: 10px;
    font-size: 1rem;
  }
}

@media (max-width: 400px) {
  .logo img {
    max-width: 80px;
  }
}
</style>

<header role="banner">
  <div class="logo">
    <a href="CompassHome.php" aria-label="Compass Home">
      <img src="Compass_Site/Assets/images/compass_logo.gif" alt="Compass logo" />
    </a>
  </div>

  <nav role="navigation" aria-label="Main menu">
    <ul>
      <li><a href="TripPlanner.php" class="tripplanner-btn" aria-label="Trip Planner">Trip Planner</a></li>
      <li><a href="Destinations.php" class="destinations-btn" aria-current="page" aria-label="Destinations">Destinations</a></li>
      <li><a href="Travelog.php" class="travelog-btn" aria-label="Travel Log">Travel Log</a></li>
    </ul>
  </nav>

  <div class="user-info" id="userInfo" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
    <?= htmlspecialchars($username) ?>
    <div id="dropdownMenu" role="menu" aria-label="User options">
      <a href="#" id="profileBtn" role="menuitem">Profile</a>
      <a href="../logout.php" role="menuitem">Logout</a>
    </div>
  </div>
</header>

<div id="profileModal" class="profile-modal" tabindex="-1">
  <div class="profile-modal-content">
    <span class="close" onclick="closeProfileModal()">&times;</span>
    <h2>User Profile</h2>
    <div class="profile-label">Username:</div>
    <div class="profile-value"><?= htmlspecialchars($username) ?></div>
    <div class="profile-label">Email:</div>
    <div class="profile-value"><?= htmlspecialchars($email) ?></div>
    <form action="../logout.php" method="post">
      <button type="submit" class="logout-btn">Logout</button>
    </form>
  </div>
</div>

<script>
  const userInfo = document.getElementById('userInfo');
  userInfo.addEventListener('click', function (e) {
    e.stopPropagation();
    this.classList.toggle('active');
    const expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', !expanded);
  });

  const profileBtn = document.getElementById('profileBtn');
  const profileModal = document.getElementById('profileModal');
  function closeProfileModal() {
    profileModal.style.display = 'none';
    document.body.style.overflow = '';
  }
  profileBtn.addEventListener('click', function(e) {
    e.preventDefault();
    profileModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    userInfo.classList.remove('active');
    userInfo.setAttribute('aria-expanded', false);
  });
  window.addEventListener('click', function(event) {
    if (event.target === profileModal) {
      closeProfileModal();
    }
  });
  window.addEventListener('keydown', function(event) {
    if (event.key === "Escape") closeProfileModal();
  });
</script>
