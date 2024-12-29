<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
require 'config.php'; // Your database connection

// Fetch the logged-in student's username
$studentUsername = $_SESSION['username'];

// Debug output to check session values
if (!isset($studentUsername)) {
    echo "Username is not set in session.";
    exit();
}

// Prepare the SQL query to fetch the email and name based on the registered number
$newsQuery = $conn->prepare("SELECT email, name FROM users WHERE register_number=?");
$newsQuery->bind_param("s", $studentUsername); // Binding the parameter
$newsQuery->execute(); // Execute the query

// Get the result
$newsResult = $newsQuery->get_result();

// Check if the query returned any results
if ($newsResult->num_rows > 0) {
    // Fetch the result as an associative array
    $row = $newsResult->fetch_assoc();
    $studentEmail = $row['email']; // Store the student's email
    $studentName = $row['name']; // Store the student's name
} else {
    // Handle the case where no user is found
    echo "No user found with the provided register number: $studentUsername";
    exit(); // Stop further execution
}

// Fetch all email addresses from the database
$query = "SELECT email FROM users";
$result = $conn->query($query);
$recipients = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipients[] = $row['email'];
    }
}

// Handle the news submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newsTitle = trim($_POST['newsTitle']);
    $newsContent = trim($_POST['newsContent']);

    // Check if news title and content are not empty
    if (!empty($newsTitle) && !empty($newsContent)) {
        // Now send email to all recipients
        sendBulkEmail($recipients, $newsTitle, $newsContent, $studentEmail, $studentName);

        // Optional: Show a success message
        $successMessage = "News submitted successfully and emails sent!";
    } else {
        $errorMessage = "News title and content cannot be empty.";
    }
}

// Function to send bulk emails
function sendBulkEmail($recipients, $newsTitle, $newsContent, $studentEmail, $studentName) {
    // Server settings common to all emails
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();                                          
        $mail->Host       = 'smtp.gmail.com';                   
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'srpsample@gmail.com'; // Your SMTP username (Gmail account)
        $mail->Password   = 'vukj ayfy mhuj eaaj'; // Your SMTP password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       
        $mail->Port       = 587;                                  

        // Email content setup
        $mail->isHTML(true);                                     
        $mail->Subject = $newsTitle;
        $mail->Body    = "<h4>$newsTitle</h4><p>$newsContent</p>";
        $mail->AltBody = strip_tags($newsContent); // For non-HTML clients

        // Loop through recipients and send emails individually
        foreach ($recipients as $recipient) {
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL) && $recipient !== $studentEmail) { // Validate email and avoid sending to the sender
                // Clone the mail object for each recipient to avoid sending the same email
                $mailClone = clone $mail; 
                $mailClone->clearAddresses(); // Clear previous recipients
                $mailClone->setFrom('srpsample@gmail.com', $studentName); // Use student's email and name
                $mailClone->addAddress($recipient); // Add current recipient
                
                // Send email
                if (!$mailClone->send()) {
                    // Handle error (e.g., log it)
                    echo "Mailer Error: " . $mailClone->ErrorInfo;
                }
            }
        }
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVGR College of Engineering</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .main-content {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 {
            color: #0056b3;
            text-align: center;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #0056b3;
            color: white;
            padding: 12px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #003f7f;
        }

        .logout-button {
            background-color: #dc3545; /* Bootstrap danger color */
            margin-bottom: 20px; /* Spacing below the button */
        }

        .logout-button:hover {
            background-color: #c82333; /* Darker red on hover */
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
    <div class="main-content">
        <h2>Upload News</h2>

        <?php if (isset($successMessage)) echo "<p style='color: green;'>$successMessage</p>"; ?>
        <?php if (isset($errorMessage)) echo "<p style='color: red;'>$errorMessage</p>"; ?>

        <!-- Back Button -->
        <a href="admin_page.php" class="back-link">Back</a>

        <form action="student_page.php" method="POST">
            <input type="text" id="newsTitle" name="newsTitle" placeholder="News Title" required>
            <textarea id="newsContent" name="newsContent" placeholder="News Content" required></textarea>
            <button type="submit">Submit News</button>
        </form>

        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>

