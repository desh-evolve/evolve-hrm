<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8{
            width: 8% !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Add Policy Group</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row border-end">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="employee_ids" class="form-label mb-1 col-md-3">Employees</label>
                                    <div class="col-md-9">
                                        <div class="ps-2" id="employeeContainer">
                                            {{-- render employees dynamically --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="over_time_policy_ids" class="form-label mb-1 col-md-3">Overtime Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="over_time_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="round_interval_policy_ids" class="form-label mb-1 col-md-3">Rounding Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="round_interval_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="meal_policy_ids" class="form-label mb-1 col-md-3">Meal Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="meal_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="break_policy_ids" class="form-label mb-1 col-md-3">Break Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="break_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="accrual_policy_ids" class="form-label mb-1 col-md-3">Accrual Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="accrual_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="premium_policy_ids" class="form-label mb-1 col-md-3">Premium Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="premium_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="holiday_policy_ids" class="form-label mb-1 col-md-3">Holiday Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="holiday_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="exception_policy_control_id" class="form-label mb-1 col-md-3">Exception Policy</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="exception_policy_control_id" >
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="policy_group_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- get data to dropdowns js -->
    <script>

        let dropdownData = [];

        $(document).ready(function () {
            getDropdownData();
            // Check if the `id` parameter is available in the query string
            <?php if (isset($_GET['id'])): ?>
                const id = <?= json_encode($_GET['id']); ?>; // Safely pass PHP variable to JavaScript
                getUpdateData(id);
            <?php endif; ?>
        });

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/policy_group/dropdown');

                // Helper function to populate dropdowns
                const populateDropdown = (selector, dataList, defaultOption = '--None--') => {
                    const options = (dataList || [])
                        .map(item => `<option value="${item.id}">${item.name}</option>`)
                        .join('');
                    $(selector).html(`<option value="0">${defaultOption}</option>` + options);
                };

                // Populate each dropdown using the helper function
                populateDropdown('#over_time_policy_ids', dropdownData?.overtime_policies);
                populateDropdown('#round_interval_policy_ids', dropdownData?.rounding_policies);
                populateDropdown('#meal_policy_ids', dropdownData?.meal_policies);
                populateDropdown('#break_policy_ids', dropdownData?.break_policies);
                populateDropdown('#accrual_policy_ids', dropdownData?.accrual_policies);
                populateDropdown('#premium_policy_ids', dropdownData?.premium_policies);
                populateDropdown('#holiday_policy_ids', dropdownData?.holiday_policies);
                populateDropdown('#exception_policy_control_id', dropdownData?.exception_policies);

                // Initialize the multiSelector for employees
                $('#employeeContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.employees || [],
                });

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        async function getUpdateData(id) {
            try {
                let response = await commonFetchData(`/policy/policy_group/${id}`);
                let data = response?.[0]; // Assuming the API returns an array with one item

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }
                
                //console.log('Fetched policy  group data:', data);

                // Set the name and status fields
                $('#name').val(data.name);
                $('#policy_group_id').val(data.id);

                // Set employee multiSelector values
                const employeeIds = data.employees.map(emp => emp.employee_id);
                
                // Initialize the multiSelector for employees
                $('#employeeContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.employees || [],
                    setSelected: employeeIds,
                });

                // Clear all dropdowns before populating to avoid duplication
                const dropdowns = [
                    '#over_time_policy_ids',
                    '#round_interval_policy_ids',
                    '#meal_policy_ids',
                    '#break_policy_ids',
                    '#accrual_policy_ids',
                    '#premium_policy_ids',
                    '#holiday_policy_ids',
                    '#exception_policy_control_id'
                ];
                dropdowns.forEach(selector => $(selector).val([]).trigger('change'));

                // Populate dropdowns based on policies
                data.policies.forEach(policy => {
                    let dropdownSelector = '';
                    switch (policy.policy_table) {
                        case 'over_time_policy':
                            dropdownSelector = '#over_time_policy_ids';
                            break;
                        case 'round_interval_policy':
                            dropdownSelector = '#round_interval_policy_ids';
                            break;
                        case 'meal_policy':
                            dropdownSelector = '#meal_policy_ids';
                            break;
                        case 'break_policy':
                            dropdownSelector = '#break_policy_ids';
                            break;
                        case 'accrual_policy':
                            dropdownSelector = '#accrual_policy_ids';
                            break;
                        case 'premium_policy':
                            dropdownSelector = '#premium_policy_ids';
                            break;
                        case 'holiday_policy':
                            dropdownSelector = '#holiday_policy_ids';
                            break;
                        case 'exception_policy':
                            dropdownSelector = '#exception_policy_control_id';
                            break;
                        default:
                            console.warn('Unknown policy_table:', policy.policy_table);
                            return;
                    }

                    // Add the policy_id to the appropriate dropdown
                    const currentValues = $(dropdownSelector).val() || [];
                    $(dropdownSelector)
                        .val([...currentValues, policy.policy_id])
                        .trigger('change');
                });

            } catch (error) {
                console.error('Error fetching policy group data:', error);
            }
        }

    </script>

    <!-- submit js -->
    <script>

        $(document).on('click', '#form_submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            let formData = await createFormData();

            let policy_group_id = $('#policy_group_id').val();
            let createUrl = `/policy/policy_group/create`;
            let updateUrl = `/policy/policy_group/update/${policy_group_id}`;

            const isUpdating = Boolean(policy_group_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('policy_group_id', policy_group_id);
                method = 'PUT';
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    window.location.href = '/policy/policy_group';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }

        });


        async function createFormData() {
            // Initialize a new FormData object
            let formData = new FormData();

            // Collect data from text inputs
            formData.append('name', $('#name').val());

            // Collect selected values from multi-select dropdowns
            formData.append('over_time_policy_ids', JSON.stringify($('#over_time_policy_ids').val() || []));
            formData.append('round_interval_policy_ids', JSON.stringify($('#round_interval_policy_ids').val() || []));
            formData.append('meal_policy_ids', JSON.stringify($('#meal_policy_ids').val() || []));
            formData.append('break_policy_ids', JSON.stringify($('#break_policy_ids').val() || []));
            formData.append('accrual_policy_ids', JSON.stringify($('#accrual_policy_ids').val() || []));
            formData.append('premium_policy_ids', JSON.stringify($('#premium_policy_ids').val() || []));
            formData.append('holiday_policy_ids', JSON.stringify($('#holiday_policy_ids').val() || []));
            formData.append('exception_policy_control_id', $('#exception_policy_control_id').val());

            
            // Collect selected employee IDs from the multiSelector component
            const selectedIds = $('#employeeContainer .selected-list option').map(function () {
                return $(this).val();
            }).get();
            formData.append('employee_ids', JSON.stringify(selectedIds));

            return formData;
        }

    </script>

</x-app-layout>