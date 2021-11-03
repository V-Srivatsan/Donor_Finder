<?php
	include './functions.php';
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

	<title>Find The Donor | Home</title>

	<script id="bing_maps" src="https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AnUg7N6uwYDOeuVq7KWinBNePMn6AE_dcHgnd7H-4Cf2BJzt_xoZ4UH0e5Ntu1D7"></script>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
		integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

	<!-- Custom styles for this template -->
	<link href="./staticFiles/carousel.css" rel="stylesheet">
	<link rel="stylesheet" href="./staticFiles/index.css">
</head>

<body>

	<header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand" href="#">Find The Donor</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
				aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item border-bottom mr-2">
						<a href="#" data-toggle="modal" data-target="#register_modal" class="nav-link"><i
								class="fas fa-user-plus"></i> &nbsp; Sign up</a>
					</li>
					<li class="nav-item border-bottom mr-2">
						<a href="#" onclick="launchLogInModal(false); return false;" class="nav-link"><i
								class="fas fa-sign-in-alt"></i> &nbsp; Log in</a>
					</li>
					<li class="nav-item border-bottom mr-2" data-toggle="modal" data-target="#admin_modal">
						<a href="#" class="nav-link"><i class="fas fa-user-shield"></i> &nbsp; Admin</a>
					</li>
					<li class="nav-item">
						<button class="nav-link btn btn-outline-success money_donate_launcher" data-toggle="modal"
							data-target="#money_donation_modal"><i class="fas fa-money-bill-wave"></i> &nbsp; Donate in
							Cash</button>
					</li>
				</ul>
			</div>
		</nav>
	</header>

	<main role="main">
		<div id="info_carousel" class="carousel slide" data-interval="3000" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#info_carousel" data-slide-to="0" class="active"></li>
				<li data-target="#info_carousel" data-slide-to="1"></li>
				<li data-target="#info_carousel" data-slide-to="2"></li>
			</ol>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="./images/hospital.jpg" alt="Hospital" class="first-slide">
					<div class="container">
						<div class="carousel-caption text-left">
							<h1>Register</h1>
							<p>Register as a Hospital or a Donor.<br>You can specify your requirements / donations and we will help you pair up!
							</p>
							<p><a class="btn btn-lg btn-primary" href="#" data-toggle="modal"
									data-target="#register_modal">Register</a></p>
						</div>
					</div>
				</div>
				<div class="carousel-item">
					<img src="./images/donation.jpg" alt="Donate" class="second-slide">
					<div class="container">
						<div class="carousel-caption text-left">
							<h1>Donate</h1>
							<p>Want to donate? <br>
							Donations can even be in cash! Fill out the form to extend a helping hand!</p>
							<p><a class="btn btn-lg btn-primary money_donate_launcher" href="#" data-toggle="modal"
									data-target="#money_donation_modal">Donate</a></p>
						</div>
					</div>
				</div>
				<div class="carousel-item">
					<img src="./images/save_life.png" alt="Save A Life" class="third-slide">
					<div class="container">
						<div class="carousel-caption text-left">
							<h1>Save a Life</h1>
							<p>Doesn't matter whether you are donating or taking a donation,<br>
								you will save a life!</p>
							<p><a class="btn btn-lg btn-primary test" href="#about">Learn More</a></p>
						</div>
					</div>
				</div>
			</div>
			<a class="carousel-control-prev" href="#info_carousel" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#info_carousel" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>


		<!-- Marketing messaging and featurettes
      ================================================== -->
		<!-- Wrap the rest of the page in another container to center all the content. -->

		<div class="container marketing">

			<!-- Three columns of text below the carousel -->
			<div class="row">
				<div class="col-lg-4">
					<img class="rounded-circle"
						src="https://cdn.pixabay.com/photo/2020/08/03/09/43/medical-5459653__340.png"
						alt="Generic placeholder image" width="140" height="140">
					<h2>Register</h2>
					<p>First, register as a hospital. Then, you can add the specifications
						for the donation required. Simply select a Donor and contact him.</p>
					<p><a class="btn btn-secondary" href="#" data-toggle="modal"
							data-target="#register_modal">Register</a></p>
				</div>
				<div class="col-lg-4">
					<img class="rounded-circle"
						src="https://cdn.pixabay.com/photo/2020/07/29/06/09/diversity-5447016__340.jpg"
						alt="Generic placeholder image" width="140" height="140">
					<h2>Donate</h2>
					<p>A small donation can save someone's life, and it doesn't have to be organic! Fill out the form to donate in cash :)</p>
					<p><a class="btn btn-secondary money_donate_launcher" href="#" data-toggle="modal"
							data-target="#money_donation_modal">Donate</a>
					</p>
				</div>
				<div class="col-lg-4">
					<img class="rounded-circle"
						src="https://cdn.pixabay.com/photo/2015/07/24/23/28/love-859067__340.jpg"
						alt="Generic placeholder image" width="140" height="140">
					<h2>Save a Life</h2>
					<p>Many people suffer due to lack of timely donations. You can become a saviour by giving timely
						support and help.</p>
					<p><a href="#about" class="btn btn-secondary">Learn More</a></p>
				</div>
			</div>

			<hr class="featurette-divider">

			<div class="my-3 justify-content-center">
				<h2>Search for Donors in your Locality</h2>
				<form id="req_donation_form">
					<div class="form-group mr-1 d-inline-block col-2">
						<select id="donation_req_organ" class="form-control" required>
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
					<div class="form-group mr-1 d-inline-block col-2">
						<select id="donation_req_blood_group" class="form-control" required>
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
					<div class="form-group mr-1 d-inline-block col-7">
						<div class="input-group">
							<input type="text" id="donation_req_locality" class="form-control" required
								placeholder="Locality">
								<button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
						</div>
					</div>
				</form>

				<div id="myMap" class="col-12" style='position:relative; height: 50vh; display: none;'></div>
			</div>

			<!-- START THE FEATURETTES -->

			<hr class="featurette-divider">

			<div class="row featurette" id="about">
				<div class="col-md-7">
					<h2 class="featurette-heading m-0">About</h2>
					<p class="lead mt-3">“We make a living by what we get. We make a life by what we give.” ―Winston S.
						Churchill</p>
					<p class="lead">We make finding donations for hospitals easier as well as provide an elegant
						platform for Donors. This platform provides a slight chance of timely donation which can save
						someone's life.</p>
					<p class="lead">
						Various Hospitals tie up with us seeking Donors.
						As a Donor, you have the oppurtunity to choose from a plethora of hospitals to donate to. After
						confirming the donation, you will recieve an email confirmation regarding the same, and your
						hospital will get in touch with you.
					</p>
					<p class="lead">SAVE A LIFE, BE A DONOR, BE A HERO!</p>
				</div>
				<div class="col-md-5 my-auto">
					<img class="featurette-image img-fluid mx-auto" src="./images/about.jpg" alt="About">
				</div>
			</div>

			<hr class="featurette-divider">

		</div>
	</main>

	<!-- FOOTER -->
	<footer class="footer text-white bg-dark">
		<div class="container py-4">
			<h3>Contact Us</h3>
			<i class="fas fa-envelope"></i> &nbsp; <a href="mailto:test@gmail.com"> test@gmail.com</a> <br>
			<i class="fas fa-phone-volume"></i> &nbsp; <a href="tel:+911234567890"> (+91) 1234567890</a>
		</div>
	</footer>

	<!-- Bootstrap Modals -->

	<div class="modal fade" id="register_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header mx-3" id="register_header">
					<h3 class="modal-title">Register</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body mx-3">
					<ul class="nav nav-pills nav-justified" role="tablist">
						<li class="nav-item">
							<a id="hospital_tab_register" class="nav-link active" data-toggle="tab"
								href="#register_hospital">Hospital</a>
						</li>
						<li class="nav-item">
							<a id="donor_tab_register" class="nav-link" data-toggle="tab"
								href="#register_donor">Donor</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div id="register_hospital" class="container tab-pane active"><br>
							<div class="form-group">
								<a href="#" onclick="changeToLoginModal(false); return false;">Already Have an Account?
									Log In!</a>
							</div>
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
						<div id="register_donor" class="container tab-pane"><br>
							<div class="form-group">
								<a href="#" onclick="changeToLoginModal(true); return false;">Already Have an Account?
									Log In!</a>
							</div>
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
		</div>
	</div>

	<div class="modal fade" id="login_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header mx-3" id="register_header">
					<h3 class="modal-title">Log in</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body mx-3">
					<ul class="nav nav-pills nav-justified" role="tablist">
						<li class="nav-item">
							<a id="hospital_tab_login" class="nav-link active" data-toggle="tab"
								href="#login_hospital">Hospital</a>
						</li>
						<li class="nav-item">
							<a id="donor_tab_login" class="nav-link" data-toggle="tab" href="#login_donor">Donor</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div id="login_hospital" class="container tab-pane active"><br>
							<div class="form-group">
								<a href="#" onclick="changeToSignUpModal(false); return false;">Don't Have an Account?
									Sign up for free!</a>
							</div>
							<div class="alert alert-danger alert-dismissable" id="invalid_login_hospital"
								style="display: none;">
								<button type="button" class="close"
									onclick="this.parentElement.style.display = 'none'">&times;</button>
								The Hospital Code or the password is incorrect. Please try again!
							</div>
							<form id="hospital_login_form">
								<div class="form-group">
									<label for="hospital_code_login">Hospital Code <span
											class="text-danger">*</span></label>
									<input type="text" id="hospital_code_login" class="form-control text-lowercase"
										required>
								</div>
								<div class="form-group">
									<label for="hospital_pass_login">Password <span class="text-danger">*</span></label>
									<input type="password" id="hospital_pass_login" class="form-control" required>
								</div>
								<div class="form-group">
									<a href="#" data-toggle="modal" data-target="#forgot_pass_modal" data-dismiss="modal">Forgot Password?</a>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</form>
						</div>
						<div id="login_donor" class="container tab-pane"><br>
							<div class="form-group">
								<a href="#" onclick="changeToSignUpModal(true); return false;">Don't Have an Account?
									Sign up for free!</a>
							</div>
							<div class="alert alert-danger alert-dismissable" id="invalid_login_donor"
								style="display: none;">
								<button type="button" class="close"
									onclick="this.parentElement.style.display = 'none'">&times;</button>
								The Username or the password is incorrect. Please try again!
							</div>
							<form id="donor_login_form">
								<div class="form-group">
									<label for="donor_code_login">Username <span class="text-danger">*</span></label>
									<input type="text" id="donor_code_login" class="form-control text-lowercase"
										required>
								</div>
								<div class="form-group">
									<label for="donor_pass_login">Password <span class="text-danger">*</span></label>
									<input type="password" id="donor_pass_login" class="form-control" required>
								</div>
								<div class="form-group">
									<a href="#" data-toggle="modal" data-target="#forgot_pass_modal" data-dismiss="modal">Forgot Password?</a>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="money_donation_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header mx-3">
					<h4 class="modal-title">Donate in Cash</h4>
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body mx-3">
					<form id="money_donate_form_1">
						<div class="form-group">
							<label>Account Number</label>
							<input type="text" id="money_donate_account_number" class="form-control" required
								placeholder="XXXX-XXXX-XXXX">
						</div>
						<div class="form-group">
							<label>Amount for Donation</label>
							<input type="number" id="money_donate_amount" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-primary">Proceed</button>
						<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</form>

					<form id="money_donate_form_2" style="display: none;">
						<div class="form-group">
							<label>OTP</label>
							<input type="text" id="money_donate_otp" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-primary">Validate</button>
						<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</form>

					<div id="money_donate_success" class="text-center text-success" style="display: none;">
						<i class="fas fa-check-double"></i>
						<p>Transaction Successfull! Thank you for your kind donation!</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="admin_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header mx-3" id="register_header">
					<h3 class="modal-title">Login | Admin</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body mx-3">
					<div class="alert alert-danger alert-dismissable" id="invalid_login_admin"
						style="display: none;">
						<button type="button" class="close"
							onclick="this.parentElement.style.display = 'none'">&times;</button>
						The Username or the password is incorrect. Please try again!
					</div>
					<form id="admin_login_form">
						<div class="form-group">
							<label for="admin_code_login">Username <span class="text-danger">*</span></label>
							<input type="text" id="admin_code_login" class="form-control text-lowercase"
								required>
						</div>
						<div class="form-group">
							<label for="admin_pass_login">Password <span class="text-danger">*</span></label>
							<input type="password" id="admin_pass_login" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="forgot_pass_modal">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header mx-3">
					<h4 class="modal-title">Forgot Password</h4>
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body mx-3">
					<form id="forgot_pwd_form">
						<div class="form-group">
							<label for="code_forgot">Hospital Code <span class="text-danger">*</span></label>
							<input type="text" id="code_forgot" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="role_forgot">Role <span
									class="text-danger">*</span></label>
							<select id="role_forgot" class="form-control" onchange="
								if (this.value == 'Hospital') this.parentElement.previousElementSibling.children[0].innerHTML = 'Hospital Code <span class=\'text-danger\'>*</span>'
								else this.parentElement.previousElementSibling.children[0].innerHTML = 'Username <span class=\'text-danger\'>*</span>'
							">
								<option value="Hospital">Hospital</option>
								<option value="Donor">Donor</option>
							</select>
						</div>
						<button type="submit" class="btn btn-primary" id="code_submit_forgot_pwd">Next &raquo;</button>
						<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</form>
					
					<div class="responses" style="display: none;">
						<div class="text-success font-weight-bold" id="forgot_pwd_response_valid"></div>
						<div class="text-danger font-weight-bold" id="forgot_pwd_response_invalid">Sorry, your account could not be found!</div>
						<div class="text-danger font-weight-bold" id="forgot_pwd_otp_invalid" style="display: none;">Invalid OTP. Please try again!</div>

						<form id="forgot_pwd_otp_form" style="display: none !important;" class="d-inline">
							<div class="form-group">
								<label>OTP</label>
								<input type="number" id="forgot_pwd_otp" class="form-control" required>
							</div>
							
							<button type="submit" class="btn btn-primary">Next &raquo;</button>
						</form>
						<button class="btn btn-secondary" onclick="$('.responses').hide(); $('#forgot_pwd_form').show(); return false;">&laquo; Prev</button>
					</div>

					<div class="reset_pwd" style="display: none;">
						<div class="text-danger font-weight-bold" id="forgot_pwd_reset_invalid" style="display: none;">The passwords don't match! Please try again!</div>
						<div class="text-success font-weight-bold" id="forgot_pwd_reset_valid" style="display: none;">Password restored successfully! Now, you can Login!</div>
						<form id="forgot_pwd_reset_form" class="d-inline">
							<div class="form-group">
								<label>New Password</label>
								<input type="password" id="forgot_pwd_new_pass" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Confirm New Password</label>
								<input type="password" id="confirm_forgot_pwd_new_pass" class="form-control" required>
							</div>

							<button type="submit" class="btn btn-primary">Update</button>
						</form>
						<button class="btn btn-secondary" onclick="$('.reset_pwd').hide(); $('.responses').show(); return false;">&laquo; Prev</button>
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
	<!-- <script id="bing_maps" src="https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AnUg7N6uwYDOeuVq7KWinBNePMn6AE_dcHgnd7H-4Cf2BJzt_xoZ4UH0e5Ntu1D7"></script> -->
	<script src="./staticFiles/index.js"></script>

	<script>
		callSubmitFuncs()
	</script>
</body>

</html>