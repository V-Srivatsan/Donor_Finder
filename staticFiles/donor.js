let API_KEY = ""

function fillDonorProfileModal(data) {
    $('#donor_name_update').attr('value', data['Name'])
    $('#donor_code_update').attr('value', data['Username'])
    $('#donor_contact_update').attr('value', data['Contact'])
    $('#donor_email_update').attr('value', data['Email'])
    $('#donor_address_update').val(data['Address'])
    $("#donor_locality_update").val(data["Locality"])

    $('.navbar-brand').append(data['Name'] + "!")
}

function updateDonorDetails(lat, long) {

    $.ajax({
        type: "POST",
        url: "./donor_server.php",
        data: {
            "updateUser": true,
            "new_name": $("#donor_name_update").val(),
            "new_contact": $("#donor_contact_update").val(),
            "new_email": $("#donor_email_update").val(),
            "new_address": $("#donor_address_update").val(),
            "new_locality": $("#donor_locality_update").val(),
            "new_lat": lat,
            "new_long": long
        },
        success: function (response) {
            $("#update_donor_success").show()
        }
    })

}

function getCoords() {
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

function updateDonorPass() {
    $(document).on('submit', '#donor_update_pass_form', function (form) {
        form.preventDefault()
        let resField = document.getElementById("donor_update_pass_field")

        let pass = $("#donor_new_pass_update").val()
        if (pass != $("#confirm_donor_new_pass_update").val()) {
            resField.classList.remove("alert-success")
            resField.classList.add("alert-danger")
            $("#donor_update_pass_message").text("The passwords do not match. Please try again!")
            $("#donor_update_pass_field").show()
            return null;
        }

        $.ajax({
            type: "POST",
            url: "./donor_server.php",
            data: {
                "updatePass": true,
                "old_pass": $("#donor_pass_update").val(),
                "new_pass": pass,
            },
            success: function (response) {
                var res = JSON.parse(response)

                if (res['valid']) {
                    resField.classList.remove("alert-danger")
                    resField.classList.add("alert-success")
                    $('#donor_update_pass_form').trigger('reset')
                }
                else {
                    resField.classList.remove("alert-success")
                    resField.classList.add("alert-danger")
                }
                $("#donor_update_pass_message").text(res['message'])
                $("#donor_update_pass_field").show()
            }
        })
    })
}

function deleteDonor() {
    let res = prompt("Are you sure you want to delete your Account? (Type 'yes' to delete)")
    if (res == "yes") {
        $.ajax({
            type: "POST",
            url: "./donor_server.php",
            data: {
                "deleteDonor": true,
            },
            success: function (response) {
                location = JSON.parse(response)["location"]
            }
        })
    }
}

function addDonationSpecs() {
    $(document).on('submit', '#donate_form', function (form) {
        form.preventDefault()

        let checkedOrgan = null
        document.getElementsByName("organ").forEach(organ => {
            if (organ.checked) {
                checkedOrgan = organ.value
            }
        });

        $.ajax({
            type: "POST",
            url: "./donor_server.php",
            data: {
                "addDonationSpecsDonor": true,
                "organ": checkedOrgan,
            },
            success: function (response) {
                var res = JSON.parse(response)

                if (res['valid']) {
                    $("#dashboard").load(window.location.href + " #dashboard")
                    $("#donate_form").trigger("reset")
                }
                $("#donation_field").show()
            }
        })
    })
}

function deleteDonationSpecs(element)  {
    $.ajax({
        type: "POST",
        url: "./donor_server.php",
        data: {
            "deleteDonationSpecsDonor": true,
            id: element.querySelector(".donor_donation_id").innerText
        },
        success: function (response) {
            $("#dashboard").load(window.location.href + " #dashboard")
        }
    })
}

function selectHospitalForDonation(count) {
    let row_num = count-1
    let id = $(".donation_id")[row_num]

    if (!id) {
        alert("Invalid Row Number!")
        return null
    }

    $.ajax({
        type: "POST",
        url: "./donor_server.php",
        data: {
            "applyHospitalForDonation": true,
            "id": id.innerText
        },
        success: function (response) {
            let decodedRes = JSON.parse(response)

            if (decodedRes['valid']) {
                alert("Applied for donation successfully! The Hospital will soon contact you!")
                $("#dashboard").load(window.location.href + " #dashboard")
                $("#donate_form").trigger("reset")
            }

            else {
                alert("You have already applied for this hospital :)")
            }
        }
    })
}

function callSubmitFuncs() {
    getCoords()
    updateDonorPass()
    addDonationSpecs()
}