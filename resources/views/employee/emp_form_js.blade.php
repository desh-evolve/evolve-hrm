<script>

let dropdownData = [];
let filesArray = []; // Array to hold files selected before the reset


$(document).ready(function(){
    resetForm();
    getNextEmployeeNumber();
    getDropdownData();

    const urlParams = new URLSearchParams(window.location.search);
    let emp_id = urlParams.get('emp_id');

    if (emp_id) {
        getEmployeeDetails(emp_id); // Fetch employee details for editing
        modifyFormForUpdate();
    } else {
        $("#employee-details-title").text("Add Employee Details"); // change title when adding new
        $('#user_id').val('');
    }
});



// Function to modify form when updating an employee
async function modifyFormForUpdate() {

    // Change title when editing
    $("#employee-details-title").text("Edit Employee Details");

    // Disable user_no field
    $('#user_no').prop('disabled', true);

    // Hide Documents Tab and Section
    $('#steparrow-document-info-tab').closest('li').remove();
    $('#steparrow-document-info').remove();

    // Hide Password and Confirm Password Fields
    $('#password').closest('.mb-3').hide();
    $('#confirm_password').closest('.mb-3').hide();

    // Adjust Employee Identification & Contact Information Tabs
    $(".step-arrow-nav .nav").addClass("nav-justified");

    // Replace "Go to Documents" Button with Submit Button
    $('#second-form-button').replaceWith(`
        <button type="button" class="btn btn-success btn-label right ms-auto emp_form_submit">
            <i class="ri-check-line label-icon align-middle fs-16 ms-2"></i> Submit
        </button>
    `);
}



async function getDropdownData() {
    showPreloader();

    try {
        dropdownData = await commonFetchData('/employee/dropdown');

        // Populate branch dropdown
        let branchList = (dropdownData?.branches || [])
            .map(branch => `<option value="${branch.id}">${branch.branch_name} (${branch.short_name})</option>`)
            .join('');
        $('#branch_id').html('<option value="">Select a branch</option>' + branchList);

        // Populate department dropdown
        $('#department_id').html('<option value="">Select a branch first</option>');

        // Populate user group dropdown
        let empGroupList = (dropdownData?.user_groups || [])
            .map(empGroup => `<option value="${empGroup.id}">${empGroup.emp_group_name}</option>`)
            .join('');
        $('#user_group_id').html('<option value="">Select an user group</option>' + empGroupList);

        // Populate user designation dropdown
        let designationList = (dropdownData?.user_designations || [])
            .map(designation => `<option value="${designation.id}">${designation.emp_designation_name}</option>`)
            .join('');
        $('#designation_id').html('<option value="">Select a designation</option>' + designationList);

        // Populate policy group dropdown
        let policyGroupList = (dropdownData?.policy_groups || [])
            .map(policyGroup => `<option value="${policyGroup.id}">${policyGroup.name}</option>`)
            .join('');
        $('#policy_group_id').html('<option value="">Select a policy group</option>' + policyGroupList);

        // Populate user status dropdown
        let empStatusList = (dropdownData?.user_status || [])
            .map(empStatus => `<option value="${empStatus.id}">${empStatus.user_status_name} ${empStatus.description ? ' - ' + empStatus.description : ''}</option>`)
            .join('');
        $('#user_status').html('<option value="">Select an user status</option>' + empStatusList);

        // Populate currency dropdown
        let currencyList = (dropdownData?.currencies || [])
            .map(currency => `<option value="${currency.id}">${currency.currency_name} - (${currency.iso_code})</option>`)
            .join('');
        $('#currency_id').html('<option value="">Select a currency</option>' + currencyList);

        // Populate pay period schedule dropdown
        let payPeriodList = (dropdownData?.pay_period || [])
            .map(payPeriod => `<option value="${payPeriod.id}">${payPeriod.name}</option>`)
            .join('');
        $('#pay_period_schedule_id').html('<option value="">Select a pay period schedule</option>' + payPeriodList);

        // Populate permission group dropdown
        let permGroupList = (dropdownData?.roles || [])
            .map(permGroup => `<option value="${permGroup.name}">${permGroup.name}</option>`)
            .join('');
        $('#permission_group_id').html('<option value="">Select a permission group</option>' + permGroupList);

        // Populate basis of employment
        let empTypeList = (dropdownData?.employment_types || [])
            .map(empType => `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="employment_type" id="employment_type_${empType.id}" value="${empType.id}" required>
                    <label class="form-check-label" for="employment_type_${empType.id}">${empType.name}</label>
                </div>
            `)
            .join('');
        $('#employment_type').html(empTypeList);

        // Populate permission group dropdown
        let religionList = (dropdownData?.religion || [])
            .map(rel => `<option value="${rel.id}">${rel.name}</option>`)
            .join('');
        $('#religion_id').html('<option value="">Select a religion</option>' + religionList);

        // Populate country dropdown
        let countryList = (dropdownData?.countries || [])
            .map(country => `<option value="${country.id}">${country.country_name} (${country.country_code})</option>`)
            .join('');
        $('#country_id').html('<option value="">Select a country</option>' + countryList);

        // Default values for province and city
        $('#province_id').html('<option value="">Select a country first</option>');
        $('#city_id').html('<option value="">Select a country first</option>');

        // Populate doc type dropdown
        let docTypeList = (dropdownData?.doc_types || [])
            .map(doc_type => `<option value="${doc_type.id}">${doc_type.name}</option>`)
            .join('');
        $('#doc_type_id').html('<option value="">Select a doc type</option>' + docTypeList);

    } catch (error) {
        console.error('Error fetching dropdown data:', error);
    } finally {
        hidePreloader();
    }
}

async function getNextEmployeeNumber(){
    try {
        let nextNumber = await commonFetchData('/employee/next_employee_id');
        $('#user_no').attr('placeholder', `Next Employee Number = ${nextNumber}`)
        //$('#punch_machine_user_id').attr('placeholder', `Next ID = ${nextNumber}`)
        //console.log('nextNumber', nextNumber)
    } catch (error) {
        console.error('error at emp_form_js -> getNextEmployeeNumber', error);
    }
}


//=========================================================================================================
// update employee record
//=========================================================================================================

async function getEmployeeDetails() {
    try {
        // Get emp_id from URL
        const urlParams = new URLSearchParams(window.location.search);
        let emp_id = urlParams.get('emp_id');

        if (!emp_id) {
            console.error('Employee ID not found in URL.');
            return;
        }

        // Fetch employee details from API
        let emp_data = await commonFetchData(`/employee/single_record/${emp_id}`);

        if (!emp_data || emp_data.length === 0) {
            console.warn('No details found for this employee.');
            return;
        }

        emp_data = emp_data[0];
        console.log('Fetched Employee Data:', emp_data);

        // Branch and Department Details
        let branchDepartment = emp_data.branch_department_details?.[0] || {};
        let emailDetail = emp_data.email_details?.[0] || {};
        let permissionGroupDetail = emp_data.role_details?.[0] || {};
        let payPeriodScheduleDetail = emp_data.pay_period_schedule_details?.[0] || {};

        // Populate form fields
        $('#emp_id').val(emp_data.emp_id || '');
        $('#user_no').val(emp_data.id || '');
        $('#punch_machine_user_id').val(emp_data.punch_machine_user_id || '');

        $('#branch_id').val(branchDepartment.branch_id || '');
        await loadDepartments(branchDepartment.branch_id);
        $('#department_id').val(branchDepartment.department_id || '');

        $('#user_group_id').val(emp_data.user_group_id || '');
        $('#designation_id').val(emp_data.designation_id || '');
        $('#policy_group_id').val(emp_data.policy_group_id || '');
        $('#user_status').val(emp_data.user_status || '');
        $('#currency_id').val(emp_data.currency_id || '');
        $('#pay_period_schedule_id').val(payPeriodScheduleDetail.pay_period_schedule_id || '');
        $('#appointment_date').val(emp_data.appointment_date || '');
        $('#appointment_note').val(emp_data.appointment_note || '');
        $('#terminated_date').val(emp_data.terminated_date || '');
        $('#terminated_note').val(emp_data.terminated_note || '');
        $('#confirmed_date').val(emp_data.confirmed_date || '');
        $('#retirement_date').val(emp_data.retirement_date || '');
        $('#bond_period').val(emp_data.bond_period || '');
        $('#permission_group_id').val(permissionGroupDetail.name || '');

        // Contact Information
        $('#email').val(emailDetail.email || '');
        $('#title').val(emp_data.title || '');
        $('#name_with_initials').val(emp_data.name_with_initials || '');
        $('#first_name').val(emp_data.first_name || '');
        $('#last_name').val(emp_data.last_name || '');
        $('#full_name').val(emp_data.full_name || '');
        $('#dob').val(emp_data.dob || '');
        $('#nic').val(emp_data.nic || '');
        $('#gender').val(emp_data.gender || '');
        $('#religion_id').val(emp_data.religion || '');
        $('#marital_status').val(emp_data.marital_status || '');
        $('#personal_email').val(emp_data.personal_email || '');
        $('#contact_1').val(emp_data.contact_1 || '');
        $('#contact_2').val(emp_data.contact_2 || '');
        $('#address_1').val(emp_data.address_1 || '');
        $('#address_2').val(emp_data.address_2 || '');
        $('#address_3').val(emp_data.address_3 || '');
        $('#postal_code').val(emp_data.postal_code || '');
        $('#country_id').val(emp_data.country_id || '');

        // Populate Province and City based on Country
        await loadProvinces(emp_data.country_id);
        $('#province_id').val(emp_data.province_id || '');

        await loadCities(emp_data.province_id);
        $('#city_id').val(emp_data.city_id || '');

        // Work Information
        $('#work_email').val(emp_data.work_email || '');
        $('#work_contact').val(emp_data.work_contact || '');
        $('#immediate_contact_person').val(emp_data.immediate_contact_person || '');
        $('#immediate_contact_no').val(emp_data.immediate_contact_no || '');
        $('#home_contact').val(emp_data.home_contact || '');
        $('#epf_reg_no').val(emp_data.epf_reg_no || '');
        $('#resigned_date').val(emp_data.resigned_date || '');

        // Set Employment Type (Radio Button)
        $("input[name='employment_type']").each(function () {
            if ($(this).val() == emp_data.employment_type_id) {
                $(this).prop("checked", true);
            }
        });

        // Show or Hide "Months" Field Based on Employment Type
        const selectedEmpType = emp_data.employment_type_id;
        const isDurationType = dropdownData?.employment_types?.some(
            empType => empType.id == selectedEmpType && empType.is_duration === 1
        );

        if (isDurationType) {
            $('#month-selection').show();
            $('#employment_time').val(emp_data.employment_time || '');
        } else {
            $('#month-selection').hide();
            $('#employment_time').val('');
        }

        // Load Document Details
        displayDocuments(emp_data?.document_details || []);

    } catch (error) {
        console.error('Error fetching employee details:', error);
        $('#error-msg').text('Error fetching employee details. Please try again later.');
    }
}


function displayDocuments(documents) {
    $('#document_tbody').html(''); // Clear previous data

    if (!documents || documents.length === 0) {
        $('#document_tbody').html(`
            <tr class="no-doc-row">
                <td colspan="5" class="text-center">No Documents Found</td>
            </tr>
        `);
        return;
    }

    // Loop through documents and display
    documents.forEach((doc, index) => {
        filesArray.push(doc); // Store for later deletion

        // Use the /storage/ URL path since we created a symbolic link
        let downloadUrl = `/storage/uploads/employee/documents/${doc.file}`;

        $('#document_tbody').append(`
            <tr data-index="${index}">
                <td>${index + 1}</td>
                <td>${doc.doc_type_name || 'N/A'}</td>
                <td>${doc.title || 'Untitled'}</td>
                <td>${doc.file || 'No file'}</td>
                <td>
                    <a href="${downloadUrl}" class="btn btn-info btn-sm click_download_doc" title="Download Document">
                        <i class="ri-download-2-line"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-sm click_delete_doc" data-index="${index}" title="Delete Document">
                        <i class="ri-delete-bin-fill"></i>
                    </button>
                </td>
            </tr>
        `);
    });
}

//======================================================================================================
// DELETE DOCUMENTS WHEN UPDATING
//======================================================================================================

    $(document).on('click', '.click_delete_doc', function() {
        const $row = $(this).closest('tr');
        const index = $row.data('index'); // Get index from row attribute
        const id = filesArray[index]?.id; // Get document ID from stored filesArray

            deleteItem(id, $row);

    });

    async function deleteItem(id, $row) {
        const url ='/employee/document/delete';
        const title ='Employee Document';

        try {
            const res = await commonDeleteFunction(id, url, title, $row);
            if (res) {
                // Remove document from array
                filesArray.splice(index, 1);
                $row.remove(); // Remove row from UI

                // Re-index table rows
                $('#document_tbody tr').each((i, tr) => {
                    $(tr).attr('data-index', i);
                    $(tr).find('td:first').text(i + 1);
                });

                // If no documents remain, display a message
                if (filesArray.length === 0) {
                    $('#document_tbody').html(`
                        <tr class="no-doc-row">
                            <td colspan="5" class="text-center">No Documents Found</td>
                        </tr>
                    `);
                }
            }
        } catch (error) {
            console.error('Error deleting document:', error);
        }
    }

//=========================================================================================================

$(document).on('change', '#country_id', function () {
    let country_id = $(this).val();
    loadProvinces(country_id);
});

$(document).on('change', '#province_id', function () {
    let province_id = $(this).val();
    loadCities(province_id);
});

async function loadProvinces(countryId) {
    // Check if a country is selected
    if (!countryId) {
        $('#province_id').html('<option value="">Select a country first</option>');
        $('#city_id').html('<option value="">Select a province first</option>');
        return;
    }

    // Filter provinces by country_id
    const provinceList = (dropdownData?.provinces || [])
        .filter(province => province.country_id == countryId)
        .map(province => `<option value="${province.id}">${province.province_name}</option>`)
        .join('');

    // Populate the province dropdown
    $('#province_id').html('<option value="">Select a province</option>' + provinceList);
    $('#city_id').html('<option value="">Select a province first</option>');
}

async function loadCities(provinceId) {
    // Check if a province is selected
    if (!provinceId) {
        $('#city_id').html('<option value="">Select a province first</option>');
        return;
    }

    // Filter cities by province_id
    const cityList = (dropdownData?.cities || [])
        .filter(city => city.province_id == provinceId)
        .map(city => `<option value="${city.id}">${city.city_name}</option>`)
        .join('');

    // Populate the city dropdown
    $('#city_id').html('<option value="">Select a city</option>' + cityList);
}

//=========================================================================================================
// Added document
//=========================================================================================================

$(document).on('click', '.add_doc_to_list', function () {
    const doc_type_id = $('#doc_type_id').val();
    const doc_type_name = $('#doc_type_id option:selected').text();
    const doc_title = $('#doc_title').val().trim();
    const files = $('#doc_file')[0].files; // Get selected files

    let missingFields = [];

    if (!doc_type_id) missingFields.push('Document Type');
    if (!doc_title) missingFields.push('Document Title');
    if (files.length === 0) missingFields.push('File');

    if (missingFields.length > 0) {
        $('#document-error-msg').html(`
            <div class="alert alert-danger alert-dismissible">
                <strong>Error!</strong> Please fill in the following fields: <strong>${missingFields.join(', ')}.</strong>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        return;
    } else {
        $('#document-error-msg').html('');
    }

    // Loop through each selected file
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Add to filesArray
        filesArray.push({
            doc_type_id: doc_type_id,
            doc_title: doc_title,
            file: file,
            doc_type_name: doc_type_name,
        });

        // Extract the file name
        const fileName = file.name;

        // Remove the "Not any Document" row if it exists
        $('#document_tbody .no-doc-row').remove();


        // Add file to table
        $('#document_tbody').append(`
            <tr data-index="${filesArray.length - 1}">
                <td>${filesArray.length}</td>
                <td>${doc_type_name}</td>
                <td>${doc_title}</td>
                <td>${fileName}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm click_remove_document" title="Remove Document">
                        Remove
                    </button>
                </td>
            </tr>
        `);
    }

    // Reset file input & fields
    $('#doc_type_id').val('');
    $('#doc_title').val('');
    $('#doc_file').val('');
});

//===================================================================================================================
//Added documents remove & download
//===================================================================================================================

// remove document
$(document).on('click', '.click_remove_document', function () {
    const row = $(this).closest('tr');
    const index = row.data('index');

    // Remove document from array
    filesArray.splice(index, 1);
    row.remove();

    // Re-index table
    $('#document_tbody tr').each((i, tr) => {
        $(tr).attr('data-index', i);
        $(tr).find('td:first').text(i + 1);
    });

    // Show "Not any Document" row if empty
    if (filesArray.length === 0) {
        $('#document_tbody').html(`
            <tr class="no-doc-row">
                <td colspan="5" class="text-center">Not any Document</td>
            </tr>
        `);
    }
});


//download document
$(document).on('click', '.click_download_doc', function (e) {
    e.preventDefault();

    let fileName = $(this).data('file');
    if (!fileName) {
        alert('No file found.');
        return;
    }

    let downloadUrl = `/employee/document/download/${fileName}`;
    window.open(downloadUrl, '_blank');
});

//=========================================================================================================

// show/hide employment time according to type
$(document).on('change', 'input[name="employment_type"]', function() {
    // Get the selected value
    const selectedValue = $(this).val();

    // Check if the selected employment type has is_duration === 1
    const checkIsDuration = (dropdownData?.employment_types || [])
        .some(empType => empType.id == selectedValue && empType.is_duration === 1);

    // Show month input only for Contract, Training, and Permanent (With Probation)
    if (checkIsDuration) {
        $('#month-selection').show();
        $('#employment_time').prop('required', true); // Make month input required
    } else {
        $('#month-selection').hide();
        $('#employment_time').prop('required', false); // Remove required attribute
        $('#employment_time').val(''); // Clear month input
    }
});


//=========================================================================================================
// change & validate department according to branch
//=========================================================================================================

// $(document).on('change', '#branch_id', function () {
//     let branchId = $(this).val();

//     if (!branchId) {
//         // If no branch is selected, reset and display error for both branch and department
//         $('#department_id').html('<option value="">Select a branch first</option>');
//         $('#department_id').addClass('is-invalid');
//         $('#department_id').siblings('.invalid-feedback').text('This field is required');

//         $('#branch_id').addClass('is-invalid');
//         $('#branch_id').siblings('.invalid-feedback').text('This field is required');
//     } else {
//         // If a branch is selected, remove branch error and populate department dropdown
//         $('#branch_id').removeClass('is-invalid');
//         $('#branch_id').siblings('.invalid-feedback').text('');

//         let departmentList = (dropdownData?.departments || [])
//             .filter(department => department.branch_departments.some(br_dep => br_dep.branch_id == branchId))
//             .map(department => `<option value="${department.id}">${department.department_name}</option>`)
//             .join('');

//         $('#department_id').html('<option value="">Select a department</option>' + departmentList);

//     }
// });

async function loadDepartments(branchId) {
    return new Promise((resolve) => {
        if (!branchId) {
            $('#department_id').html('<option value="">Select a branch first</option>');
            $('#department_id').addClass('is-invalid');
            $('#department_id').siblings('.invalid-feedback').text('This field is required');
            return resolve(); // Ensure function completes
        }

        // Filter departments based on selected branch
        const departmentList = (dropdownData?.departments || [])
            .filter(department => department.branch_departments.some(br_dep => br_dep.branch_id == branchId))
            .map(department => `<option value="${department.id}">${department.department_name}</option>`)
            .join('');

        // Populate department dropdown
        $('#department_id').html('<option value="">Select a department</option>' + departmentList);
        $('#department_id').removeClass('is-invalid');
        $('#department_id').siblings('.invalid-feedback').text('');

        resolve(); // Mark the function as completed
    });
}


$(document).on('change', '#branch_id', async function () {
    let branchId = $(this).val();
    await loadDepartments(branchId);
});

//=========================================================================================================
// validation part 01 in go to contact info page
//=========================================================================================================


$(document).on('click', '#first-form-button', function () {
    let allFieldsValid = true;
    const urlParams = new URLSearchParams(window.location.search);
    let emp_id = urlParams.get('emp_id');
    const isUpdating = Boolean(emp_id);

    // Define required field IDs
    const requiredFields = [
        'user_no',
        'branch_id',
        'department_id',
        'user_group_id',
        'designation_id',
        'policy_group_id',
        'user_status',
        'currency_id',
        'pay_period_schedule_id',
        'appointment_date',
        'permission_group_id',
        'email',
        'bond_period',
    ];

    if (!isUpdating) {
        requiredFields.push('password', 'confirm_password');
    }

    const employmentTypeId = 'employment_type';

    // Iterate through required fields
    requiredFields.forEach(fieldId => {
        const field = $(`#${fieldId}`);
        const errorMessage = field.siblings('.invalid-feedback');

        if (!field.val()?.trim()) {
            allFieldsValid = false;
            errorMessage.text('This field is required');
            field.addClass('is-invalid');
        } else {
            errorMessage.text('');
            field.removeClass('is-invalid');
        }
    });

    // Validate employment_type separately
    const empTypeContainer = $(`#${employmentTypeId}`);
    const selectedEmpType = empTypeContainer.find("input[name='employment_type']:checked").val();
    const empTypeErrorMessage = empTypeContainer.siblings('.invalid-feedback');


    if (!selectedEmpType) {
        allFieldsValid = false;
        empTypeErrorMessage.text('This field is required');
        empTypeContainer.addClass('is-invalid');
        empTypeContainer.find("input[name='employment_type']").addClass('is-invalid');
    } else {
        empTypeErrorMessage.text('');
        empTypeContainer.removeClass('is-invalid');
        empTypeContainer.find("input[name='employment_type']").removeClass('is-invalid');
    }



     // Validate "Duration Months" only if required
     const durationRequiredTypes = ["1", "2", "3"]; // Replace with actual IDs for Contract, Training, Permanent (With Probation)
    if (durationRequiredTypes.includes(selectedEmpType)) {
        const monthsField = $('#employment_time');
        const monthsErrorMessage = monthsField.siblings('.invalid-feedback');

        if (!monthsField.val()?.trim()) {
            allFieldsValid = false;
            monthsErrorMessage.text('This field is required');
            monthsField.addClass('is-invalid');
        } else {
            monthsErrorMessage.text('');
            monthsField.removeClass('is-invalid');
        }
    }


    // Navigate to the next tab if all fields are valid
    if (allFieldsValid) {
        $('.error-msgs').html('');
        $('#steparrow-contact-info-tab').tab('show');
    } else {
        $('.error-msgs').html(`
            <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow" role="alert">
                <i class="ri-error-warning-line label-icon"></i>
                <strong>Error!</strong> Please fill out all required fields before proceeding.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    }
});

// Real-time validation for individual fields
$(document).on('input change', '[required], select', function () {
    const field = $(this);
    const errorMessage = field.siblings('.invalid-feedback');

    if (field.val()?.trim()) {
        errorMessage.text('');
        field.removeClass('is-invalid');
        $('.error-msgs').html('');
    } else {
        errorMessage.text('This field is required');
        field.addClass('is-invalid');
        $('.error-msgs').html(`
            <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow" role="alert">
                <i class="ri-error-warning-line label-icon"></i>
                <strong>Error!</strong> Please fill out all required fields before proceeding.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    }
});


// Real-time validation for employment_type
$(document).on('change', "input[name='employment_type']", function () {
    const empTypeContainer = $('#employment_type');
    const empTypeErrorMessage = empTypeContainer.siblings('.invalid-feedback');

    if ($(this).is(':checked')) {
        empTypeErrorMessage.text('');
        empTypeContainer.removeClass('is-invalid');
        empTypeContainer.find("input[name='employment_type']").removeClass('is-invalid');
    }
});


//=========================================================================================================
// check whether password and confirm password matches && email validation
//=========================================================================================================

// email validation
$(document).on('keyup', '#email, #personal_email, #work_email', function () {
    const fieldId = $(this).attr('id'); // Get the id of the field being validated
    const email = $(this).val()?.trim();
    const emailPattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    // Determine the error message
    let errorContainer = fieldId === 'email' ? '.error-msgs' : '.second-part-error-msgs';

    // Clear any existing error messages
    $(errorContainer).html('');

    if (!emailPattern.test(email)) {
        if (fieldId === 'email') {
            $('#first-form-button').prop('disabled', true); // Disable the first form button
        } else {
            $('#second-form-button').prop('disabled', true); // Disable the second form button
        }

        $(errorContainer).append(`
            <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow" role="alert">
                <i class="ri-error-warning-line label-icon"></i>
                <strong>Error!</strong> - Entered ${fieldId === 'email' ? 'Email' : (fieldId === 'personal_email' ? 'Personal Email' : 'Work Email')} is not Valid!!
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    } else {
        if (fieldId === 'email') {
            $('#first-form-button').prop('disabled', false);
        } else {
            $('#second-form-button').prop('disabled', false);
        }
    }
});


//password validation
$(document).on('keyup', '#password, #confirm_password', function () {
    const password = $('#password').val();
    const confirmPassword = $('#confirm_password').val();

    // Password validation criteria
    const passwordValidation = [
        { regex: /.{8,}/, message: "Password must be at least 8 characters long." },
        { regex: /[A-Z]/, message: "Password must include at least one uppercase letter." },
        { regex: /[a-z]/, message: "Password must include at least one lowercase letter." },
        { regex: /[0-9]/, message: "Password must include at least one number." },
        { regex: /[\W_]/, message: "Password must include at least one special character." },
    ];

    // Clear existing error messages
    $('.error-msgs').html('');
    let isPasswordValid = true;
    let errorMessages = [];

    // Check password against each validation criterion
    passwordValidation.forEach(criteria => {
        if (!criteria.regex.test(password)) {
            isPasswordValid = false;
            errorMessages.push(criteria.message);
        }
    });

    // Display combined validation errors in a single alert
    if (!isPasswordValid) {
        $('.error-msgs').append(`
            <div class="alert alert-danger alert-dismissible alert-additional fade show material-shadow" role="alert">
                <div class="alert-body">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-error-warning-line fs-16 align-middle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading">Important message !</h5>
                        </div>
                    </div>
                </div>
                <div class="alert-content">
                    <p class="mb-0">${errorMessages.join('<br>')}</p>
                </div>
            </div>
        `);
    }

    // Check if passwords match
    if (isPasswordValid && password !== confirmPassword) {
        $('#first-form-button').prop('disabled', true);
        $('.error-msgs').append(`
            <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow right" role="alert">
                <i class="ri-error-warning-line label-icon"></i>
                <strong>Error!</strong> - Passwords don't match.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    } else if (isPasswordValid) {
        $('#first-form-button').prop('disabled', false);
    }
});



// show and hide password
$(document).on('click', '.toggle-password', function () {
    const targetField = $(this).siblings('input'); // Get the input field in the same group
    const icon = $(this).find('i'); // Get the icon inside the button

    // Toggle the field type
    if (targetField.attr('type') === 'password') {
        targetField.attr('type', 'text'); // Show password
        icon.removeClass('bi-eye').addClass('bi-eye-slash'); // Change icon
    } else {
        targetField.attr('type', 'password'); // Hide password
        icon.removeClass('bi-eye-slash').addClass('bi-eye'); // Change icon
    }
});


//=========================================================================================================
// validation part 02 in go to document page
//=========================================================================================================

$(document).on('click', '#second-form-button', function () {
    let allFieldsValidContact = true;

    // Define required field IDs
    const requiredFieldsContact = [
        'title',
        'name_with_initials',
        'first_name',
        'last_name',
        'full_name',
        'dob',
        'nic',
        'gender',
        'religion_id',
        'marital_status',
        'personal_email',
        'contact_1',
        'address_1',
        'country_id',
        'province_id',
        'city_id'
    ];

    // Clear previous error messages
    $('.second-part-error-msgs').html('');


    requiredFieldsContact.forEach(fieldId => {
        const field = $(`#${fieldId}`);
        const errorMessage = field.siblings('.invalid-feedback');

        if (!field.val()?.trim()) {
            allFieldsValidContact = false;
            errorMessage.text('This field is required');
            field.addClass('is-invalid');
        } else {
            errorMessage.text('');
            field.removeClass('is-invalid');
        }
    });

    // Check validation and navigate or show errors
    if (allFieldsValidContact) {
        $('#steparrow-document-info-tab').tab('show');
    } else {
        $('.second-part-error-msgs').html(`
            <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow" role="alert">
                <i class="ri-error-warning-line label-icon"></i>
                <strong>Error!</strong> Please fill out all required fields before proceeding.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    }
});

//=========================================================================================================
//submit the form
//=========================================================================================================

$(document).on('click', '.emp_form_submit', async function (e) {
    e.preventDefault(); // Prevent default form submission

    const urlParams = new URLSearchParams(window.location.search);
    let emp_id = urlParams.get('emp_id');
    let user_id = $('#user_id').val();

    const isUpdating = Boolean(emp_id);
    const url = isUpdating ? `/employee/update/${emp_id}` : `/employee/create`;
    const method = isUpdating ? 'PUT' : 'POST';

    const formData = new FormData();

    // Append user_id or emp_id depending on the action
    if (isUpdating) {
        formData.append('emp_id', emp_id);
        // formData.append('user_id', user_id);
    } else {
        formData.append('user_id', user_id); // Create new employee
    }

    // Ensure password is only sent if the user updates it
    const password = $('#password').val();
    if (password) {
        formData.append('password', password);
    }

    // Append standard form fields
    const fields = [
        'user_no', 'punch_machine_user_id', 'branch_id', 'department_id', 'user_group_id',
        'designation_id', 'policy_group_id', 'user_status', 'currency_id', 'pay_period_schedule_id',
        'appointment_date', 'appointment_note', 'terminated_date', 'resigned_date', 'bond_period', 'terminated_note', 'confirmed_date', 'retirement_date',
        'employment_time', 'permission_group_id', 'email', 'password', 'title', 'name_with_initials', 'first_name',
        'last_name', 'full_name', 'dob', 'nic', 'gender', 'religion_id', 'marital_status', 'personal_email',
        'contact_1', 'contact_2', 'address_1', 'address_2', 'address_3', 'postal_code', 'country_id',
        'province_id', 'city_id', 'work_email', 'work_contact', 'immediate_contact_person',
        'immediate_contact_no', 'home_contact', 'epf_reg_no'
    ];

    fields.forEach(field => {
        const value = $(`#${field}`).val();
        if (value) {
            formData.append(field, value);
        }
    });

    // Append 'months' field value as 'employment_time'
    const months = $('#employment_time').val();
    if (months) {
        formData.append('employment_time', months); // Map 'months' to 'employment_time'
    }

    // Append employment type (radio/checkbox)
    const employmentType = $("input[name='employment_type']:checked").val();
    if (employmentType) {
        formData.append('employment_type_id', employmentType);
    }

    // Append user photo (if a file is selected)
    const userPhoto = $('#user_image')[0].files[0];
    if (userPhoto) {
        formData.append('user_image', userPhoto);
    }


    // Append document files
    filesArray.forEach((doc, index) => {
        formData.append(`doc_file[${index}]`, doc.file);
        formData.append(`doc_type_id[${index}]`, doc.doc_type_id);
        formData.append(`doc_title[${index}]`, doc.doc_title);
    });



    try {
        // Send data and handle response
        let res = await commonSaveData(url, formData, method);
        await commonAlert(res.status, res.message);

        if (res.status === 'success') {
            let userId = res.data.user_id;
            console.log('Redirecting to:', `/employee/profile/${userId}`);
            window.location.href = `/employee/profile/${userId}`;
        }
    } catch (error) {
        console.error('Error:', error);
        $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
    }
});



//=========================================================================================================
// Reset form
//=========================================================================================================

// Reset the Emplyee identification form
$(document).on('click', '.reset-user-form', function () {
    const formSection = $('#steparrow-basic-info'); // Target only the contact info section

    // Reset all input fields inside this section
    formSection.find('input, select, textarea').each(function () {
        if ($(this).is(':checkbox') || $(this).is(':radio')) {
            $(this).prop('checked', false); // Uncheck checkboxes & radio buttons
        } else {
            $(this).val('');
        }
    });

    // Clear
    formSection.find('.is-invalid').removeClass('is-invalid');
    formSection.find('.invalid-feedback').text('');
    $('.error-msgs').html('');
    $('#first-form-button').prop('disabled', false);
});


// Reset the contact information form
$(document).on('click', '.reset-contact-form', function () {
    const formSection = $('#steparrow-contact-info'); // Target only the contact info section

    // Reset all input fields inside this section
    formSection.find('input, select, textarea').each(function () {
        if ($(this).is(':checkbox') || $(this).is(':radio')) {
            $(this).prop('checked', false); // Uncheck checkboxes & radio buttons
        } else {
            $(this).val(''); // Reset other inputs
        }
    });

    // Clear
    formSection.find('.is-invalid').removeClass('is-invalid');
    formSection.find('.invalid-feedback').text('');
    $('.second-part-error-msgs').html('');
    $('#second-form-button').prop('disabled', false);
});


function resetForm() {
    $('#emp_id').val('');
    $('#user_no').val('');
    $('#title').val('');
    $('#first_name').val('');
    $('#last_name').val('');
    $('#full_name').val('');
    $('#name_with_initials').val('');
    $('#address_1').val('');
    $('#address_2').val('');
    $('#address_3').val('');
    $('#nic').val('');
    $('#country_id').val('');
    $('#province_id').val('');
    $('#city_id').val('');
    $('#postal_code').val('');
    $('#contact_1').val('');
    $('#contact_2').val('');
    $('#work_contact').val('');
    $('#home_contact').val('');
    $('#immediate_contact_person').val('');
    $('#immediate_contact_no').val('');
    $('#personal_email').val('');
    $('#work_email').val('');
    $('#epf_reg_no').val('');
    $('#religion_id').val('');
    $('#dob').val('');
    $('#gender').val('');
    $('#bond_period').val('');
    $('#user_status').val('');
    $('#marital_status').val('');
    $('#user_image').val('');
    $('#branch_id').val('');
    $('#department_id').val('');
    $('#punch_machine_user_id').val('');
    $('#designation_id').val('');
    $('#user_group_id').val('');
    $('#policy_group_id').val('');
    $('#pay_period_schedule_id').val('');
    $('#appointment_date').val('');
    $('#appointment_note').val('');
    $('#terminated_date').val('');
    $('#terminated_note').val('');
    $('#employment_type_id').val('');
    $('#employment_time').val('');
    $('#confirmed_date').val('');
    $('#resigned_date').val('');
    $('#retirement_date').val('');
    $('#currency_id').val('');
    $('#pay_period_id').val('');
    $('#permission_group_id').val('');
    $('#email').val('');
    $('#password').val('');
    $('#doc_file').val('');
    $('#doc_title').val('');
    $('#doc_type_id').val('');
    $('.second-part-error-msgs').html('');
    $('.error-msgs').html('');
}

//=========================================================================================================
</script>
