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
                    <li class="nav-item mr-2 active border-bottom">
                        <a href="#" class="nav-link"><span
                                data-feather="home"></span> Dashboard</a>
                    </li>
                    <li class="nav-item mr-2">
                        <a href="#" data-toggle="modal" data-target="#donor_profile_modal" class="nav-link"><span
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
                                data-target="#donate_modal"><span class="fas fa-plus"></span> Add New</button>
                        </div>
                    </div>

                    <?php
                        $res = $PDO->query("SELECT * FROM availabledonations WHERE Donor='$_SESSION[pk_value]'");

                        $count = 0;
                        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                            $count++;
                            
                            echo "
                            <div class=\"card organ_card shadow text-center mb-4 bg-white rounded mr-3\">
                                <div class=\"card-header p-1 bg-dark text-white\">
                                    <span type=\"button\" onclick=\"deleteDonationSpecs(this.parentElement.parentElement)\" class=\"fas fa-trash\"></span>
                                    <span class=\"donor_donation_id\" style=\"display: none;\">$row[ID]</span>
                                </div>
                                <div class=\"card-footer p-0\">
                                    <h4 class=\"card-header p-3\">$row[Organ]</h4>
                                </div>
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
                            <h3 class="featurette-heading m-0">Available Hospitals</h3>
                        </div>
                    </div>

                    <?php
                        $hospitals = filterHospitalsForDonor();
                        $count = 0;

                        if (sizeof($hospitals) != 0) {
                            echo "
                            <table class=\"table table-striped text-center\">
                                <thead style=\"position: sticky; top: 60px\" class=\"thead-dark\">
                                    <tr>
                                        <th class=\"header\" scope=\"col\">#</th>
                                        <th class=\"header\" scope=\"col\">Hospital</th>
                                        <th class=\"header\" scope=\"col\">Address</th>
                                        <th class=\"header\" scope=\"col\">Contact</th>
                                        <th class=\"header\" scope=\"col\">Email</th>
                                        <th class=\"header\" scope=\"col\">Website</th>
                                        <th class=\"header\" scope=\"col\">Organ</th>
                                        <th class=\"header\" scope=\"col\">Honorarium</th>
                                        <th class=\"header\" scope=\"col\"></th>
                                    </tr>
                                </thead>
                                <tbody>
                            ";
                        }

                        foreach ($hospitals as $hospital) {
                            $count++;
                            if ($hospital[4]) {
                                echo "
                                    <tr>
                                        <td>$count</td>
                                        <td scope=\"row\">$hospital[0]</td>
                                        <td scope=\"row\">$hospital[1]</td>
                                        <td scope=\"row\">$hospital[2]</td>
                                        <td scope=\"row\">$hospital[3]</td>
                                        <td scope=\"row\"><a href=\"$hospital[4]\" target=\"_blank\">$hospital[4]</a></td>
                                        <td scope=\"row\" class=\"organ\">$hospital[5]</td>
                                        <td scope=\"row\">INR $hospital[6]</td>
                                        <td scope=\"row\"><button class=\"btn btn-outline-success\" onclick=\"selectHospitalForDonation($count)\">Donate</button></td>
                                        <span class=\"donation_id\" style=\"display: none\">$hospital[7]</span>
                                    </tr>
                                ";
                            }
                            else {
                                echo "
                                    <tr>
                                        <td>$count</td>
                                        <td scope=\"row\">$hospital[0]</td>
                                        <td scope=\"row\">$hospital[1]</td>
                                        <td scope=\"row\">$hospital[2]</td>
                                        <td scope=\"row\">$hospital[3]</td>
                                        <td scope=\"row\">-</td>
                                        <td scope=\"row\" class=\"organ\">$hospital[5]</td>
                                        <td scope=\"row\">INR $hospital[6]</td>
                                        <td scope=\"row\"><button class=\"btn btn-outline-success\" onclick=\"selectHospitalForDonation($count)\">Donate</button></td>
                                        <span class=\"donation_id\" style=\"display: none\">$hospital[7]</span>
                                    </tr>
                                ";
                            }
                        }

                        if ($count == 0) {
                            echo "
                                <div id=\"no_donors\" class=\"col-sm-12 text-center\">
                                <img src=\"./images/no_hospitals.svg\" alt=\"\" class=\"mx-auto d-block\"
                                    style=\"opacity: 0.5; height: 20vh;\">
                                <p class=\"lead mt-3\">NO HOSPITALS AVAILABLE YET :)</p>
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
                        <div id="pass" class="container tab-pane"><br>
                            <div class="alert alert-danger alert-dismissable" id="donor_update_pass_field"
                                style="display: none;">
                                <button type="button" class="close"
                                    onclick="this.parentElement.style.display = 'none'">&times;</button>
                                <span id="donor_update_pass_message"></span>
                            </div>
                            <form id="donor_update_pass_form">
                                <div class="form-group">
                                    <label for="donor_pass_update">Current Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="donor_pass_update" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="donor_new_pass_update">New Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="donor_new_pass_update" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_donor_new_pass_update">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="confirm_donor_new_pass_update" class="form-control"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" onclick="deleteDonor(); return false;"><span data-feather="trash-2"></span> Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donate_modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header mx-3">
                    <h4 class="modal-title">Donate</h4>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body mx-3">
                    <div class="alert alert-success alert-dismissable" id="donation_field"
                        style="display: none;">
                        <button type="button" class="close"
                            onclick="this.parentElement.style.display = 'none'">&times;</button>
                        Donation Added Successfully!
                    </div>
                    <form id="donate_form">
                        <div class="form-group">
                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Blood" checked>
                                <div class="card-footer">Blood</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Kidney">
                                <div class="card-footer">Kidney</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Liver">
                                <div class="card-footer">Liver</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Pancreas">
                                <div class="card-footer">Pancreas</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Heart">
                                <div class="card-footer">Heart</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Lung">
                                <div class="card-footer">Lung</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Eye">
                                <div class="card-footer">Eye</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Bone">
                                <div class="card-footer">Bone</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Skin">
                                <div class="card-footer">Skin</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Tendon">
                                <div class="card-footer">Tendon</div>
                            </label>

                            <label class="card shadow text-center mb-4 bg-white rounded mr-3 col-sm-4 col-md-2 p-0 d-inline-block">
                                <input type="radio" name="organ" value="Plasma">
                                <div class="card-footer">Plasma</div>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>
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
        function initMap(data_values) {
            var ctx = document.getElementById("myChart");
            const data = {
                labels: ["Blood", "Kidney", "Liver", "Pancreas", "Heart", "Lung", "Eye", "Bone", "Skin", "Tendon", "Plasma"],
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

    <script src="./staticFiles/donor.js"></script>
    <script>feather.replace();
    </script>
    <?php
        $PDO = new PDO("mysql:host=localhost;dbname=$_SESSION[dbname]", "$_SESSION[username]", "$_SESSION[pass]");
        $query = $PDO->query("SELECT * FROM $_SESSION[loggedUserTable] WHERE $_SESSION[pk]='$_SESSION[pk_value]'");
        $row = json_encode($query->fetch(PDO::FETCH_ASSOC));

        echo "<script>
                fillDonorProfileModal($row)
                callSubmitFuncs()
            </script>";
    ?>
</body>

</html>