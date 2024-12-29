<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer

// Handle the bulk email form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sample list of email recipients (you can fetch this from your database)
    $recipients = [
        'kotahemanth1430@gmail.com',
        'tanoojsrichakra@gmail.com'
    ];

    // Email subject and body from the form
    $subject = $_POST['subject'] ?? 'Test Bulk Email';
    $body = $_POST['body'] ?? 'This is a test email sent in bulk.';

    // Call the function to send bulk email
    sendBulkEmail($recipients, $subject, $body);
}

// Function to send bulk emails
function sendBulkEmail($recipients, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'srpsample@gmail.com'; // Your SMTP email address
        $mail->Password = 'vukj ayfy mhuj eaaj'; // Your SMTP password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Set the sender's email and name
        $mail->setFrom('kondutanooj@gmail.com', 'Your Name');

        // Loop through each recipient and send the email
        foreach ($recipients as $recipient) {
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($recipient); // Add a recipient
            } else {
                echo "Invalid email address: $recipient<br>"; // Debug output for invalid emails
            }
        }

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject; // Set the email subject
        $mail->Body    = nl2br($body); // Convert newlines to <br> for HTML
        $mail->AltBody = strip_tags($body); // For non-HTML clients

        // Send the email
        $mail->send();
        echo "Emails sent successfully!";
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
    <title>Bulk Email Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bulk Email Sender</h1>
        <form action="" method="POST">
            <input type="text" name="subject" placeholder="Email Subject" required>
            <textarea name="body" rows="5" placeholder="Email Body" required></textarea>
            <button type="submit">Send Bulk Emails</button>
        </form>
    </div>
</body>
</html>

