<?php
    include './functions.php';

    if (isset($_POST['updateUser'])) {
        $res = updateHospitalDetails(
            $_POST['new_name'],
            $_POST['new_address'],
            $_POST['new_contact'],
            $_POST['new_email'],
            $_POST['new_website']
        );
        echo json_encode(array("res" => $res));
    }

    else if (isset($_POST['updatePass'])) {

        $res = updateHospitalPass(
            $_POST['old_pass'],
            $_POST['new_pass']
        );
        if ($res) echo json_encode(array("valid" => $res, "message" => "Updated Password successfully!"));
        else echo json_encode(array("valid" => $res, "message" => "Incorrect Password. Please try again!"));
    }

    else if (isset($_POST['deleteHospital'])) {
        deleteHospital();
        echo json_encode(["location" => "signout.php"]);
    }

    else if(isset($_POST['addDonationSpecs'])) {
        if (addDonationSpecs(
            json_decode($_POST['bloodGroups']), 
            [
                "hospital" => $_SESSION['pk_value'],
                "min_age" => $_POST['min_age'],
                "max_age" => $_POST['max_age'],
                "organ" => $_POST['organ'],
                "honorarium" => $_POST['honorarium']
            ]
        )) {
            echo json_encode(["valid" => true, "message" => "Added Donation Specification successfully!"]);
        }
        else {
            echo json_encode(["valid" => false, "message" => "You have already requested for this organ within this age group. If you want to add new blood groups, you can do so by updating the specification on the dashboard by clicking on the card!"]);
        }
    }

    else if (isset($_POST['updateDonationSpecs'])) {
        $id = updateDonationSpecs(
            $_POST['organ_id'],
            json_decode($_POST['blood_groups']),
            [
                "hospital" => $_SESSION['pk_value'],
                "min_age" => $_POST['min_age'],
                "max_age" => $_POST['max_age'],
                "organ" => $_POST['organ'],
                "honorarium" => $_POST['honorarium']
            ]
        );

        if ($id != -1) {
            echo json_encode(["valid" => true, "message" => "Updated the specifications successfully!", "organ_id" => $id]);
        }
        else {
            echo json_encode(["valid" => false, "message" => "You have already requested for this organ within this age group. If you want to add new blood groups, you can do so by updating the specification on the dashboard by clicking on the card!"]);
        }
    }

    else if(isset($_POST['deleteDonationSpecs'])) {
        deleteDonationSpecs($_POST['organ_id']);
        http_response_code(200);
    }

    else if (isset($_POST['payDonor'])) {
        $donor_details = payDonor($_POST['organ'], $_POST['donor'], $_POST['honorarium'], $_POST['id'], $_POST['patient_name']);

        $to_email = $donor_details["Email"];
        $subject = "Donation Accepted";
        $body = "
Hello $donor_details[Name],<br><br>
Thank you for your interest to donate. Your donation of <strong>$_POST[organ]</strong> has been accepted by <strong>$_POST[hospital]</strong>.<br><br>
An Amount of <strong>INR $_POST[honorarium]</strong> has been transferred into your account!<br><br>
Regards,<br>
    $_POST[hospital],<br>
    The DonorFinder Team";
        $headers = "From: findthedonor@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        echo mail($to_email, $subject, $body, $headers);
        die();
    }

    else if (isset($_POST['getTransactionCounts'])) {
        $res = getTransactionDetails();
        echo json_encode(["counts" => $res[0], "details" => $res[1]]);
    }

    else if (isset($_POST['registerPatient'])) {
        $res = registerPatient($_POST['name'], $_POST['ward'], $_POST['bed'], $_POST['blood_group'], $_POST['organ']);
        if ($res) {
            echo json_encode(["valid" => true]);
        } else {
            echo json_encode(["valid" => false]);
        }
    }

    else if (isset($_POST['deletePatient'])) {
        deletePatient($_POST['ward'], $_POST['bed']);
        echo json_encode(["valid" => true]);
    }

    else if (isset($_POST['filterPatients'])) {
        $patients = filterPatients($_POST['blood_group'], $_POST['organ']);
        if (sizeof($patients) == 0) {
            echo json_encode(["valid" => false]);
        }
        else {
            echo json_encode(["valid" => true, "patients" => $patients]);
        }
    }
?>