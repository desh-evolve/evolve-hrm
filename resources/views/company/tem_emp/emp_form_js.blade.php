<script>

let dropdownData = [];
let filesArray = []; // Array to hold files selected before the reset

$(document).ready(function(){
    getNextEmployeeNumber();
    getDropdownData();
})

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
            .map(empStatus => `<option value="${empStatus.id}">${empStatus.name} ${empStatus.description ? ' - ' + empStatus.description : ''}</option>`)
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
            .map(permGroup => `<option value="${permGroup.value}">${permGroup.name}</option>`)
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
        $('#employment_types').html(empTypeList);

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

        // Populate country dropdown
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

$(document).on('click', '.add_doc_to_list', function() {
    const doc_type_id = $('#doc_type_id').val(); // Get selected document type
    const doc_type_name =$('#doc_type_id option:selected').text();
    const doc_title = $('#doc_title').val(); // Get document title
    const file = $('#doc_file')[0].files[0]; // Get selected file

    // Check if all fields are filled
    if (!doc_type_name || !file) {
        alert('Please fill in all fields and select a file.');
        return;
    }

    // Check if the document type is already added
    const isDocTypeExist = filesArray.some(doc => doc.doc_type_id === doc_type_id);

    if (isDocTypeExist) {
        alert('This document type has already been added.');
        return;
    }

    // Store the file in the filesArray
    filesArray.push({
        doc_type_id: doc_type_id,
        doc_title: doc_title,
        file: file,
        doc_type_name: doc_type_name
    });

    // Extract file name from the file input
    const fileName = file.name;

    // Create a new row for the document
    const newRow = `
        <tr>
            <td>${doc_type_name}</td>
            <td>${doc_title}</td>
            <td>${fileName}</td>
            <td>
                <input type="hidden" name="doc_type_id[]" value="${doc_type_id}" />
                <input type="hidden" name="doc_title[]" value="${doc_title}" />
                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_download_document" title="Download Document">
                    <i class="ri-download-2-line"></i>
                </button>
                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_document" title="Remove Document">
                    <i class="ri-delete-bin-fill"></i>
                </button>
            </td>
        </tr>
    `;

    // Append the new row to the table body
    $('#document_tbody').append(newRow);

    // Reset the input fields after adding the document
    $('#doc_type_id').val('');
    $('#doc_title').val('');
    $('#doc_file').val('');
});


//==

$(document).on('click', '.add_doc_to_list', function () {
    const doc_type_id = $('#doc_type_id').val(); // Get selected document type
    const doc_type_name = $('#doc_type_id option:selected').text(); // Get document type name
    const doc_title = $('#doc_title').val(); // Get document title
    const files = $('#doc_file')[0].files; // Get selected files

    // Check if all fields are filled
    if (!doc_type_name || files.length === 0) {
        alert('Please fill in all fields and select at least one file.');
        return;
    }

    // Check if the document type already exists in filesArray
    let docTypeExists = filesArray.find(doc => doc.doc_type_id === doc_type_id);

    // If the document type exists, append files; otherwise, create a new entry
    if (docTypeExists) {
        Array.from(files).forEach(file => {
            docTypeExists.files.push({
                file: file,
                doc_title: doc_title || 'Untitled'
            });
        });
    } else {
        filesArray.push({
            doc_type_id: doc_type_id,
            doc_type_name: doc_type_name,
            files: Array.from(files).map(file => ({
                file: file,
                doc_title: doc_title || 'Untitled'
            }))
        });
    }

    // Update the table
    renderDocumentTable();

    // Reset the input fields
    $('#doc_type_id').val('');
    $('#doc_title').val('');
    $('#doc_file').val('');
});

// Render the table dynamically
function renderDocumentTable() {
    let rows = '';

    filesArray.forEach(doc => {
        doc.files.forEach((fileEntry, index) => {
            rows += `
                <tr>
                    <td>${doc.doc_type_name}</td>
                    <td>${fileEntry.doc_title}</td>
                    <td>${fileEntry.file.name}</td>
                    <td>
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_download_document" title="Download Document">
                            <i class="ri-download-2-line"></i>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_document" data-doc-type-id="${doc.doc_type_id}" data-file-index="${index}" title="Remove Document">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    });

    // If no documents, show "Not any Document" row
    if (!rows) {
        rows = `<tr><td colspan="4" class="text-center">Not any Document</td></tr>`;
    }

    $('#document_tbody').html(rows);
}

// Delete a document from the list
$(document).on('click', '.click_delete_document', function () {
    const docTypeId = $(this).data('doc-type-id');
    const fileIndex = $(this).data('file-index');

    // Find the document type and remove the specific file
    const docType = filesArray.find(doc => doc.doc_type_id === docTypeId);
    if (docType) {
        docType.files.splice(fileIndex, 1); // Remove the specific file
        if (docType.files.length === 0) {
            // Remove the document type if no files are left
            filesArray = filesArray.filter(doc => doc.doc_type_id !== docTypeId);
        }
    }

    // Re-render the table
    renderDocumentTable();
});


//==============================================

// Delete document from the list
$(document).on('click', '.click_delete_document', function() {
    // Find the closest row (tr) that contains the delete button
    const row = $(this).closest('tr');

    // Get the doc_type_id and doc_title hidden inputs from the row to find the corresponding file in filesArray
    const doc_type_id = row.find('input[name="doc_type_id[]"]').val();
    const doc_title = row.find('input[name="doc_title[]"]').val();

    // Remove the row from the table
    row.remove();

    // Remove the document from the filesArray based on doc_type_id and doc_title (or any other criteria)
    filesArray = filesArray.filter(doc => !(doc.doc_type_id === doc_type_id && doc.doc_title === doc_title));
});


// Handle the file download (this is just an example and should be updated based on how the file is stored)
$(document).on('click', '.click_download_document', function() {
    // Example code for downloading the file.
    // You would need a proper URL to the file based on how your server stores it.
    const fileName = $(this).closest('tr').find('td:nth-child(4)').text();
    alert('Downloading ' + fileName);
    // Add your file download logic here (e.g., linking to a server-side file or triggering a download).
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
        $('#months').prop('required', true); // Make month input required
    } else {
        $('#month-selection').hide();
        $('#months').prop('required', false); // Remove required attribute
        $('#months').val(''); // Clear month input
    }
});

// change department according to branch
$(document).on('change', '#branch_id', function() {
    let branch_id = $(this).val();
    if (branch_id == '') {
        $('#department_id').html('<option value="">Select a branch first</option>');
        return;
    }

    // Populate department dropdown
    let departmentList = (dropdownData?.departments || [])
        .filter(department => department.branch_departments.some(br_dep => br_dep.branch_id == branch_id)) // Check if branch_id exists in branch_departments
        .map(department => `<option value="${department.id}">${department.department_name}</option>`)
        .join('');

    $('#department_id').html('<option value="">Select a department</option>' + departmentList);
});

//=========================================================================================================
// validation part in go to next page
//=========================================================================================================



// check whether password and confirm password matches
$(document).on('keyup', '#password, #confirm_password', function(e) {
    let password = $('#password').val();
    let confirm_password = $('#confirm_password').val();

    if (password !== confirm_password) {
        $('[data-nexttab="steparrow-contact-info-tab"]').prop('disabled', true);
        if ($('.password_error').length === 0) { // Check if the message already exists
            $('.error-msgs').html('<p class="text-danger password_error m-0">Passwords don\'t match</p>');
        }
    } else {
        $('[data-nexttab="steparrow-contact-info-tab"]').prop('disabled', false);
        $('.error-msgs').html('');
    }
});

//submit the form
$(document).on('click', '.emp_form_submit', async function(e) {
    e.preventDefault(); // Prevent default form submission

    const userId = $('#user_id').val();
    const createUrl = `/employee/create`;
    const updateUrl = `/employee/update/${userId}`;

    // Get the password and confirm_password values
    const password = $('#password').val();
    const confirmPassword = $('#confirm_password').val();

    // Check if the passwords match
    if (password !== confirmPassword) {
        alert('Passwords do not match. Please re-enter.');
        return; // Stop the form submission if passwords don't match
    }

    // Create a FormData object to gather form data, including files
    const formData = new FormData();

    // Append standard form fields
    const fields = [
        'user_no', 'punch_machine_user_id', 'branch_id', 'department_id', 'user_group_id', 'designation_id', 'policy_group_id', 'user_status', 'currency_id', 'pay_period_schedule_id', 'appointment_date', 'appointment_note', 'termination_date', 'confirmed_date', 'retirement_date', 'months', 'permission_group_id', 'email', 'password', 'title', 'name_with_initials', 'first_name', 'last_name', 'full_name', 'dob', 'nic', 'gender', 'religion_id', 'marital_status', 'personal_email', 'contact_1', 'contact_2', 'address_1', 'address_2', 'address_3', 'postal_code', 'country_id', 'province_id', 'city_id', 'work_email', 'work_contact', 'immediate_contact_person', 'immediate_contact_no', 'home_contact', 'epf_no'
    ];

    fields.forEach(field => {
        const value = $(`#${field}`).val();
        if (value) {
            formData.append(field, value);
        }
    });

    // Append employment type (radio/checkbox)
    formData.append('employment_type_id', $("input[name='employment_type']:checked").val());

    // Append user photo (if a file is selected)
    const userPhoto = $('#user_photo')[0].files[0];
    if (userPhoto) {
        formData.append('user_photo', userPhoto);
    }

    // Append the files stored in filesArray to FormData
    filesArray.forEach((doc, index) => {
        formData.append(`doc_file[${index}]`, doc.file); // Add file to FormData
        formData.append(`doc_type_id[${index}]`, doc.doc_type_id); // Add doc_type_id to FormData
        formData.append(`doc_title[${index}]`, doc.doc_title); // Add doc_title to FormData
    });

    // Determine if it's an update or create operation
    const isUpdating = Boolean(userId);
    const url = isUpdating ? updateUrl : createUrl;
    const method = isUpdating ? 'PUT' : 'POST';

    if (isUpdating) {
        formData.append('user_id', userId);
    }

    try {
        // Send data and handle response
        let res = await commonSaveData(url, formData, method);
        await commonAlert(res.status, res.message);

        if (res.status === 'success') {
            window.location.href = '/employee/profile?emp='+res.data.id;
        }
    } catch (error) {
        console.error('Error:', error);
        $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
    }
});




</script>
