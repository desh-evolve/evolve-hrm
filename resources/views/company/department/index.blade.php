<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">

    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Departments') }}</h4>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Departments</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_add_department">New Department <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Department Name</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="department_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="department-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="department-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="department-form-body" class="row">
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="department_name" class="form-label mb-1 req">Department Name</label>
                            <input type="text" class="form-control" id="department_name" placeholder="Enter Department Name" value="" >
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="department_status" class="form-label mb-1 req">Department Status</label>
                            <select class="form-select" id="department_status" >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <hr>
                        <div class="col-xxl-12 col-md-12 mb-3">
                            <label for="branches" class="form-label mb-1 req">Branches</label>
                            <select class="select2-multiple" id="branches" name="branches[]" multiple="multiple">

                            </select>
                        </div>
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="department_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="department-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(function(){
            getDropdownData();
            renderDepartmentTable();
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/company/department/dropdown');
                // Populate branches dropdown
                let branchList = (dropdownData?.branches || [])
                    .map(branch => `<option value="${branch.id}">${branch.branch_name}</option>`)
                    .join('');
                $('#branches').html(branchList);
                $(".select2-multiple").select2();
            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        async function renderDepartmentTable(){
            let list = '';

            const departments = await commonFetchData('/company/departments');

            if(departments && departments.length > 0){
                departments.map((department, i) => {
                    list += `
                        <tr department_id="${department.id}"">
                            <td>${i + 1}</td>
                            <td>${department.department_name}</td>
                            <td class="text-capitalize">${department.status === 'active' ? `<span class="badge border border-success text-success">${department.status}</span>` : `<span class="badge border border-warning text-warning">${department.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_department" title="Edit Department" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_department" title="Delete Department" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                                <button type="button" class="btn btn-success waves-effect waves-light btn-sm click_assign_employees" title="Department Employees" data-tooltip="tooltip" data-bs-placement="top">
                                    Department Employees <i class="ri-group-line"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            }else{
                list = '<tr><td colspan="7" class="text-center">No Departments Found!</td></tr>';
            }


            $('#department_table_body').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        }

        // check here
        $(document).on('click', '#click_add_department', function(){
            resetForm();
            $('#department-form-title').text('Add Department');
            $('#department-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_department', async function() {
            resetForm();
            $('#error-msg').html('<p class="text-danger">* Note - Updating department branches may affect department employees.</p>');

            // Get department data by id
            let department_id = $(this).closest('tr').attr('department_id');

            try {
                let department_data = await commonFetchData(`/company/department/${department_id}`);
                department_data = department_data[0];
                let branches = department_data?.branch_departments?.map(branch => (branch.branch_id).toString()) || [];

                // Set initial form values
                $('#department_id').val(department_id);
                $('#department_name').val(department_data?.department_name || '');
                $('#department_status').val(department_data?.status || '');

                //==============================================================
                // select2 code for update
                //==============================================================
                // Reset previously disabled options
                $('#branches option').attr('disabled', false);

                // Set selected branches and disable them in the dropdown
                $('#branches').val(branches).trigger('change');

                // Disable selected options in the dropdown
                branches.forEach(branchId => {
                    //$('#branches option[value="' + branchId + '"]').attr('disabled', true); //when we disable it can't get value to send by formData. do sth else here
                });

                $(".select2-multiple").select2();
                //==============================================================
            } catch (error) {
                console.error('error at getDepartmentById: ', error);
            } finally {
                $('#department-form-title').text('Edit Department');
                $('#department-form-modal').modal('show');
            }
        });

        $(document).on('click', '.click_delete_department', async function(){
            let department_id = $(this).closest('tr').attr('department_id');

            try {
                let url = `/company/department/delete`;
                const res = await commonDeleteFunction(department_id, url, 'Department');  // Await the promise here

                if (res) {
                    await renderDepartmentTable();
                }
            } catch (error) {
                console.error(`Error during department deletion:`, error);
            }
        })

        $(document).on('click', '.click_assign_employees', async function(){
            let department_id = $(this).closest('tr').attr('department_id');
            window.location.href = '/company/department/employees?dep_id='+department_id;
        })

        $(document).on('click', '#department-submit-confirm', async function() {
            const department_id = $('#department_id').val();

            let createUrl = `/company/department/create`;
            let updateUrl = `/company/department/update/${department_id}`;

            let department_name = $('#department_name').val();
            let department_status = $('#department_status').val();
            let branches = $('#branches').val();

            let formData = new FormData();
            let missingFields = [];

            // Check for missing fields and add them to the array
            if (!department_name) missingFields.push('department_name');
            if (!department_status) missingFields.push('department_status');
            if (!branches || branches.length == 0) missingFields.push('branches');

            // If there are any missing fields, display the error message and stop execution
            if (missingFields.length > 0) {
                let errorMsg = '<p class="text-danger">The following fields are required: ';
                errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
                $('#error-msg').html(errorMsg);
                return;  // Stop further execution if there are missing fields
            } else {
                $('#error-msg').html(''); // Clear any previous error messages
            }

            // Append form data
            formData.append('department_name', department_name);
            formData.append('department_status', department_status);
            formData.append('branches', branches);

            // Determine if updating or creating
            const isUpdating = Boolean(department_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('department_id', department_id);
                // Use a workaround if necessary for the PUT method by adding a hidden `_method` field.
                formData.append('_method', 'PUT');
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);

                if (res && res.status === 'success') {
                    await commonAlert(res.status, res.message);
                    renderDepartmentTable();
                    $('#department-form-modal').modal('hide');
                } else {
                    // Handle possible failure scenarios
                    let errorMessage = res && res.message ? res.message : 'An unexpected error occurred.';
                    $('#error-msg').html('<p class="text-danger">' + errorMessage + '</p>');
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm(){
            $('#department_id').val('');
            $('#department_name').val('');
            $('#department_status').val('active');
            $('#branches').val([]).trigger('change');
            $('#error-msg').html('');
        }

    </script>
</x-app-layout>
