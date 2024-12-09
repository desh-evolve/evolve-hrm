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
                                <label for="start_date" class="form-label mb-1 col-md-3">Start Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control w-50" id="start_date" >
                                    <span class="ps-4">ie: 25/02/2001 (Leave blank for no start date)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="end_date" class="form-label mb-1 col-md-3">End Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control w-50" id="end_date" >
                                    <span class="ps-4">ie: 25/02/2001 (Leave blank for no end date)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="start_time" class="form-label mb-1 col-md-3">Start Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="start_time" >
                                    <span class="ps-4">ie: 20:09 (Leave blank for no start time)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="end_time" class="form-label mb-1 col-md-3">End Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="end_time" >
                                    <span class="ps-4">ie: 20:09 (Leave blank for no end time)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="include_partial_punch" class="form-label mb-1 col-md-3">Include Partial Punches</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="include_partial_punch">
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="daily_trigger_time" class="form-label mb-1 col-md-3">Active After Daily (Regular) Hours</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="daily_trigger_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="weekly_trigger_time" class="form-label mb-1 col-md-3">Active After Weekly (Regular) Hours</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="weekly_trigger_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>

                            <div class="row mb-3" id="apply_frequency_section">
                                <label for="apply_frequency" class="form-label mb-1 col-md-3">Effective Days</label>
                                <div class="col-md-9 row">
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="sun" class="form-label m-0">Sun</label>
                                        <input type="checkbox" class="form-check-input" id="sun">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="mon" class="form-label m-0">Mon</label>
                                        <input type="checkbox" class="form-check-input" id="mon">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="tue" class="form-label m-0">Tue</label>
                                        <input type="checkbox" class="form-check-input" id="tue">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="wed" class="form-label m-0">Wed</label>
                                        <input type="checkbox" class="form-check-input" id="wed">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="thu" class="form-label m-0">Thu</label>
                                        <input type="checkbox" class="form-check-input" id="thu">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="fri" class="form-label m-0">Fri</label>
                                        <input type="checkbox" class="form-check-input" id="fri">
                                    </div>
                                    <div class="col-md-1 d-flex flex-column">
                                        <label for="sat" class="form-label m-0">Sat</label>
                                        <input type="checkbox" class="form-check-input" id="sat">
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
                                <label for="daily_trigger_time2" class="form-label mb-1 col-md-3">Active After Daily Hours</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="daily_trigger_time2" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="maximum_no_break_time" class="form-label mb-1 col-md-3">Maximum Time Without A Break</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="maximum_no_break_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_break_time" class="form-label mb-1 col-md-3">Minimum Time Recognized As Break</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_break_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                        </div>

                        <div id="callback_section">
                            <u><h5 class="bg-primary text-white">Callback Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_time_between_shift" class="form-label mb-1 col-md-3">Minimum Time Between Shifts</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_time_between_shift" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_first_shift_time" class="form-label mb-1 col-md-3">First Shift Must Be At Least</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_first_shift_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                        </div>

                        <div id="min_shift_section">
                            <u><h5 class="bg-primary text-white">Minimum Shift Time Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_shift_time" class="form-label mb-1 col-md-3">Minimum Shift Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_shift_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div id="hours_section">
                            <u><h5 class="bg-primary text-white">Hours/Pay Criteria</h5></u>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_time" class="form-label mb-1 col-md-3">Minimum Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="minimum_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15) (Use 0 for no minimum)</span>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="maximum_time" class="form-label mb-1 col-md-3">Maximum Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="maximum_time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15) (Use 0 for no maximum)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="include_meal_policy" class="form-label mb-1 col-md-3">Include Meal Policy in Calculation</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="include_meal_policy">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="include_break_policy" class="form-label mb-1 col-md-3">Include Break Policy in Calculation</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="include_break_policy">
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
                                    <input type="text" class="form-control numonly w-50" id="rate" value="1.00">
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
                            <input type="hidden" id="premium_policy_id" value="" />
                            <button type="button" id="form_submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(function(){
            getDropdownData();
            showSections('date_time');

            <?php if (isset($_GET['id'])): ?>
                const id = <?= json_encode($_GET['id']); ?>; // Safely pass PHP variable to JavaScript
                getUpdateData(id);
            <?php endif; ?>
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

        async function getUpdateData(id) {
            try {
                // Fetch policy data
                let response = await commonFetchData(`/policy/premium/${id}`);
                let data = response?.[0]; // Correctly accessing the data array

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                console.log('Fetched premium policy data:', data);
                $('#premium_policy_id').val(data.id); // For update, set the policy ID

                // Checkbox values (convert to 1/0)
                $('#include_partial_punch').prop('checked', data.include_partial_punch == 1 ? true : false);
                $('#include_meal_policy').prop('checked', data.include_meal_policy == 1 ? true : false);
                $('#include_break_policy').prop('checked', data.include_break_policy == 1 ? true : false);

                $('#start_time').val(convertSecondsToHoursAndMinutes(data.start_time || 0));
                $('#end_time').val(convertSecondsToHoursAndMinutes(data.end_time || 0));
                $('#daily_trigger_time').val(convertSecondsToHoursAndMinutes(data.daily_trigger_time || 0));
                $('#weekly_trigger_time').val(convertSecondsToHoursAndMinutes(data.weekly_trigger_time || 0));
                $('#minimum_time').val(convertSecondsToHoursAndMinutes(data.minimum_time || 0));
                $('#maximum_time').val(convertSecondsToHoursAndMinutes(data.maximum_time || 0));
                $('#daily_trigger_time2').val(convertSecondsToHoursAndMinutes(data.daily_trigger_time2 || 0));
                $('#maximum_no_break_time').val(convertSecondsToHoursAndMinutes(data.maximum_no_break_time || 0));
                $('#minimum_break_time').val(convertSecondsToHoursAndMinutes(data.minimum_break_time || 0));
                $('#minimum_time_between_shift').val(convertSecondsToHoursAndMinutes(data.minimum_time_between_shift || 0));
                $('#minimum_first_shift_time').val(convertSecondsToHoursAndMinutes(data.minimum_first_shift_time || 0));
                $('#minimum_shift_time').val(convertSecondsToHoursAndMinutes(data.minimum_shift_time || 0));

                // Set branch multiSelector values
                const branchIds = data.branches.map(branch => branch.branch_id);

                $('#branchContainer').multiSelector({
                    title: 'Branches',
                    data: dropdownData?.branches || [],
                    setSelected: branchIds,
                });

                // Set department multiSelector values
                const departmentIds = data.departments.map(department => department.department_id);

                $('#departmentContainer').multiSelector({
                    title: 'Departments',
                    data: dropdownData?.departments || [],
                    setSelected: departmentIds,
                });

                // Append form fields
                $('#name').val(data.name);
                $('#type').val(data.type).trigger('change');
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);

                // Weekly trigger values
                $('#sun').prop('checked', data.sun == 1 ? true : false);
                $('#mon').prop('checked', data.mon == 1 ? true : false);
                $('#tue').prop('checked', data.tue == 1 ? true : false);
                $('#wed').prop('checked', data.wed == 1 ? true : false);
                $('#thu').prop('checked', data.thu == 1 ? true : false);
                $('#fri').prop('checked', data.fri == 1 ? true : false);
                $('#sat').prop('checked', data.sat == 1 ? true : false);

                // Additional fields
                $('#pay_type').val(data.pay_type).trigger('change');
                $('#rate').val(data.rate);
                $('#wage_group_id').val(data.wage_group_id || 0).trigger('change');
                $('#pay_stub_entry_account_id').val(data.pay_stub_entry_account_id || 0).trigger('change');
                $('#accrual_policy_id').val(data.accrual_policy_id || 0).trigger('change');

            } catch (error) {
                console.error('Error fetching premium policy data:', error);
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
            
        }

        function renderDepartments(departments){
            $('#departmentContainer').multiSelector({
                title: 'Branches',
                data: departments,
                onSelectionChange: function (selectedIds) {
                    //console.log("Selected IDs:", selectedIds);
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

        $(document).on('click', '#form_submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let premium_policy_id = $('#premium_policy_id').val(); // For update, set the policy ID

            // Checkbox values (convert to 1/0)
            let includePartialPunch = $('#include_partial_punch').is(':checked') ? 1 : 0;
            let includeMealPolicy = $('#include_meal_policy').is(':checked') ? 1 : 0;
            let includeBreakPolicy = $('#include_break_policy').is(':checked') ? 1 : 0;

            let start_time = convertHoursAndMinutesToSeconds($('#start_time').val() || '00:00');
            let end_time = convertHoursAndMinutesToSeconds($('#end_time').val() || '00:00');
            let daily_trigger_time = convertHoursAndMinutesToSeconds($('#daily_trigger_time').val() || '00:00');
            let weekly_trigger_time = convertHoursAndMinutesToSeconds($('#weekly_trigger_time').val() || '00:00');
            let minimum_time = convertHoursAndMinutesToSeconds($('#minimum_time').val() || '00:00');
            let maximum_time = convertHoursAndMinutesToSeconds($('#maximum_time').val() || '00:00');
            let daily_trigger_time2 = convertHoursAndMinutesToSeconds($('#daily_trigger_time2').val() || '00:00');
            let maximum_no_break_time = convertHoursAndMinutesToSeconds($('#maximum_no_break_time').val() || '00:00');
            let minimum_break_time = convertHoursAndMinutesToSeconds($('#minimum_break_time').val() || '00:00');
            let minimum_time_between_shift = convertHoursAndMinutesToSeconds($('#minimum_time_between_shift').val() || '00:00');
            let minimum_first_shift_time = convertHoursAndMinutesToSeconds($('#minimum_first_shift_time').val() || '00:00');
            let minimum_shift_time = convertHoursAndMinutesToSeconds($('#minimum_shift_time').val() || '00:00');

            // Differential details
            let branches = $('#branchContainer .selected-list option').map(function () {
                return $(this).val();
            }).get();

            let departments = $('#departmentContainer .selected-list option').map(function () {
                return $(this).val();
            }).get();

            formData.append('branches', JSON.stringify(branches));
            formData.append('departments', JSON.stringify(departments));

            // Append form fields
            formData.append('name', $('#name').val());
            formData.append('type', $('#type').val());
            formData.append('start_date', $('#start_date').val());
            formData.append('end_date', $('#end_date').val());
            formData.append('start_time', start_time);
            formData.append('end_time', end_time);
            formData.append('include_partial_punch', includePartialPunch);
            formData.append('daily_trigger_time', daily_trigger_time);
            formData.append('weekly_trigger_time', weekly_trigger_time);

            // Weekly trigger values
            formData.append('sun', $('#sun').is(':checked') ? 1 : 0);
            formData.append('mon', $('#mon').is(':checked') ? 1 : 0);
            formData.append('tue', $('#tue').is(':checked') ? 1 : 0);
            formData.append('wed', $('#wed').is(':checked') ? 1 : 0);
            formData.append('thu', $('#thu').is(':checked') ? 1 : 0);
            formData.append('fri', $('#fri').is(':checked') ? 1 : 0);
            formData.append('sat', $('#sat').is(':checked') ? 1 : 0);

            // Additional fields
            formData.append('daily_trigger_time2', daily_trigger_time2);
            formData.append('maximum_no_break_time', maximum_no_break_time);
            formData.append('minimum_break_time', minimum_break_time);
            formData.append('minimum_time_between_shift', minimum_time_between_shift);
            formData.append('minimum_first_shift_time', minimum_first_shift_time);
            formData.append('minimum_shift_time', minimum_shift_time);
            formData.append('minimum_time', minimum_time);
            formData.append('maximum_time', maximum_time);
            formData.append('include_meal_policy', includeMealPolicy);
            formData.append('include_break_policy', includeBreakPolicy);
            formData.append('pay_type', $('#pay_type').val());
            formData.append('rate', $('#rate').val() || '1.00');
            formData.append('wage_group_id', $('#wage_group_id').val());
            formData.append('pay_stub_entry_account_id', $('#pay_stub_entry_account_id').val());
            formData.append('accrual_policy_id', $('#accrual_policy_id').val());
            formData.append('premium_policy_id', $('#premium_policy_id').val());

            // Determine create or update URL
            let createUrl = `/policy/premium/create`;
            let updateUrl = `/policy/premium/update/${premium_policy_id}`;
            const isUpdating = Boolean(premium_policy_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', premium_policy_id);
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    //window.location.href = '/policy/premium';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


    </script>

</x-app-layout>