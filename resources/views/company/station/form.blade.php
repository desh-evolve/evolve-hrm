<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8 {
            width: 8% !important;
        }

        .custom-width {
            width: 50%;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Edit Station</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="company_deduction_status" class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="company_deduction_status" style="width: 50%;">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="type" style="width: 50%;">
                                        <option value="1">PC</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="type">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <label for="station_id" class="form-label mb-1 col-md-3">Station ID</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="station_id">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="source" class="form-label mb-1 col-md-3">Source</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="source">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="form-label mb-1 col-md-3">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="description">
                                </div>
                            </div>
                        </div>

                        <div id="default_Punch_settings_section">
                            <u>
                                <h5 class="bg-primary text-white">Default Punch Settings</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="branch_id" class="form-label mb-1 col-md-3">Branch</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="branch_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="department_id" class="form-label mb-1 col-md-3">Department</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="department_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="last_punch_time_stamp" class="form-label mb-1 col-md-3">Last Downloaded Punch</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control w-50" id="last_punch_time_stamp">
                                </div>
                            </div>
                        </div>

                        <div id="employee_criteria_section">
                            <u>
                                <h5 class="bg-primary text-white">Employee Criteria</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="group_ids" class="form-label mb-1 col-md-3">Employee Groups</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="empGroupContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="branch_ids" class="form-label mb-1 col-md-3">Branches</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="branchContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="department_ids" class="form-label mb-1 col-md-3">Departments</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="departmentContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="include_user_ids" class="form-label mb-1 col-md-3">Include Employees</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="includeUserContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exclude_user_ids" class="form-label mb-1 col-md-3">Exclude Employees</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="excludeUserContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="company_deduction_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        const userIds = '';
        let dropdownData = [];

        $(document).ready(async function() {
            
            console.log('1');
            await getDropdownData();

            // // Check if the `id` parameter is available in the query string
            // <?php if (isset($_GET['id'])): ?>
            // const id = <?= json_encode($_GET['id']) ?>; // Safely pass PHP variable to JavaScript
            // getUpdateData(id);
            // <?php endif; ?>
        });

        async function getDropdownData() {
            try {
                
                let dropdownData = await commonFetchData('/company/station/dropdown')

                console.log("Employees Data:", dropdownData);

                // // Time Zone dropdown
                // let stubEntryAccountList = (dropdownData?.pay_stub_entry_accounts || [])
                //     .map(stub_entry_account =>
                //         `<option value="${stub_entry_account.id}">${stub_entry_account.name}</option>`)
                //     .join('');
                // $('#pay_stub_entry_account_id').html('<option value="">Select Stub Entry Accounts</option>' +
                //     stubEntryAccountList);

                // // Initialize the multiSelector for users
                // $('#userContainer').multiSelector({
                //     title: 'Employees',
                //     data: dropdownData?.users || [],
                // });

                // // Initialize the multiSelector for exclude Account
                // $('#excludeAccContainer').multiSelector({
                //     title: 'Exclude Account',
                //     data: dropdownData?.pay_stub_entry_accounts || [],
                // });
                // // Initialize the multiSelector for exclude Account
                // $('#includeAccContainer').multiSelector({
                //     title: 'Include Account',
                //     data: dropdownData?.pay_stub_entry_accounts || [],
                // });

                // // Populate timesheet_verify_type dropdown
                // let calculationList = (dropdownData?.calculation_list || [])
                //     .map(over => `<option value="${over.value}">${over.name}</option>`)
                //     .join('');
                // $('#calculation_type').html(calculationList);

                // // Populate overtime_week dropdown
                // let minlengthOfServiceList = (dropdownData?.length_of_service || [])
                //     .map(service => `<option value="${service.value}">${service.name}</option>`)
                //     .join('');
                // $('#minimum_length_of_service_unit').html(minlengthOfServiceList);
                // // Populate overtime_week dropdown
                // let maxlengthOfServiceList = (dropdownData?.length_of_service || [])
                //     .map(service => `<option value="${service.value}">${service.name}</option>`)
                //     .join('');
                // $('#maximum_length_of_service_unit').html(maxlengthOfServiceList);

                // // Populate type dropdown
                // let typeList = (dropdownData?.type || [])
                //     .map(over => `<option value="${over.value}">${over.name}</option>`)
                //     .join('');
                // $('#type').html('<option value="">Select Overtime Week</option>' + typeList);


                // // Populate basis Of Employment List dropdown
                // let basisOfEmploymentList = (dropdownData?.basis_of_employment || [])
                //     .map(basis => `<option value="${basis.value}">${basis.name}</option>`)
                //     .join('');
                // $('#basis_of_employment').html(basisOfEmploymentList);

                // // Populate basis Of Employment List dropdown
                // let amountTypeList_1 = (dropdownData?.amount_type_list || [])
                //     .map(amount_type_1 => `<option value="${amount_type_1.value}">${amount_type_1.name}</option>`)
                //     .join('');
                // $('#include_account_amount_type').html(amountTypeList_1);

                // // Populate basis Of Employment List dropdown
                // let amountTypeList_2 = (dropdownData?.amount_type_list || [])
                //     .map(amount_type_2 => `<option value="${amount_type_2.value}">${amount_type_2.name}</option>`)
                //     .join('');
                // $('#exclude_account_amount_type').html(amountTypeList_2);


                $('#always_show_section').show();
                $('#percent_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#fixed_amount_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#advanced_percent_section').hide();

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        //  click event
        $(document).on('click', '#form_submit', async function() {

            const company_deduction_id = $('#company_deduction_id').val();
            console.log('company_deduction_id', company_deduction_id);

            // Collect selected user IDs from the multiSelector component
            const selectedIds = $('#userContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();
            // Collect selected user IDs from the multiSelector component
            const selectedExcludeAccIds = $('#excludeAccContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();
            // Collect selected user IDs from the multiSelector component
            const selectedIncludeAccIds = $('#includeAccContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            let createUrl = `/payroll/company_deduction/create`;
            let updateUrl = `/payroll/company_deduction/update/${company_deduction_id}`;

            let formData = new FormData();

            // if (!name || !type) {
            //     $('#error-msg').html('<p class="text-danger">All fields are required</p>');
            //     return;
            // } else {
            //     $('#error-msg').html(''); // Clear error message if no issues
            // }

            formData.append('user_ids', JSON.stringify(selectedIds));
            formData.append('exclude_pay_stub_entry_account_ids', JSON.stringify(selectedExcludeAccIds));
            formData.append('include_pay_stub_entry_account_ids', JSON.stringify(includeAccContainer));

            formData.append('name', $('#name').val());
            formData.append('type', $('#type').val());
            formData.append('name', $('#name').val());
            formData.append('start_date', $('#start_date').val());
            formData.append('end_date', $('#end_date').val());
            formData.append('minimum_length_of_service', $('#minimum_length_of_service').val());
            formData.append('minimum_length_of_service_unit', $('#minimum_length_of_service_unit').val());
            formData.append('maximum_length_of_service', $('#maximum_length_of_service').val());
            formData.append('maximum_length_of_service_unit', $('#maximum_length_of_service_unit').val());
            formData.append('include_account_amount_type', $('#include_account_amount_type').val());
            formData.append('exclude_account_amount_type', $('#exclude_account_amount_type').val());
            formData.append('minimum_user_age', $('#minimum_user_age').val());
            formData.append('maximum_user_age', $('#maximum_user_age').val());
            formData.append('basis_of_employment', $('#basis_of_employment').val());
            formData.append('user_value1', $('#user_value1').val());
            formData.append('user_value2', $('#user_value2').val());
            formData.append('user_value3', $('#user_value3').val());
            formData.append('user_value4', $('#user_value4').val());
            formData.append('user_value5', $('#user_value5').val());
            formData.append('pay_stub_entry_account_id', $('#pay_stub_entry_account_id').val());
            formData.append('calculation_order', $('#calculation_order').val());
            formData.append('calculation_type', $('#calculation_type').val());
            formData.append('company_deduction_status', $('#company_deduction_status').val());


            const isUpdating = Boolean(company_deduction_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'payroll/company_deduction';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('payroll.company_deduction') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        // ================================ Type change ====================================

        $(document).on('change', '#calculation_type', function() {

            if ($(this).val() == 'fixed_amount') {
                $('#always_show_section').show();
                $('#fixed_amount_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#percent_section').hide();
                $('#advanced_percent_section').hide();
            } else if ($(this).val() == 'fixed_amount_range_bracket') {
                $('#always_show_section').show();
                $('#fixed_amount_range_bracket_section').show();

                $('#fixed_amount_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#percent_section').hide();
                $('#advanced_percent_section').hide();
            } else if ($(this).val() == 'advanced_percent') {
                $('#always_show_section').show();
                $('#advanced_percent_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#percent_section').hide();
                $('#fixed_amount_section').hide();
            } else if ($(this).val() == 'advanced_percent_range_bracket') {
                $('#always_show_section').show();
                $('#advanced_percent_range_bracket_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#fixed_amount_section').hide();
                $('#percent_section').hide();
                $('#advanced_percent_section').hide();
            } else if ($(this).val() == 'percent') {
                $('#always_show_section').show();
                $('#percent_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#fixed_amount_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#advanced_percent_section').hide();
            }
        })


        function getID(id) {
            // Loop through the range of user values (1 to 5)
            for (let i = 1; i <= 5; i++) {
                // Check if the id matches any of the valid input IDs for the current index
                if (
                    id === `farbs_user_value${i}` ||
                    id === `aprbs_user_value${i}` ||
                    id === `fas_user_value${i}` ||
                    id === `ps_user_value${i}` ||
                    id === `aps_user_value${i}`
                ) {
                    // Assign the value to the corresponding #user_value{i}
                    $(`#user_value${i}`).val($(`#${id}`).val());
                    break; // Exit the loop since we've found a match
                }
            }
        }

        function getUserValueByType(type, data) {
            // Define mappings for dynamic assignment
            const userValueMappings = {
                percent: ['ps_user_value1'],
                fixed_amount: ['fas_user_value1'],
                fixed_amount_range_bracket: ['farbs_user_value1', 'farbs_user_value2', 'farbs_user_value3',
                    'farbs_user_value4'
                ],
                advanced_percent: ['aps_user_value1', 'aps_user_value2', 'aps_user_value3'],
                advanced_percent_range_bracket: ['aprbs_user_value1', 'aprbs_user_value2', 'aprbs_user_value3',
                    'aprbs_user_value4', 'aprbs_user_value5'
                ],
            };

            // Get the user value keys for the selected type
            const userFields = userValueMappings[type] || [];
            userFields.forEach((field, index) => {
                $(`#${field}`).val(data[`user_value${index + 1}`] || '');
            });
        }

        async function changeType(type) {

            if (type == 'fixed_amount') {
                $('#always_show_section').show();
                $('#fixed_amount_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#percent_section').hide();
                $('#advanced_percent_section').hide();
            } else if (type == 'fixed_amount_range_bracket') {
                $('#always_show_section').show();
                $('#fixed_amount_range_bracket_section').show();

                $('#fixed_amount_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#percent_section').hide();
                $('#advanced_percent_section').hide();
            } else if (type == 'advanced_percent') {
                $('#always_show_section').show();
                $('#advanced_percent_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#percent_section').hide();
                $('#fixed_amount_section').hide();
            } else if (type == 'advanced_percent_range_bracket') {
                $('#always_show_section').show();
                $('#advanced_percent_range_bracket_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#fixed_amount_section').hide();
                $('#percent_section').hide();
                $('#advanced_percent_section').hide();
            } else if (type == 'percent') {
                $('#always_show_section').show();
                $('#percent_section').show();

                $('#fixed_amount_range_bracket_section').hide();
                $('#fixed_amount_section').hide();
                $('#advanced_percent_range_bracket_section').hide();
                $('#advanced_percent_section').hide();
            }
        }

        async function getUpdateData(id) {
            try {
                let response = await commonFetchData(`/payroll/company_deduction/${id}`);
                let data = response?.[0]; // Assuming the API returns an array with one item

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                console.log('Fetched pay period schedule data:', data);

                // Set the name and status fields
                // format date
                let newStartDate = data.start_date;
                let formattedStartDate = new Date(newStartDate).toISOString().split('T')[0]; // 'YYYY-MM-DD'
                $('#start_date').val(formattedStartDate);

                let newEndDate = data.end_date;
                let formattedEndDate = new Date(newEndDate).toISOString().split('T')[0]; // 'YYYY-MM-DD'
                $('#end_date').val(formattedEndDate);

                $('#company_deduction_id').val(data.id);
                // Populate form fields
                $('#name').val(data.name || '');
                $('#type').val(data.type || '');
                $('#calculation_type').val(data.calculation_type || '');
                $('#calculation_order').val(data.calculation_order || '');
                $('#user_value1').val(data.user_value1 || '');
                $('#user_value2').val(data.user_value2 || '');
                $('#user_value3').val(data.user_value3 || '');
                $('#user_value4').val(data.user_value4 || '');
                $('#user_value5').val(data.user_value5 || '');
                $('#pay_stub_entry_account_id').val(data.pay_stub_entry_account_id || '');
                // $('#start_date').val(data.start_date || '');
                // $('#end_date').val(data.end_date || '');
                $('#minimum_length_of_service').val(data.minimum_length_of_service || '');
                $('#minimum_length_of_service_unit').val(data.minimum_length_of_service_unit || '');
                $('#maximum_length_of_service').val(data.maximum_length_of_service);
                $('#maximum_length_of_service_unit').val(data.maximum_length_of_service_unit);
                $('#include_account_amount_type').val(data.include_account_amount_type);
                $('#exclude_account_amount_type').val(data.exclude_account_amount_type);
                $('#minimum_user_age').val(data.minimum_user_age);
                $('#maximum_user_age').val(data.maximum_user_age);
                $('#basis_of_employment').val(data.basis_of_employment);
                $('#company_deduction_status').val(data.status);

                // userIds = data.users.map(emp => emp.user_id);

                // // Initialize the multiSelector for users
                // $('#userContainer').multiSelector({
                //     title: 'Employees',
                //     data: dropdownData?.users || [],
                //     selectedIds: userIds,
                // });

                await changeType($('#calculation_type').val());
                await getUserValueByType($('#calculation_type').val(), data);

            } catch (error) {
                console.error('Error fetching policy group data:', error);
            }
        }

        function resetForm() {
            $('#name').val('');
            $('#description').val('');
            $('#start_week_day').val('');
            $('#time_zone').val('');
            $('#new_day_trigger_time').val('');
            $('#maximum_shift_time').val('');
            $('#shift_assigned_day').val('');
            $('#timesheet_verify_type').val('');
            $('#type').val('');
            $('#start_day_of_week').val('');
            $('#transaction_date').val('');
            $('#transaction_date_bd').val('');
            $('#anchor_date').val('');
            $('#timesheet_verify_before_end_date').val('');
            $('#timesheet_verify_before_transaction_date').val('');
            $('#primary_day_of_month').val('');
            $('#primary_transaction_day_of_month').val('');
            $('#secondary_day_of_month').val('');
            $('#secondary_transaction_day_of_month').val('');
            $('#transaction_date_bd').val('');
            $('#company_deduction_status').val('');

            // getDropdownData();
        }
    </script>


</x-app-layout>
