<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Hierarchy') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>


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

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <a href="/company/hierarchy" class="btn btn-danger">Back</a>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <form>

                        <div>

                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3 req">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="name" placeholder="Enter Name here">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="form-label mb-1 col-md-3">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="description" placeholder="Enter Description here">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="object_type" class="form-label mb-1 col-md-3 req">Objects:
                                    (Select one or more)</label>
                                <div class="col-md-9">

                                    <select class="form-select w-50" multiple aria-label="multiple select example"
                                        id="object_type">

                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="user_ids" class="form-label mb-1 col-md-3 req">Subordinates</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="userContainer">
                                        {{-- render users dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="hierarchy_status" class="form-label mb-1 col-md-3 req">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="hierarchy_status" style="width: 50%;">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="always_show_section">
                            <h5 class="bg-primary text-white p-1 mb-4 mt-3">NOTE: Level one denotes the top or last level of the
                                hierarchy and employees at the same level share responsibilities.
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <!-- Level Field -->
                                    <label for="level" class="form-label mb-1 col-md-3 req">Level</label>
                                    <input type="text" class="form-control w-100" id="level" value="1">
                                </div>

                                <div class="col-md-5">
                                    <!-- Superiors Field -->
                                    <label for="superiors_id" class="form-label mb-1 col-md-3 req">Superiors</label>
                                    <select class="form-select w-100" id="superiors_id">

                                    </select>
                                </div>

                                <div class="col-md-1 d-flex align-items-end ms-3">
                                    <!-- Add Button -->
                                    <button type="button" class="btn btn-primary" id="addPermissionBtn"><i class="ri-add-line"></i></button>
                                </div>
                            </div>


                            <div id="level-error-msg"></div>

                        </div>

                        <div class="mb-3">
                            <label class="fs-5">Permissions List</label>
                            <hr class="mt-0">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Level</th>
                                            <th>Superiors</th>
                                            <th>Action</th>
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

                        <div id="error-msg"></div>

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
        let deletedLevels = [];

        $(document).ready(async function() {
            await getDropdownData();

            <?php if (isset($_GET['id'])): ?>
            const id = <?= json_encode($_GET['id']) ?>;
            getUpdateData(id);
            <?php endif; ?>

        });


        async function getDropdownData()
        {
            try {
                dropdownData = await commonFetchData('/company/hierarchy/dropdown')

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
                $('#superiors_id').html('<option value="">Select Superiors</option>' + superiorsList);

                // Initialize the multiSelector for users
                $('#userContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.users || [],
                });


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        function getSelectedObjectTypeIds()
        {
            // Get the selected options from the dropdown
            let selectedOptions = $('#object_type option:selected');

            // Extract the values (IDs) of the selected options
            let selectedObjectIds = selectedOptions.map(function() {
                return $(this).val();
            }).get(); // Convert the jQuery object to an array

            return selectedObjectIds; // Return the array of selected IDs
        }


        async function getUpdateData(id)
        {
            try {
                let response = await commonFetchData(`/company/hierarchy/${id}`);
                let data = response?.[0];

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                // Populate  Form Fields
                $('#hierarchy_id').val(data.hierarchy_control_id || 'N/A');
                $('#name').val(data.hierarchy_name || 'N/A');
                $('#description').val(data.description || 'N/A');
                $('#hierarchy_status').val(data.status || 'N/A');


                // Populate Object Types (Fix Selection)
                if (Array.isArray(data.hierarchy_objectTypes_details)) {
                    let objectTypeIds = data.hierarchy_objectTypes_details.map(item => String(item.object_type_id)); // Ensure string IDs

                    $('#object_type option').each(function() {
                        $(this).prop('selected', objectTypeIds.includes($(this).val()));
                    });

                    $('#object_type').trigger('change'); // Ensure UI updates
                }


                // Populate MultiSelector for Users
                if (Array.isArray(data.hierarchy_users_details)) {
                    const userIds = data.hierarchy_users_details.map(emp => emp.user_id);

                    $('#userContainer').multiSelector({
                        title: 'Employees',
                        data: dropdownData?.users || [],  // Use correct users list
                        setSelected: userIds,
                    });
                }


                //reset preveious data
                levelList = [];
                deletedLevels = [];
                $('#levelTableBody').empty(); // Clear previous entries


                // Populate Levels Table
                if (Array.isArray(data.hierarchy_levels_details)) {
                    data.hierarchy_levels_details.forEach((item, index) => {
                        let userName = dropdownData?.users?.find(user => user.id === item.user_id)?.name || `User ${item.user_id}`;

                        $('#levelTableBody').append(`
                            <tr data-level-id="${item.id}">
                                <td>${index + 1}</td>
                                <td>${item.level}</td>
                                <td>${userName}</td>
                                <td>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm removeLevelBtn" data-id="${item.id}" title="Remove Permission List" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `);

                        levelList.push({
                            id: item.id,
                            level: item.level,
                            superior: item.user_id
                        });
                    });
                }

                console.log('Successfully updated form fields with data:', data);

            } catch (error) {
                console.error('Error fetching hierarchy data:', error);
            }
        }


        //  click submit event
        $(document).on('click', '#form_submit', async function() {
            const hierarchy_id = $('#hierarchy_id').val();
            const isUpdating = Boolean(hierarchy_id);
            const url = isUpdating ? `/company/hierarchy/update/${hierarchy_id}` : `/company/hierarchy/create`;
            const method = isUpdating ? 'PUT' : 'POST';

            // Collect form values
            const name = $('#name').val().trim();
            const hierarchy_status = $('#hierarchy_status').val();
            const selectedIds = $('#userContainer .selected-list option').map(function () {
                return $(this).val();
            }).get();

            const selectedObjectTypeIds = getSelectedObjectTypeIds();


            let missingFields = [];

            if (!name) missingFields.push('Name');
            if (selectedObjectTypeIds.length === 0) missingFields.push('Objects Selection');
            if (selectedIds.length === 0) missingFields.push('Subordinates');
            if (!hierarchy_status.trim()) missingFields.push('Hierarchy Status');

            if (missingFields.length > 0) {
                $('#error-msg').html(`
                    <div class="alert alert-danger alert-dismissible">
                        <strong>Error!</strong> Please fill in the following fields: <strong>${missingFields.join(', ')}</strong>.
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                return;
            } else {
                $('#error-msg').html(''); // Clear previous errors
            }


            let formData = new FormData();

            formData.append('level_list', JSON.stringify(levelList));
            formData.append('removed_levels', JSON.stringify(deletedLevels));
            formData.append('user_ids', JSON.stringify(selectedIds));
            formData.append('object_ids', JSON.stringify(selectedObjectTypeIds));
            formData.append('name', $('#name').val());
            formData.append('description', $('#description').val());
            formData.append('hierarchy_status', $('#hierarchy_status').val());



            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('company.hierarchy.index') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        // add permission list button
        $('#addPermissionBtn').on('click', function() {
            // Get the values of Level and Superiors
            const levelValue = $('#level').val().trim();
            const superiorsValue = $('#superiors_id option:selected').text().trim();
            const superiorsId = $('#superiors_id').val(); // ID for hidden input

            let missingFields = [];

            if (!levelValue) missingFields.push('Level');
            if (!superiorsId) missingFields.push('Superiors');

            if (missingFields.length > 0) {
                $('#level-error-msg').html(`
                    <div class="alert alert-danger alert-dismissible">
                        <strong>Error!</strong> Please fill in the following fields: <strong>${missingFields.join(', ')}.</strong>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                return;
            } else {
                $('#level-error-msg').html('');
            }


            let rowCount = $('#levelTableBody tr').length + 1;

            // Add the data to the table
            $('#levelTableBody').append(`
                <tr>
                    <td>${rowCount}</td>
                    <td>${levelValue}</td>
                    <td>${superiorsValue}</td>
                    <td>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm removeLevelBtn" title="Remove Permission List" data-tooltip="tooltip" data-bs-placement="top"><i class="ri-delete-bin-fill"></i></button>
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
            $('#level').val('1');
            $('#superiors_id').val('');

        });


        // Remove row functionality
        // $('#levelTableBody').on('click', '.removeLevelBtn', function() {
        //     const levelValue = $(this).data('level');
        //     const superiorsId = $(this).data('superior');

        //     // Remove the table row
        //     $(this).closest('tr').remove();

        //     // Remove the entry from the global list
        //     levelList = levelList.filter(item => item.level !== levelValue || item.superior !== superiorsId);

        // });



        // Handle Removing Levels
        $('#levelTableBody').on('click', '.removeLevelBtn', function () {
            let row = $(this).closest('tr');
            let levelId = row.data('level-id');

            if (levelId) {
                deletedLevels.push(levelId);
            }

            row.remove();
            levelList = levelList.filter(item => item.id !== levelId);
        });


        function resetForm() {
            $('#name').val('');
            $('#description').val('');
            $('#object_type').val('').trigger('change');  // Reset select field
            $('#userContainer .selected-list').html('');  // Clear selected users
            $('#level').val('1');
            $('#superiors_id').val('');
            $('#hierarchy_status').val('');
            $('#error-msg').html('');
            $('#level-error-msg').html('');
            levelList = [];
            deletedLevels = [];
        }

    </script>


</x-app-layout>
