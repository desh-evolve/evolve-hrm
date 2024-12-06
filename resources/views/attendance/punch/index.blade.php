<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Punches') }}</h4>
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
                        <h5 class="mb-0">Punches</h5>
                    </div>
                    <div>
                        {{-- <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add_new_punch_btn">New Punch <i class="ri-add-line"></i></button> --}}

                        <button type="button" class="btn btn-primary" id="add_new_punch_btn">
                            New Punch <i class="ri-add-line"></i>
                        </button>
                    </div>


                </div>


                <div class="card-body">

                    <div class="row mb-3 mb-4">
                        <div class="col-lg-2 d-flex align-items-center">
                            <label for="employee_idname" class="form-label mb-1 req">Employee Name</label>
                        </div>

                        <div class="col-lg-10">
                            <select class="form-select form-select-sm js-example-basic-single" id="employee_id">
                                <option value="">Select Employee</option>
                            </select>
                        </div>


                    </div>
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white"/>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Employee</th>
                                <th class="col">Punch Type</th>
                                <th class="col">Punch Status</th>
                                <th class="col">Time</th>
                                <th class="col">Date</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="punch_table_body">
                            <tr>
                                <td colspan="7" class="text-center">Please Select a Employee ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Include the popup modal here -->
    @include('attendance.punch_popup')

    <script>
        let employeeId = '';

        $(document).ready(async function() {
            await getDropdownData();

        });

        // Get employee data when selecting employee name
        $(document).on('change', '#employee_id', async function() {
            employeeId = $(this).val();
            let employeeName = $('#employee_id option:selected').text();
            $('#employee_name').val(employeeName);
            $('#employee_id').val(employeeId);

            if (employeeId === "") {

                $('#table_body').html(
                    '<tr><td colspan="8" class="text-center text-info">Please Select a Employee</td></tr>');
                $('#employee_name').val('');
                $('#employee_id').val('');
            } else {
                await renderPunchTable();
            }
        });

        // Fetch and render punchs for the selected employee
        async function renderPunchTable() {
            if (!employeeId) {
                $('#punch_table_body').html(
                    '<tr><td colspan="7" class="text-center">No Employee Selected</td></tr>');
                return;
            }

            let employees_punchs = await commonFetchData(`/company/employee_punch/${employeeId}`);
            let list = '';

            if (employees_punchs && employees_punchs.length > 0) {
                employees_punchs.forEach((item, i) => {
                    list += `
                <tr punch_id="${item.id}">
                    <td>${i + 1}</td>
                    <td>${item.name_with_initials}</td>
                    <td>${item.punch_type}</td>
                    <td>${item.punch_status}</td>
                    <td>${item.time_stamp}</td>
                    <td>${item.date}</td>
                    <td class="text-capitalize">${item.status === 'active'
                        ? `<span class="badge border border-success text-success">${item.status}</span>`
                        : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                    <td>
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-punch" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_punch" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
                    `;
                });
            } else {
                list = `<tr><td colspan="7" class="text-danger text-center">No Qualification Yet!</td></tr>`;
            }

            $('#punch_table_body').html(list);
        }

        //get dropdown data
        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/company/employee_punch/dropdown');

                // Populate employee name dropdown
                let employeeList = (dropdownData?.employees || [])
                    .map(employee => `<option value="${employee.id}">${employee.name_with_initials}</option>`)
                    .join('');
                $('#employee_id').html('<option value="">Select Employee Name</option>' + employeeList);


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
            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('click', '#add_new_punch_btn', function() {
            resetForm();
            title = `Add New Punch`;
            $('#punch-form-title').html(title);
            $('#punch-form-modal').modal('show');
        });


        //  click event
        $(document).on('click', '#punch-submit-confirm', async function() {
            const punch_id = $('#punch_id').val();
            const time = $('#time').val();
            const date = $('#date').val();
            const punch_type = $('#punch_type').val();
            const branch_id = $('#branch_id').val();
            const department_id = $('#department_id').val();
            const station = $('#station').val();
            const note = $('#note').val();
            const punch_status = $('#punch_status').val();
            const emp_punch_status = $('#emp_punch_status').val();

            const time_stamp = `${date}T${time}`; // Format: "2024-11-11T16:19"

            let createUrl = `/company/employee_punch/create`;
            let updateUrl = `/company/employee_punch/update/${punch_id}`;

            let formData = new FormData();

            if (!punch_type || !punch_status) {
                $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }

            formData.append('employee_id', employeeId);
            formData.append('punch_id', punch_id);
            formData.append('punch_type', punch_type);
            formData.append('punch_status', punch_status);
            formData.append('branch_id', branch_id);
            formData.append('department_id', department_id);
            formData.append('station', station);
            formData.append('note', note);
            formData.append('time_stamp', time_stamp);
            formData.append('emp_punch_status', emp_punch_status);
            formData.append('date', date);
            formData.append('time', time);

            const isUpdating = Boolean(punch_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    $('#punch-form-modal').modal('hide');
                    await renderPunchTable(); // Re-render table on success
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });
        $(document).on('click', '.click-edit-punch', async function() {
            // resetForm();
            let punch_id = $(this).closest('tr').attr('punch_id');


            // Get branch data by id
            try {
                let punch_data = await commonFetchData(
                    `/company/single_employee_punch/${punch_id}`);
                punch_data = punch_data[0];
                console.log('punch_data', punch_data);

                var datePart = punch_data.time_stamp.split(' ')[0]; // "2024-11-21"
                var timePart = punch_data.time_stamp.split(' ')[1];
                
                console.log('punch_data', datePart);
                // Set initial form values
                $('#punch_id').val(punch_id);
                $('#date').val(datePart || '');
                $('#time').val(timePart || '');
                $('#punch_type').val(punch_data?.punch_type || '');
                $('#punch_status').val(punch_data?.punch_status || '');
                $('#branch_id').val(punch_data?.branch_id || '');
                $('#department_id').val(punch_data?.department_id || '');
                $('#note').val(punch_data?.note || '');
                $('#emp_punch_status').val(punch_data?.status || '');
                // Load the country, province, and city accordingly


            } catch (error) {
                console.error('error at getQulificationById: ', error);
            } finally {
                title = `Edit Qualification`;
                $('#punch-form-title').html(title);
                $('#punch-form-modal').modal('show');
            }
        });
        $(document).on('click', '.click_delete_punch', async function() {
            let punch_id = $(this).closest('tr').attr('punch_id');

            try {
                let url = `/company/employee_punch/delete`;
                const res = await commonDeleteFunction(punch_id, url,
                    'Designation'); // Await the promise here

                if (res) {
                    await renderPunchTable();
                }
            } catch (error) {
                console.error(`Error during Qualification deletion:`, error);
            }
        })

        function resetForm() {
            $('#punch_id').val('');
            $('#punch').val('');
            $('#institute').val('');
            $('#year').val('');
            $('#remarks').val('');
            $('#punch_status').val('active'); // Reset status to default
            $('#error-msg').html(''); // Clear error messages
        }
    </script>



</x-app-layout>
