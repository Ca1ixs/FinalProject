<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Compass</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('LogAccount/Assets/IndexBack.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-attachment: fixed; 
        }

        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 60px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .brand {
            display: flex;
            align-items: center; 
            justify-content: center;
            color: #fcd835;
            font-family: 'Georgia', serif;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .brand img {
            width: 30px;  
            height: 30px; 
            margin-left: 10px; 
        }

        .button {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            background-color: #f0b400;
            text-decoration: none;
        }

        .button:hover {
            background-color: #d19900;
        }

        .info-text {
            margin-top: 15px;
            font-size: 0.95rem;
            color: #555;
        }

        .info-text a {
            color: #c69400;
            text-decoration: none;
            font-weight: bold;
        }

        .info-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to</h1>
    <div class="brand">
        Compass
    </div>

    <div class="info-text">Already have an account?</div>
    <a href="login.php" class="button">Log In</a>

    <div class="info-text">Don't have an account yet?</div>
    <a href="sign_up.php" class="button">Sign Up</a>
</div>

</body>
</html>
