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
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">New Job History<i class="ri-add-line"></i></button>
                        <a href="/employee/list" class="btn btn-danger">Back</a>
                    </div>
                </div>
            </div>


            <div class="card-body">
                    <div class="card-body">

                        <div class="row mb-3 mb-4">
                            <div class="col-lg-2">
                                <label for="user_idname" class="form-label mb-1 req">Employee Name</label>
                            </div>

                            <div class="col-lg-10">
                                <select class="form-select" id="userDropdown" >

                                </select>
                            </div>

                        </div>


                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Branch</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">First Worked Date</th>
                                    <th scope="col">Last Worked Date</th>
                                    <th scope="col">Note</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">

                                <tr>
                                    <td colspan="8" class="text-center">Please Select a Employee...</td>
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



<!-- form modal -->


<div id="jobhistory_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="employee.user_preferencetic" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="jobhistory-form-body" class="row">

                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="user_name" class="form-label mb-1">Employee Name</label>
                        <input type="text" class="form-control" id="user_name" value="" disabled>
                        <input type="hidden" class="form-control" id="user_id" value="" disabled>
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

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="first_worked_date" class="form-label">First Worked Date</label>
                        <input type="date" class="form-control" id="first_worked_date" value="">
                    </div>

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="last_worked_date" class="form-label">Last Worked Date</label>
                        <input type="date" class="form-control" id="last_worked_date" value="">
                    </div>

                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="form-control" id="note" rows="5"></textarea>
                    </div>


                <div id="error-msg"></div>

                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                    <input type="hidden" id="jobhistory_id" value="">
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
let userId = "{{ $user->user_id }}";

let dropdownData = [];


        $(document).ready(async function(){
            await getDropdownData();


        });

        // Get user data when selecting user name
                $(document).on('change', '#userDropdown', async function () {
                    userId = $(this).val();
                    let userName = $('#userDropdown option:selected').text();
                    $('#user_name').val(userName);

                    // Enable button if user is selected
                    if (userId) {
                        $('#add_new_btn').prop('disabled', false);
                    } else {
                        $('#add_new_btn').prop('disabled', true);
                    }


                    if (userId === "") {
                        $('#table_body').html('<tr><td colspan="8" class="text-center">Select Employee...</td></tr>');
                        $('#employee_name').val('');
                        $('#employee_id').val('');
                    } else {
                        await renderJobHistoryTable();
                    }
                });

        //render table using user Id
          async function renderJobHistoryTable(){
            let list = '';

            const jobs = await commonFetchData(`/employee/jobhistory/${userId}`);

            if(jobs && jobs.length > 0){
                jobs.map((job, i) => {
                    list += `
                        <tr jobhistory_id="${job.id}">
                            <td>${i + 1}</td>
                            <td>${job.branch_name}</td>
                            <td>${job.department_name}</td>
                            <td>${job.emp_designation_name}</td>
                            <td>${job.first_worked_date}</td>
                            <td>${job.last_worked_date}</td>
                            <td>${job.note}</td>
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
                list = '<tr><td colspan="8" class="text-center text-danger">No Job History Found!</td></tr>';
            }


            $('#table_body').html(list);
        }

//======================================================================================================
//get dropdown data
//======================================================================================================

        async function getDropdownData() {
            try {
              let dropdownData = await commonFetchData('/employee/jobhistory/dropdown');

              if (dropdownData) {
                // Populate user name dropdown
                let userList = (dropdownData?.users || [])
                    .map(user => `<option value="${user.user_id}">${user.first_name} ${user.last_name}</option>`)
                    .join('');
                $('#userDropdown').html('<option value="">Select Employee</option>' + userList);

                // Check if a userId is already selected
                if (userId) {
                    $('#userDropdown').val(userId); // Pre-select the dropdown value
                    $('#user_name').val($('#userDropdown option:selected').text()); // Display name
                    await renderJobHistoryTable(userId); // Render table for the selected user
                }
            } else {
                    console.log('No users found');
            }

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
        const id = $row.attr('jobhistory_id');

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
        resetForm();
        title = `Add Job History`;
        $('.modal-title').html(title);
        $('#jobhistory_form_modal').modal('show');
    });


     // Edit job history
    $(document).on('click', '.click_edit', async function () {
        resetForm();
        title = `Edit Job History`;
        $('.modal-title').html(title);

        let jobhistory_id = $(this).closest('tr').attr('jobhistory_id');

        try {
            let job_data = await commonFetchData(`/employee/single_jobhistory/${jobhistory_id}`);
            job_data = job_data[0];
                console.log('users_data', job_data);

                // Set form values with fetched data
                $('#jobhistory_id').val(jobhistory_id);
                $('#branch_id').val(job_data?.branch_id || '');
                $('#department_id').val(job_data?.department_id || '');
                $('#designation_id').val(job_data?.designation_id || '');
                $('#first_worked_date').val(job_data?.first_worked_date || '');
                $('#last_worked_date').val(job_data?.last_worked_date || '');
                $('#note').val(job_data?.note || '');

        } catch (error) {
            console.error('Error at getJobHistoryById:', error);
            $('#error-msg').html('<p class="text-danger">Error fetching user job history data. Please try again.</p>');
        } finally {
            $('#jobhistory_form_modal').modal('show');
        }
    });


    // Submit (Add/Edit)
    $(document).on('click', '#job-history-submit-confirm', async function () {
        const jobhistory_id = $('#jobhistory_id').val();

        const isUpdating = Boolean(jobhistory_id);
        const url = isUpdating ? `/employee/jobhistory/update/${jobhistory_id}` : `/employee/jobhistory/create`;
        const method = isUpdating ? 'PUT' : 'POST';

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

        for (const key in formFields) {
            const value = $('#' + key).val();
            if (formFields[key] === 'required' && !value) {
                missingFields.push(key);
            }
            formData.append(key, value || ''); // Append all fields to formData
        }

        if (missingFields.length > 0) {
            $('#error-msg').html('<p class="text-danger">The following fields are required: ' +
                missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>');
            return;
        } else {
            $('#error-msg').html('');
        }

            formData.append('user_id', userId);

        try {
            let res = await commonSaveData(url, formData, method);
            console.log('Response:', res); // Debugging response data
            await commonAlert(res.employee.user_preferencetus, res.message);

            if (res.employee.user_preferencetus === 'success') {
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

        $('#jobhistory_id').val('');
        $('#branch_id').val('');
        $('#department_id').val('');
        $('#designation_id').val('');
        $('#first_worked_date').val('');
        $('#last_worked_date').val('');
        $('#note').val('');
        $('#error-msg').html('');
    }

</script>


</x-app-layout>
