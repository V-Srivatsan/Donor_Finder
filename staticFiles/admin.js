let API_KEY = ""
let CURRENT_CONTENT = "#donors"

function switchContent(replace_id) {
    $(CURRENT_CONTENT).fadeOut(225, function () {
        $(replace_id).fadeIn(275)
        CURRENT_CONTENT = replace_id
    })
}

function filter(search_value, search_component_id, search_param_class) {
    let search_component = document.getElementById(search_component_id)
    let elems = search_component.getElementsByClassName(search_param_class)
    
    for (let idx = 0; idx < elems.length; idx++) {
        let elem = elems[idx]
        if (!elem.innerText.toUpperCase().includes(search_value)) {
            elem.parentElement.style.display = "none"
        }
        else {
            elem.parentElement.style.display = ""
        }
    }
}


// CRUD



// Hospitals

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
                    $(".info-hospitals").load(window.location.href + " .info-hospitals")
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

function launchHospitalProfileModal(row_num) {
    details = document.getElementById("hospitals").getElementsByTagName("tr")[row_num].children
    $('#hospital_code_update').attr('value', details[1].innerText)
    $('#hospital_name_update').attr('value', details[2].innerText)
    $('#hospital_email_update').attr('value', details[3].innerText)
    $('#hospital_contact_update').attr('value', details[4].innerText)
    $('#hospital_address_update').val(details[5].innerText)
    $("#hospital_website_update").attr('value', details[6].innerText)

    $("#hospital_profile_modal").modal('show')
}

function updateHospitalDetails() {
    $(document).on('submit', '#hospital_update_form', function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./admin_server.php",
            data: {
                "updateHospital": true,
                "new_name": $("#hospital_name_update").val(),
                "new_contact": $("#hospital_contact_update").val(),
                "new_email": $("#hospital_email_update").val(),
                "new_website": $("#hospital_website_update").val(),
                "new_address": $("#hospital_address_update").val(),
                "code": $("#hospital_code_update").val()
            },
            success: function (response) {
                $(".info-hospitals").load(window.location.href + " .info-hospitals")
                $("#update_hospital_success").show()
            }
        })
    })
}

function deleteHospital(row_num) {
    let code = document.getElementById("hospitals").getElementsByTagName("tr")[row_num].children[1].innerText
    $.ajax({
        type: "POST",
        url: "./admin_server.php",
        data: {
            "deleteHospital": true,
            "code": code
        },
        success: function (response) {
            $(".info-hospitals").load(window.location.href + " .info-hospitals")
        }
    })
}

function deleteDonationSpecs(button) {
    $.ajax({
        type: "POST",
        url: "./admin_server.php",
        data: {
            "deleteDonationSpecs": true,
            "id": button.getAttribute("data-id")
        },
        success: function (response) {
            $(".info-donationspecs").load(window.location.href + " .info-donationspecs")
        }
    })
}



// Donors

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
                $(".info-donors").load(window.location.href + " .info-donors")
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

function updateDonorDetails(lat, long) {

    $.ajax({
        type: "POST",
        url: "./admin_server.php",
        data: {
            "updateDonor": true,
            "new_name": $("#donor_name_update").val(),
            "new_contact": $("#donor_contact_update").val(),
            "new_email": $("#donor_email_update").val(),
            "new_address": $("#donor_address_update").val(),
            "new_locality": $("#donor_locality_update").val(),
            "new_lat": lat,
            "new_long": long,
            "username": $("#donor_code_update").val()
        },
        success: function (response) {
            $(".info-donors").load(window.location.href + " .info-donors")
            $("#update_donor_success").show()
        }
    })

}

function updateCoords() {
    $(document).on('submit', "#donor_update_form", function (form) {
        form.preventDefault()

        let lat = ""
        let long = ""
        $.ajax({
            type: "GET",
            url: "https://dev.virtualearth.net/REST/v1/Locations?query=" + $("#donor_address_update").val() + "&key="+API_KEY,
            success: function (response) {
                let coords = response["resourceSets"][0]["resources"][0]["geocodePoints"][0]["coordinates"]
                lat = coords[0]
                long = coords[1]
                updateDonorDetails(lat, long)
            }
        })
    })
}

function deleteDonor(row_num) {
    let username = document.getElementById("donors").getElementsByTagName("tr")[row_num].children[1].innerText
    $.ajax({
        type: "POST",
        url: "./admin_server.php",
        data: {
            "deleteDonor": true,
            "username": username
        },
        success: function (response) {
            $(".info-donors").load(window.location.href + " .info-donors")
        }
    })
}

function launchDonorProfileModal(row_num) {
    details = document.getElementById("donors").getElementsByTagName("tr")[row_num].children
    $('#donor_code_update').attr('value', details[1].innerText)
    $('#donor_name_update').attr('value', details[2].innerText)
    $('#donor_email_update').attr('value', details[3].innerText)
    $('#donor_contact_update').attr('value', details[4].innerText)
    $('#donor_address_update').val(details[5].innerText)
    $("#donor_locality_update").val(details[6].innerText)

    $("#donor_profile_modal").modal('show')
}


// Patients 

function deletePatient(row_num) {
    let details = document.getElementById("patients").getElementsByTagName("tr")[row_num].children

    $.ajax({
        type: "POST",
        url: "./admin_server.php",
        data: {
            "deletePatient": true,
            "ward": details[2].innerText,
            "bed":  details[3].innerText,
            "hospital": details[5].innerText
        },
        success: function (response) {
            $(".info-patients").load(window.location.href + " .info-patients")
        }
    })
}


addHospital()
updateHospitalDetails()

getCoords()
updateCoords()