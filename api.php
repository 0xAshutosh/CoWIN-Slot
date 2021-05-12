<?php
    error_reporting(0);
    require_once "connect.inc.php";
    require 'PHPMailer/PHPMailer.php';

    $mail = new PHPMailer(true);

    $ch = curl_init();    
    // print_r($data['sessions'][0]['name']);
    $pin_code = $conn -> query("SELECT * FROM pincode");
    
    //$url = "https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByPin?pincode=110034&date=12-05-2021";

    if ($pin_code -> num_rows > 0) {
        while ($row = $pin_code -> fetch_array()) {
            $pin = $row['pin'];
            $date = date("d-m-Y");
            $url = "https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByPin?pincode=$pin&date=$date";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);

            $data = json_decode($result, true);

            $session = $data['sessions'];

            $no_of_sessions = count($session);

            if ($no_of_sessions > 0) {
                $q = $conn -> query("SELECT * FROM email WHERE pin = $pin");

                if ($q -> num_rows > 0) {
                    $t1 = "<table border='1'>
                    <thead>
                        <tr>
                            <th>Availability</th>
                            <th>Center ID</th>
                            <th>Center Name</th>
                            <th>Address</th>
                            <th>State</th>
                            <th>District</th>
                            <th>Pin Code</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Fee Type</th>
                            <th>Date</th>
                            <th>Min Age</th>
                            <th>Vaccine Name</th>
                            <th>Book</th>
                        </tr>
                    </thead>
                    <tbody>
                ";
                $tab = array();
                for ($i = 0; $i < count($session); $i++) {
                    $center_id = $session[$i]['center_id'];
                    $name = $session[$i]['name'];
                    $address = $session[$i]['address'];
                    $state_name = $session[$i]['state_name'];
                    $district_name = $session[$i]['district_name'];
                    $block_name = $session[$i]['block_name'];
                    $pincode = $session[$i]['pincode'];
                    $from = $session[$i]['from'];
                    $to = $session[$i]['to'];
                    $fee_type = $session[$i]['fee_type'];
                    $session_id = $session[$i]['session_id'];
                    $date = $session[$i]['date'];
                    $available_capacity = $session[$i]['available_capacity'];
                    $fee = $session[$i]['fee'];
                    $min_age_limit = $session[$i]['min_age_limit'];
                    $vaccine = $session[$i]['vaccine'];
                    $slots = $session[$i]['vaccine'];
            
                    array_push($tab, "
                        <tr>
                            <td>$available_capacity</td>
                            <td>$center_id</td>
                            <td>$name</td>
                            <td>$address</td>
                            <td>$state_name</td>
                            <td>$district_name</td>
                            <td>$pincode</td>
                            <td>$from</td>
                            <td>$to</td>
                            <td>$fee_type</td>
                            <td>$date</td>
                            <td>$min_age_limit</td>
                            <td>$vaccine</td>
                            <td><a href='https://selfregistration.cowin.gov.in'>Book Slot Now</a></td>
                        </tr>
                    ");
                }
                $t2 = "</tbody>
                </tbody>";

                $item = implode("", $tab);
            



                $final = $t1 . $item . $t2;

                while ($row = $q -> fetch_array()) {
                    $email = $row['email'];
                    $mail -> setFrom('');
                    $mail -> addAddress($email);
                    $mail -> isHTML(true);
                    $mail -> Subject = "Vacinne slot is available in your area.";
                    $mail -> Body = "<p>Dear Friend, <br>
                                Vacinnation slot is available in your area book an appointment now. We have deleted your email from our system.</p> $final";

                    if ($mail -> Send) {
                        $conn -> query("DELETE FROM email WHERE email = $email");
                    }
                }
                 
            }
        }    
    }
    } else {
        exit("Nothing!");
    }
?>
