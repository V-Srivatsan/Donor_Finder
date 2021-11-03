<?php
    include './functions.php';

    if (isset($_POST['updateUser'])) {
        $res = updateDonorDetails(
            $_POST['new_name'],
            $_POST['new_address'],
            $_POST['new_contact'],
            $_POST['new_email'],
            strtolower($_POST['new_locality']),
            $_POST['new_lat'],
            $_POST['new_long']
        );
        echo json_encode(array("res" => $res));
    }

    else if (isset($_POST['updatePass'])) {

        $res = updateDonorPass(
            $_POST['old_pass'],
            $_POST['new_pass']
        );
        if ($res) echo json_encode(array("valid" => $res, "message" => "Updated Password successfully!"));
        else echo json_encode(array("valid" => $res, "message" => "Incorrect Password. Please try again!"));
    }

    else if (isset($_POST['deleteDonor'])) {
        deleteDonor();
        echo json_encode(["location" => "signout.php"]);
    }

    else if(isset($_POST['addDonationSpecsDonor'])) {
        if (addDonationSpecsDonor([
            "donor" => $_SESSION['pk_value'],
            "organ" => $_POST['organ']
            ])) {
            echo json_encode(["valid" => true]);
        }
        else {
            echo json_encode(["valid" => false]);
        }
    }

    else if (isset($_POST['deleteDonationSpecsDonor'])) {
        deleteDonationSpecsDonor($_POST['id']);
        echo json_encode(["valid" => true]);
    }

    else if (isset($_POST['applyHospitalForDonation'])) {
        $res = applyForDonation($_POST['id']);
        echo json_encode(["valid" => $res]);
    }
?>