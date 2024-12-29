<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require 'config.php'; // Include your database connection file

// Initialize success and error messages
$success = '';
$error = '';

// Handle form submission to create user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are set
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $batch = isset($_POST['batch']) ? $_POST['batch'] : '';
    $register_number = isset($_POST['register_number']) ? $_POST['register_number'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Insert into database only if required fields are not empty
    if (!empty($name) && !empty($register_number) && !empty($password) && !empty($role)) {
        $query = "INSERT INTO users (name, email, phone, department, batch, register_number, password, role) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $name, $email, $phone, $department, $batch, $register_number, $password, $role);

        if ($stmt->execute()) {
            $success = "User account created successfully!";
        } else {
            $error = "Error creating user account: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Please fill in all required fields.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, select {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            color: green;
        }
        .error {
            color: red;
        }
        .logout-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .logout-link:hover {
            background-color: #c82333;
        }
        .back-link {
            display: inline-block;
            margin: 20px 0;
            text-align: center;
            padding: 10px;
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #0056b3; /* Darker shade of blue */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Access - Create User Account</h2>

        <?php if (!empty($success)) echo "<p class='message'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

        <!-- Back Button -->
        <a href="admin_page.php" class="back-link">Back</a>

        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone">

            <label for="department">Department:</label>
            <input type="text" id="department" name="department">

            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch">

            <label for="register_number">Register Number:</label>
            <input type="text" id="register_number" name="register_number" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Create Account</button>
        </form>

        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>

