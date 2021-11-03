<?php
    include "./functions.php";

    if (! isset($_SESSION['admin'])) {
        header("Location: /donor-finder/");
        die();
    }
    
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Admin |
        <?php echo $_SESSION['admin'] ?>
    </title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="./staticFiles/user.css" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
		integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>

    <style>
        .header {
            position: sticky;
            top: 60px;
        }
    </style>
</head>

<body style="padding-top: 70px;">

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="#">Welcome <?php echo $_SESSION['admin'] ?>!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-2 active border-bottom">
                        <a href="#" class="nav-link"><span
                                data-feather="home"></span> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="./signout.php" class="nav-link"><span data-feather="log-out"></span> Sign out</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">
        <div class="container" id="content">
            <div id="dashboard">
                <h1>Dashboard</h1>
                <hr class="featurette-divider">

                <div class="row featurette justify-content-center">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0">Details</h3>
                        </div>
                    </div>

                    <div class="card organ_card p-0 col-md-3 ml-2 mr-2 shadow text-center mb-4 bg-white rounded\" onclick="switchContent('#donors')">
                        <div class="card-body p-0">
                            <h4 class="p-4">Donors</h4>
                        </div>
                    </div>
                    <div class="card organ_card p-0 col-md-3 ml-2 mr-2 shadow text-center mb-4 bg-white rounded\" onclick="switchContent('#hospitals')">
                        <div class="card-body p-0">
                            <h4 class="p-4">Hospitals</h4>
                        </div>
                    </div>
                    <div class="card organ_card p-0 col-md-3 ml-2 mr-2 shadow text-center mb-4 bg-white rounded\" onclick="switchContent('#patients')">
                        <div class="card-body p-0">
                            <h4 class="p-4">Patients</h4>
                        </div>
                    </div>
                    <div class="card organ_card p-0 col-md-3 ml-2 mr-2 shadow text-center mb-4 bg-white rounded\" onclick="switchContent('#transactions')">
                        <div class="card-body p-0">
                            <h4 class="p-4">Transactions</h4>
                        </div>
                    </div>
                    <div class="card organ_card p-0 col-md-3 ml-2 mr-2 shadow text-center mb-4 bg-white rounded\" onclick="switchContent('#availabledonations')">
                        <div class="card-body p-0">
                            <h4 class="p-4">Available Donations</h4>
                        </div>
                    </div>
                    <div class="card organ_card p-0 col-md-3 ml-1 shadow text-center mb-4 bg-white rounded\" onclick="switchContent('#donationspecs')">
                        <div class="card-body p-0">
                            <h4 class="p-4">Donation Speciations</h4>
                        </div>
                    </div>
                    
                </div>

                <hr class="featurette-divider mb-4">

                <div class="row featurette" id="donors">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0" id="selected_info">Donors</h3>

                            <div class="input-group col-md-3 mr-auto ml-5">
                                <button class="btn btn-sm mr-3 btn-outline-secondary" data-toggle="modal"
                                    data-target="#register_donor_modal"><span class="fas fa-plus"></span> Add New</button>

                                <div class="input-group-append">
                                    <span type="button" onclick="$('.info-donors').load(window.location.href + ' .info-donors'); return false;" class="input-group-text" id="refresh"><i class="fas fa-sync"></i></span>
                                </div>
                            </div>

                            <div class="input-group col-md-3 float-right">
                                <input type="text" onkeyup="filter(this.value.toUpperCase(), 'donors', 'name')" class="form-control" placeholder="Search by Name" aria-label="Recipient's username" aria-describedby="search">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="search"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-donors w-100">
                        <?php
                            $donors = getAllDetails("SELECT * FROM donors");
                            $count = 0;

                            if (sizeof($donors) > 0) {
                                echo "
                                <table class=\"table table-striped text-center\">
                                    <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                        <tr>
                                            <th class=\"header\" scope=\"col\">#</th>
                                            <th class=\"header\" scope=\"col\">Username</th>
                                            <th class=\"header\" scope=\"col\">Name</th>
                                            <th class=\"header\" scope=\"col\">Email</th>
                                            <th class=\"header\" scope=\"col\">Contact</th>
                                            <th class=\"header\" scope=\"col\">Address</th>
                                            <th class=\"header\" scope=\"col\">Locality</th>
                                            <th class=\"header\" scope=\"col\">Blood Group</th>
                                            <th class=\"header\" scope=\"col\">DOB</th>
                                            <th class=\"header\" scope=\"col\"></th>
                                            <th class=\"header\" scope=\"col\"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
                            }

                            foreach ($donors as $donor) {
                                $count++;

                                echo "
                                    <tr>
                                        <td scope=\"row\">$count</td>
                                        <td scope=\"row\">$donor[Username]</td>
                                        <td scope=\"row\" class=\"name\">$donor[Name]</td>
                                        <td scope=\"row\">$donor[Email]</td>
                                        <td scope=\"row\">$donor[Contact]</td>
                                        <td scope=\"row\">$donor[Address]</td>
                                        <td scope=\"row\">$donor[Locality]</td>
                                        <td scope=\"row\">$donor[Blood_Group]</td>
                                        <td scope=\"row\">$donor[DOB]</td>
                                        <td scope=\"row\"><button class=\"btn btn-primary\" onclick=\"launchDonorProfileModal($count)\">Update</button></td>
                                        <td scope=\"row\"><button class=\"btn btn-danger\" onclick=\"deleteDonor($count)\">Delete</button></td>
                                    </tr>
                                ";
                            }

                            if ($count != 0) {
                                echo "
                                        </tbody>
                                    </table>
                                ";
                            }
                            else {
                                echo "
                                    <div id=\"no_donors\" class=\"col-sm-12 text-center\">
                                        <img src=\"./images/no_donors.svg\" alt=\"\" class=\"mx-auto d-block\"
                                            style=\"opacity: 0.5; height: 20vh;\">
                                        <p class=\"lead mt-3\">NO DONORS HAVE BEEN REGISTERED YET</p>
                                    </div>     
                                ";
                            }
                        ?>
                    </div>
                    
                </div>

                <div class="row featurette" id="hospitals" style="display: none;">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0" id="selected_info">Hospitals</h3>
                            <div class="input-group col-md-3 mr-auto ml-5">
                                <button class="btn btn-sm btn-outline-secondary mr-3" data-toggle="modal"
                                    data-target="#register_hospital_modal"><span class="fas fa-plus"></span> Add New</button>
                                
                                <div class="input-group-append">
                                    <span type="button" onclick="$('.info-hospitals').load(window.location.href + ' .info-hospitals'); return false;" class="input-group-text" id="refresh"><i class="fas fa-sync"></i></span>
                                </div>
                            </div>

                            <div class="input-group col-md-3 float-right">
                                <input type="text" onkeyup="filter(this.value.toUpperCase(), 'hospitals', 'name')" class="form-control" placeholder="Search by Name" aria-label="Recipient's username" aria-describedby="search">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="search"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-hospitals w-100">
                        <?php
                            $hospitals = getAllDetails("SELECT * FROM hospitals");
                            $count = 0;

                            if (sizeof($hospitals) > 0) {
                                echo "
                                <table class=\"table table-striped text-center\">
                                    <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                        <tr>
                                            <th class=\"header\" scope=\"col\">#</th>
                                            <th class=\"header\" scope=\"col\">Code</th>
                                            <th class=\"header\" scope=\"col\">Name</th>
                                            <th class=\"header\" scope=\"col\">Email</th>
                                            <th class=\"header\" scope=\"col\">Contact</th>
                                            <th class=\"header\" scope=\"col\">Address</th>
                                            <th class=\"header\" scope=\"col\">Website</th>
                                            <th class=\"header\" scope=\"col\"></th>
                                            <th class=\"header\" scope=\"col\"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
                            }

                            foreach ($hospitals as $hospital) {
                                $count++;

                                echo "
                                    <tr>
                                        <td scope=\"row\">$count</td>
                                        <td scope=\"row\">$hospital[Code]</td>
                                        <td scope=\"row\" class=\"name\">$hospital[Name]</td>
                                        <td scope=\"row\">$hospital[Email]</td>
                                        <td scope=\"row\">$hospital[Contact]</td>
                                        <td scope=\"row\">$hospital[Address]</td>
                                        <td scope=\"row\">$hospital[Website]</td>
                                        <td scope=\"row\"><button class=\"btn btn-primary\" onclick=\"launchHospitalProfileModal($count)\">Update</button></td>
                                        <td scope=\"row\"><button class=\"btn btn-danger\" onclick=\"deleteHospital($count)\">Delete</button></td>
                                    </tr>
                                ";
                            }

                            if ($count != 0) {
                                echo "
                                        </tbody>
                                    </table>
                                ";
                            }
                            else {
                                echo "
                                    <div id=\"no_hospitals\" class=\"col-sm-12 text-center\">
                                        <img src=\"./images/no_hospitals.svg\" alt=\"\" class=\"mx-auto d-block\"
                                            style=\"opacity: 0.5; height: 20vh;\">
                                        <p class=\"lead mt-3\">NO HOSPITALS HAVE BEEN REGISTERED YET</p>
                                    </div>     
                                ";
                            }
                        ?>
                    </div>
                    
                </div>

                <div class="row featurette" id="patients" style="display: none;">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0" id="selected_info">Patients</h3>
                            <div class="input-group-append ml-5 mr-auto">
                                <span type="button" onclick="$('.info-patients').load(window.location.href + ' .info-patients'); return false;" class="input-group-text" id="refresh"><i class="fas fa-sync"></i></span>
                            </div>

                            <div class="input-group col-md-3 float-right">
                                <input type="text" onkeyup="filter(this.value.toUpperCase(), 'patients', 'name')" class="form-control" placeholder="Search by Name" aria-label="Recipient's username" aria-describedby="search">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="search"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-patients w-100">
                        <?php
                            $patients = getAllDetails("SELECT * FROM patients");
                            $count = 0;

                            if (sizeof($patients) > 0) {
                                echo "
                                <table class=\"table table-striped text-center\">
                                    <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                        <tr>
                                            <th class=\"header\" scope=\"col\">#</th>
                                            <th class=\"header\" scope=\"col\">Name</th>
                                            <th class=\"header\" scope=\"col\">Ward</th>
                                            <th class=\"header\" scope=\"col\">Bed</th>
                                            <th class=\"header\" scope=\"col\">Organ</th>
                                            <th class=\"header\" scope=\"col\">Hospital</th>
                                            <th class=\"header\" scope=\"col\">Blood Group</th>
                                            <th class=\"header\" scope=\"col\"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
                            }

                            foreach ($patients as $patient) {
                                $count++;

                                echo "
                                    <tr>
                                        <td scope=\"row\">$count</td>
                                        <td scope=\"row\" class=\"name\">$patient[Name]</td>
                                        <td scope=\"row\">$patient[Ward]</td>
                                        <td scope=\"row\">$patient[Bed]</td>
                                        <td scope=\"row\">$patient[Organ]</td>
                                        <td scope=\"row\">$patient[Hospital]</td>
                                        <td scope=\"row\">$patient[Blood_Group]</td>
                                        <td scope=\"row\"><button class=\"btn btn-danger\" onclick=\"deletePatient($count)\">Delete</button></td>
                                    </tr>
                                ";
                            }

                            if ($count != 0) {
                                echo "
                                        </tbody>
                                    </table>
                                ";
                            }
                            else {
                                echo "
                                    <div id=\"no_patients\" class=\"col-sm-12 text-center\">
                                        <img src=\"./images/no_patients.svg\" alt=\"\" class=\"mx-auto d-block\"
                                            style=\"opacity: 0.5; height: 20vh;\">
                                        <p class=\"lead mt-3\">NO PATIENTS HAVE BEEN REGISTERED YET</p>
                                    </div>     
                                ";
                            }
                        ?>
                    </div>

                </div>

                <div class="row featurette" id="transactions" style="display: none;">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0" id="selected_info">Transactions</h3>
                            <div class="input-group-append ml-5 mr-auto">
                                <span type="button" onclick="$('.info-transactions').load(window.location.href + ' .info-transactions'); return false;" class="input-group-text" id="refresh"><i class="fas fa-sync"></i></span>
                            </div>

                            <div class="input-group col-md-3 float-right">
                                <input type="text" onkeyup="filter(this.value.toUpperCase(), 'transactions', 'date')" class="form-control" placeholder="Search by Date" aria-label="Recipient's username" aria-describedby="search">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="search"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-transactions w-100">
                        <?php
                            $transactions = getAllDetails("SELECT *, DATE_FORMAT(Transaction_Date, \"%d-%m-%Y\") AS Date FROM transactions");
                            $count = 0;

                            if (sizeof($transactions) > 0) {
                                echo "
                                <table class=\"table table-striped text-center\">
                                    <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                        <tr>
                                            <th class=\"header\" scope=\"col\">#</th>
                                            <th class=\"header\" scope=\"col\">Donor</th>
                                            <th class=\"header\" scope=\"col\">Hospital</th>
                                            <th class=\"header\" scope=\"col\">Patient</th>
                                            <th class=\"header\" scope=\"col\">Organ</th>
                                            <th class=\"header\" scope=\"col\">Honorarium</th>
                                            <th class=\"header\" scope=\"col\">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
                            }

                            foreach ($transactions as $transaction) {
                                $count++;

                                echo "
                                    <tr>
                                        <td scope=\"row\">$count</td>
                                        <td scope=\"row\">$transaction[Donor]</td>
                                        <td scope=\"row\">$transaction[Hospital]</td>
                                        <td scope=\"row\">$transaction[Patient]</td>
                                        <td scope=\"row\">$transaction[Organ]</td>
                                        <td scope=\"row\">$transaction[Honorarium]</td>
                                        <td scope=\"row\" class=\"date\">$transaction[Date]</td>
                                    </tr>
                                ";
                            }

                            if ($count != 0) {
                                echo "
                                        </tbody>
                                    </table>
                                ";
                            }
                            else {
                                echo "
                                    <div id=\"no_transactions\" class=\"col-sm-12 text-center\">
                                        <img src=\"./images/no_transactions.svg\" alt=\"\" class=\"mx-auto d-block\"
                                            style=\"opacity: 0.5; height: 20vh;\">
                                        <p class=\"lead mt-3\">NO TRANSACTIONS HAVE BEEN MADE YET</p>
                                    </div>     
                                ";
                            }
                        ?>
                    </div>
                    
                </div>

                <div class="row featurette" id="availabledonations" style="display: none;">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0" id="selected_info">Available Donations</h3>
                            <div class="input-group-append ml-5 mr-auto">
                                <span type="button" onclick="$('.info-available-donations').load(window.location.href + ' .info-available-donations'); return false;" class="input-group-text" id="refresh"><i class="fas fa-sync"></i></span>
                            </div>

                            <div class="input-group col-md-3 float-right">
                                <input type="text" onkeyup="filter(this.value.toUpperCase(), 'availabledonations', 'organ')" class="form-control" placeholder="Search by Organ" aria-label="Recipient's username" aria-describedby="search">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="search"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-available-donations w-100">
                        <?php
                            $availabledonations = getAllDetails("SELECT * FROM availabledonations");
                            $count = 0;

                            if (sizeof($availabledonations) > 0) {
                                echo "
                                <table class=\"table table-striped text-center\">
                                    <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                        <tr>
                                            <th class=\"header\" scope=\"col\">#</th>
                                            <th class=\"header\" scope=\"col\">Donor</th>
                                            <th class=\"header\" scope=\"col\">Organ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
                            }

                            foreach ($availabledonations as $availabledonation) {
                                $count++;

                                echo "
                                    <tr>
                                        <td scope=\"row\">$count</td>
                                        <td scope=\"row\">$availabledonation[Donor]</td>
                                        <td scope=\"row\" class=\"organ\">$availabledonation[Organ]</td>
                                    </tr>
                                ";
                            }

                            if ($count != 0) {
                                echo "
                                        </tbody>
                                    </table>
                                ";
                            }
                            else {
                                echo "
                                    <div id=\"no_donations\" class=\"col-sm-12 text-center\">
                                        <img src=\"./images/no_donations.svg\" alt=\"\" class=\"mx-auto d-block\"
                                            style=\"opacity: 0.5; height: 20vh;\">
                                        <p class=\"lead mt-3\">NO DONATIONS HAVE BEEN OFFERED YET</p>
                                    </div>     
                                ";
                            }
                        ?>
                        
                    </div>
                </div>

                <div class="row featurette" id="donationspecs" style="display: none;">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0" id="selected_info">Donation Specifications</h3>
                            <div class="input-group-append ml-5 mr-auto">
                                <span type="button" onclick="$('.info-donationspecs').load(window.location.href + ' .info-donationspecs'); return false;" class="input-group-text" id="refresh"><i class="fas fa-sync"></i></span>
                            </div>

                            <div class="input-group col-md-3 float-right">
                                <input type="text" onkeyup="filter(this.value.toUpperCase(), 'donationspecs', 'hospital')" class="form-control" placeholder="Search by Hospital" aria-label="Recipient's username" aria-describedby="search">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="search"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-donationspecs w-100">
                        <?php
                            $specs = getAllDetails("SELECT *, donationspecs.ID AS donationID FROM donationspecs JOIN organspecs ON donationspecs.Organ=organspecs.ID JOIN hospitals ON hospitals.Code=organspecs.Hospital");
                            $count = 0;

                            if (sizeof($specs) > 0) {
                                echo "
                                <table class=\"table table-striped text-center\">
                                    <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                        <tr>
                                            <th class=\"header\" scope=\"col\">#</th>
                                            <th class=\"header\" scope=\"col\">Hospital</th>
                                            <th class=\"header\" scope=\"col\">Age Group</th>
                                            <th class=\"header\" scope=\"col\">Organ</th>
                                            <th class=\"header\" scope=\"col\">Blood Group</th>
                                            <th class=\"header\" scope=\"col\">Honorarium</th>
                                            <th class=\"header\" scope=\"col\"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
                            }

                            foreach ($specs as $spec) {
                                $count++;
                                echo "
                                    <tr>
                                        <td scope=\"row\">$count</td>
                                        <td scope=\"row\" class=\"hospital\">$spec[Code] ⟶ $spec[Name]</td>
                                        <td scope=\"row\">$spec[Min_Age] - $spec[Max_Age]</td>
                                        <td scope=\"row\">$spec[Organ]</td>
                                        <td scope=\"row\">$spec[Blood_Group]</td>
                                        <td scope=\"row\">$spec[Honorarium]</td>
                                        <td scope=\"row\"><button data-id=\"$spec[donationID]\" class=\"btn btn-danger\" onclick=\"deleteDonationSpecs(this)\">Delete</button></td>
                                    </tr>
                                ";
                            }

                            if ($count != 0) {
                                echo "
                                        </tbody>
                                    </table>
                                ";
                            }
                            else {
                                echo "
                                    <div id=\"no_specifications\" class=\"col-sm-12 text-center\">
                                        <img src=\"./images/no_specifications.svg\" alt=\"\" class=\"mx-auto d-block\"
                                            style=\"opacity: 0.5; height: 20vh;\">
                                        <p class=\"lead mt-3\">NO SPECIFICATIONS HAVE BEEN ADDED YET</p>
                                    </div>     
                                ";
                            }
                        ?>
                    </div>

                </div>

            </div>
        </div>
    </main>


    <div class="modal fade" id="register_hospital_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header mx-3" id="register_header">
					<h3 class="modal-title">Register | Hospital</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body mx-3">
                    <div class="alert alert-danger alert-dismissable" id="invalid_register_hospital"
                        style="display: none;" onclick="this.parentElement.style.display = 'none'">
                        <button type="button" class="close"
                            onclick="this.ParentElement.style.display = 'none'">&times;</button>
                        <span id="register_error_hospital"></span>
                    </div>
                    <form id="hospital_register_form">
                        <div class="form-group">
                            <label for="hospital_name_register">Hospital Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="hospital_name_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="hospital_code_register">Hospital Code <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="hospital_code_register" class="form-control text-lowercase"
                                placeholder="GHS123" required>
                        </div>
                        <div class="form-group">
                            <label for="hospital_contact_register">Contact No <span
                                    class="text-danger">*</span></label>
                            <input type="tel" id="hospital_contact_register" pattern="[0-9]{10}"
                                class="form-control" placeholder="Ex: 1234567890" required>
                        </div>
                        <div class="form-group">
                            <label for="hospital_email_register">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" id="hospital_email_register" class="form-control"
                                placeholder="hospital@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="hospital_website_register">Website <span
                                    class="text-danger">*</span></label>
                            <input type="url" id="hospital_website_register" class="form-control"
                                placeholder="https://www.hospital.com">
                        </div>
                        <div class="form-group">
                            <label for="hospital_address_register">Address <span
                                    class="text-danger">*</span></label>
                            <textarea id="hospital_address_register" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="hospital_pass_register">Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" id="hospital_pass_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_hospital_pass_register">Confirm Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" id="confirm_hospital_pass_register" class="form-control"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="register_donor_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header mx-3" id="register_header">
					<h3 class="modal-title">Register | Donor</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body mx-3">
                    <div class="alert alert-danger alert-dismissable" id="invalid_register_donor"
                        style="display: none;">
                        <button type="button" class="close"
                            onclick="this.parentElement.style.display = 'none'">&times;</button>
                        <span id="register_error_donor"></span>
                    </div>
                    <form id="donor_register_form">
                        <div class="form-group">
                            <label for="donor_name_register">Name <span class="text-danger">*</span></label>
                            <input type="text" id="donor_name_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="donor_code_register">Username <span class="text-danger">*</span></label>
                            <input type="text" id="donor_code_register" class="form-control text-lowercase"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="donor_contact_register">Contact No <span
                                    class="text-danger">*</span></label>
                            <input type="tel" id="donor_contact_register" pattern="[0-9]{10}"
                                class="form-control" placeholder="Ex: 1234567890" required>
                        </div>
                        <div class="form-group">
                            <label for="donor_address_register">Address <span
                                    class="text-danger">*</span></label>
                            <textarea id="donor_address_register" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="donor_locality_register">Locality <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="donor_locality_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="donor_email_register">Email <span class="text-danger">*</span></label>
                            <input type="email" id="donor_email_register" class="form-control"
                                placeholder="donor@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="donor_dob_register">DOB <span class="text-danger">*</span></label>
                            <input type="date" id="donor_dob_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="donor_blood_group_register">Blood Group <span
                                    class="text-danger">*</span></label>
                            <select id="donor_blood_group_register" class="form-control" required>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="donor_pass_register">Password <span class="text-danger">*</span></label>
                            <input type="password" id="donor_pass_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_donor_pass_register">Confirm Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" id="confirm_donor_pass_register" class="form-control"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donor_profile_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header mx-3" id="register_header">
                    <h4 class="modal-title">Profile</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body mx-3">
                    
                    <div id="general"><br>
                        <div class="alert alert-success alert-dismissable" id="update_donor_success"
                            style="display: none;">
                            <button type="button" class="close"
                                onclick="this.parentElement.style.display = 'none'">&times;</button>
                            Updated profile successfully!
                        </div>
                        <form id="donor_update_form">
                            <div class="form-group">
                                <label for="donor_code_update">Username</label>
                                <input type="text" id="donor_code_update" class="form-control" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="donor_name_update">Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="donor_name_update" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="donor_contact_update">Contact No <span
                                        class="text-danger">*</span></label>
                                <input type="tel" id="donor_contact_update" pattern="[0-9]{10}"
                                    class="form-control" placeholder="Ex: 1234567890" required>
                            </div>
                            <div class="form-group">
                                <label for="donor_email_update">Email <span class="text-danger">*</span></label>
                                <input type="email" id="donor_email_update" class="form-control"
                                    placeholder="donor@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="donor_address_update">Address <span
                                        class="text-danger">*</span></label>
                                <textarea id="donor_address_update" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="donor_locality_update">Locality <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="donor_locality_update" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>        
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hospital_profile_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header mx-3" id="register_header">
                    <h4 class="modal-title">Profile</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body mx-3">
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#general">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pass">Password</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="general" class="container tab-pane active"><br>
                            <div class="alert alert-success alert-dismissable" id="update_hospital_success"
                                style="display: none;">
                                <button type="button" class="close"
                                    onclick="this.parentElement.style.display = 'none'">&times;</button>
                                Updated profile successfully!
                            </div>
                            <form id="hospital_update_form">
                                <div class="form-group">
                                    <label for="hospital_code_update">Hospital Code</label>
                                    <input type="text" id="hospital_code_update" class="form-control" readonly required>
                                </div>
                                <div class="form-group">
                                    <label for="hospital_name_update">Hospital Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="hospital_name_update" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="hospital_contact_update">Contact No <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" id="hospital_contact_update" pattern="[0-9]{10}"
                                        class="form-control" placeholder="Ex: 1234567890" required>
                                </div>
                                <div class="form-group">
                                    <label for="hospital_email_update">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="hospital_email_update" class="form-control"
                                        placeholder="hospital@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="hospital_website_update">Website <span
                                            class="text-danger">*</span></label>
                                    <input type="url" id="hospital_website_update" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="hospital_address_update">Address <span
                                            class="text-danger">*</span></label>
                                    <textarea id="hospital_address_update" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                        <div id="pass" class="container tab-pane"><br>
                            <div class="alert alert-danger alert-dismissable" id="hospital_update_pass_field"
                                style="display: none;">
                                <button type="button" class="close"
                                    onclick="this.parentElement.style.display = 'none'">&times;</button>
                                <span id="hospital_update_pass_message"></span>
                            </div>
                            <form id="hospital_update_pass_form">
                                <div class="form-group">
                                    <label for="hospital_pass_update">Current Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="hospital_pass_update" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="hospital_new_pass_update">New Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="hospital_new_pass_update" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_hospital_new_pass_update">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="confirm_hospital_new_pass_update" class="form-control"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <!-- Icons -->
    <script src="./staticFiles/index.js"></script>
    <script src="./staticFiles/donor.js"></script>
    <script src="./staticFiles/hospital.js"></script>
    <script src="./staticFiles/admin.js"></script>
    <script>feather.replace();</script>
</body>

</html>