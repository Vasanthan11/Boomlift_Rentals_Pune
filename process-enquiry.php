<?php
// --------------------
// CONFIGURATION
// --------------------

// Email where enquiries will be received
$receiver_email = "info@boomliftrentpune.com";  // UPDATED EMAIL ID

// Email subject
$email_subject = "New Rental Enquiry From Pune Website";

// --------------------
// CHECK FORM SUBMISSION
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize data
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
    // BASIC VALIDATION
    // --------------------
    if (empty($full_name) || empty($phone) || empty($location) || empty($equipment)) {
        echo "<script>alert('Please fill all required fields.'); history.back();</script>";
        exit();
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid Email Address!'); history.back();</script>";
        exit();
    }

    // --------------------
    // EMAIL BODY
    // --------------------
    $body = "
    New Rental Enquiry From Pune Website:

    Full Name:   $full_name
    Email:       $email
    Phone:       $phone
    Location:    $location
    Equipment:   $equipment
    Message:     $message

    Source:      $source
    Time:        " . date("d-m-Y H:i A") . "
    IP Address:  " . $_SERVER['REMOTE_ADDR'] . "
    ";

    // HEADERS
    $headers  = "From: BoomLift Rentals Pune <no-reply@boomliftrentpune.com>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // SEND EMAIL
    $sent = @mail($receiver_email, $email_subject, $body, $headers);

    if ($sent) {
        // SUCCESS → Redirect to thank-you page
        header("Location: thankyou.html");
        exit();
    } else {
        echo "<script>alert('Something went wrong. Try again later.'); history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid Request!'); window.location.href='index.html';</script>";
}
