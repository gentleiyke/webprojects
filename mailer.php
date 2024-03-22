<?php
// Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $origin = trim($_POST["origin"]);
        $destination = trim($_POST["destination"]);
        $services = trim($_POST["services"]);
        $subj = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "info@twcourier.com";

        // Set the email subject.
        $subject = "New Quote from $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Orginating From: $origin\n";
        $email_content .= "Destination: $destination\n";
        $email_content .= "Service Requested: $services\n";
        $email_content .= "Subject: $subj\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.

        $email_headers = "From: <no-reply@twcourier.com>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Quotation sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "<strong>Oops! Something went wrong and we couldn't send your quotation.</strong>";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }
?>