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
                        <h5 class="mb-0">Hierarchy</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>

                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="form-label mb-1 col-md-3">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="description">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="object_type" class="form-label mb-1 col-md-3">Objects:
                                    (Select one or more)</label>
                                <div class="col-md-9">

                                    <select class="form-select w-50" multiple aria-label="multiple select example"
                                        id="object_type">

                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="user_ids" class="form-label mb-1 col-md-3">Subordinates</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="userContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="hierarchy_status" class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="hierarchy_status" style="width: 50%;">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="always_show_section">
                            <u>
                                <h5 class="bg-primary text-white">NOTE: Level one denotes the top or last level of the
                                    hierarchy and employees at the same level share responsibilities.</h5>
                            </u>
                            <div class="row mb-3">
                                <div class="col-md-9 d-flex align-items-center">
                                    <!-- Level Field -->
                                    <label for="level" class="form-label mb-1 me-2">Level</label>
                                    <input type="text" class="form-control w-25 me-3" id="level" value="0">

                                    <!-- Superiors Field -->
                                    <label for="superiors_id" class="form-label mb-1 me-2">Superiors</label>
                                    <select class="form-select w-50 me-3" id="superiors_id">
                                        <option value=""></option>
                                    </select>

                                    <!-- Add Button -->
                                    <button type="button" class="btn btn-secondary" id="addPermissionBtn">Add</button>
                                </div>
                            </div>


                        </div>
                        <div class="mb-3">
                            <label>Permissions List</label>
                            <hr class="mt-0">
                            <div>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Level</th>
                                            <th>Superiors</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="levelTableBody">
                                        <!-- Rows will be appended here -->
                                    </tbody>
                                </table>

                                <!-- Hidden inputs container -->
                                <div id="hiddenInputsContainer">
                                    <!-- Hidden inputs for Level and Superiors will be added here -->
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="hierarchy_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        const userIds = '';
        const selectedObjectIds = '';
        let dropdownData = [];
        // Global list to store Level and Superiors
        let levelList = [];

        $(document).ready(async function() {
            await getDropdownData();

            <?php if (isset($_GET['id'])): ?>
            const id = <?= json_encode($_GET['id']) ?>;
            getUpdateData(id);
            <?php endif; ?>

        });

        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/company/hierarchy/dropdown')

                // object type dropdown
                let objectTypeList = (dropdownData?.object_type || [])
                    .map(object_type =>
                        `<option value="${object_type.id}">${object_type.name}</option>`)
                    .join('');
                $('#object_type').html(objectTypeList);

                // superiors_id
                let superiorsList = (dropdownData?.users || [])
                    .map(superiors_type =>
                        `<option value="${superiors_type.id}">${superiors_type.name}</option>`)
                    .join('');
                $('#superiors_id').html('<option value="">Select Superiors</option>' +
                    superiorsList);

                // Initialize the multiSelector for users
                $('#userContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.users || [],
                });


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        function getSelectedObjectTypeIds() {
            // Get the selected options from the dropdown
            let selectedOptions = $('#object_type option:selected');

            // Extract the values (IDs) of the selected options
            let selectedObjectIds = selectedOptions.map(function() {
                return $(this).val();
            }).get(); // Convert the jQuery object to an array

            return selectedObjectIds; // Return the array of selected IDs
        }

        function assignObjectTypeIdsToDropdown(objectTypesIds) {
            if (!Array.isArray(objectTypesIds)) {
                console.error('Invalid data: objectTypesIds is not an array');
                return;
            }

            // Clear current selections
            $('#object_type option').prop('selected', false);

            // Iterate over the array and set the options as selected
            objectTypesIds.forEach(id => {
                $('#object_type option[value="' + id + '"]').prop('selected', true);
            });
        }


        //  click event
        $(document).on('click', '#form_submit', async function() {

            const hierarchy_id = $('#hierarchy_id').val();
            console.log('hierarchy_id', hierarchy_id);

            // Collect selected user IDs from the multiSelector component
            const selectedIds = $('#userContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            // // Example usage
            const selectedObjectTypeIds = getSelectedObjectTypeIds();
            console.log('Selected Object Type IDs:', selectedObjectTypeIds);

            let createUrl = `/company/hierarchy/create`;
            let updateUrl = `/company/hierarchy/update/${hierarchy_id}`;

            let formData = new FormData();

            // if (!name || !type) {
            //     $('#error-msg').html('<p class="text-danger">All fields are required</p>');
            //     return;
            // } else {
            //     $('#error-msg').html(''); // Clear error message if no issues
            // }
            // Append the levelList to formData
            formData.append('level_list', JSON.stringify(levelList));

            formData.append('user_ids', JSON.stringify(selectedIds));
            formData.append('object_ids', JSON.stringify(selectedObjectTypeIds));

            formData.append('name', $('#name').val());
            formData.append('description', $('#description').val());

            formData.append('hierarchy_status', $('#hierarchy_status').val());


            const isUpdating = Boolean(hierarchy_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'company/hierarchy';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('company.hierarchy.index') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        $('#addPermissionBtn').on('click', function() {
            // Get the values of Level and Superiors
            const levelValue = $('#level').val().trim();
            const superiorsValue = $('#superiors_id option:selected').text().trim();
            const superiorsId = $('#superiors_id').val(); // ID for hidden input

            // Validate inputs
            if (levelValue && superiorsValue) {
                // Add the data to the table
                $('#levelTableBody').append(`
                    <tr>
                        <td>${levelValue}</td>
                        <td>${superiorsValue}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeLevelBtn">Remove</button>
                        </td>
                    </tr>
                `);

                // Add hidden inputs to track the data
                $('#hiddenInputsContainer').append(`
                    <input type="hidden" name="levels[]" value="${levelValue}">
                    <input type="hidden" name="superiors[]" value="${superiorsId}">
                `);

                // Add the entry to the global list
                levelList.push({
                    level: levelValue,
                    superior: superiorsId
                });
                // Clear the input fields
                $('#level').val('0');
                $('#superiors_id').val('');
            } else {
                alert('Please provide both Level and Superiors values.');
            }
        });

        // Remove row functionality
        $('#levelTableBody').on('click', '.removeLevelBtn', function() {
            const levelValue = $(this).data('level');
            const superiorsId = $(this).data('superior');

            // Remove the table row
            $(this).closest('tr').remove();

            // Remove the entry from the global list
            levelList = levelList.filter(item => item.level !== levelValue || item.superior !== superiorsId);
        });



        async function getUpdateData(id) {
            try {
                let response = await commonFetchData(`/company/hierarchy/${id}`);
                let data = response;

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                // Clear current selections in the dropdown
                $('#object_type option').prop('selected', false);

                // Set the selected options for object_types
                if (Array.isArray(data.object_types)) {
                    data.object_types.forEach(id => {
                        $('#object_type option[value="' + id + '"]').prop('selected', true);
                    });
                }

                // Set values for other form fields
                $('#hierarchy_id').val(data.hierarchy_control_id || '');
                $('#name').val(data.hierarchy_name || '');
                $('#description').val(data.description || '');
                $('#hierarchy_status').val(data.status || '');

                console.log('Successfully updated form fields with data:', data);

            } catch (error) {
                console.error('Error fetching hierarchy data:', error);
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
            $('#hierarchy_status').val('');

            // getDropdownData();
        }
    </script>


</x-app-layout>
