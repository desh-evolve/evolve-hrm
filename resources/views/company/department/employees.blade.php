<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">
   
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Department Employees') }}</h4>
    </x-slot>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card info-card" style="display: none">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_back_btn"><i class="ri-arrow-left-line"></i></button>
                        <h5 class="mb-0 ms-4" id="dep_emp_page_title">Department Employees</h5>
                    </div>
                </div>
                <div class="card-body row" id="emp_list">
                    <!-- content rendered here -->
                </div>
            </div>
            <div class="card loading-card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <h4>Loading...</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="employee-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="employee-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="employee-form-body" class="row">
                        <div class="col-xxl-12 col-md-12 mb-3">
                            <label for="employeesList" class="form-label mb-1">Select Department Branch Employees</label>
                            <select class="select2-multiple" id="employeesList" name="employeesList[]" multiple="multiple">
                                
                            </select>
                        </div>                   
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="branch_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="employee-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get the department_id from the URL query parameter
        let urlParams = new URLSearchParams(window.location.search);
        let department_id = urlParams.get('dep_id');
        let department_data = null;
        let dropdownData = [];

        $(document).ready(function(){
            if (department_id) {
                getDropdownData();
                getDepartmentById(department_id);
            } else {
                console.error('No department ID found in the URL');
            }
        });

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/company/department/employees/dropdown');

                // Generate the dropdown list, marking any employees from this branch as selected
                const employeesList = (dropdownData?.employees || [])
                    .map(emp => `<option value="${emp.id}">[${emp.id}] ${emp.first_name} ${emp.last_name}</option>`)
                    .join('') || '<option>No employees available</option>';

                // Update dropdown HTML and initialize select2
                $('#employeesList').html(employeesList);
                $(".select2-multiple").select2();
            } catch (error) {
                console.error("Error fetching dropdown data:", error);
            }
        }

        async function getDepartmentById(department_id) {
            $('.info-card').hide();
            $('.loading-card').show();
            try {
                let list = ``;
                let res = await commonFetchData(`/company/department/${department_id}`);
                department_data = res[0];
                
                $('#dep_emp_page_title').html(`Employees of ${department_data.department_name} Department`);

                if (department_data?.branch_departments && department_data.branch_departments.length > 0) {
                    for (const branch of department_data.branch_departments) {
                        list += `
                            <div class="col-xxl-4 col-md-6  col-sm-12">
                                <div class="card card-height-100 border card-border-primary" branch_id="${branch.branch_id}">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Branch - ${branch.branch_name}</h4>
                                        <div class="flex-shrink-0">
                                            <div class="dropdown card-header-dropdown">
                                                <button type="button" class="btn btn-sm btn-success add_employees_btn"><i class="ri-add-line align-middle me-1"></i> Add Employees</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body p-0">
                                        <ul class="list-group">
                        `;

                        let branch_employees = await commonFetchData(`/company/${branch.branch_id}/department/${department_id}/employees`);
                        branch['employees'] = branch_employees; //add to department data array

                        if(branch_employees && branch_employees.length > 0){
                            branch_employees.map(emp => {
                                list += `
                                    <li class="list-group-item d-flex justify-content-between" employee_id="${emp.employee_id}">
                                        [${emp.employee_id}] ${emp.first_name} ${emp.last_name}
                                        <p class="cursor-pointer text-danger mb-0 remove_employees_btn">remove</p>
                                    </li>
                                `;
                            })
                        }
                                
                                list += `
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        `;

                    }
                }

                $('#emp_list').html(list);

            } catch (error) {
                console.error('error fetching department by id: ', error);
            } finally {
                $('.info-card').show();
                $('.loading-card').hide();
            }
        }

        $(document).on('click', '#click_back_btn', function(){
            window.history.back();
        })

        $(document).on('click', '.remove_employees_btn', async function(){
            let branch_id = $(this).closest('.card').attr('branch_id');
            let employee_id = $(this).closest('.list-group-item').attr('employee_id');

            try {
                let url = `/company/department/employee/delete/${department_id}/${branch_id}/${employee_id}`;
                const res = await commonDeleteFunction(null, url, 'Department Employee');  // Await the promise here

                if (res) {
                    await getDepartmentById(department_id);
                }
            } catch (error) {
                console.error(`Error during branch deletion:`, error);
            }
        })

        $(document).on('click', '.add_employees_btn', async function(){
            let branch_id = $(this).closest('.card').attr('branch_id');
            $('#branch_id').val(branch_id);

            //==============================================================
            // Additional select2 code to handle selected and disabled options
            //==============================================================
            
            // Find the branch department with the matching branch_id
            let branch = department_data?.branch_departments.find(branch => branch.branch_id === parseInt(branch_id));
            
            // If the branch is found, retrieve its employees; otherwise, use an empty array
            let employees = branch ? branch.employees : [];
            let employeesArr = employees.map(e => e.employee_id.toString()) || [];

            // Reset previously disabled options
            $('#employeesList option').prop('disabled', false);

            // Pre-select the options corresponding to `branches`
            $('#employeesList').val(employeesArr).trigger('change');

            // Disable the options already selected in `branches`
            employeesArr.forEach(employeeId => {
                //$('#employeesList option[value="' + employeeId + '"]').prop('disabled', true); //when we disable it can't get value to send by formData. do sth else here
            });

            // Reinitialize select2 to reflect changes in disabled options
            $(".select2-multiple").select2();

            //==============================================================

            $('#employee-form-modal').modal('show');
        });

        $(document).on('click', '#employee-submit-confirm', async function(){
            let branch_id = $('#branch_id').val();
            let employees = $('#employeesList').val();

            if (!employees || employees.length === 0) {
                $('#error-msg').html(`<p class="text-danger">No Employees Selected!</p>`);
                return;
            }

            let url = `/company/department/employees/create`;
            
            let formData = new FormData();

            // Append form data
            formData.append('branch_id', branch_id);
            formData.append('department_id', department_id);
            formData.append('employees', employees);

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, 'POST');
                
                if (res && res.status === 'success') {
                    await commonAlert(res.status, res.message);
                    getDepartmentById(department_id)
                    $('#employee-form-modal').modal('hide');
                } else {
                    // Handle possible failure scenarios
                    let errorMessage = res && res.message ? res.message : 'An unexpected error occurred.';
                    $('#error-msg').html('<p class="text-danger">' + errorMessage + '</p>');
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }

        })


        

    </script>

</x-app-layout>