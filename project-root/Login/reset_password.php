<?php
require_once 'db.php'; 

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$message = '';
$show_form = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';
    $new_pass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new_pass !== $confirm) {
        $error = "Passwords do not match!";
    } 
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_pass)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            $userId = $user['id'];
            $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $userId);
            $stmt->execute();

            $success = "Password has been reset. <a href='login.php'>Click here to login</a>.";
            $show_form = false;
        } else {
            $error = "Invalid or expired reset token.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('LogAccount/Assets/SignUp.png') no-repeat center center fixed;
            background-size: cover; 
            background-position: center; 
        }

        .reset-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }

        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            box-sizing: border-box;
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            font-size: 16px;
            background-color: #f0b400;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #d19900;
        }

        p {
            margin-bottom: 10px;
        }

        .checkbox-container {
            text-align: left;
            margin-top: 10px;
        }

        .checkbox-container label {
            font-size: 14px;
            cursor: pointer;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 5px;
            transform: scale(1.1);
            vertical-align: middle;
        }

        .form-group {
            width: 100%;
            margin-top: 10px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<div class="reset-box">
    <h2>Reset Your Password</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($show_form): ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <input type="password" id="new_password" name="password" placeholder="Enter new password" required>
            </div>

            <div class="form-group">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
            </div>

            <div class="checkbox-container">
                <label>
                    <input type="checkbox" id="show_password" onclick="togglePassword()"> Show Passwords
                </label>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</div>

<script>
    function togglePassword() {
        const pw1 = document.getElementById("new_password");
        const pw2 = document.getElementById("confirm_password");
        const isText = pw1.type === "text";
        pw1.type = isText ? "password" : "text";
        pw2.type = isText ? "password" : "text";
    }
</script>
</body>
</html>
