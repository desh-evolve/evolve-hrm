<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Wage') }}</h4>
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
                        <h5 class="mb-0">Employee Wage</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add-new-employee-wage-btn">New Wage Detail <i class="ri-add-line"></i></button>
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
                                <th class="col">Wage Group</th>
                                <th class="col">Wage Type</th>
                                <th class="col">Wage</th>
                                <th class="col">Budgetary Allowance</th>
                                <th class="col">Effective Date</th>
                                <th class="col">Weekly Time</th>
                                <th class="col">Hourly Rate</th>
                                <th class="col">Note</th>
                                <th class="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="employee-wage-table-body">
                            <tr>
                                <td colspan="7" class="text-center">Please Select a Employee ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="employee-wage-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="employee-wage-form-title">New Wage Detail</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="employee-wage-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="employee_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="employee_name" value="" disabled>
                        </div>
                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="wageGroupDropdown" class="form-label mb-1 req ">Wage Group</label>
                            <select class="form-select" id="wageGroupDropdown">
                                <option value="">Select Wage Group</option>
                            </select>
                        </div>
                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="wageTypeDropdown" class="form-label mb-1 req ">Wage Type</label>
                            <select class="form-select" id="wageTypeDropdown">
                                <option value="">Select Wage Type</option>
                            </select>
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="wage" class="form-label mb-1 req">Wage</label>
                            <input type="text" class="form-control" id="wage" rows="3">
                        </div>
                        {{-- <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="budgetary_allowance" class="form-label">Budgetary Allowance</label>
                            <input type="text" class="form-control" id="budgetary_allowance" rows="3">
                        </div> --}}
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="effective_date" class="form-label">Effective Date </label>
                            <input type="date" class="form-control" id="effective_date" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="weekly_time" class="form-label">Average Hours Per Week</label>
                            <input type="text" class="form-control" id="weekly_time" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate</label>
                            <input type="text" class="form-control" id="hourly_rate" rows="3" value=""
                                disabled>
                        </div>
                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="employee_wage_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="employee_wage_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-xxl-3 col-md-12 mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea type="text" class="form-control" id="note" rows="3"></textarea>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="wage_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary"
                                id="employee-wage-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let employeeId = '';
            let wageTypeId = '';

            $(document).ready(async function() {
                try {
                    let response = await commonFetchData('/company/employee_wage/dropdown');
                    console.log('response:', response);

                    if (response) {
                        // Populate Wage Group Dropdown
                        if (response.wageGroups && response.wageGroups.length > 0) {
                            $.each(response.wageGroups, function(index, wageGroup) {
                                $('#wageGroupDropdown').append(
                                    `<option value="${wageGroup.id}">${wageGroup.wage_group_name}</option>`
                                );
                            });
                        } else {
                            $('#wageGroupDropdown').append('<option>No Wage Groups Available</option>');
                        }

                        // Populate Wage Type Dropdown
                        if (response.wageTypes && response.wageTypes.length > 0) {
                            $.each(response.wageTypes, function(index, wageType) {
                                $('#wageTypeDropdown').append(
                                    `<option value="${wageType.id}">${wageType.name}</option>`
                                );
                            });
                        } else {
                            $('#wageTypeDropdown').append('<option>No Wage Types Available</option>');
                        }

                        // Populate Employee Dropdown
                        if (response.employees && response.employees.length > 0) {
                            let dropdown = $('#employeeDropdown');
                            dropdown.empty();
                            dropdown.append('<option value="">Select Employee</option>'); // Add a default option

                            // Loop through the employees and add options
                            response.employees.forEach(employee => {
                                let option =
                                    `<option value="${employee.id}">${employee.first_name} ${employee.last_name}</option>`;
                                dropdown.append(option);
                            });
                        } else {
                            console.log('No employees found');
                            $('#employeeDropdown').append('<option>No Employees Found</option>');
                        }

                        // Bind change event to the employee dropdown
                        $('#employeeDropdown').on('change', async function() {

                            employeeId = $(this).val(); // Get selected employee ID
                            if (employeeId) {

                                let employeeName = $('#employeeDropdown option:selected').text();
                                $('#employee_name').val(employeeName);
                                await renderEmployeeWageTable(employeeId);
                            } else {
                                console.log('No employee selected');
                            }
                        });
                        $('#weekly_time').parent().hide();
                        $('#hourly_rate').parent().hide();

                        // Bind change event to the employee dropdown
                        $('#wageTypeDropdown').on('change', async function() {
                            let wageTypeId = $(this).val(); // Get selected employee ID
                            if (wageTypeId) {
                                let selectedWageType = response.wageTypes.find(wageType => wageType
                                    .id == wageTypeId);

                                if (selectedWageType) {
                                    const showFields = ["weekly", "bi-weekly", "monthly", "annual"];
                                    if (showFields.includes(selectedWageType.wage_type)) {
                                        $('#weekly_time').parent().show();
                                        $('#hourly_rate').parent().show();
                                    } else {
                                        $('#weekly_time').parent().hide();
                                        $('#hourly_rate').parent().hide();
                                    }

                                    let wages_per_year = selectedWageType.wages_per_year;
                                    let number_of_weeks = selectedWageType.number_of_weeks;

                                    calculateHourlyRate(wages_per_year, number_of_weeks);
                                }

                            } else {
                                console.log('No employee selected');
                            }
                        });

                    } else {
                        console.log('Error: response data structure is unexpected:', response);
                    }

                } catch (error) {
                    console.log('Error fetching dropdown data:', error);
                }
            });

            // Your calculateHourlyRate function
            function calculateHourlyRate(wagesPerYear, numberOfWeeks) {
                $('#wage, #weekly_time').on('input', function() {
                    let wage = parseFloat($('#wage').val()); // Get the value from the 'wage' input
                    let weeklyTime = parseFloat($('#weekly_time')
                        .val()); // Get the value from the 'wage' input and convert to float

                    if (!isNaN(wage) && !isNaN(weeklyTime)) {
                        // Perform the calculation for annual wage
                        let annualWage = wage * wagesPerYear;
                        let annualHourse = weeklyTime * numberOfWeeks * wagesPerYear;
                        let hourlyRate = annualWage / annualHourse;

                        $('#hourly_rate').val(hourlyRate); // Assuming you have an input with id 'calculatedSalary'
                    } else {
                        console.log('Invalid wage input');
                    }
                });
            }

            // Fetch and render employees_wage for the selected employee
            async function renderEmployeeWageTable(employeeId) {
                if (!employeeId) {
                    $('#employee-wage-table-body').html(
                        '<tr><td colspan="7" class="text-center">No Employee Selected</td></tr>');
                    return;
                }

                let employees_wage = await commonFetchData(`/company/employee_wage/${employeeId}`);
                let list = '';

                if (employees_wage && employees_wage.length > 0) {
                    employees_wage.forEach((item, i) => {
                        list += `
                <tr wage_id="${item.id}">
                    <td>${i + 1}</td>
                    <td>${item.wage_group_name}</td>
                    <td>${item.wage_type_name}</td>
                    <td>${item.wage}</td>
                    <td>${item.budgetary_allowance}</td>
                    <td>${item.effective_date}</td>
                    <td>${item.weekly_time}</td>
                    <td>${item.hourly_rate}</td>
                    <td>${item.note}</td>
                    <td class="text-capitalize">${item.status === 'active' 
                        ? `<span class="badge border border-success text-success">${item.status}</span>` 
                        : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                    <td>
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-employee-wage" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click-delete-employee-wage" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
                    `;
                    });
                } else {
                    list = `<tr><td colspan="7" class="text-danger text-center">No Employee Wage Yet!</td></tr>`;
                }

                $('#employee-wage-table-body').html(list);
            }

            $(document).on('click', '#add-new-employee-wage-btn', function() {
                resetForm();
                title = `Add New Employee Wage`;
                $('#employee-wage-form-title').html(title);
                $('#employee-wage-form-modal').modal('show');
            });

            $(document).on('click', '#employee-wage-submit-confirm', async function() {
                const wage_id = $('#wage_id').val();
                const wage_group_id = $('#wageGroupDropdown').val();;
                const wage_type_id = $('#wageTypeDropdown').val();
                const wage = $('#wage').val();
                // const budgetary_allowance = $('#budgetary_allowance').val();
                const effective_date = $('#effective_date').val();
                const weekly_time = $('#weekly_time').val();
                const hourly_rate = $('#hourly_rate').val();
                const note = $('#note').val();
                const employee_wage_status = $('#employee_wage_status').val();

                let createUrl = `/company/employee_wage/create`;
                let updateUrl = `/company/employee_wage/update/${wage_id}`;

                let formData = new FormData();

                if (!wage_group_id || !wage_type_id || !wage || !employeeId) {

                    $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                    return;
                } else {
                    $('#error-msg').html(''); // Clear error message if no issues
                }

                formData.append('employee_id', employeeId);
                formData.append('wage_group_id', wage_group_id);
                formData.append('wage_type_id', wage_type_id);
                formData.append('wage', wage);
                // formData.append('budgetary_allowance', budgetary_allowance);
                formData.append('effective_date', effective_date);
                formData.append('weekly_time', weekly_time);
                formData.append('hourly_rate', hourly_rate);
                formData.append('note', note);
                formData.append('employee_wage_status', employee_wage_status);

                const isUpdating = Boolean(wage_id);
                let url = isUpdating ? updateUrl : createUrl;
                let method = isUpdating ? 'PUT' : 'POST';

                try {
                    let res = await commonSaveData(url, formData, method);
                    await commonAlert(res.status, res.message);

                    if (res.status === 'success') {
                        $('#employee-wage-form-modal').modal('hide');
                        await renderEmployeeWageTable(employeeId); // Re-render table on success
                    }
                } catch (error) {
                    console.error('Error:', error);
                    $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
                }
            });

            // edit click event
            $(document).on('click', '.click-edit-employee-wage', async function() {
                // resetForm();
                let wage_id = $(this).closest('tr').attr('wage_id');
                // Get branch data by id
                try {
                    let wage_data = await commonFetchData(
                        `/company/single_employee_wage/${wage_id}`);
                    wage_data = wage_data[0];

                    // Set initial form values
                    $('#wage_id').val(wage_id);
                    $('#wageGroupDropdown').val(wage_data?.wage_group_id || '');
                    $('#wageTypeDropdown').val(wage_data?.wage_type_id || '');
                    $('#wage').val(wage_data?.wage || '');
                    $('#effective_date').val(wage_data?.effective_date || '');
                    $('#weekly_time').val(wage_data?.weekly_time || '');
                    $('#hourly_rate').val(wage_data?.hourly_rate || '');
                    $('#note').val(wage_data?.note || '');
                    $('#employee_wage_status').val(wage_data?.status || '');
                    // Load the country, province, and city accordingly
                   
                    
                    const showFields = [2, 3, 4, 5];
                    if (showFields.includes(wage_data.wage_type_id)) {
                        $('#weekly_time').parent().show();
                        $('#hourly_rate').parent().show();
                    } else {
                        $('#weekly_time').parent().hide();
                        $('#hourly_rate').parent().hide();
                    }
                } catch (error) {
                    console.error('error at getWorkExperienceById: ', error);
                } finally {
                    title = `Edit Employee Wage`;
                    $('#employee-wage-form-title').html(title);
                    $('#employee-wage-form-modal').modal('show');
                }
            });

            $(document).on('click', '.click-delete-employee-wage', async function() {
                let wage_id = $(this).closest('tr').attr('wage_id');

                try {
                    let url = `/company/employee_wage/delete`;
                    const res = await commonDeleteFunction(wage_id, url,
                        'Employee Wage'); // Await the promise here

                    if (res) {
                        await renderEmployeeWageTable(employeeId); 
                    }
                } catch (error) {
                    console.error(`Error during Employee Wage deletion:`, error);
                }
            })

            function resetForm() {
                $('#wage_id').val('');
                $('#wageGroupDropdown').val('');
                $('#wageTypeDropdown').val('');
                $('#wage').val('');
                // $('#budgetary_allowance').val('');
                $('#effective_date').val('');
                $('#weekly_time').val('40');
                $('#hourly_rate').val('');
                $('#note').val('');
                $('#employee_wage_status').val('active'); // Reset status to default
                $('#error-msg').html(''); // Clear error messages
            }
        </script>
</x-app-layout>
