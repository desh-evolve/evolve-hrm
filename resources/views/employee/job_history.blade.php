<!-- pawanee(2024-10-28) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Job History') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>



{{-- Generate Lists table --}}


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between">
                <div >
                    <h4 class="card-title mb-0 flex-grow-1">Employee Job History</h4>
                </div>

                <div class="justify-content-md-end">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New<i class="ri-add-line"></i></button>
                    </div>
                </div>
            </div>


            <div class="card-body">
                    <div class="card-body">

                        <div class="row mb-3 mb-4">
                            <div class="col-lg-2">
                                <label for="employee_idname" class="form-label mb-1 req">Employee Name</label>
                            </div>

                            <div class="col-lg-10">
                                <select class="form-select form-select-sm" id="employee_idname" >

                                </select>
                            </div>

                        </div>


                        <table class="table table-nowrap" id="jobhistory_table">
                            <thead class="table-light" id="table_head">
                                <tr>
                                    <th scope="col">Branch</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">First Worked Date</th>
                                    <th scope="col">Last Worked Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">

                                <tr>
                                    <td colspan="6" class="text-center text-warning">Select Employee Name Display their Job History</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>



<!-- wageGroup form modal -->


<div id="jobhistory_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="jobhistory-form-body" class="row">

                    <div class="col-xxl-3 col-md-12 mb-3">
                        <label for="employee_name" class="form-label mb-1">Employee Name</label>
                        <input type="text" class="form-control" id="employee_name" value="" disabled>
                    </div>

                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="branch_id" class="form-label mb-1 req">Branch</label>
                        <select class="form-select" id="branch_id" >
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="department_id" class="form-label mb-1 req">Department</label>
                        <select class="form-select" id="department_id" >
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="designation_id" class="form-label mb-1 req">Employee Designation</label>
                        <select class="form-select" id="designation_id" >
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="first_worked_date" class="form-label">First Worked Date</label>
                        <input type="date" class="form-control" id="first_worked_date" value="">
                    </div>

                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="last_worked_date" class="form-label">Last Worked Date</label>
                        <input type="date" class="form-control" id="last_worked_date" value="">
                    </div>

                    <div class="col-xxl-3 col-md-12 mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="form-control" id="note" rows="3"></textarea>
                    </div>


                <div id="error-msg"></div>

                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                    <input type="hidden" id="employee_id" value="">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-primary" id="job-history-submit-confirm">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>




<script>


//======================================================================================================
// RENDER TABLE
//======================================================================================================

let dropdownData = [];

        $(document).ready(function(){
            getDropdownData();

        });



        //get employee data click employee name
        $(document).on('change', '#employee_idname', function () {
            let employee_id = $(this).val();

                if (employee_id === "") {
                    $('#table_body').html('<tr><td colspan="6" class="text-center text-warning">Select Employee Name Display their Job History</td></tr>');
                } else {
                    //Render the job history
                    renderJobHistoryTable(employee_id);
                }
        });




        //render table using employee Id
        async function renderJobHistoryTable(employee_id){
            let list = '';

            const employees = await commonFetchData(`/employee/jobhistory/${employee_id}`);

            if(employees && employees.length === 1){
                employees.map((employee) => {
                    list += `
                        <tr employee_id="${employee.id}">
                            <td>${employee.branch_name}</td>
                            <td>${employee.department_name}</td>
                            <td>${employee.emp_designation_name}</td>
                            <td>${employee.first_worked_date}</td>
                            <td>${employee.last_worked_date}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            }else{
                list = '<tr><td colspan="6" class="text-center">No Employee Job History Found!</td></tr>';
            }


            $('#table_body').html(list);
        }


//======================================================================================================
//get dropdown data
//======================================================================================================

async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/employee/jobhistory/dropdown');

                // Populate employee name dropdown
                let employeeList = (dropdownData?.employees || [])
                    .map(employee => `<option value="${employee.id}">${employee.name_with_initials}</option>`)
                    .join('');
                $('#employee_idname').html('<option value="">Select Employee Name</option>' + employeeList);


                // Populate branch dropdown
                let branchList = (dropdownData?.branches || [])
                    .map(branch => `<option value="${branch.id}">${branch.branch_name}</option>`)
                    .join('');
                $('#branch_id').html('<option value="">Select Branch</option>' + branchList);


                // Populate department dropdown
                let departmentList = (dropdownData?.departments || [])
                    .map(department => `<option value="${department.id}">${department.department_name}</option>`)
                    .join('');
                $('#department_id').html('<option value="">Select Department</option>' + departmentList);


                // Populate designation dropdown
                let designationList = (dropdownData?.designations || [])
                    .map(designation => `<option value="${designation.id}">${designation.emp_designation_name}</option>`)
                    .join('');
                $('#designation_id').html('<option value="">Select Designation</option>' + designationList);


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }




//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

$(document).on('click', '.click_delete', function() {
        const $row = $(this).closest('tr');
        const id = $row.attr('employee_id');

            deleteItem(id, $row);

    });

    async function deleteItem(id, $row) {
        const url ='/employee/jobhistory/delete';
        const title ='Employee Job History';


        try {
                    const res = await commonDeleteFunction(id, url, title, $row);
                    if(res){
                        renderJobHistoryTable()
                    }
                } catch (error) {
                    console.error('Error deleting item:', error);
                }
    }



//==================================================================================================
// ADD & EDIT FUNCTION
//==================================================================================================

    // Add job history
    $(document).on('click', '#add_new_btn', function () {
        resetForm(); // Reset the form
        $('.modal-title').text('Add Employee Job History');  // Set modal title for adding
        $('#jobhistory_form_modal').modal('show'); // Show the modal for adding a new job history
    });


     // Edit job history
    $(document).on('click', '.click_edit', async function () {
        resetForm();

        // Corrected variable name to employee_id
        let employee_id = $(this).closest('tr').attr('employee_id');

        // Fetch employee job history data by ID
        try {
            let employees_data = await commonFetchData(`/employee/jobhistory/${employee_id}`);
                employees_data = employees_data[0]; // Assuming it returns an array with one object
                console.log('employees_data', employees_data);

                // Set form values with fetched data
                $('#employee_id').val(employee_id);
                $('#branch_id').val(employees_data?.branch_id || '');
                $('#department_id').val(employees_data?.department_id || '');
                $('#designation_id').val(employees_data?.designation_id || '');
                $('#first_worked_date').val(employees_data?.first_worked_date || '');
                $('#last_worked_date').val(employees_data?.last_worked_date || '');
                $('#note').val(employees_data?.note || '');

            } catch (error) {
                console.error('Error at getJobHistoryById:', error);
                $('#error-msg').html('<p class="text-danger">Error fetching employee job history data. Please try again.</p>');
            } finally {
                $('#jobhistory_form_modal').modal('show');
            }
        });


    // Submit (Add/Edit)
    $(document).on('click', '#job-history-submit-confirm', async function () {
        const employee_id = $('#employee_id').val();

        let createUrl = `/employee/jobhistory/create`;
        let updateUrl = `/employee/jobhistory/update/${employee_id}`;

        const formFields = {
            branch_id: 'required',
            department_id: 'required',
            designation_id: 'required',
            first_worked_date: 'required',
            last_worked_date: 'required',
            note: 'required',
        };

        let formData = new FormData();
        let missingFields = [];

        // Validate only required fields
        for (const key in formFields) {
            const fieldId = key;
            const value = $('#' + fieldId).val(); // Fetch value using the ID

            // Check only required fields
            if (formFields[key] === 'required' && !value) {
                missingFields.push(fieldId);
            }

            // Append all fields to formData
            formData.append(key, value || ''); // Append empty string if no value for optional fields
        }


        // If there are missing required fields, display an error message
        if (missingFields.length > 0) {
            let errorMsg = '<p class="text-danger">The following fields are required: ';
            errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
            $('#error-msg').html(errorMsg);
            return;
        } else {
            $('#error-msg').html(''); // Clear error message if no issues
        }


        // Check if updating
        const isUpdating = Boolean(employee_id);
        let url = isUpdating ? updateUrl : createUrl;
        let method = isUpdating ? 'PUT' : 'POST'; // Set method based on update/create

        if (isUpdating) {
            formData.append('id', employee_id); // Append ID if updating
        }

        try {
            // Send data and handle response
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                renderJobHistoryTable();
                $('#jobhistory_form_modal').modal('hide');
            } else {
                $('#error-msg').html('<p class="text-danger">' + res.message + '</p>');
            }

        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });

    // Reset Function
    function resetForm() {
        $('#employee_id').val('');
        $('#employee_name').val('');
        $('#branch_id').val('');
        $('#department_id').val('');
        $('#designation_id').val('');
        $('#first_worked_date').val('');
        $('#last_worked_date').val('');
        $('#note').val('');
        $('#error-msg').html('');
    }



    // Submit function for Add/Edit Job History
$(document).on('click', '#job-history-submit-confirm', async function () {
    const employee_id = $('#employee_id').val();

    let createUrl = `/employee/jobhistory/create`;
    let updateUrl = `/employee/jobhistory/update/${employee_id}`;

    // Determine if updating or creating
    const isUpdating = Boolean(employee_id);
    let url = isUpdating ? updateUrl : createUrl;
    let method = isUpdating ? 'PUT' : 'POST'; // Use 'PUT' for updates, 'POST' for new entries

    let formData = new FormData();
    let missingFields = [];

    // Form fields required for validation
    const formFields = {
        branch_id: 'required',
        department_id: 'required',
        designation_id: 'required',
        first_worked_date: 'required',
        last_worked_date: 'required',
        note: 'required',
    };

    // Loop through each field to validate
    for (const field in formFields) {
        const value = $('#' + field).val();

        if (formFields[field] === 'required' && !value) {
            missingFields.push(field); // Add to missing fields if required and empty
        }

        // Append field data to formData
        formData.append(field, value || '');
    }

    // If fields are missing, show an error and prevent submission
    if (missingFields.length > 0) {
        const errorMsg = `<p class="text-danger">The following fields are required: ${missingFields.map(f => f.replace('_', ' ')).join(', ')}.</p>`;
        $('#error-msg').html(errorMsg);
        return;
    } else {
        $('#error-msg').html(''); // Clear error message if validation passes
    }

    // Append ID if updating
    if (isUpdating) {
        formData.append('id', employee_id);
    }

    try {
        // Save data and handle response
        let res = await commonSaveData(url, formData, method);
        await commonAlert(res.status, res.message); // Show status alert

        if (res.status === 'success') {
            renderJobHistoryTable(); // Reload table on success
            $('#jobhistory_form_modal').modal('hide'); // Close modal
        } else {
            $('#error-msg').html(`<p class="text-danger">${res.message}</p>`);
        }

    } catch (error) {
        console.error('Error:', error);
        $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
    }
});


</script>


</x-app-layout>
