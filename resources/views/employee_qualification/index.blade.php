<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee qualification') }}</h4>
    </x-slot>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 flex-grow-1">Employee qualification</h5>
                    </div>

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_qualification_btn">New Qualification <i class="ri-add-line"></i></button>
                            <a href="/employee/list" class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </div>


                <div class="card-body">

                    <div class="row mb-3 mb-4">
                        <div class="col-lg-2 d-flex align-items-center">
                            <label for="user_idname" class="form-label mb-1 req">Employee Name</label>
                        </div>

                        <div class="col-lg-10">
                            <select class="form-select form-select-sm" id="userDropdown">
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col">#</th>
                                <th class="col">qualification</th>
                                <th class="col">Institute</th>
                                <th class="col">Year</th>
                                <th class="col">Remark</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="qualification_table_body">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div id="qualification-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="qualification-form-title">New Qualification</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="qualification-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="user_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="user_name" value="" disabled>
                            <input type="hidden" class="form-control" id="user_id" value="" disabled>
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="qualification" class="form-label">qualification</label>
                            <input type="text" class="form-control" id="qualification" rows="3">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="institute" class="form-label">Institute</label>
                            <input type="text" class="form-control" id="institute" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" class="form-control" id="year" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="remarks" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="remarks" rows="3">
                        </div>

                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="qualification_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="qualification_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="qualification_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary" id="qualification-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>

    let userId = "{{ $user->id }}";

    $(document).ready(async function() {
        await getEmployeeList();


        $('#userDropdown').on('change', async function() {
            userId = $(this).val(); // Get selected user ID
            let userName = $('#userDropdown option:selected').text();
            $('#user_name').val(userName);

            // Enable button if user is selected
            if (userId) {
                $('#add_new_qualification_btn').prop('disabled', false);
            } else {
                $('#add_new_qualification_btn').prop('disabled', true);
            }

            // Render qualifications table for the selected user
            await renderQualificationsTable();
        });

        //===========================================================================
        // get dropdown list
        //===========================================================================

        async function getEmployeeList() {
            let users = await commonFetchData('/employee/qualification/dropdown');

            // Check if users data is valid
            if (users && users.length > 0) {
                // Target the dropdown element
                let dropdown = $('#userDropdown');

                // Clear existing options (optional)
                dropdown.empty();
                dropdown.append('<option value="">Select Employee</option>'); // Add a default option

                // Loop through the users and add options
                users.forEach(user => {
                    let isSelected = user.id == userId ? "selected" : ""; // Pre-select if IDs match
                    let option = `<option value="${user.id}" ${isSelected}>${user.first_name} ${user.last_name}</option>`;
                    dropdown.append(option);

                    if (isSelected) {
                        userName = `${user.first_name} ${user.last_name}`;
                    }
                });

                if (userId) {
                    $('#userDropdown').val(userId); // Set the dropdown value
                    $('#user_name').val(userName); // Display the name in the input field
                    await renderQualificationsTable(userId); 
                }
            } else {
                console.log('No users found');
            }
        }

        //===========================================================================
        // render table
        //===========================================================================

        async function renderQualificationsTable() {
            if (!userId) {
                $('#qualification_table_body').html(
                    '<tr><td colspan="7" class="text-center">Please Select Employee...</td></tr>');
                return;
            }

            let users_qualifications = await commonFetchData(`/employee/qualification/${userId}`);
            let list = '';

            if (users_qualifications && users_qualifications.length > 0) {
                users_qualifications.forEach((user, i) => {
                    list += `
                        <tr qualification_id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.qualification}</td>
                            <td>${user.institute}</td>
                            <td>${user.year}</td>
                            <td>${user.remarks}</td>
                            <td class="text-capitalize">${user.status === 'active'
                                ? `<span class="badge border border-success text-success">${user.status}</span>`
                                : `<span class="badge border border-warning text-warning">${user.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-qualification" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_qualification" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr> `;
                    });
            } else {
                list = `<tr><td colspan="7" class="text-danger text-center">No Qualification Yet!</td></tr>`;
            }

            $('#qualification_table_body').html(list);
        }


        //===========================================================================
        // Add & Edit
        //===========================================================================

        $(document).on('click', '#add_new_qualification_btn', function() {
            resetForm();
            title = `Add New qualification`;
            $('#qualification-form-title').html(title);
            $('#qualification-form-modal').modal('show');
        });


        $(document).on('click', '#qualification-submit-confirm', async function() {
            const qualification_id = $('#qualification_id').val();
            const qualification = $('#qualification').val();
            const institute = $('#institute').val();
            const year = $('#year').val();
            const remarks = $('#remarks').val(); // Correctly fetch the remarks value
            const qualification_status = $('#qualification_status').val();

            let createUrl = `/employee/qualification/create`;
            let updateUrl = `/employee/qualification/update/${qualification_id}`;

            let formData = new FormData();

            if (!qualification || !institute || !year || !qualification_status) {
                $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }
            console.log('Remarks:', remarks);

            formData.append('user_id', userId);
            formData.append('qualification', qualification);
            formData.append('institute', institute);
            formData.append('year', year);
            formData.append('remarks', remarks); // Use the fetched remarks value here
            formData.append('qualification_status', qualification_status);

            const isUpdating = Boolean(qualification_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    $('#qualification-form-modal').modal('hide');
                    await renderQualificationsTable(); // Re-render table on success
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        // edit click event
        $(document).on('click', '.click-edit-qualification', async function() {
            // resetForm();
            let qualification_id = $(this).closest('tr').attr('qualification_id');

            // Get branch data by id
            try {
                let qualification_data = await commonFetchData(`/employee/single_qualification/${qualification_id}`);
                qualification_data = qualification_data[0];
                console.log('qualification_data', qualification_data);

                // Set initial form values
                $('#qualification_id').val(qualification_id);
                $('#qualification').val(qualification_data?.qualification || '');
                $('#institute').val(qualification_data?.institute || '');
                $('#year').val(qualification_data?.year || '');
                $('#remarks').val(qualification_data?.remarks || '');
                $('#qualification_status').val(qualification_data?.status || '');
                // Load the country, province, and city accordingly

            } catch (error) {
                console.error('error at getQulificationById: ', error);
            } finally {
                title = `Edit Qualification`;
                $('#qualification-form-title').html(title);
                $('#qualification-form-modal').modal('show');
            }
        });


        //===========================================================================
        // Delete
        //===========================================================================

        $(document).on('click', '.click_delete_qualification', async function() {
            let qualification_id = $(this).closest('tr').attr('qualification_id');

            try {
                let url = `/employee/qualification/delete`;
                const res = await commonDeleteFunction(qualification_id, url,'Qualification');

                if (res) {
                    await renderQualificationsTable();
                }
            } catch (error) {
                console.error(`Error during Qualification deletion:`, error);
            }
        })


    });



    //reset function
    function resetForm() {
        $('#qualification_id').val('');
        $('#qualification').val('');
        $('#institute').val('');
        $('#year').val('');
        $('#remarks').val('');
        $('#qualification_status').val('active');
        $('#error-msg').html('');
    }


</script>

</x-app-layout>
