<?php
session_start();
$conn = new mysqli('localhost', 'root', 'Ktanooj@2004', 'bulk_email_system');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to check user credentials
    $query = "SELECT * FROM users WHERE register_number='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        $_SESSION['username'] = $user['register_number']; // or the desired username field
        $_SESSION['role'] = $user['role']; // Set user role if applicable
        // Set session variables and redirect based on user role
        if ($user['role'] == 'admin') {
            $_SESSION['role'] = 'admin';
            echo "Admin Login Successful!"; // Success message for admin
            header("Location: admin_page.php");
            exit();
        } elseif ($user['role'] == 'student') {
            $_SESSION['role'] = 'student';
            echo "Student Login Successful!"; // Success message for student
            header("Location: student_page.php");
            exit();
        }
    } else {
        echo "<script>
    alert('Invalid username or password');
    window.location.href = 'index.html';
</script>";

    }   
}
?>

