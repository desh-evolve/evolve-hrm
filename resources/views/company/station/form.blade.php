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
                                <label for="station_status" class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="station_status" style="width: 50%;">
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
                                <label for="station" class="form-label mb-1 col-md-3">Station ID</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="station">
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
                                <label for="last_punch_time_stamp" class="form-label mb-1 col-md-3">Last Downloaded
                                    Punch</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50" id="last_punch_time_stamp"
                                        @disabled(true)>
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
                            <input type="hidden" id="station_id" value="" />
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
            await getDropdownData();

            // Check if the `id` parameter is available in the query string
            <?php if (isset($_GET['id'])): ?>
            const id = <?= json_encode($_GET['id']) ?>; // Safely pass PHP variable to JavaScript
            getUpdateData(id);
            <?php endif; ?>
        });

        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/company/station/dropdown')

                console.log("Employees Data:", dropdownData?.users);

                // Time Zone dropdown
                let branchList = (dropdownData?.branches || [])
                    .map(branch =>
                        `<option value="${branch.id}">${branch.name}</option>`)
                    .join('');
                $('#branch_id').html('<option value="">Select Branch</option>' +
                    branchList);

                let departmentList = (dropdownData?.departments || [])
                    .map(department =>
                        `<option value="${department.id}">${department.name}</option>`)
                    .join('');
                $('#department_id').html('<option value="">Select Department</option>' +
                    departmentList);

                // Initialize the multiSelector for users
                $('#includeUserContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.users || [],
                });
                // Initialize the multiSelector for exclude Account
                $('#excludeUserContainer').multiSelector({
                    title: 'Exclude Account',
                    data: dropdownData?.users || [],
                });
                // Initialize the multiSelector for exclude Account
                $('#departmentContainer').multiSelector({
                    title: 'Include Account',
                    data: dropdownData?.departments || [],
                });
                // Initialize the multiSelector for exclude Account
                $('#branchContainer').multiSelector({
                    title: 'Include Account',
                    data: dropdownData?.branches || [],
                });
                // Initialize the multiSelector for exclude Account
                $('#empGroupContainer').multiSelector({
                    title: 'Include Account',
                    data: dropdownData?.user_groups || [],
                });

                // $('#always_show_section').show();
                // $('#percent_section').show();

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        //  click event
        $(document).on('click', '#form_submit', async function() {

            const station_id = $('#station_id').val();
            console.log('station_id', station_id);

            // Collect selected user IDs from the multiSelector component
            const selectedEmpGroupIds = $('#empGroupContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();
            // Collect selected user IDs from the multiSelector component
            const selectedBranchIds = $('#branchContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();
            // Collect selected user IDs from the multiSelector component
            const selectedDepartmentIds = $('#departmentContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();
            // Collect selected user IDs from the multiSelector component
            const selectedIncludeUserIds = $('#includeUserContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();
            // Collect selected user IDs from the multiSelector component
            const selectedExcludeUserIds = $('#excludeUserContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            let createUrl = `/company/station/create`;
            let updateUrl = `/company/station/update/${station_id}`;
            
            let formData = new FormData();

            formData.append('group_ids', JSON.stringify(selectedEmpGroupIds));
            formData.append('branch_ids', JSON.stringify(selectedBranchIds));
            formData.append('department_ids', JSON.stringify(selectedDepartmentIds));
            formData.append('include_user_ids', JSON.stringify(selectedIncludeUserIds));
            formData.append('exclude_user_ids', JSON.stringify(selectedExcludeUserIds));

            formData.append('station_status', $('#station_status').val());
            formData.append('type', $('#type').val());
            formData.append('station_id', $('#station_id').val());
            formData.append('station', $('#station').val());
            formData.append('source', $('#source').val());
            formData.append('description', $('#description').val());
            formData.append('branch_id', $('#branch_id').val());
            formData.append('department_id', $('#department_id').val());
            formData.append('last_punch_time_stamp', $('#last_punch_time_stamp').val());


            const isUpdating = Boolean(station_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url,formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'company/station';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('company.station.index') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        async function getUpdateData(id) {
            try {
                let response = await commonFetchData(`/company/station/${id}`);
                let data = response?.[0]; // Assuming the API returns an array with one item

                if (!response) {
                    console.error('No data found for the given ID.');
                    return;
                }

                console.log('Fetched pay period schedule data:', response);

                $('#station_id').val(response.id);
                // Populate form fields

                $('#time_zone').val(response.time_zone);
                $('#department_id').val(response.department_id);
                $('#branch_id').val(response.branch_id);
                $('#description').val(response.description);
                $('#source').val(response.source);
                $('#station').val(response.station_customer_id);
                $('#type').val(response.station_type_id);
                $('#station_status').val(response.status);

                // // // Initialize the multiSelector for users
                // $('#include_user_ids').multiSelector({
                //     title: 'Employees',
                //     data: dropdownData?.users || [],
                //     selectedIds: val(response.include_user_ids),
                // });

            } catch (error) {
                console.error('Error fetching policy group data:', error);
            }
        }

        function resetForm() {
            $('#type').val('');
            $('#station_id').val('');
            $('#station').val('');
            $('#source').val('');
            $('#description').val('');
            $('#branch_id').val('');
            $('#department_id').val('');
            $('#last_punch_time_stamp').val('');
            $('#station_status').val('');
        }
    </script>


</x-app-layout>
