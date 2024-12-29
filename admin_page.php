<?php
session_start();
// Check if the user is logged in; otherwise, redirect to login page
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Handle logout if the logout button is pressed
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #0056b3;
            margin-bottom: 30px;
        }

        .button {
            background-color: #0056b3;
            color: white;
            padding: 15px 30px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #003f7f;
        }

        .logout-button {
            background-color: #dc3545; /* Bootstrap danger color */
        }

        .logout-button:hover {
            background-color: #c82333; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Page</h1>
        <form action="createaccount.php" method="POST">
            <button type="submit" class="button">Create Account</button>
        </form>
        <form action="sendmessage.php" method="POST">
            <button type="submit" class="button">Send Message</button>
        </form>
        <form action="" method="POST">
            <button type="submit" name="logout" class="button logout-button">Logout</button>
        </form>
    </div>
</body>
</html>

