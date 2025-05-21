<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['login_message'])) {
    $_SESSION['login_message'] = '';
}
$lock_remaining = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($input) || empty($password)) {
        $_SESSION['login_message'] = "Please enter both username/email and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $lock_time = strtotime($user['last_failed_login']);
            if ($user['failed_attempts'] >= 5 && $user['last_failed_login'] && time() - $lock_time < 30) {
                $lock_remaining = 30 - (time() - $lock_time);
                if ($lock_remaining < 0 || $lock_remaining > 30) $lock_remaining = 30;
                $_SESSION['login_message'] = "Too many failed attempts. Please try again in <span id='lockCountdown'>{$lock_remaining}</span> seconds.";
                $_SESSION['lock_remaining'] = $lock_remaining;
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email']; 
                $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_failed_login = NULL WHERE id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $_SESSION['login_message'] = '';
                unset($_SESSION['lock_remaining']);
                header("Location: Main_Page/CompassHome.php");
                exit;
            } else {
                if ($user['failed_attempts'] < 5 || time() - $lock_time >= 30) {
                    $stmt = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_login = NOW() WHERE id = ?");
                    $stmt->bind_param("i", $user['id']);
                    $stmt->execute();
                }
                $_SESSION['login_message'] = "Incorrect password. Please try again.";
                unset($_SESSION['lock_remaining']);
            }
        } else {
            $_SESSION['login_message'] = "User not found.";
            unset($_SESSION['lock_remaining']);
        }
    }
    header("Location: login.php");
    exit;
}

$message = $_SESSION['login_message'] ?? '';
$lock_remaining = $_SESSION['lock_remaining'] ?? 0;
unset($_SESSION['login_message']);
unset($_SESSION['lock_remaining']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('LogAccount/Assets/IndexBack.jpg') no-repeat center center fixed;
    background-size: cover;
    position: relative;
    z-index: 0;
}

.overlay {
    background-color: rgba(255, 255, 255, 0.3);
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    z-index: 1;
}

.split-screen {
    display: flex;
    width: 90%;
    max-width: 950px;
    height: 85vh;
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.left-side {
    flex: 1;
    background-color: skyblue;
    padding: 40px 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
}

.left-side h1 {
    font-size: 2.8rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 20px;
}

.brand-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.brand-logo .brand {
    font-size: 2.3rem;
    color: #fcd835;
    font-family: 'Georgia', serif;
}

.brand-logo img {
    max-height: 40px;
    height: auto;
    width: auto;
    object-fit: contain;
}

.right-side {
    flex: 1;
    background: url('LogAccount/Assets/right-bg.jpg') no-repeat center center;
    background-size: cover;
    position: relative;
    padding: 60px 50px 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.right-side::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 0;
}

.right-side > * {
    position: relative;
    z-index: 1;
    color: black;
}

.right-side h2 {
    font-size: 2rem;
    color: #c69400;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.right-side small {
    margin-bottom: 1.5rem;
    color: black; 
}

.right-side small a {
    color:rgb(255, 255, 255);
    text-decoration: none;
}

.right-side small a:hover {
    text-decoration: underline;
}

form {
    width: 100%;
    display: flex;
    flex-direction: column;
}

form input[type="text"],
form input[type="email"],
form input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
}

form button {
    width: 100%;
    padding: 12px;
    background-color: #f0b400;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    margin-bottom: 20px;
}

form button:hover {
    background-color: #d19900;
}

.right-side label {
    font-size: 0.9rem;
    margin-bottom: 16px;
    color: #555;
    display: flex;
    align-items: center;
}

.right-side label input[type="checkbox"] {
    margin-right: 8px;
}

p.error {
    color: red;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

p.success {
    color: green;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

small:last-child {
    margin-top: 15px;
}

.white-link {
    color: white !important;
    text-decoration: underline;
    font-weight: bold;
}

@media (max-width: 768px) {
    .split-screen {
        flex-direction: column;
        height: auto;
        width: 95%;
        margin: 20px;
    }

    .left-side,
    .right-side {
        width: 100%;
        padding: 30px;
        text-align: center;
    }

    .left-side h1 {
        font-size: 2rem;
    }

    .brand-logo .brand {
        font-size: 1.8rem;
    }

    .brand-logo img {
        max-height: 30px;
    }

    .right-side h2 {
        font-size: 1.5rem;
    }

    .right-side form {
        width: 100%;
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form button {
        font-size: 0.95rem;
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .left-side h1 {
        font-size: 1.5rem;
    }

    .brand-logo .brand {
        font-size: 1.5rem;
    }

    .right-side h2 {
        font-size: 1.3rem;
    }

    form button {
        font-size: 0.9rem;
    }
}
</style>
</head>
<body>
<div class="split-screen">
    <div class="left-side">
        <h1>Find<br>your way with</h1>
        <div class="brand-logo">
            <div class="brand">Compass</div>
        </div>
    </div>
    <div class="right-side">
        <h2>WELCOME</h2>
        <small>Log In with Email</small>

        <?php if ($message): ?>
            <p class="error"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" novalidate>
            <input type="text" name="username_or_email" required placeholder="Username or Email" <?= $lock_remaining ? 'disabled' : '' ?> />
            <input type="password" name="password" required placeholder="Password" id="password" <?= $lock_remaining ? 'disabled' : '' ?> />
            <label><input type="checkbox" onclick="togglePasswordVisibility()" <?= $lock_remaining ? 'disabled' : '' ?>> Show Password</label>
            <div style="text-align: right; margin-top: -12px; margin-bottom: 10px;">
                <a href="forgot_password.php" style="color:black; font-size: 0.85rem;">Forgot Password?</a>
            </div>
            <button type="submit" <?= $lock_remaining ? 'disabled' : '' ?>>Log In</button>
        </form>

        <small>Don't have an account? <a href="sign_up.php">Sign up!</a></small>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById("password");
    passwordField.type = passwordField.type === "password" ? "text" : "password";
}

// Countdown for lockout
<?php if ($lock_remaining): ?>
let countdown = <?= $lock_remaining ?>;
const countdownElem = document.getElementById('lockCountdown');
const interval = setInterval(() => {
    countdown--;
    if (countdownElem) countdownElem.textContent = countdown;
    if (countdown <= 0) {
        clearInterval(interval);
        window.location.reload();
    }
}, 1000);
<?php endif; ?>
</script>
</body>
</html>
