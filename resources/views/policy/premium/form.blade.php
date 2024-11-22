<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Add Premium Policy</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="name" placeholder="Enter Name" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="type">
                                        <option value="date_time">Date/Time</option>
                                        <option value="shift_differential">Shift Differential</option>
                                        <option value="meal_break">Meal/Break</option>
                                        <option value="callback">Callback</option>
                                        <option value="minimum_shift_time">Minimum Shift Time</option>
                                        <option value="holiday">Holiday</option>
                                        <option value="advanced">Advanced</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="date_time_section">
                            <u><h5 class="bg-primary text-white">Date/Time Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Start Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control w-50" id="minimum_employed_days" >
                                    <span class="ps-4">ie: 25/02/2001 (Leave blank for no start date)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">End Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control w-50" id="minimum_employed_days" >
                                    <span class="ps-4">ie: 25/02/2001 (Leave blank for no end date)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Start Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" >
                                    <span class="ps-4">ie: 20:09 (Leave blank for no start time)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">End Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" >
                                    <span class="ps-4">ie: 20:09 (Leave blank for no end time)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="enable_pay_stub_balance_display" class="form-label mb-1 col-md-3">Include Partial Punches</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Active After Daily (Regular) Hours</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Active After Weekly (Regular) Hours</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>

                            <div class="row mb-3" id="apply_frequency_section">
                                <label for="apply_frequency" class="form-label mb-1 col-md-3">Effective Days</label>
                                <div class="col-md-9 row">
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Sun</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Mon</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Tue</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Wed</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Thu</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Fri</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="enable_pay_stub_balance_display" class="form-label m-0">Sat</label>
                                        <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div id="differential_section">
                            <u><h5 class="bg-primary text-white">Differential Criteria</h5></u>
                            <div class="row mb-3">
                                <label for="branch_id" class="form-label mb-1 col-md-3">Branches</label>
                                <div class="col-md-9" id="branchContainer">
                                    {{-- render branches dynamically --}}
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <label for="department_id" class="form-label mb-1 col-md-3">Departments</label>
                                <div class="col-md-9" id="departmentContainer">
                                    {{-- render department dynamically --}}
                                </div>
                            </div>
                        </div>

                        <div id="meal_section">
                            <u><h5 class="bg-primary text-white">Meal/Break Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Active After Daily Hours</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Maximum Time Without A Break</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Time Recognized As Break</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                        </div>

                        <div id="callback_section">
                            <u><h5 class="bg-primary text-white">Callback Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Time Between Shifts</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">First Shift Must Be At Least</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                        </div>

                        <div id="min_shift_section">
                            <u><h5 class="bg-primary text-white">Minimum Shift Time Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Shift Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div id="hours_section">
                            <u><h5 class="bg-primary text-white">Hours/Pay Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15) (Use 0 for no minimum)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Maximum Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15) (Use 0 for no maximum)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="milestone_rollover_hire_date" class="form-label mb-1 col-md-3">Include Meal Policy in Calculation</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="milestone_rollover_hire_date" class="form-label mb-1 col-md-3">Include Break Policy in Calculation</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="pay_type" class="form-label mb-1 col-md-3">Pay Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="pay_type">
                                        <option value="pay_multiplied">Pay Multiplied By Factor</option>
                                        <option value="pay_plus_premium">Pay + Premium</option>
                                        <option value="flat_hourly_rate">Flat Hourly Rate (Relative to Wage)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="rate" class="form-label mb-1 col-md-3">Rate</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="rate" value="1:00">
                                    <span class="ps-4">(ie: 1.5 for time and a half)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="wage_group_id" class="form-label mb-1 col-md-3">Wage Group</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="wage_group_id">
                                        <!-- Add options dynamically -->
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="pay_stub_entry_account_id" class="form-label mb-1 col-md-3">Pay Stub Account</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="pay_stub_entry_account_id">
                                        <!-- Add options dynamically -->
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="accrual_policy_id" class="form-label mb-1 col-md-3">Accrual Policy</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="accrual_policy_id">
                                        <!-- Add options dynamically -->
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            getDropdownData();
            showSections('date_time');
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/premium/dropdown');

                // Populate wage groups dropdown
                let wageGroupsList = (dropdownData?.wage_groups || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#wage_group_id').html('<option value="0">--Default--</option>' + wageGroupsList);

                // Populate pay stub accounts dropdown
                let payStubsList = (dropdownData?.pay_stubs || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#pay_stub_entry_account_id').html('<option value="0">--</option>' + payStubsList);

                // Populate accrual policies dropdown
                let accrualPolicies = (dropdownData?.accrual_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#accrual_policy_id').html('<option value="0">--None--</option>' + accrualPolicies);

                // Populate branches and departments multiselect
                renderBranches(dropdownData?.branches || [], dropdownData?.departments || [], dropdownData?.br_deps || []);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        function renderBranches(branches, departments, br_deps) {
            // Set an initial message in the department container
            $('#departmentContainer').html('<p class="text-danger">Select Branches First</p>');

            // Initialize the multiSelector for branches (multiselector is in components->hrm->multiselector.blade.php)
            $('#branchContainer').multiSelector({
                title: 'Branches',
                data: branches, // Array of branch objects
                onSelectionChange: function (selectedIds) {
                    if (selectedIds.length === 0) {
                        // If no branches are selected, show the message
                        $('#departmentContainer').html('<p class="text-danger">Select Branches First</p>');
                        return;
                    }

                    // Convert `selectedIds` to integers for comparison
                    const selectedBranchIds = selectedIds.map(id => parseInt(id));

                    // Filter departments based on the selected branch IDs using br_deps
                    const branchDepartments = br_deps
                        .filter(br_dep => selectedBranchIds.includes(br_dep.branch_id)) // Match branch_id with selectedBranchIds
                        .map(br_dep => br_dep.department_id); // Get all matching department IDs

                    // Get unique departments corresponding to these branch IDs
                    const filteredDepartments = departments.filter(dep => branchDepartments.includes(dep.id));

                    // Call renderDepartments to display the filtered departments
                    renderDepartments(filteredDepartments);
                }
            }); 
            
            /*
            $('#getSelected').on('click', function () {
                const selectedIds = $('#multiSelectorContainer .selected-list option').map(function () {
                    return $(this).val();
                }).get();
                alert("Selected IDs: " + selectedIds.join(', '));
            });
            */
        }

        function renderDepartments(departments){
            $('#departmentContainer').multiSelector({
                title: 'Branches',
                data: departments,
                onSelectionChange: function (selectedIds) {
                    console.log("Selected IDs:", selectedIds);
                }
            });
        }

        $(document).on('change', '#type', function () {
            let type = $(this).val();
            showSections(type);
        });

        function showSections(type){
            // Hide all sections initially
            const sections = ['#date_time_section', '#differential_section', '#meal_section', '#callback_section', '#min_shift_section', '#hours_section'];
            sections.forEach(section => $(section).hide());
            
            // Determine which sections to show based on the selected type
            const typeMap = {
                'date_time': ['#date_time_section', '#hours_section'],
                'shift_differential': ['#differential_section', '#hours_section'],
                'meal_break': ['#meal_section', '#hours_section'],
                'callback': ['#callback_section', '#hours_section'],
                'minimum_shift_time': ['#min_shift_section', '#hours_section'],
                'holiday': ['#hours_section'],
                'advanced': ['#date_time_section', '#differential_section', '#hours_section']
            };
            
            if (typeMap[type]) {
                typeMap[type].forEach(section => $(section).show());
            } else {
                console.error('Wrong type selected');
            }
        }

        $(document).on('change', '#pay_type', function(){
            let pay_type = $(this).val();

            if(pay_type === 'pay_multiplied'){
                $('#rate').closest('.row').find('label').text('Rate');
                $('#rate').closest('.row').find('span').text('(ie: 1.5 for time and a half)');
                $('#wage_group_id').closest('.row').show();
            }else if(pay_type === 'pay_plus_premium'){
                $('#rate').closest('.row').find('label').text('Premium');
                $('#rate').closest('.row').find('span').text('(ie: 0.75 for 75 cents/hr)');
                $('#wage_group_id').closest('.row').hide();
            }else if(pay_type === 'flat_hourly_rate'){
                $('#rate').closest('.row').find('label').text('Hourly Rate');
                $('#rate').closest('.row').find('span').text('(ie: 10.00/hr)');
                $('#wage_group_id').closest('.row').show();
            }else{
                console.error('Wrong pay type selected');
            }
        })

    </script>

</x-app-layout>