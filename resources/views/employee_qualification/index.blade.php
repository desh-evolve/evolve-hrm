<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee qualification') }}</h4>
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
                        <h5 class="mb-0">Employee qualification</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add_new_qualification_btn">New qualification <i class="ri-add-line"></i></button>
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
                                <th class="col">qualification</th>
                                <th class="col">Institute</th>
                                <th class="col">Year</th>
                                <th class="col">Remark</th>
                            </tr>
                        </thead>
                        <tbody id="qualification_table_body">
                            <tr>
                                <td colspan="7" class="text-center">Please Select a Employee ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="qualification-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="qualification-form-title">New Qualification</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="qualification-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="employee_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="employee_name" value="" disabled>
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="qualification" class="form-label">qualification</label>
                            <input type="text" class="form-control" id="qualification" rows="3">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="institute" class="form-label">Institute</label>
                            <input type="text" class="form-control" id="institute" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" class="form-control" id="year" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="remarks" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="remarks" rows="3">
                        </div>

                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="qualification_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="qualification_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="qualification_id" value="">
                            <input type="hidden" id="employee_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary"
                                id="qualification-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let employeeId = '';

            // Fetch and render qualifications for the selected employee
            async function renderQualificationsTable() {
                if (!employeeId) {
                    $('#qualification_table_body').html(
                        '<tr><td colspan="7" class="text-center">No Employee Selected</td></tr>');
                    return;
                }

                let employees_qualifications = await commonFetchData(`/company/employee_qualification/${employeeId}`);
                let list = '';

                if (employees_qualifications && employees_qualifications.length > 0) {
                    employees_qualifications.forEach((item, i) => {
                        list += `
                <tr qualification_id="${item.id}">
                    <td>${i + 1}</td>
                    <td>${item.qualification}</td>
                    <td>${item.institute}</td>
                    <td>${item.year}</td>
                    <td>${item.remarks}</td>
                    <td class="text-capitalize">${item.status === 'active'
                        ? `<span class="badge border border-success text-success">${item.status}</span>`
                        : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                    <td>
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-qualification" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_qualification" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
                    `;
                    });
                } else {
                    list = `<tr><td colspan="7" class="text-danger text-center">No Qualification Yet!</td></tr>`;
                }

                $('#qualification_table_body').html(list);
            }

            async function getEmployeeList() {
                let employees = await commonFetchData('/company/employee_qualification/dropdown');


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

                    // Render qualifications table for the selected employee
                    await renderQualificationsTable();
                });
            });

            $(document).on('click', '#add_new_qualification_btn', function() {
                resetForm();
                title = `Add New qualification`;
                $('#qualification-form-title').html(title);
                $('#qualification-form-modal').modal('show');
            });
            $(document).on('click', '#qualification-submit-confirm', async function() {
                const qualification_id = $('#qualification_id').val();
                const qualification = $('#qualification').val();
                const institute = $('#institute').val();
                const year = $('#year').val();
                const remarks = $('#remarks').val(); // Correctly fetch the remarks value
                const qualification_status = $('#qualification_status').val();

                let createUrl = `/company/employee_qualification/create`;
                let updateUrl = `/company/employee_qualification/update/${qualification_id}`;

                let formData = new FormData();

                if (!qualification || !institute || !year || !qualification_status) {
                    $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                    return;
                } else {
                    $('#error-msg').html(''); // Clear error message if no issues
                }
                console.log('Remarks:', remarks);

                formData.append('employee_id', employeeId);
                formData.append('qualification', qualification);
                formData.append('institute', institute);
                formData.append('year', year);
                formData.append('remarks', remarks); // Use the fetched remarks value here
                formData.append('qualification_status', qualification_status);

                const isUpdating = Boolean(qualification_id);
                let url = isUpdating ? updateUrl : createUrl;
                let method = isUpdating ? 'PUT' : 'POST';

                try {
                    let res = await commonSaveData(url, formData, method);
                    await commonAlert(res.status, res.message);

                    if (res.status === 'success') {
                        $('#qualification-form-modal').modal('hide');
                        await renderQualificationsTable(); // Re-render table on success
                    }
                } catch (error) {
                    console.error('Error:', error);
                    $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
                }
            });


            // edit click event
            $(document).on('click', '.click-edit-qualification', async function() {
                // resetForm();
                let qualification_id = $(this).closest('tr').attr('qualification_id');


                // Get branch data by id
                try {
                    let qualification_data = await commonFetchData(
                        `/company/single_employee_qualification/${qualification_id}`);
                    qualification_data = qualification_data[0];
                    console.log('qualification_data', qualification_data);

                    // Set initial form values
                    $('#qualification_id').val(qualification_id);
                    $('#qualification').val(qualification_data?.qualification || '');
                    $('#institute').val(qualification_data?.institute || '');
                    $('#year').val(qualification_data?.year || '');
                    $('#remarks').val(qualification_data?.remarks || '');
                    $('#qualification_status').val(qualification_data?.status || '');
                    // Load the country, province, and city accordingly


                } catch (error) {
                    console.error('error at getQulificationById: ', error);
                } finally {
                    title = `Edit Qualification`;
                    $('#qualification-form-title').html(title);
                    $('#qualification-form-modal').modal('show');
                }
            });
            $(document).on('click', '.click_delete_qualification', async function() {
                let qualification_id = $(this).closest('tr').attr('qualification_id');

                try {
                    let url = `/company/employee_qualification/delete`;
                    const res = await commonDeleteFunction(qualification_id, url,
                        'Designation'); // Await the promise here

                    if (res) {
                        await renderQualificationsTable();
                    }
                } catch (error) {
                    console.error(`Error during Qualification deletion:`, error);
                }
            })

            function resetForm() {
                $('#qualification_id').val('');
                $('#qualification').val('');
                $('#institute').val('');
                $('#year').val('');
                $('#remarks').val('');
                $('#qualification_status').val('active'); // Reset status to default
                $('#error-msg').html(''); // Clear error messages
            }






        </script>



</x-app-layout>
