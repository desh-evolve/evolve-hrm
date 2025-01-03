<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Family') }}</h4>
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
                        <h5 class="mb-0">Employee Family</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add-new-user-family-btn">New Family Detail <i class="ri-add-line"></i></button>
                    </div>
                </div>


                <div class="card-body">

                    <div class="row mb-3 mb-4">
                        <div class="col-lg-2 d-flex align-items-center">
                            <label for="user_idname" class="form-label mb-1 req">Employee Name</label>
                        </div>

                        <div class="col-lg-10">
                            <select class="form-select form-select-sm js-example-basic-single" id="userDropdown">
                                <option value="">Select Employee</option>
                            </select>
                        </div>


                    </div>
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white"/>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Relationship</th>
                                <th class="col">DOB</th>
                                <th class="col">NIC</th>
                                <th class="col">Gender</th>
                                <th class="col">Contact No</th>
                                <th class="col">Address</th>
                                <th class="col">Note</th>
                                <th class="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="user-family-table-body">
                            <tr>
                                <td colspan="7" class="text-center">Please Select a Employee ...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="user-family-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="user-family-form-title">New Family Detail</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="user-family-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="user_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="user_name" value="" disabled>
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="name" class="form-label mb-1 req">Name</label>
                            <input type="text" class="form-control" id="name" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="relationship" class="form-label mb-1 req">Relationship</label>
                            <input type="text" class="form-control" id="relationship" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="dob" class="form-label mb-1 req">DOB</label>
                            <input type="date" class="form-control" id="dob" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="nic" class="form-label">NIC</label>
                            <input type="text" class="form-control" id="nic" rows="3">
                        </div>
                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="gender" class="form-label mb-1 req">Gender</label>
                            <select class="form-select" id="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="contact_1" class="form-label">Contact 1</label>
                            <input type="text" class="form-control" id="contact_1" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="contact_2" class="form-label">Contact 2</label>
                            <input type="text" class="form-control" id="contact_2" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="address_1" class="form-label">Address 1</label>
                            <input type="text" class="form-control" id="address_1" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="address_2" class="form-label">Aaddress 2</label>
                            <input type="text" class="form-control" id="address_2" rows="3">
                        </div>

                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="user_family_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="user_family_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-xxl-3 col-md-12 mb-3">
                            <label for="notes" class="form-label">Note</label>
                            <textarea type="text" class="form-control" id="notes" rows="3"></textarea>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="family_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary"
                                id="user-family-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let userId = '';

            // Fetch and render users_family for the selected user
            async function renderEmployeeFamilyTable() {
                if (!userId) {
                    $('#user-family-table-body').html(
                        '<tr><td colspan="7" class="text-center">No Employee Selected</td></tr>');
                    return;
                }
                
                let users_family = await commonFetchData(`/company/user_family/${userId}`);
                let list = '';
                
                if (users_family && users_family.length > 0) {
                    users_family.forEach((item, i) => {
                        list += `
                <tr family_id="${item.id}">
                    <td>${i + 1}</td>
                    <td>${item.name}</td>
                    <td>${item.relationship}</td>
                    <td>${item.dob}</td>
                    <td>${item.nic}</td>
                    <td>${item.gender}</td>
                    <td>${item.contact_1}</td>
                    <td>${item.address_1}</td>
                    <td>${item.notes}</td>
                    <td class="text-capitalize">${item.status === 'active' 
                        ? `<span class="badge border border-success text-success">${item.status}</span>` 
                        : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                    <td>
                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-user-family" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click-delete-user-family" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
                    `;
                    });
                } else {
                    list = `<tr><td colspan="7" class="text-danger text-center">No Employee Family Yet!</td></tr>`;
                }

                $('#user-family-table-body').html(list);
            }

            async function getEmployeeList() {
                let users = await commonFetchData('/company/user_family/dropdown');

            
                
                // Check if users data is valid
                if (users && users.length > 0) {
                    // Target the dropdown element
                    let dropdown = $('#userDropdown');

                    // Clear existing options (optional)
                    dropdown.empty();
                    dropdown.append('<option value="">Select Employee</option>'); // Add a default option

                    // Loop through the users and add options
                    users.forEach(user => {
                        let option =
                            `<option value="${user.id}">${user.first_name} ${user.last_name}</option>`;
                        dropdown.append(option);
                    });
                } else {
                    console.log('No users found');
                }

            }

            // Populate user dropdown and set up change event
            $(document).ready(async function() {
                await getEmployeeList();

                $('#userDropdown').on('change', async function() {
                    userId = $(this).val(); // Get selected user ID
                    let userName = $('#userDropdown option:selected').text();
                    $('#user_name').val(userName);

                    // Render table for the selected user
                    await renderEmployeeFamilyTable();
                });
            });

            $(document).on('click', '#add-new-user-family-btn', function() {
                resetForm();
                title = `Add New Employee Family`;
                $('#user-family-form-title').html(title);
                $('#user-family-form-modal').modal('show');
            });
            $(document).on('click', '#user-family-submit-confirm', async function() {
                const family_id = $('#family_id').val();
                const name = $('#name').val();
                const relationship = $('#relationship').val();
                const dob = $('#dob').val();
                const nic = $('#nic').val();
                const gender = $('#gender').val();
                const contact_1 = $('#contact_1').val();
                const contact_2 = $('#contact_2').val();
                const address_1 = $('#address_1').val();
                const address_2 = $('#address_2').val();
                const notes = $('#notes').val();
                const user_family_status = $('#user_family_status').val();

                let createUrl = `/company/user_family/create`;
                let updateUrl = `/company/user_family/update/${family_id}`;

                let formData = new FormData();
                
                if (!name || !relationship ||!dob || !gender ) {
                    $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                    return;
                } else {
                    $('#error-msg').html(''); // Clear error message if no issues
                }
                
                formData.append('user_id', userId);
                formData.append('name', name);
                formData.append('relationship', relationship);
                formData.append('dob', dob);
                formData.append('nic', nic);
                formData.append('gender', gender);
                formData.append('contact_1', contact_1); 
                formData.append('contact_2', contact_2); 
                formData.append('address_1', address_1); 
                formData.append('address_2', address_2); 
                formData.append('notes', notes); 
                formData.append('user_family_status', user_family_status);

                const isUpdating = Boolean(family_id);
                let url = isUpdating ? updateUrl : createUrl;
                let method = isUpdating ? 'PUT' : 'POST';

                try {
                    let res = await commonSaveData(url, formData, method);
                    await commonAlert(res.status, res.message);

                    if (res.status === 'success') {
                        $('#user-family-form-modal').modal('hide');
                        await renderEmployeeFamilyTable(); // Re-render table on success
                    }
                } catch (error) {
                    console.error('Error:', error);
                    $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
                }
            });


            // edit click event
            $(document).on('click', '.click-edit-user-family', async function() {
                // resetForm();
                let family_id = $(this).closest('tr').attr('family_id');


                // Get branch data by id
                try {
                    let family_data = await commonFetchData(
                        `/company/single_user_family/${family_id}`);
                        family_data = family_data[0];
                    console.log('family_data', family_data);

                    // Set initial form values
                    $('#family_id').val(family_id);
                    $('#name').val(family_data?.name || '');
                    $('#relationship').val(family_data?.relationship || '');
                    $('#dob').val(family_data?.dob || '');
                    $('#nic').val(family_data?.nic || '');
                    $('#gender').val(family_data?.gender || '');
                    $('#contact_1').val(family_data?.contact_1 || '');
                    $('#contact_2').val(family_data?.contact_2 || '');
                    $('#address_1').val(family_data?.address_1 || '');
                    $('#address_2').val(family_data?.address_2 || '');
                    $('#notes').val(family_data?.notes || '');
                    $('#user_family_status').val(family_data?.status || '');
                    // Load the country, province, and city accordingly


                } catch (error) {
                    console.error('error at getWorkExperienceById: ', error);
                } finally {
                    title = `Edit Employee Family`;
                    $('#user-family-form-title').html(title);
                    $('#user-family-form-modal').modal('show');
                }
            });
            $(document).on('click', '.click-delete-user-family', async function() {
                let family_id = $(this).closest('tr').attr('family_id');

                try {
                    let url = `/company/user_family/delete`;
                    const res = await commonDeleteFunction(family_id, url,
                        'Employee Family'); // Await the promise here

                    if (res) {
                        await renderEmployeeFamilyTable();
                    }
                } catch (error) {
                    console.error(`Error during Employee Family deletion:`, error);
                }
            })

            function resetForm() {
                $('#family_id').val('');
                $('#name').val('');
                $('#relationship').val('');
                $('#dob').val('');
                $('#nic').val('');
                $('#gender').val('');
                $('#contact_1').val('');
                $('#contact_2').val('');
                $('#address_1').val('');
                $('#address_2').val('');
                $('#notes').val('');
                $('#user_family_status').val('active'); // Reset status to default
                $('#error-msg').html(''); // Clear error messages
            }
        </script>
</x-app-layout>
