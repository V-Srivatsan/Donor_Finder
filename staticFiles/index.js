let API_KEY = ""


function launchLogInModal(isDonor) {
    //Tabs And Tab-Content
    let hospitalTab = document.getElementById('hospital_tab_login')
    let donorTab = document.getElementById('donor_tab_login')
    let hospital = document.getElementById('login_hospital')
    let donor = document.getElementById('login_donor')

    if (isDonor) {
        donorTab.classList.add('active')
        donor.classList.add('active')

        hospitalTab.classList.remove('active')
        hospital.classList.remove('active')
    }
    else {
        hospitalTab.classList.add('active')
        hospital.classList.add('active')

        donorTab.classList.remove('active')
        donor.classList.remove('active')
    }

    $('#login_modal').modal('show')
}

function launchSignUpModal(isDonor) {
    //Tabs And Tab-Content
    let hospitalTab = document.getElementById('hospital_tab_register')
    let donorTab = document.getElementById('donor_tab_register')
    let hospital = document.getElementById('register_hospital')
    let donor = document.getElementById('register_donor')

    if (isDonor) {
        donorTab.classList.add('active')
        donor.classList.add('active')

        hospitalTab.classList.remove('active')
        hospital.classList.remove('active')
    }
    else {
        hospitalTab.classList.add('active')
        hospital.classList.add('active')

        donorTab.classList.remove('active')
        donor.classList.remove('active')
    }

    $('#register_modal').modal('show')
}

function calculateAge(birthday) {
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}


// already have an account?
function changeToLoginModal(isDonor) {
    $("#register_modal").modal('hide')
    launchLogInModal(isDonor)
}

// Don't have an account
function changeToSignUpModal(isDonor) {
    $("#login_modal").modal('hide')
    launchSignUpModal(isDonor)
}

function addDonor(lat, long) {

    let message_field = document.getElementById("invalid_register_donor")
    let message_elem = $("#register_error_donor")

    let age = calculateAge(new Date($("#donor_dob_register").val()))
    if (age < 18) {
        message_field.classList.remove("alert-success")
        message_field.classList.add("alert-danger")
        message_elem.text("You are too young to donate, but we appreciate your interest :) Thank you!")
        $("#invalid_register_donor").show()
        return null;
    }

    let pass = $("#donor_pass_register").val()
    if (pass != $("#confirm_donor_pass_register").val()) {
        message_field.classList.remove("alert-success")
        message_field.classList.add("alert-danger")
        message_elem.text("The Passwords don't match! Please try again!")
        $("#invalid_register_donor").show()
        return null;
    }

    $.ajax({
        type: "POST",
        url: "./index_server.php",
        data: {
            "addUser": true,
            "isDonor": true,
            "name": $("#donor_name_register").val(),
            "username": $("#donor_code_register").val(),
            "contact": $("#donor_contact_register").val(),
            "address": $("#donor_address_register").val(),
            "locality": $("#donor_locality_register").val(),
            "lat": lat,
            "long": long,
            "email": $("#donor_email_register").val(),
            "dob": $("#donor_dob_register").val(),
            "blood_group": $("#donor_blood_group_register").val(),
            "password": pass
        },
        success: function (response) {
            var decodedResponse = JSON.parse(response)
            if (decodedResponse['valid']) {
                message_field.classList.remove("alert-danger")
                message_field.classList.add("alert-success")
                $("#donor_register_form").trigger("reset")
            }
            else {
                message_field.classList.remove("alert-success")
                message_field.classList.add("alert-danger")
            }
            message_elem.text(decodedResponse['message'])
            message_field.style.display = "block"
        }
    })
}

function getCoords() {
    $(document).on('submit', "#donor_register_form", function (form) {
        form.preventDefault()

        let lat = ""
        let long = ""
        $.ajax({
            type: "GET",
            url: "https://dev.virtualearth.net/REST/v1/Locations?query=" + $("#donor_address_register").val() + "&key="+API_KEY,
            success: function (response) {
                let coords = response["resourceSets"][0]["resources"][0]["geocodePoints"][0]["coordinates"]
                lat = coords[0]
                long = coords[1]
                addDonor(lat, long)
            }
        })
    })
}

function addHospital() {
    $(document).on('submit', "#hospital_register_form", function (form) {
        form.preventDefault()
        let message_field = document.getElementById("invalid_register_hospital")
        let message_elem = $("#register_error_hospital")

        let pass = $("#hospital_pass_register").val()
        if (pass != $("#confirm_hospital_pass_register").val()) {
            message_field.classList.remove("alert-success")
            message_field.classList.add("alert-danger")
            message_elem.text("The Passwords don't match! Please try again!")
            $("#invalid_register_hospital").show()
            return null;
        }

        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "addUser": true,
                "name": $("#hospital_name_register").val(),
                "code": $("#hospital_code_register").val(),
                "contact": $("#hospital_contact_register").val(),
                "email": $("#hospital_email_register").val(),
                "address": $("#hospital_address_register").val(),
                "password": pass,
                "website": $("#hospital_website_register").val()
            },
            success: function (response) {

                var decodedResponse = JSON.parse(response)
                if (decodedResponse['valid']) {
                    message_field.classList.remove("alert-danger")
                    message_field.classList.add("alert-success")
                    $("#hospital_register_form").trigger("reset")
                }
                else {
                    message_field.classList.remove("alert-success")
                    message_field.classList.add("alert-danger")
                }
                message_elem.text(decodedResponse['message'])
                message_field.style.display = "block"
            }
        })
    })
}

function loginHospital() {
    $(document).on('submit', "#hospital_login_form", function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "logUser": true,
                "pass": $("#hospital_pass_login").val(),
                "code": $("#hospital_code_login").val()
            },
            success: function (response) {
                var decodedResponse = JSON.parse(response)
                if (!decodedResponse['valid']) {
                    $("#invalid_login_hospital").show()
                }
                else {
                    location = decodedResponse['location']
                }
            }
        })
    })
}

function loginDonor() {
    $(document).on('submit', "#donor_login_form", function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "logUser": true,
                "pass": $("#donor_pass_login").val(),
                "username": $("#donor_code_login").val(),
                "isDonor": true
            },
            success: function (response) {
                var decodedResponse = JSON.parse(response)
                if (!decodedResponse['valid']) {
                    $("#invalid_login_donor").show()
                }
                else {
                    location = decodedResponse['location']
                }
            }
        })
    })
}

function loginAdmin() {
    $(document).on('submit', "#admin_login_form", function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./admin_server.php",
            data: {
                "loginAdmin": true,
                "username": $("#admin_code_login").val(),
                "pass": $("#admin_pass_login").val()
            },
            success: function (response) {
                let decodedResponse = JSON.parse(response)
                if (decodedResponse['valid']) {
                    location = decodedResponse['location']
                }
                else {
                    $("#invalid_login_admin").show()
                }
            }
        })
    })
}

function moneyTransaction() {
    $(document).on('submit', "#money_donate_form_1", function (form) {
        form.preventDefault()
        $("#money_donate_form_1").hide()
        $("#money_donate_form_2").show()
    })

    $(document).on('submit', "#money_donate_form_2", function (form) {
        form.preventDefault()
        $("#money_donate_form_2").hide()
        $("#money_donate_success").show()
    })

    $(".money_donate_launcher").click(function () {
        $("#money_donate_form_2").hide().trigger('reset')
        $("#money_donate_success").hide()
        $("#money_donate_form_1").show().trigger('reset')
    })
}

function getInfobox(e) {
    if (e.target.metadata) {
        //Set the infobox options with the metadata of the pushpin.
        infobox.setOptions({
            location: e.target.getLocation(),
            title: e.target.metadata.title,
            description: e.target.metadata.description,
            visible: true
        });
    }
}

function mapAllDonors(data) {
    let map = new Microsoft.Maps.Map('#myMap', {
        credentials: API_KEY,
        zoom: 12
    })

    //Make the infobox
    infobox = new Microsoft.Maps.Infobox(map.getCenter(), {
        visible: false
    })

    //Assign the infobox to a map instance.
    infobox.setMap(map);

    data.forEach(donor => {

        // Set the pins coords
        var pin = new Microsoft.Maps.Pushpin({
            latitude: donor[4],
            longitude: donor[5]
        }, {
            icon: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="28.5" height="46.5" viewBox="0 0 32 52"><g transform="matrix(0.03057603,0,0,0.03070631,-6.3065742,-4.4882472)"><path stroke-miterlimit="10" d="M 730.94,1839.63 C 692.17401,1649.33 623.82397,1490.96 541.03699,1344.1899 479.63001,1235.3199 408.49301,1134.83 342.673,1029.25 320.70099,994.00702 301.73901,956.77399 280.62601,920.19702 238.41,847.06 204.18201,762.26202 206.357,652.26501 208.482,544.79199 239.565,458.58099 284.38699,388.09299 358.10599,272.15799 481.58801,177.104 647.271,152.12399 782.737,131.7 909.74597,166.20599 999.81403,218.87199 1073.41,261.91 1130.41,319.39899 1173.73,387.15201 c 45.22,70.716 76.36,154.25998 78.97,263.23196 1.34,55.83002 -7.8,107.53204 -20.68,150.41803 -13.03,43.409 -33.99,79.69501 -52.64,118.45398 -36.41,75.659 -82.05,144.98402 -127.86,214.34402 -136.43699,206.61 -264.49601,417.31 -320.58,706.03 z" style="clip-rule:evenodd;fill:{color};fill-rule:evenodd;stroke-width:37;stroke-miterlimit:10" /><circle cx="725.54596" cy="661.047" r="337.33295" style="clip-rule:evenodd;fill:#1f1f1f;fill-rule:evenodd" /></g></svg>',
            anchor: new Microsoft.Maps.Point(14, 47),
            color: '#ff0f0f',
        })

        pin.metadata = {
            title: donor[0],
            description: "Email: "+donor[1]+"<br>Contact: "+donor[2]+"<br>Address: "+donor[3]
        }


        // Add OnClick Listener
        Microsoft.Maps.Events.addHandler(pin, 'click', getInfobox);
        map.entities.push(pin);
    })

    $("#myMap").show()
}

function searchForDonors() {
    $(document).on('submit', "#req_donation_form", function (form) {
        form.preventDefault()
        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "requestDonor": true,
                "blood_group": $("#donation_req_blood_group").val(),
                "organ": $("#donation_req_organ").val(),
                "locality": $("#donation_req_locality").val()
            },
            success: function (response) {
                let decodedRes = JSON.parse(response)

                if (decodedRes['valid']) {

                    let map = new Microsoft.Maps.Map('#myMap', {
                        credentials: API_KEY,
                        zoom: 6
                    })

                    //Make the infobox
                    infobox = new Microsoft.Maps.Infobox(map.getCenter(), {
                        visible: false
                    })
            
                    //Assign the infobox to a map instance.
                    infobox.setMap(map);

                    decodedRes['data'].forEach(donor => {

                        // Set the pins coords
                        var pin = new Microsoft.Maps.Pushpin({
                            latitude: donor[4],
                            longitude: donor[5]
                        }, {
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="28.5" height="46.5" viewBox="0 0 32 52"><g transform="matrix(0.03057603,0,0,0.03070631,-6.3065742,-4.4882472)"><path stroke-miterlimit="10" d="M 730.94,1839.63 C 692.17401,1649.33 623.82397,1490.96 541.03699,1344.1899 479.63001,1235.3199 408.49301,1134.83 342.673,1029.25 320.70099,994.00702 301.73901,956.77399 280.62601,920.19702 238.41,847.06 204.18201,762.26202 206.357,652.26501 208.482,544.79199 239.565,458.58099 284.38699,388.09299 358.10599,272.15799 481.58801,177.104 647.271,152.12399 782.737,131.7 909.74597,166.20599 999.81403,218.87199 1073.41,261.91 1130.41,319.39899 1173.73,387.15201 c 45.22,70.716 76.36,154.25998 78.97,263.23196 1.34,55.83002 -7.8,107.53204 -20.68,150.41803 -13.03,43.409 -33.99,79.69501 -52.64,118.45398 -36.41,75.659 -82.05,144.98402 -127.86,214.34402 -136.43699,206.61 -264.49601,417.31 -320.58,706.03 z" style="clip-rule:evenodd;fill:{color};fill-rule:evenodd;stroke-width:37;stroke-miterlimit:10" /><circle cx="725.54596" cy="661.047" r="337.33295" style="clip-rule:evenodd;fill:#1f1f1f;fill-rule:evenodd" /></g></svg>',
                            anchor: new Microsoft.Maps.Point(14, 47),
                            color: '#ff0f0f',
                        })

                        pin.metadata = {
                            title: donor[0],
                            description: "Email: "+donor[1]+"<br>Contact: "+donor[2]+"<br>Address: "+donor[3]
                        }


                        // Add OnClick Listener
                        Microsoft.Maps.Events.addHandler(pin, 'click', getInfobox);
                        map.entities.push(pin);
                    })

                    $("#myMap").show()
                }
                else {
                    alert("Sorry. No Donors avilable :(")
                }
            }
        })
    })
}

function forgotPassword() {
    $(document).on('submit', "#forgot_pwd_form", function (form) {
        form.preventDefault()

        let code = $("#code_forgot").val()
        let role = $("#role_forgot").val()

        $("#code_submit_forgot_pwd").attr("disabled", "")

        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "resetPassword": true,
                "code": code,
                "role": role
            },
            success: function (response) {
                let decodedRes = JSON.parse(response)
                $("#forgot_pwd_form").hide()
                $(".responses").show()

                if (decodedRes['valid']) {
                    $("#forgot_pwd_response_valid").text(decodedRes['message'])
                    $("#forgot_pwd_response_invalid").hide()
                    $("#forgot_pwd_otp_form").show()
                }
                else {
                    $("#forgot_pwd_response_valid").hide()
                }
            }
        })
    })

    $(document).on('submit', "#forgot_pwd_otp_form", function (form) {
        form.preventDefault()

        $("#forgot_pwd_response_valid").hide()
        $("#forgot_pwd_response_invalid").hide()

        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "resetPassword": true,
                "otpResetPassword": true,
                "otp": $("#forgot_pwd_otp").val()
            },
            success: function (response) {
                let decodedRes = JSON.parse(response)

                if (decodedRes['valid']) {
                    $(".responses").hide()
                    $(".reset_pwd").show()
                }
                else {
                    $("#forgot_pwd_otp_invalid").show()
                }
            }
        })
    })

    $(document).on('submit', "#forgot_pwd_reset_form", function (form) {
        form.preventDefault()

        let new_pass = $("#forgot_pwd_new_pass").val()
        if (new_pass != $("#confirm_forgot_pwd_new_pass").val()) {
            $("#forgot_pwd_reset_invalid").show()
            return null
        }

        $.ajax({
            type: "POST",
            url: "./index_server.php",
            data: {
                "setNewPassword": true,
                "new_pass": new_pass,
                "role": $("#role_forgot").val()
            },
            success: function (response) {
                
                $("#forgot_pwd_reset_valid").show()
                $("#forgot_pwd_reset_invalid").hide()

                $("#forgot_pwd_reset_form").trigger('reset')
                $("#forgot_pwd_otp_form").trigger('reset')
                $("#forgot_pwd_form").trigger('reset')
            }
        })
    })
}

function callSubmitFuncs() {
    getCoords()
    addHospital()
    loginHospital()
    loginDonor()
    loginAdmin()
    moneyTransaction()
    searchForDonors()
    forgotPassword()
}