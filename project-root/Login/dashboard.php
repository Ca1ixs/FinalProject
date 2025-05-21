<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('LogAccount/Assets/SignUp.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .menu {
            display: flex;
            gap: 25px;
        }

        .menu div {
            font-weight: bold;
            color: #444;
            font-size: 1.1rem;
            cursor: default;
            user-select: none;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info .username {
            font-weight: bold;
            color: #c69400;
            font-size: 1.1rem;
        }

        .user-info a.logout {
            padding: 8px 15px;
            background: #c69400;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .user-info a.logout:hover {
            background: #a87600;
        }

        .boxes-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            padding: 20px;
        }

        .box {
            background: white;
            width: 300px;
            height: 350px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="menu">
        <div>Home</div>
        <div>About</div>
        <div>Contact</div>
    </div>

    <div class="user-info">
        <div class="username"><?= $username ?></div>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="boxes-container">
    <div class="box"></div>
    <div class="box"></div>
    <div class="box"></div>
</div>

</body>
</html>
