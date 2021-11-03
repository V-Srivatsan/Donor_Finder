<?php

    // Start the session and establish DB connection
    session_start();
    if (! isset($_SESSION['dbname'])) {
		$data = json_decode(file_get_contents("./db.json"));
		
		$_SESSION['dbname'] = $data->dbname;
		$_SESSION['username'] = $data->username;
		$_SESSION['pass'] = $data->pass;
	}
    $PDO = new PDO("mysql:host=localhost;dbname=$_SESSION[dbname]", "$_SESSION[username]", "$_SESSION[pass]");


    // REGISTRATION

    function addDonor($data) {
        try {
            $query = $GLOBALS['PDO']->prepare("INSERT INTO donors VALUES (:username, :name, :email, :contact, :address, :locality, :lat, :long, :blood, :dob, PASSWORD(:pass))");
            return $query->execute($data);
        } catch (\Throwable $th) {
            return false;
        }
    }

    function addHospital($data) {
        try {
            $query = $GLOBALS['PDO']->prepare("INSERT INTO hospitals VALUES (:code, :name, :address, :contact, :email, PASSWORD(:pass), :website)");
            return $query->execute($data);
        } catch (\Throwable $th) {
            return false;
        }
    }


    // LOGIN

    function loginHospital($data) {
        $query = $GLOBALS['PDO']->prepare("SELECT COUNT(*) AS numRecords FROM hospitals WHERE Code=:code AND Pass=PASSWORD(:pass)");
        $query->execute($data);
        return $query->fetch(PDO::FETCH_ASSOC)['numRecords'];
    }

    function loginDonor($data) {
        $query = $GLOBALS['PDO']->prepare("SELECT COUNT(*) AS numRecords FROM donors WHERE Username=:username AND Pass=PASSWORD(:pass)");
        $query->execute($data);
        return $query->fetch(PDO::FETCH_ASSOC)['numRecords'];
    }

    function generateCode() {
        $pass = "";
        for ($i=0; $i < 6; $i++) { 
            $rand_idx = random_int(0, 10);
            $pass .= $rand_idx;
        }
        return $pass;
    }

    function generateOtp($table, $code, $pk_column) {

        $query = $GLOBALS['PDO']->prepare("SELECT Name, Email FROM $table WHERE $pk_column=:code");
        $query->execute([
            "code" => $code
        ]);
        
        $email = "";
        $name = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $email = $row["Email"];
            $name = $row["Name"];
        }

        if ($email == "" || $name == "") return false;

        $failure = true;
        while ($failure) {
            try {
                $query = $GLOBALS['PDO']->prepare("INSERT INTO onetimepass VALUES (:code, :pass)");
                $new_pass = generateCode();
                $query->execute([
                    "pass" => $new_pass,
                    "code" => $code
                ]);
                $failure = false;
            } catch (\Throwable $th) {
                $failure = true;
            }
        }

        $_SESSION['reset_code'] = $code;
        return [$new_pass, $email, $name];
    }

    function validateOtp($otp) {
        $query = $GLOBALS['PDO']->prepare("SELECT * FROM onetimepass WHERE User='$_SESSION[reset_code]' AND Pass=:pass");
        $query->execute(["pass" => $otp]);

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['allow_reset'] = true;
            return true;
        }

        return false;
    }

    function setNewPass($new_pass, $table_name, $column_name) {
        $query = $GLOBALS['PDO']->prepare("UPDATE $table_name SET Pass=PASSWORD(:pass) WHERE $column_name='$_SESSION[reset_code]'");
        $query->execute(["pass" => $new_pass]);
        $GLOBALS['PDO']->query("DELETE FROM onetimepass WHERE User='$_SESSION[reset_code]'");
    }

    // HOSPITAL (CRUD)

    function updateHospitalDetails($new_name, $new_address, $new_contact, $new_email, $new_website) {
        $query = $GLOBALS['PDO']->prepare("UPDATE hospitals SET Name=:name, Address=:address, Contact=:contact, Email=:email, Website=:website WHERE Code='$_SESSION[pk_value]'");
        return $query->execute(array(
            "name" => $new_name,
            "address" => $new_address,
            "contact" => $new_contact,
            "email" => $new_email,
            "website" => $new_website
        ));
    }

    function updateHospitalPass($old_pass, $new_pass) {
        $count = loginHospital([
            "code" => $_SESSION['pk_value'],
            "pass" => $old_pass
        ]);

        if ($count == 0) return false;
        $query = $GLOBALS['PDO']->prepare("UPDATE hospitals SET Pass=PASSWORD(:pass) WHERE Code='$_SESSION[pk_value]'");
        return $query->execute(["pass" => $new_pass]);
    }

    function deleteHospital() {
        $query = $GLOBALS['PDO']->prepare("DELETE FROM hospitals WHERE Code=:code");
        $query->execute(["code" => $_SESSION['pk_value']]);
    }

    function addDonationSpecs($bloodGroups, $data) {
        try {
            $query = $GLOBALS['PDO']->prepare("INSERT INTO organspecs(Hospital, Min_Age, Max_Age, Organ, Honorarium) VALUES (:hospital, :min_age, :max_age, :organ, :honorarium)");
            $query->execute($data);
        } catch (Exception $e) {
            return false;
        }

        $id = ($GLOBALS['PDO']->query("SELECT MAX(ID) AS id FROM organspecs"))->fetch(PDO::FETCH_ASSOC)['id'];

        $query =  $GLOBALS['PDO']->prepare("INSERT INTO donationspecs(Organ, Blood_Group) VALUES ($id, :bg)");
        foreach ($bloodGroups as $bloodGroup) {
            $query->execute(["bg" => $bloodGroup]);
        }
        return true;
    }

    function deleteDonationSpecs($id) {
        $query = $GLOBALS['PDO']->prepare("DELETE FROM organspecs WHERE ID=:id");
        return $query->execute(["id" => $id]);
    }

    function updateDonationSpecs($prev_id, $bloodGroups, $new_data) {
        deleteDonationSpecs($prev_id);
        if (! addDonationSpecs($bloodGroups, $new_data)) {
            return -1;
        }

        $id = ($GLOBALS['PDO']->query("SELECT MAX(ID) AS id FROM organspecs"))->fetch(PDO::FETCH_ASSOC)['id'];
        return $id;
    }

    function registerPatient($name, $ward, $bed, $blood_group, $organ) {
        try {
            $query = $GLOBALS['PDO']->prepare("INSERT INTO patients(Name, Ward, Bed, Blood_Group, Organ, Hospital) VALUES (:name, :ward, :bed, :bg, :organ, '$_SESSION[pk_value]')");
            $query->execute([
                "name" => $name,
                "ward" => $ward,
                "bed" => $bed,
                "bg" => $blood_group,
                "organ" => $organ
            ]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function getPatients() {
        $query = $GLOBALS['PDO']->query("SELECT * FROM patients WHERE Hospital='$_SESSION[pk_value]'");
        $patients = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $patient = [
                $row["Name"],
                $row["Ward"],
                $row["Bed"],
                $row["Blood_Group"],
                $row["Organ"]
            ];
            array_push($patients, $patient);
        }

        return $patients;
    }

    function deletePatient($ward, $bed) {
        $query = $GLOBALS['PDO']->prepare("DELETE FROM patients WHERE Ward=:ward AND Bed=:bed AND Hospital='$_SESSION[pk_value]'");
        $query->execute([
            "ward" => $ward,
            "bed" => $bed
        ]);
    }

    function filterPatients($blood_group, $organ) {
        $query = $GLOBALS['PDO']->prepare("SELECT * FROM patients WHERE Organ=:organ AND Blood_Group=:bg");
        $query->execute([
            "organ" => $organ,
            "bg" => $blood_group
        ]);

        $patients = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $patient = [
                "ward" => $row["Ward"],
                "bed" => $row["Bed"],
                "name" => $row["Name"]
            ];

            array_push($patients, $patient);
        }

        return $patients;
    }

    function getDonorDetails() {
        $query = $GLOBALS['PDO']->query("
            SELECT donors.Name, donors.Contact, donors.Email, YEAR(CURDATE())-YEAR(donors.DOB) AS Age, organspecs.Organ, donors.Blood_Group, donors.Username, organspecs.Honorarium, donationspecs.ID FROM 
            selectedspecs JOIN donationspecs ON selectedspecs.Donation=donationspecs.ID
            JOIN organspecs ON organspecs.ID=donationspecs.Organ
            JOIN donors ON donors.Username=selectedspecs.Donor
            WHERE organspecs.Hospital='$_SESSION[pk_value]'
        ");

        $donors = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $details = [
                $row["Name"],
                $row["Email"],
                $row["Contact"],
                $row["Blood_Group"],
                $row["Age"],
                $row["Organ"],
                $row["Honorarium"],
                $row["Username"],
                $row["ID"]
            ];
            array_push($donors, $details);
        }
        return $donors;
    }

    function payDonor($organ, $donor, $honorarium, $donation_id, $patient_name) {
        $query = $GLOBALS['PDO']->prepare("INSERT INTO transactions VALUES (:donor, :patient, :hospital, :organ, :honorarium, :date)");
        $query->execute([
            "donor" => $donor,
            "patient" => $patient_name,
            "hospital" => $_SESSION['pk_value'],
            "organ" => $organ,
            "honorarium" => $honorarium,
            "date" => date("Y-m-d")
        ]);

        $query = $GLOBALS['PDO']->prepare("DELETE FROM donationspecs WHERE ID=:id");
        $query->execute(["id" => $donation_id]);

        $query = $GLOBALS['PDO']->prepare("SELECT * FROM donors WHERE Username=:donor_id");
        $query->execute(["donor_id" => $donor]);

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            return [
                "Name" => $row["Name"],
                "Email" => $row["Email"]
            ];
        }
    }

    function getTransactionDetails() {
        $counts = [
            "Blood" => 0,
            "Kidney" => 0,
            "Liver" => 0,
            "Pancreas" => 0,
            "Heart" => 0,
            "Lung" => 0,
            "Eye" => 0,
            "Bone" => 0,
            "Skin" => 0,
            "Tendon" => 0,
            "Plasma" => 0
        ];

        $query = $GLOBALS['PDO']->query("SELECT Organ, COUNT(*) AS Count FROM transactions WHERE Hospital='$_SESSION[pk_value]' GROUP BY Organ");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $counts[$row["Organ"]] = $row["Count"];
        }

        $details = [];

        $query = $GLOBALS['PDO']->query("SELECT Name, Contact, Blood_Group, Honorarium, DATE_FORMAT(Transaction_Date, \"%d-%m-%Y\") AS Date, Organ, Patient FROM transactions JOIN donors on transactions.Donor=donors.Username WHERE Hospital='$_SESSION[pk_value]' ORDER BY Transaction_Date DESC");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($details, [$row["Name"], $row["Patient"], $row["Contact"], $row["Organ"], $row["Blood_Group"], $row["Honorarium"], $row["Date"]]);
        }

        return [$counts, $details];
    }


    // DONOR (CRUD)

    function updateDonorDetails($new_name, $new_address, $new_contact, $new_email, $new_locality, $new_lat, $new_long) {
        $query = $GLOBALS['PDO']->prepare("UPDATE donors SET Name=:name, Address=:address, Locality=:locality, Latitude=:lat, Longitude=:long, Contact=:contact, Email=:email WHERE Username='$_SESSION[pk_value]'");
        return $query->execute(array(
            "name" => $new_name,
            "address" => $new_address,
            "contact" => $new_contact,
            "email" => $new_email,
            "locality" => $new_locality,
            "lat" => $new_lat,
            "long" => $new_long
        ));
    }

    function updateDonorPass($old_pass, $new_pass) {
        $count = loginDonor([
            "username" => $_SESSION['pk_value'],
            "pass" => $old_pass
        ]);

        if ($count == 0) return false;
        $query = $GLOBALS['PDO']->prepare("UPDATE donors SET Pass=PASSWORD(:pass) WHERE Username='$_SESSION[pk_value]'");
        return $query->execute(["pass" => $new_pass]);
    }

    function deleteDonor() {
        $query = $GLOBALS['PDO']->prepare("DELETE FROM donors WHERE Username=:code");
        $query->execute(["code" => $_SESSION['pk_value']]);
    }

    function addDonationSpecsDonor($data) {
        try {
            $query = $GLOBALS['PDO']->prepare("INSERT INTO availabledonations(Donor, Organ) VALUES (:donor, :organ)");
            $query->execute($data);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    function deleteDonationSpecsDonor($id) {
        $query = $GLOBALS['PDO']->prepare("DELETE FROM availabledonations WHERE ID=:id");
        $query->execute(["id" => $id]);
    }

    function filterHospitalsForDonor() {
        $query = $GLOBALS['PDO']->query("
            SELECT hospitals.Code, hospitals.Name, hospitals.Address, organspecs.Organ, organspecs.Honorarium, hospitals.Contact, hospitals.Email, hospitals.Website, donationspecs.ID
            FROM organspecs JOIN donationspecs ON organspecs.ID=donationspecs.Organ 
            JOIN hospitals ON organspecs.Hospital=hospitals.Code 
            JOIN availabledonations JOIN donors ON availabledonations.Donor=donors.Username AND availabledonations.Organ=organspecs.Organ AND donors.Blood_Group=donationspecs.Blood_Group AND YEAR(CURDATE())-YEAR(donors.DOB) BETWEEN organspecs.Min_Age AND organspecs.Max_Age
            WHERE availabledonations.Donor='$_SESSION[pk_value]'
        ");
        $hospital_details = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $detail = [
                $row["Name"],
                $row["Address"],
                $row["Contact"],
                $row["Email"],
                $row["Website"],
                $row["Organ"],
                $row["Honorarium"],
                $row["ID"]
            ];
            array_push($hospital_details, $detail);
        }
        return $hospital_details;
    }

    function applyForDonation($donation_id) {
        try {
            $query = $GLOBALS['PDO']->prepare("INSERT INTO selectedspecs VALUES (:donation, :donor)");
            $query->execute(["donation" => $donation_id, "donor" => $_SESSION['pk_value']]);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }


    // REQUEST DONOR

    function getAllDonors() {
        $query = $GLOBALS['PDO']->query("SELECT * FROM donors");
        $donors = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $donor = [
                $row["Name"],
                $row["Email"],
                $row["Contact"],
                $row["Address"],
                $row["Latitude"],
                $row["Longitude"]
            ];
            array_push($donors, $donor);
        }

        return $donors;
    }

    function requestDonor($bloodGroup, $organ, $locality) {
        $query = $GLOBALS['PDO']->prepare("SELECT * FROM donors JOIN availabledonations ON donors.Username=availabledonations.Donor WHERE Organ=:organ AND Blood_Group=:bloodGroup AND Locality=:locality");
        $query->execute([
            "organ" => $organ,
            "bloodGroup" => $bloodGroup,
            "locality" => $locality
        ]);

        $res = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $donor = [
                $row["Name"],
                $row["Email"],
                $row["Contact"],
                $row["Address"],
                $row["Latitude"],
                $row["Longitude"]
            ];
            array_push($res, $donor);
        }
        return $res;
    }


    // ADMIN

    function getAllDetails($query) {
        $details = [];
        $query = $GLOBALS['PDO']->query("$query");

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($details, $row);
        }

        return $details;
    }
?>