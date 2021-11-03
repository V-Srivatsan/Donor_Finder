<?php
    include "./functions.php";


    // LOGIN

    $ADMINS = [
        "admin" => "root",
    ];

    if (isset($_POST['loginAdmin'])) {

        if ( isset( $ADMINS[$_POST['username']] ) && $ADMINS[$_POST['username']] == $_POST['pass']) {
            $_SESSION['admin'] = $_POST['username'];
            echo json_encode(["valid" => true, "location" => "admin.php"]);
        }

        else {
            echo json_encode(["valid" => false]);
        }
    }


    // DONORS 

    if (isset($_POST['updateDonor'])) {
        $_SESSION['pk_value'] = $_POST['username'];
        $res = updateDonorDetails(
            $_POST['new_name'],
            $_POST['new_address'],
            $_POST['new_contact'],
            $_POST['new_email'],
            strtolower($_POST['new_locality']),
            $_POST['new_lat'],
            $_POST['new_long']
        );
        unset($_SESSION['pk_value']);
        echo json_encode(array("res" => $res));
    }

    if (isset($_POST['deleteDonor'])) {
        $_SESSION['pk_value'] = $_POST['username'];
        deleteDonor();
        unset($_SESSION['pk_value']);
        echo json_encode(["valid" => true]);
    }



    // HOSPITALS

    if (isset($_POST['updateHospital'])) {
        $_SESSION['pk_value'] = $_POST['code'];
        $res = updateHospitalDetails(
            $_POST['new_name'],
            $_POST['new_address'],
            $_POST['new_contact'],
            $_POST['new_email'],
            $_POST['new_website']
        );
        unset($_SESSION['pk_value']);
        echo json_encode(array("res" => $res));
    }

    

    if (isset($_POST['deleteHospital'])) {
        $_SESSION['pk_value'] = $_POST['code'];
        deleteHospital();
        unset($_SESSION['pk_value']);
        echo json_encode(["valid" => true]);
    }

    if (isset($_POST['deleteDonationSpecs'])) {
        $query = $GLOBALS['PDO']->prepare("DELETE FROM donationspecs WHERE ID=:id");
        return $query->execute(["id" => $_POST['id']]);
        echo json_encode(["valid" => true]);
    }


    // PATIENTS

    if (isset($_POST['deletePatient'])) {
        $_SESSION['pk_value'] = $_POST['hospital'];
        deletePatient($_POST['ward'], $_POST['bed']);
        unset($_SESSION['pk_value']);
        echo json_encode(["valid" => true]);
    }
?>