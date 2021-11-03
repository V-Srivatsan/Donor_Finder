<?php
    include "./functions.php";

    if (! isset($_SESSION['loggedUserTable'])) {
        header("Location: ./index.php");
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

    <title>Find The Donor |
        <?php echo $_SESSION['pk_value'] ?>
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
            <a class="navbar-brand" href="#">Welcome </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-2 active border-bottom" id="dashboard_field">
                        <a href="#" onclick="changeContent('Dashboard'); return false;" id="dashboard_link" class="nav-link"><span
                                data-feather="home"></span> Dashboard</a>
                    </li>
                    <li class="nav-item mr-2" id="transaction_field">
                        <a href="#" onclick="changeContent('Transactions'); return false;" id="transaction_link" class="nav-link"><span
                                data-feather="bar-chart-2"></span> Transactions</a>
                    </li>
                    <li class="nav-item mr-2" id="patients_field">
                        <a href="#" onclick="changeContent('Patients'); return false;" id="patients_link" class="nav-link"><span
                                data-feather="activity"></span> Patients</a>
                    </li>
                    <li class="nav-item mr-2">
                        <a href="#" data-toggle="modal" data-target="#hospital_profile_modal" class="nav-link"><span
                                data-feather="user"></span> Profile</a>
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

                <div class="row featurette">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0">Donation Specifications</h3>
                            <button class="btn btn-sm float-right btn-outline-secondary" data-toggle="modal"
                                data-target="#donor_specs_modal"><span class="fas fa-plus"></span> Add New</button>
                        </div>
                    </div>

                    <?php
                        $res = $PDO->query("SELECT * FROM organspecs WHERE Hospital='$_SESSION[pk_value]' ORDER BY Organ, Min_Age, Max_Age-Min_Age");

                        $count = 0;
                        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                            $count++;
                            
                            $bloodGroups = "";
                            $bloodRes = $PDO->query("SELECT Blood_Group FROM donationspecs WHERE Organ=$row[ID]");
                            while ($bloodRow = $bloodRes->fetch(PDO::FETCH_ASSOC)) {
                                $bloodGroups .= $bloodRow['Blood_Group'] . ", ";
                            }
                            $bloodGroups = substr($bloodGroups, 0, -2);
                            
                            echo "
                            <div class=\"card organ_card shadow text-center mb-4 bg-white rounded mr-3\" onclick=\"launchUpdateSpecsModal(this); return false;\">
                                <h4 class=\"card-header p-3 bg-dark text-white\">$row[Organ]</h4>
                                <div class=\"card-body p-4\">
                                    <div class=\"font-weight-bold\"><span class=\"min_age\">$row[Min_Age]</span> - <span class=\"max_age\">$row[Max_Age]</span> Years</div>
                                    <div class=\"font-weight-bold blood_group\">$bloodGroups</div>
                                    <span style=\"display: none;\" class=\"organ_id\">$row[ID]</span>
                                </div> 
                                <div class=\"card-footer\">INR $row[Honorarium]</div>
                            </div>
                            ";

                        }

                        if ($count == 0) {
                            echo "<div id=\"no_donor_specs\" class=\"col-sm-12 text-center\">
                            <img src=\"./images/add_entries.svg\" alt=\"No Entries\" class=\"mx-auto d-block\"
                                style=\"opacity: 0.5; height: 20vh;\">
                            <p class=\"lead mt-3\">NO SPECIFICATIONS YET. PLEASE ADD USING THE BUTTON ABOVE</p>
                        </div>";
                        }
                    ?>
                </div>

                <hr class="featurette-divider">

                <div class="row featurette">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                            <h3 class="featurette-heading m-0">Available Donors</h3>
                        </div>
                    </div>

                    <?php
                        $donors = getDonorDetails();
                        $count = 0;

                        if (sizeof($donors) != 0) {
                            echo "
                            <table class=\"table table-striped text-center\">
                                <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                    <tr>
                                        <th class=\"header\" scope=\"col\">#</th>
                                        <th class=\"header\" scope=\"col\">Donor</th>
                                        <th class=\"header\" scope=\"col\">Email</th>
                                        <th class=\"header\" scope=\"col\">Contact</th>
                                        <th class=\"header\" scope=\"col\">Blood Group</th>
                                        <th class=\"header\" scope=\"col\">Age</th>
                                        <th class=\"header\" scope=\"col\">Organ</th>
                                        <th class=\"header\" scope=\"col\">Honorarium</th>
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
                                    <td scope=\"row\">$donor[0]</td>
                                    <td scope=\"row\">$donor[1]</td>
                                    <td scope=\"row\">$donor[2]</td>
                                    <td scope=\"row\">$donor[3]</td>
                                    <td scope=\"row\">$donor[4]</td>
                                    <td scope=\"row\">$donor[5]</td>
                                    <td scope=\"row\">INR $donor[6]</td>
                                    <td><button class=\"btn btn-sm float-right btn-outline-success\" onclick=\"launchPayDonorModal(this)\">Pay Donor</button></td>
                                    <span class=\"donor_code\" style=\"display: none;\">$donor[7]</span>
                                    <span class=\"donation_id\" style=\"display: none;\">$donor[8]</span>
                                </tr>
                            ";
                        }

                        if ($count == 0) {
                            echo "
                            <div id=\"no_donors\" class=\"col-sm-12 text-center\">
                                <img src=\"./images/no_donors.svg\" alt=\"\" class=\"mx-auto d-block\"
                                    style=\"opacity: 0.5; height: 20vh;\">
                                <p class=\"lead mt-3\">NO DONORS AVAILABLE YET :)</p>
                            </div>      
                            ";
                        }
                        else {
                            echo "
                                    </tbody>
                                </table>
                            ";
                        }
                    ?>
                </div>
            </div>

            <div id="transactions" style="display: none;">
                <h1>Transactions</h1>
                <hr class="featurette-divider">

                <div class="row featurette">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
                            <h3 class="featurette-heading m-0">Donor Counts</h3>
                        </div>
                    </div>
                    <canvas class="my-2" id="myChart" width="80vw" height="40vh"></canvas>
                </div>

                <hr class="featurette-divider">

                <div class="row featurette">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
                            <h3 class="featurette-heading m-0">Previous Transactions</h3>
                        </div>
                    </div>

                    <table class="table table-striped text-center">
                        <thead style="position: sticky; top: 60px" class="thead-dark">
                            <tr>
                                <th class="header" scope="col">Donor</th>
                                <th class="header" scope="col">Patient</th>
                                <th class="header" scope="col">Contact</th>
                                <th class="header" scope="col">Organ</th>
                                <th class="header" scope="col">Blood Group</th>
                                <th class="header" scope="col">Honorarium</th>
                                <th class="header" scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody id="transaction_rows">

                        </tbody>
                    </table>
                </div>
            </div>
        
            <div id="patients" style="display: none;">

                <div class="row featurette">
                    <div class="col-md-12">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
                            <h1 class="featurette-heading m-0">Patients</h1>
                            <button class="btn btn-sm float-right btn-outline-secondary" data-toggle="modal" data-target="#register_patient_modal"><span class="fas fa-plus"></span> Register Patient</button>
                        </div>
                    </div>

                    <?php
                        $patients = getPatients();
                        $count = 0;

                        if (sizeof($patients) != 0) {
                            echo "
                                <table class=\"table table-striped text-center\">
                                <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                    <tr>
                                        <th class=\"header\" scope=\"col\">Patient</th>
                                        <th class=\"header\" scope=\"col\">Ward</th>
                                        <th class=\"header\" scope=\"col\">Bed</th>
                                        <th class=\"header\" scope=\"col\">Blood Group</th>
                                        <th class=\"header\" scope=\"col\">Organ</th>
                                        <th class=\"header\" scope=\"col\"></th>
                                    </tr>
                                </thead>
                                <tbody id=\"patient_rows\">
                            ";
                        }

                        foreach ($patients as $patient) {
                            $count++;
                            echo "
                                <tr>
                                    <td>$patient[0]</td>
                                    <td>$patient[1]</td>
                                    <td>$patient[2]</td>
                                    <td>$patient[3]</td>
                                    <td>$patient[4]</td>
                                    <td><button class=\"btn btn-outline-danger\" onclick=\"deletePatient(this.parentElement.parentElement)\"><span class=\"fas fa-trash\"></span> Delete</button></td>
                                </tr>
                            ";
                        }

                        if ($count == 0) {
                            echo "
                                <div id=\"no_patients\" class=\"col-sm-12 text-center\">
                                    <img src=\"./images/no_patients.svg\" alt=\"\" class=\"mx-auto d-block\"
                                        style=\"opacity: 0.5; height: 20vh;\">
                                    <p class=\"lead mt-3\">NO PATIENTS HAVE BEEN REGISTERED YET</p>
                                </div>     
                            ";
                        }
                        else {
                            echo "
                                    </tbody>
                                </table>
                            ";
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>


    <div class="modal fade" id="donor_specs_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header mx-3">
                    <h4 class="modal-title">Add Donation Specifications</h4>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body mx-3">
                    <div class="alert alert-success alert-dismissable" id="donation_specs_field" style="display: none;">
                        <button type="button" class="close"
                            onclick="this.parentElement.style.display = 'none'">&times;</button>
                        <span id="donation_specs_message"></span>
                    </div>
                    <form id="donation_specs_form">
                        <div class="form-group">
                            <label for="age_donor_specs">Age Group (in Years) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="age_min_donor_specs" min="18" class="col-md-3 form-control"
                                    placeholder="Minimum Age" required>
                                <span class="input-group-addon"> &nbsp; </span>
                                <input type="number" id="age_max_donor_specs" min="18" class="col-md-3 form-control"
                                    placeholder="Maximum Age" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="blood_group_specs">Blood Groups Required <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> A+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> A-</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> B+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> B-</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> O+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> O-</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> AB+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> AB-</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="organ_specs">Donation required <span class="text-danger">*</span></label>
                            <select id="organ_specs" class="form-control" required>
                                <option value="Blood">Blood</option>
                                <option value="Kidney">Kidney</option>
                                <option value="Liver">Liver</option>
                                <option value="Pancreas">Pancreas</option>
                                <option value="Heart">Heart</option>
                                <option value="Lung">Lung</option>
                                <option value="Eye">Eye</option>
                                <option value="Bone">Bone</option>
                                <option value="Skin">Skin</option>
                                <option value="Tendon">Tendon</option>
                                <option value="Plasma">Plasma</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="honorarium">Honorarium <span class="text-danger">*</span></label>
                            <input type="number" id="honorarium" min="10000" class="form-control"
                                placeholder="Amount in INR" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donor_update_specs_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header mx-3">
                    <h4 class="modal-title">Update Donation Specifications</h4>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body mx-3">
                    <div class="alert alert-success alert-dismissable" id="donation_specs_update_field" style="display: none;">
                        <button type="button" class="close"
                            onclick="this.parentElement.style.display = 'none'">&times;</button>
                        <span id="donation_specs_update_message"></span>
                    </div>
                    <form id="donation_specs_update_form">
                        <div class="form-group">
                            <label for="age_donor_update_specs">Age Group (in Years) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="age_min_donor_update_specs" min="18" class="col-md-3 form-control"
                                    placeholder="Minimum Age" required>
                                <span class="input-group-addon"> &nbsp; </span>
                                <input type="number" id="age_max_donor_update_specs" min="18" class="col-md-3 form-control"
                                    placeholder="Maximum Age" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="blood_group_update_specs">Blood Groups Required <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> A+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> A-</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> B+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> B-</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> O+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> O-</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> AB+</label>
                                </div>
                                <div class="form-control"><label><input class="bg-checkbox" type="checkbox"> AB-</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="organ_update_specs">Donation required <span class="text-danger">*</span></label>
                            <select id="organ_update_specs" class="form-control" required>
                                <option value="Blood">Blood</option>
                                <option value="Kidney">Kidney</option>
                                <option value="Liver">Liver</option>
                                <option value="Pancreas">Pancreas</option>
                                <option value="Heart">Heart</option>
                                <option value="Lung">Lung</option>
                                <option value="Eye">Eye</option>
                                <option value="Bone">Bone</option>
                                <option value="Skin">Skin</option>
                                <option value="Tendon">Tendon</option>
                                <option value="Plasma">Plasma</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="honorarium_update">Honorarium <span class="text-danger">*</span></label>
                            <input type="number" id="honorarium_update" min="10000" class="form-control"
                                placeholder="Amount in INR" required>
                        </div>
                        <input type="hidden" id="organ_id">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger float-right" onclick="deleteDonationSpecs(); return false;"><span data-feather="trash-2"></span> Delete</button>
                    </form>
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

                <div class="modal-footer">
                    <button class="btn btn-danger" onclick="deleteHospital(); return false;"><span data-feather="trash-2"></span> Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="register_patient_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header mx-3">
					<h4 class="modal-title">Register Patient</h4>
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body mx-3">
                    <div class="alert alert-success alert-dismissable" id="register_patient_success"
                        style="display: none;">
                        <button type="button" class="close"
                            onclick="this.parentElement.style.display = 'none'">&times;</button>
                        Patient registered successfully!
                    </div>
                    <div class="alert alert-danger alert-dismissable" id="register_patient_failure"
                        style="display: none;">
                        <button type="button" class="close"
                            onclick="this.parentElement.style.display = 'none'">&times;</button>
                        You already have a patient in the same Ward and Bed! Please try again!
                    </div>
                    <form id="register_patient_form">
                        <div class="form-group">
                            <label>Patient Name</label>
                            <input type="text" id="patient_name_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Ward</label>
                            <input type="text" id="patient_ward_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Bed No</label>
                            <input type="number" id="patient_bed_register" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Blood Group</label>
                            <select id="patient_blood_group_register" class="form-control" required>
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
                            <label>Organ</label>
                            <select id="patient_organ_register" class="form-control" required>
                                <option value="Blood">Blood</option>
                                <option value="Kidney">Kidney</option>
                                <option value="Liver">Liver</option>
                                <option value="Pancreas">Pancreas</option>
                                <option value="Heart">Heart</option>
                                <option value="Lung">Lung</option>
                                <option value="Eye">Eye</option>
                                <option value="Bone">Bone</option>
                                <option value="Skin">Skin</option>
                                <option value="Tendon">Tendon</option>
                                <option value="Plasma">Plasma</option>
                            </select>
                        </div>

                        <button class="btn btn-primary" type="submit">Submit</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pay_donor_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header mx-3">
					<h4 class="modal-title">Pay Donor</h4>
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body mx-3">
                    
                    <form id="select_patient_form">
                        <div class="form-group">
                            <label>Select Patient</label>
                            <select id="select_patient" class="form-control" required>
                            
                            </select>
                        </div>

						<button type="submit" class="btn btn-primary">Next &raquo;</button>
						<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>

					<form id="pay_donor_form_1" style="display: none;">
						<div class="form-group">
							<label>Account Number</label>
							<input type="number" id="pay_donor_account_number" class="form-control" required
								placeholder="XXXX-XXXX-XXXX">
						</div>
						<div class="form-group">
							<label>Amount for Donation</label>
							<input type="number" id="pay_donor_amount" class="form-control" readonly required>
						</div>
						<button type="submit" class="btn btn-primary">Proceed &raquo;</button>
						<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</form>

					<form id="pay_donor_form_2" style="display: none;">
						<div class="form-group">
							<label>OTP</label>
							<input type="text" id="pay_donor_otp" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-primary" id="otp_submit_pay_donor_modal">Validate &raquo;</button>
						<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</form>

					<div id="pay_donor_success" class="text-center text-success" style="display: none;">
						<i class="fas fa-check-double"></i>
						<p>Transaction Successfull!</p>
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
    

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>
        function initGraph(data_values) {
            var ctx = document.getElementById("myChart");
            const data = {
                labels: ["Blood", "Kidney", "Liver", "Pancreas", "Heart", "Lung", "Eye", "Bone", "Skin", "Tendon"],
                datasets: [{
                    label: 'Donation Counts',
                    data: data_values,
                    backgroundColor: [
                        'rgba(167, 65, 73, 0.3)',
                        'rgba(40, 39, 38, 0.3)',
                        'rgba(106, 138, 130, 0.3)',
                        'rgba(163, 124, 39, 0.3)',
                        'rgba(86, 56, 56, 0.3)',
                        'rgba(4, 68, 191, 0.3)',
                        'rgba(89, 87, 117, 0.3)',
                        'rgba(192, 53, 242, 0.3)',
                        'rgba(22, 34, 90, 0.3)',
                        'rgba(66, 58, 1, 0.3)'
                    ],
                    borderColor: [
                        'rgb(167, 65, 73)',
                        'rgb(40, 39, 38)',
                        'rgb(106, 138, 130)',
                        'rgb(163, 124, 39)',
                        'rgb(86, 56, 56)',
                        'rgb(4, 68, 191)',
                        'rgb(89, 87, 117)',
                        'rgb(192, 53, 242)',
                        'rgb(22, 34, 90)',
                        'rgb(66, 58, 1)'
                    ],
                    borderWidth: 1
                }]
            }
            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            }
            var myChart = new Chart(ctx, config)
        }
    </script>

    <script src="./staticFiles/hospital.js"></script>
    <script>feather.replace()</script>
    <?php
        $PDO = new PDO("mysql:host=localhost;dbname=$_SESSION[dbname]", "$_SESSION[username]", "$_SESSION[pass]");
        $query = $PDO->query("SELECT * FROM $_SESSION[loggedUserTable] WHERE $_SESSION[pk]='$_SESSION[pk_value]'");
        $row = json_encode($query->fetch(PDO::FETCH_ASSOC));

        echo "<script>
            callSubmitFuncs()
            fillHospitalProfileModal($row)
        </script>";
    ?>
</body>

</html>