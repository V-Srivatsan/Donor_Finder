<?php
    include "./functions.php";

    
    if (isset($_POST['addUser'])) {
        if (isset($_POST['isDonor'])) {
            $res = addDonor([
                "username" => $_POST['username'],
                "name" => $_POST['name'],
                "email" => $_POST['email'],
                "contact" => $_POST['contact'],
                "address" => $_POST['address'],
                "locality" => strtolower($_POST['locality']),
                "lat" => $_POST["lat"],
                "long" => $_POST["long"],
                "blood" => $_POST['blood_group'],
                "dob" => $_POST['dob'],
                "pass" => $_POST['password']
            ]);
            
            if ($res) {
                $message = "Donor Added Successfully! You can Log In with your account!";
            }
            else {
                $message = "Another Donor with the same Username already exists! PLease try again!";
            }
        }
        else {
            $res = addHospital([
                "code" => $_POST['code'],
                "name" => $_POST['name'],
                "address" => $_POST['address'],
                "contact" => $_POST['contact'],
                "email" => $_POST['email'],
                "pass" => $_POST['password'],
                "website" => $_POST['website']
            ]);

            if ($res) {
                $message = "Hospital Registered Successfully! You can Log In with your account!";
            }
            else {
                $message = "This Hospital Code has already been registered!";
            }
        }

        $response = array(
            "valid" => $res,
            "message" => $message,
        );
        echo json_encode($response);

    }

    else if (isset($_POST['logUser'])) {
        if (isset($_POST['isDonor'])) {
            $count = loginDonor([
                "username" => $_POST['username'],
                "pass" => $_POST['pass']
            ]);

            if ($count > 0) {
                $_SESSION['loggedUserTable'] = "donors";
                $_SESSION['pk'] = "Username";
                $_SESSION['pk_value'] = strtolower($_POST['username']);
                echo json_encode([
                    "valid" => true,
                    "location" => "donor.php"
                ]);
            }
            else {echo json_encode(["valid" => false]);}
        }
        else {
            $count = loginHospital([
                "code" => $_POST['code'],
                "pass" => $_POST['pass']
            ]);

            if ($count > 0) {
                $_SESSION["loggedUserTable"] = "hospitals";
                $_SESSION['pk'] = "Code";
                $_SESSION["pk_value"] = strtolower($_POST['code']);
                echo json_encode([
                    "valid" => true,
                    "location" => "hospital.php"
                ]);
            }
            else {echo json_encode(["valid" => false]);}
        }
    }

    else if (isset($_POST['resetPassword'])) {

        if (isset($_POST['otpResetPassword'])) {
            $res = validateOtp($_POST['otp']);
            if ($res) {
                echo json_encode(["valid" => true]);
            }
            else {
                echo json_encode(["valid" => false]);
            }
        }
 
        else {
            if ($_POST['role'] == "Hospital") $details = generateOtp("hospitals", $_POST['code'], "Code");
            else $details = generateOtp("donors", $_POST['code'], "Username");
    
            if ($details) {
                $to_email = $details[1];
                $subject = "Reset Password";
                $body = "
                    Hello $details[2],<br><br>
                        Your One Time Password is $details[0]<br><br>
                    Regards,<br>
                        The DonorFinder Team";
                $headers = "From: findthedonor@gmail.com\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
                mail($to_email, $subject, $body, $headers);
    
                $hidden_email = substr($details[1], 0, 3) . "*******@gmail.com";
                echo json_encode(["valid" => true, "message" => "An OTP has been sent to $hidden_email"]);
            }
    
            else {
                echo json_encode(["valid" => false]);
            }
        }

    }

    else if (isset($_POST['setNewPassword'])) {

        if ($_POST["role"] == "Hospital") {
            $table_name = "hospitals";
            $column_name = "Code";
        }
        else {
            $table_name = "donors";
            $column_name = "Username";
        }

        setNewPass($_POST["new_pass"], $table_name, $column_name);
        echo json_encode(["valid" => true]);
    }

    else if (isset($_POST['requestDonor'])) {
        $res = requestDonor($_POST["blood_group"], $_POST["organ"], strtolower($_POST["locality"]));
        if (sizeof($res) == 0) echo json_encode(["valid" => false]);
        else echo json_encode(["valid" => true, "data" => $res]);
    }
?>