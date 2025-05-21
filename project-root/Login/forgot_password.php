<?php
date_default_timezone_set('Asia/Manila');

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

$host = 'localhost';
$db = 'accGroup8';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $token = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        $reset_link = "http://localhost/project-root/Login/reset_password.php?token=$token";

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'siomairice77@gmail.com';     // your Gmail
            $mail->Password = 'osbifnzfamjpykhq';           // your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('siomairice77@gmail.com', 'Compass Trip Planner');
            $mail->addAddress($email);
            $mail->isHTML(true);

            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
                <p>Click the button below to reset your password:</p>
                <a href='$reset_link' 
                   style='display:inline-block;padding:12px 20px;margin-top:10px;background-color:#f0b400;
                          color:white;text-decoration:none;border-radius:4px;font-weight:bold;'>
                    Reset Password
                </a>
            ";

            $mail->send();
            $message = "A reset link has been sent to your email.";
        } catch (Exception $e) {
            $message = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $message = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            font-size: 16px;
            background-color: #f0b400;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button:hover {
            background-color: #d19900;
        }

        p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="reset-box">
    <h2>Forgot Password</h2>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</div>
</body>
</html>
