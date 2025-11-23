<?php
// --------------------
// CONFIGURATION
// --------------------

// Email where you want to receive enquiries
$receiver_email = "info@boomliftrentalsdelhi.com";  // CHANGE THIS

// Subject of email
$email_subject = "New Rental Enquiry From Website";

// --------------------
// CHECK FORM SUBMISSION
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect & sanitize values
    function clean($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $full_name   = clean($_POST['full_name'] ?? '');
    $email       = clean($_POST['email'] ?? '');
    $phone       = clean($_POST['phone'] ?? '');
    $location    = clean($_POST['location'] ?? '');
    $equipment   = clean($_POST['equipment'] ?? '');
    $message     = clean($_POST['message'] ?? '');
    $source      = clean($_POST['enquiry_source'] ?? 'Website Form');

    // --------------------
    // SIMPLE VALIDATION
    // --------------------
    if (empty($full_name) || empty($email) || empty($phone) || empty($location) || empty($equipment)) {
        echo "<h3 style='color:red; text-align:center;'>Please fill all required fields.</h3>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h3 style='color:red; text-align:center;'>Invalid Email Address!</h3>";
        exit();
    }

    // --------------------
    // CREATE EMAIL BODY
    // --------------------
    $body = "
    New Rental Enquiry Received:

    Full Name:   $full_name
    Email:       $email
    Phone:       $phone
    Location:    $location
    Equipment:   $equipment
    Message:     $message

    Source/Section: $source
    Time:           " . date("d-m-Y H:i A") . "
    IP Address:     " . $_SERVER['REMOTE_ADDR'] . "
    ";

    // --------------------
    // EMAIL HEADERS
    // --------------------
    $headers = "From: BoomLift Rentals Website <no-reply@yourdomain.com>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // --------------------
    // SEND EMAIL
    // --------------------
    $mail_sent = @mail($receiver_email, $email_subject, $body, $headers);

    if ($mail_sent) {
        echo "<h3 style='color:green; text-align:center;'>Thank you! Your enquiry has been sent successfully – We will contact you soon.</h3>";

        // OPTIONAL: redirect back after 3s
        echo "<script>
            setTimeout(function(){
                window.location.href = 'index.html';   // Change if needed
            }, 3000);
        </script>";
    } else {
        echo "<h3 style='color:red; text-align:center;'>Something went wrong. Try again later.</h3>";
    }
} else {
    echo "<h3 style='color:red; text-align:center;'>Invalid Request</h3>";
}
