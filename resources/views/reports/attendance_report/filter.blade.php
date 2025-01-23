<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8 {
            width: 8% !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Attendance Report</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="user_status_ids" class="form-label mb-1 col-md-3">Employee Status</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="userStatusContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="group_ids" class="form-label mb-1 col-md-3">Group</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="groupContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="branch_ids" class="form-label mb-1 col-md-3">Default Branch</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="branchContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="_ids" class="form-label mb-1 col-md-3">Default Department</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="departmentContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="user_title_ids" class="form-label mb-1 col-md-3">Employee Title</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="userTitleContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="include_user_ids" class="form-label mb-1 col-md-3">Include Employees</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="includeUserContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="user_ids" class="form-label mb-1 col-md-3">Exclude Employees</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="excludeUserContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="pay_stub_amendment_status" class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="pay_stub_amendment_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                        {{-- <div id="Amount_section">
                            <u>
                                <h5 class="bg-primary text-white">Amount</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Amount
                                    Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="type">
                                        <option value="fixed">Fixed</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="rate" class="form-label mb-1 col-md-3">Rate</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="rate">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="units" class="form-label mb-1 col-md-3">Units</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="units">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="amount" class="form-label mb-1 col-md-3">Amount</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="amount">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="percent_amount" class="form-label mb-1 col-md-3">Percent (%)</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="percent_amount">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="percent_amount_entry_name_id" class="form-label mb-1 col-md-3">Percent
                                    Of</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="percent_amount_entry_name_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div id="Options_section">
                            <u>
                                <h5 class="bg-primary text-white">Options</h5>
                            </u>

                            <div class="row mb-3">
                                <label for="description" class="form-label mb-1 col-md-3">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="description">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="effective_date" class="form-label mb-1 col-md-3">Effective Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control numonly w-50" id="effective_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="ytd_adjustment" class="form-label mb-1 col-md-3">Year to Date (YTD)
                                    Adjustment</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input" id="ytd_adjustment">
                                </div>
                            </div>
                        </div> --}}

                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="holiday_policy_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        let userId = '';
        let dropdownData = [];

        $(document).ready(async function() {
            await getDropdownData();
        });

        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/reports/employee_detail_report/dropdown')

                // // Assign the same list to #percent_amount_entry_name_id
                // $('#percent_amount_entry_name_id').html('<option value="">Select Account</option>' +
                //     payStubEntryAccountList);

                $('#excludeUserContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.users || [],
                });
                $('#includeUserContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.users || [],
                });

                $('#userStatusContainer').multiSelector({
                    title: 'Employee Status',
                    data: dropdownData?.emp_status || [],
                });
                $('#groupContainer').multiSelector({
                    title: 'Group',
                    data: dropdownData?.com_employee_groups || [],
                });
                $('#branchContainer').multiSelector({
                    title: 'Branch',
                    data: dropdownData?.com_branches || [],
                });
                $('#departmentContainer').multiSelector({
                    title: 'Departments',
                    data: dropdownData?.com_departments || [],
                });

                $('#userTitleContainer').multiSelector({
                    title: 'Employee Title',
                    data: dropdownData?.com_user_designations || [],
                });

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        //  click event
        $(document).on('click', '#form_submit', async function() {

            const pay_stub_amendment_id = $('#pay_stub_amendment_id').val();


            const selectedUserStatusIds = $('#userStatusContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            const selectedGroupIds = $('#groupContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            const selectedBranchIds = $('#branchContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            const selectedDepartmentIds = $('#departmentContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            const selectedUserTitleIds = $('#userTitleContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            const selectedIncludeUserIds = $('#includeUserContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            const selectedExcludeUserIds = $('#excludeUserContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            // {
            //     let formData = new FormData();

            //     formData.append('user_status_ids', JSON.stringify(selectedUserStatusIds));
            //     formData.append('group_ids', JSON.stringify(selectedGroupIds));
            //     formData.append('branch_ids', JSON.stringify(selectedBranchIds));
            //     formData.append('department_ids', JSON.stringify(selectedDepartmentIds));
            //     formData.append('user_title_ids', JSON.stringify(selectedUserTitleIds));
            //     formData.append('include_user_ids', JSON.stringify(selectedIncludeUserIds));
            //     formData.append('exclude_user_ids', JSON.stringify(selectedExcludeUserIds));

            //     formData.append('pay_stub_amendment_status', $('#pay_stub_amendment_status').val());
            // }

            const payStubAmendmentStatus = $('#pay_stub_amendment_status').val();
            const queryParams = new URLSearchParams({
                user_status_ids: selectedUserStatusIds.join(','),
                group_ids: selectedGroupIds.join(','),
                branch_ids: selectedBranchIds.join(','),
                department_ids: selectedDepartmentIds.join(','),
                user_title_ids: selectedUserTitleIds.join(','),
                include_user_ids: selectedIncludeUserIds.join(','),
                exclude_user_ids: selectedExcludeUserIds.join(','),
                pay_stub_amendment_status: payStubAmendmentStatus,
            });

            try {
                let response = await commonFetchData(
                    `/reports/employee_detail_report/list?${queryParams.toString()}`);
                // let data = response?.[0];


                if (!response) {
                    resetForm();
                    throw new Error(`HTTP error! status: ${response.status}`);
                } else {
                    window.location.href = '/reports/employee_detail_report/report?data=' + JSON.stringify(
                        response);
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        function resetForm() {
            $('#pay_stub_amendment_status').val('active');
            $('#pay_stub_entry_name_id').val('');
            $('#type').val('');
            $('#rate').val('');
            $('#units').val('');
            $('#amount').val('');
            $('#description').val('');
            $('#effective_date').val('');
            $('#percent_amount').val('');
            $('#percent_amount_entry_name_id').val('');

            $('#ytd_adjustment').prop('checked', false);

            // $('.form-check').val('');

            getDropdownData();
        }
    </script>

</x-app-layout>
