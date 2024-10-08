<?php
// Start the session
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
        }

        .welcome-message {
            margin-bottom: 30px;
            font-size: 24px;
            color: black;
        }

        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <div class="welcome-message">
            <?php
            // Display user's name if available, otherwise display 'User'
            if (isset($_SESSION['name'])) {
                echo "Hello, " . htmlspecialchars($_SESSION['name']);
            } else {
                echo "Hello, User";
            }
            ?>
        </div>
        <!-- Logout button -->
        <a href="http://localhost/login/logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
