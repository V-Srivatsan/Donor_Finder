function fillHospitalProfileModal(data) {
    $('#hospital_name_update').attr('value', data['Name'])
    $('#hospital_code_update').attr('value', data['Code'])
    $('#hospital_contact_update').attr('value', data['Contact'])
    $('#hospital_email_update').attr('value', data['Email'])
    $('#hospital_address_update').val(data['Address'])
    $("#hospital_website_update").attr('value', data['Website'])

    $('.navbar-brand').append(data['Name'] + "!")
}

function changeContent(page) {
    if (page == "Dashboard") {
        $("#dashboard_field").attr('class', 'nav-item mr-2 border-bottom active')
        $("#transaction_field").attr('class', 'nav-item mr-2')
        $("#patients_field").attr('class', 'nav-item mr-2')

        $("#dashboard").show()
        $("#transactions").hide()
        $("#patients").hide()
    }

    else if (page == "Patients") {
        $("#patients_field").attr('class', 'nav-item mr-2 border-bottom active')
        $("#transaction_field").attr('class', 'nav-item mr-2')
        $("#dashboard_field").attr('class', 'nav-item mr-2')

        $("#dashboard").hide()
        $("#transactions").hide()

        $("#patients").load(window.location.href + " #patients")
        setTimeout(function () {
            document.querySelectorAll("#patients").forEach(elem => {
                elem.style.display = "block"
            })
        }, 250)
        
        $("#patients").show()
    }

    else {
        $.ajax({
            type: "POST",
            url: "hospital_server.php",
            data: {
                "getTransactionCounts": true
            },
            success: function(response) {
                var decodedRes = JSON.parse(response)
                var counts = decodedRes["counts"]
                var details = decodedRes["details"]

                $("#transaction_rows").empty()

                for (let index = 0; index < details.length; index++) {
                    const element = details[index];
                    let row = ""
                    element.forEach(i => {
                        row += "<td>"+i+"</td>"
                    });
                    $("#transaction_rows").append($("<tr>").append(row))
                }

                $("#transaction_field").attr('class', 'nav-item mr-2 border-bottom active')
                $("#dashboard_field").attr('class', 'nav-item mr-2')
                $("#patients_field").attr('class', 'nav-item mr-2')

                $("#transactions").show()
                $("#dashboard").hide()
                $("#patients").hide()

                var map_array = [counts["Blood"], counts["Kidney"], counts["Liver"], counts["Pancreas"], counts["Heart"], counts["Lung"], counts["Eye"], counts["Bone"], counts["Skin"], counts["Tendon"]]
                initGraph(map_array)
            }
        })
    }
}

function launchUpdateSpecsModal(card) {
    let bloodGroups = card.querySelector(".blood_group").innerText.split(", ")

    $("#organ_id").val(card.querySelector(".organ_id").innerText)
    $("#age_min_donor_update_specs").val(card.querySelector(".min_age").innerText)
    $("#age_max_donor_update_specs").val(card.querySelector(".max_age").innerText)
    $("#organ_update_specs").val(card.querySelector(".card-header").innerText)
    $("#honorarium_update").val(card.querySelector(".card-footer").innerText.split(" ")[1])

    Array.from(document.querySelectorAll("#donation_specs_update_form .bg-checkbox")).forEach(item => {
        item.checked = false
        if (bloodGroups.includes(item.parentElement.innerText.trim())) item.checked = true
    })

    $("#donor_update_specs_modal").modal('show')
}

function launchPayDonorModal(element) {
    let honorarium = element.parentElement.previousElementSibling.innerText.split(" ")[1]
    let donor_code = $(".donor_code")[parseInt(element.parentElement.parentElement.children[0].innerText)-1].innerText
    let organ = element.parentElement.previousElementSibling.previousElementSibling.innerText
    let id = $(".donation_id")[parseInt(element.parentElement.parentElement.children[0].innerText)-1].innerText

    $("#pay_donor_form_2").hide().trigger('reset')
    $("#pay_donor_success").hide()
    $("#pay_donor_form_1").hide().trigger('reset')
    $("#select_patient_form").show().trigger('reset')


    // Retrieve Patients which match the donor's specs

    $.ajax({
        type: "POST",
        url: "./hospital_server.php",
        data: {
            "filterPatients": true,
            "organ": organ,
            "blood_group": element.parentElement.parentElement.children[4].innerText
        },
        success: function (response) {
            let decodedRes = JSON.parse(response)

            if (decodedRes['valid']) {
                decodedRes['patients'].forEach(selectPatient => {
                    let selection = "<option value=\"" + selectPatient["name"] + "\">" + selectPatient["name"] + " (Ward: "+ selectPatient["ward"] + " ⟶ Bed: " + selectPatient["bed"] + ")</option>"
                    $("#select_patient").append(selection)
                })
            }

            else {
                $("#select_patient_form").hide()
                $("#pay_donor_form_1").show()
            }

        }
    })


    // Change the form

    $(document).on('submit', "#select_patient_form", function (form) {
        form.preventDefault()
        $("#select_patient_form").hide()
        $("#pay_donor_form_1").show()
    })




    $("#pay_donor_amount").val(honorarium)
    $("#pay_donor_modal").modal('show')

    $(document).on('submit', "#pay_donor_form_1", function (form) {
        form.preventDefault()
        $("#pay_donor_form_1").hide()
        $("#pay_donor_form_2").show()
    })

    $(document).on('submit', "#pay_donor_form_2", function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "payDonor": true,
                "honorarium": honorarium,
                "hospital": $("#hospital_name_update").val(),
                "organ": organ,
                "id": id,
                "donor": donor_code,
                "patient_name": $("#select_patient").val(),
            },
            success: function (response) {
                $("#pay_donor_form_2").hide()
                $("#pay_donor_success").show()
                $("#dashboard").load(window.location.href+" #dashboard")
            }
        })
    })
}

function updateHospitalDetails() {
    $(document).on('submit', '#hospital_update_form', function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "updateUser": true,
                "new_name": $("#hospital_name_update").val(),
                "new_contact": $("#hospital_contact_update").val(),
                "new_email": $("#hospital_email_update").val(),
                "new_website": $("#hospital_website_update").val(),
                "new_address": $("#hospital_address_update").val()
            },
            success: function (response) {
                $("#update_hospital_success").show()
            }
        })
    })
}

function updateHospitalPass() {
    $(document).on('submit', '#hospital_update_pass_form', function (form) {
        form.preventDefault()
        let resField = document.getElementById("hospital_update_pass_field")
        
        let pass = $("#hospital_new_pass_update").val()
        if (pass != $("#confirm_hospital_new_pass_update").val()) {
            resField.classList.remove("alert-success")
            resField.classList.add("alert-danger")
            $("#hospital_update_pass_message").text("The passwords do not match. Please try again!")
            $("#hospital_update_pass_field").show()
            return null;
        }

        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "updatePass": true,
                "old_pass": $("#hospital_pass_update").val(),
                "new_pass": pass,
            },
            success: function (response) {
                var res = JSON.parse(response)
                
                
                if (res['valid']) {
                    resField.classList.remove("alert-danger")
                    resField.classList.add("alert-success")
                    $('#hospital_update_pass_form').trigger('reset')
                }
                else {
                    resField.classList.remove("alert-success")
                    resField.classList.add("alert-danger")
                }
                $("#hospital_update_pass_message").text(res['message'])
                $("#hospital_update_pass_field").show()
            }
        })
    })
}

function deleteHospital() {
    let res = prompt("Are you sure you want to delete your Account? (Type 'yes' to delete)")
    if (res == "yes") {
        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "deleteHospital": true, 
            },
            success: function (response) {
                location = JSON.parse(response)["location"]
            }
        })
    }
}

function addDonationSpecs() {
    $(document).on('submit', '#donation_specs_form', function (form) {
        form.preventDefault()
        let resField = document.getElementById("donation_specs_field")

        let min_age = parseInt($("#age_min_donor_specs").val())
        let max_age = parseInt($("#age_max_donor_specs").val())
        
        if (min_age > max_age) {
            resField.classList.remove("alert-success")
            resField.classList.add("alert-danger")
            $("#donation_specs_message").text("Min Age MUST be less than or equal to Max Age. Please try again!")
            $("#donation_specs_field").show()
            return null;
        }

        let blood_groups = [];
        Array.from(document.querySelectorAll("#donation_specs_form .bg-checkbox")).forEach(item => {
            if (item.checked) blood_groups.push(item.parentElement.innerText.trim())
        })

        if (blood_groups.length == 0) {
            resField.classList.remove("alert-success")
            resField.classList.add("alert-danger")
            $("#donation_specs_message").text("Choose atleast ONE type of Blood Group. Please try again!")
            $("#donation_specs_field").show()
            return null;
        }

        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "addDonationSpecs": true,
                "bloodGroups": JSON.stringify(blood_groups),
                "min_age": min_age,
                "max_age": max_age,
                "organ": $("#organ_specs").val(),
                "honorarium": parseInt($("#honorarium").val())
            },
            success: function (response) {
                var res = JSON.parse(response)
                
                if (res['valid']) {
                    resField.classList.remove("alert-danger")
                    resField.classList.add("alert-success")
                    $("#dashboard").load(window.location.href+" #dashboard")
                    $("#donation_specs_form").trigger("reset")
                }
                else {
                    resField.classList.remove("alert-success")
                    resField.classList.add("alert-danger")
                }
                $("#donation_specs_message").text(res['message'])
                $("#donation_specs_field").show()
            }
        })
    })
}

function deleteDonationSpecs() {
    $.ajax({
        type: "POST",
        url: "./hospital_server.php",
        data: {
            "deleteDonationSpecs": true,
            "organ_id": $("#organ_id").val()
        },
        success: function (response) {
            $("#dashboard").load(window.location.href+" #dashboard")
            feather.replace()
            $("#donor_update_specs_modal").modal('hide')
        }
    })
}

function updateDonationSpecs() {
    $(document).on('submit', '#donation_specs_update_form', function (form) {
        form.preventDefault()
        let resField = document.getElementById("donation_specs_update_field")

        let min_age = parseInt($("#age_min_donor_update_specs").val())
        let max_age = parseInt($("#age_max_donor_update_specs").val())
        
        if (min_age > max_age) {
            resField.classList.remove("alert-success")
            resField.classList.add("alert-danger")
            $("#donation_specs_update_message").text("Min Age MUST be less than or equal to Max Age. Please try again!")
            $("#donation_specs_update_field").show()
            return null;
        }

        let blood_groups = [];
        Array.from(document.querySelectorAll("#donation_specs_update_form .bg-checkbox")).forEach(item => {
            if (item.checked) blood_groups.push(item.parentElement.innerText.trim())
        })

        if (blood_groups.length == 0) {
            resField.classList.remove("alert-success")
            resField.classList.add("alert-danger")
            $("#donation_specs_update_message").text("Choose atleast ONE type of Blood Group. Please try again!")
            $("#donation_specs_update_field").show()
            return null;
        }

        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "updateDonationSpecs": true,
                "organ_id": $("#organ_id").val(),
                "blood_groups": JSON.stringify(blood_groups),
                "min_age": min_age,
                "max_age": max_age,
                "organ": $("#organ_update_specs").val(),
                "honorarium": parseInt($("#honorarium_update").val())
            },
            success: function (response) {
                var res = JSON.parse(response)
                
                if (res['valid']) {
                    resField.classList.remove("alert-danger")
                    resField.classList.add("alert-success")
                    $("#dashboard").load(window.location.href+" #dashboard")
                    feather.replace()
                    $("#organ_id").val(res['organ_id'])
                }
                else {
                    resField.classList.remove("alert-success")
                    resField.classList.add("alert-danger")
                }
                $("#donation_specs_update_message").text(res['message'])
                $("#donation_specs_update_field").show()
            }
        })
    })
}

function registerPatient() {
    $(document).on('submit', "#register_patient_form", function (form) {
        form.preventDefault()

        $.ajax({
            type: "POST",
            url: "./hospital_server.php",
            data: {
                "registerPatient": true,
                "name": $("#patient_name_register").val(),
                "ward": $("#patient_ward_register").val(),
                "bed": $("#patient_bed_register").val(),
                "blood_group": $("#patient_blood_group_register").val(),
                "organ": $("#patient_organ_register").val()
            },
            success: function (response) {
                let decodedRes = JSON.parse(response)

                if (decodedRes['valid']) {
                    $("#register_patient_success").show()
                    $("#register_patient_failure").hide()
                    $("#register_patient_form").trigger('reset')

                    $("#patients").load(window.location.href + " #patients")
                    setTimeout(function () {
                        document.querySelectorAll("#patients").forEach(elem => {
                            elem.style.display = "block"
                        })
                    }, 250)
                }

                else {
                    $("#register_patient_success").hide()
                    $("#register_patient_failure").show()
                }
            }
        })
    })
}

function deletePatient(table_row) {
    let ward = table_row.children[1].innerText
    let bed = table_row.children[2].innerText

    $.ajax({
        type: "POST",
        url: "./hospital_server.php",
        data: {
            "deletePatient": true,
            "ward": ward,
            "bed": bed
        },
        success: function (response) {
            $("#patients").load(window.location.href + " #patients")
            setTimeout(function () {
                document.querySelectorAll("#patients").forEach(elem => {
                    elem.style.display = "block"
                })
            }, 250)
        }
    })
}

function callSubmitFuncs() {
    updateHospitalDetails()
    updateHospitalPass()
    addDonationSpecs()
    updateDonationSpecs()
    registerPatient()
}