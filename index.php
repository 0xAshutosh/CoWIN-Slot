<?php
    error_reporting(0);
    require 'connect.inc.php';

    if (isset($_POST['email']) && isset($_POST['pincode'])) {
        $email = $conn -> real_escape_string(htmlentities($_POST['email']));
        echo $email;
        $pin = $conn -> real_escape_string(htmlentities($_POST['pincode']));
        echo $pin;
        $email_q = $conn -> query("SELECT * FROM email WHERE email = '$email'");
        $pin_q = $conn -> query("SELECT * FROM pincode WHERE pin = $pin");

        if ($email_q -> num_rows > 0) {
            echo "Email already registered!";
        } else {
            if ($conn -> query("INSERT INTO email (email, pin) VALUES ('$email', $pin)")) {
                echo "You will be notified by via an email if vaccine slot get's available in your pin code";
            } else {
                echo "Oops! Something went wrong! Please try after sometimes :(";
            }
        }

        if (!($pin_q -> num_rows > 0)) {
            $conn -> query("INSERT INTO pincode (pin) VALUES ($pin)");
        } else {
            echo "Something is there!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Ashutosh Singh">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
        <link rel="stylesheet" href="style/main.css">
        <title>CoWIN Slot Status</title>
    </head>
    <body>
        <div class="container">
            <div class="other-container">
                <h1>CoWIN Slot Status</h1>
                <p class="lead">Get notified on your email when we find available vaccination slot in your area.</p>
                <form action="#" method="POST">
                    <div class="mb-3">
                        <input type="email" placeholder="Enter your email." name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="number" placeholder="Enter your area pincode." name="pincode" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>   
                </form>
                <p class="center">Developed by <a href="https://instagram.com/0xAshutosh" target="_blank">Ashutosh</a></p>
            </div>
        </div>
    </body>
</html>
