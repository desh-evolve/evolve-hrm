<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Work Experience') }}</h4>
    </x-slot>

    <style>
        .card-header:hover {
            background-color: #ddd;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Employee Work Experience</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add_new_work_experience_btn">New Work Experience <i class="ri-add-line"></i></button>
                    </div>
                </div>


                <div class="card-body">

                    <div class="row mb-3 mb-4">
                        <div class="col-lg-2 d-flex align-items-center">
                            <label for="employee_idname" class="form-label mb-1 req">Employee Name</label>
                        </div>

                        <div class="col-lg-10">
                            <select class="form-select form-select-sm js-example-basic-single" id="employeeDropdown">
                                <option value="">Select Employee</option>
                            </select>
                        </div>


                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Company</th>
                                <th class="col">From Date</th>
                                <th class="col">To Date</th>
                                <th class="col">Department</th>
                                <th class="col">Designation</th>
                                <th class="col">Remark</th>
                                <th class="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="work-experience-table-body">
                            <tr>
                                <td colspan="7" class="text-center">Please Select a Employee ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="work-experience-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="work-experience-form-title">New Work Experience</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="work-experience-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="employee_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="employee_name" value="" disabled>
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="company" rows="3">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="remarks" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="remarks" rows="3">
                        </div>

                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="work_experience_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="work_experience_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="work_experience_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary"
                                id="work-experience-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let employeeId = '';

            // Fetch and render employees_work_experience for the selected employee
            async function renderWorkExperienceTable() {
                if (!employeeId) {
                    $('#work-experience-table-body').html(
                        '<tr><td colspan="7" class="text-center">No Employee Selected</td></tr>');
                    return;
                }

                let employees_work_experience = await commonFetchData(`/company/employee_work_experience/${employeeId}`);
                let list = '';

                if (employees_work_experience && employees_work_experience.length > 0) {
                    employees_work_experience.forEach((item, i) => {
                        list += `
                <tr work_experience_id="${item.id}">
                    <td>${i + 1}</td>
                    <td>${item.company}</td>
                    <td>${item.from_date}</td>
                    <td>${item.to_date}</td>
                    <td>${item.department}</td>
                    <td>${item.designation}</td>
                    <td>${item.remarks}</td>
                    <td class="text-capitalize">${item.status === 'active' 
                        ? `<span class="badge border border-success text-success">${item.status}</span>` 
                        : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                    <td>
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-work-experience" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_work_experience" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
                    `;
                    });
                } else {
                    list = `<tr><td colspan="7" class="text-danger text-center">No Work Experience Yet!</td></tr>`;
                }

                $('#work-experience-table-body').html(list);
            }

            async function getEmployeeList() {
                let employees = await commonFetchData('/company/employee_work_experience/dropdown');

            
                
                // Check if employees data is valid
                if (employees && employees.length > 0) {
                    // Target the dropdown element
                    let dropdown = $('#employeeDropdown');

                    // Clear existing options (optional)
                    dropdown.empty();
                    dropdown.append('<option value="">Select Employee</option>'); // Add a default option

                    // Loop through the employees and add options
                    employees.forEach(employee => {
                        let option =
                            `<option value="${employee.id}">${employee.first_name} ${employee.last_name}</option>`;
                        dropdown.append(option);
                    });
                } else {
                    console.log('No employees found');
                }

            }

            // Populate employee dropdown and set up change event
            $(document).ready(async function() {
                await getEmployeeList();

                $('#employeeDropdown').on('change', async function() {
                    employeeId = $(this).val(); // Get selected employee ID
                    let employeeName = $('#employeeDropdown option:selected').text();
                    $('#employee_name').val(employeeName);

                    // Render work_experience table for the selected employee
                    await renderWorkExperienceTable();
                });
            });

            $(document).on('click', '#add_new_work_experience_btn', function() {
                resetForm();
                title = `Add New Work Experience`;
                $('#work-experience-form-title').html(title);
                $('#work-experience-form-modal').modal('show');
            });
            $(document).on('click', '#work-experience-submit-confirm', async function() {
                const work_experience_id = $('#work_experience_id').val();
                const company = $('#company').val();
                const from_date = $('#from_date').val();
                const to_date = $('#to_date').val();
                const department = $('#department').val();
                const designation = $('#designation').val();
                const remarks = $('#remarks').val(); // Correctly fetch the remarks value
                const work_experience_status = $('#work_experience_status').val();

                let createUrl = `/company/employee_work_experience/create`;
                let updateUrl = `/company/employee_work_experience/update/${work_experience_id}`;

                let formData = new FormData();

                if (!company || !from_date ||!to_date || !designation || !work_experience_status) {
                    $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                    return;
                } else {
                    $('#error-msg').html(''); // Clear error message if no issues
                }
                
                formData.append('employee_id', employeeId);
                formData.append('company', company);
                formData.append('from_date', from_date);
                formData.append('to_date', to_date);
                formData.append('department', department);
                formData.append('designation', designation);
                formData.append('remarks', remarks); // Use the fetched remarks value here
                formData.append('work_experience_status', work_experience_status);

                const isUpdating = Boolean(work_experience_id);
                let url = isUpdating ? updateUrl : createUrl;
                let method = isUpdating ? 'PUT' : 'POST';

                try {
                    let res = await commonSaveData(url, formData, method);
                    await commonAlert(res.status, res.message);

                    if (res.status === 'success') {
                        $('#work-experience-form-modal').modal('hide');
                        await renderWorkExperienceTable(); // Re-render table on success
                    }
                } catch (error) {
                    console.error('Error:', error);
                    $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
                }
            });


            // edit click event
            $(document).on('click', '.click-edit-work-experience', async function() {
                // resetForm();
                let work_experience_id = $(this).closest('tr').attr('work_experience_id');


                // Get branch data by id
                try {
                    let work_experience_data = await commonFetchData(
                        `/company/single_employee_work_experience/${work_experience_id}`);
                        work_experience_data = work_experience_data[0];
                    console.log('work_experience_data', work_experience_data);

                    // Set initial form values
                    $('#work_experience_id').val(work_experience_id);
                    $('#company').val(work_experience_data?.company || '');
                    $('#from_date').val(work_experience_data?.from_date || '');
                    $('#to_date').val(work_experience_data?.to_date || '');
                    $('#department').val(work_experience_data?.department || '');
                    $('#designation').val(work_experience_data?.designation || '');
                    $('#remarks').val(work_experience_data?.remarks || '');
                    $('#work_experience_status').val(work_experience_data?.status || '');
                    // Load the country, province, and city accordingly


                } catch (error) {
                    console.error('error at getWorkExperienceById: ', error);
                } finally {
                    title = `Edit Work Experience`;
                    $('#work-experience-form-title').html(title);
                    $('#work-experience-form-modal').modal('show');
                }
            });
            $(document).on('click', '.click_delete_work_experience', async function() {
                let work_experience_id = $(this).closest('tr').attr('work_experience_id');

                try {
                    let url = `/company/employee_work_experience/delete`;
                    const res = await commonDeleteFunction(work_experience_id, url,
                        'Work Experience'); // Await the promise here

                    if (res) {
                        await renderWorkExperienceTable();
                    }
                } catch (error) {
                    console.error(`Error during Work Experience deletion:`, error);
                }
            })

            function resetForm() {
                $('#work_experience_id').val('');
                $('#company').val('');
                $('#from_date').val('');
                $('#to_date').val('');
                $('#department').val('');
                $('#designation').val('');
                $('#remarks').val('');
                $('#work_experience_status').val('active'); // Reset status to default
                $('#error-msg').html(''); // Clear error messages
            }
        </script>
</x-app-layout>
