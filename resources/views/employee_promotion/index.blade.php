<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Promotion') }}</h4>
    </x-slot>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Employee Promotion</h5>
                    </div>

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_promotion_btn">New Promotion <i class="ri-add-line"></i></button>
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
                                <th class="col">Current Designation</th>
                                <th class="col">New Designation</th>
                                <th class="col">Current Salary</th>
                                <th class="col">New Salary</th>
                                <th class="col">Effective Date</th>
                                <th class="col">Remarks</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="promotion-table-body">
                            <tr>
                                <td colspan="10" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="promotion-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="promotion-form-title">New Promotion</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="promotion-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="user_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="user_name" value="" disabled>
                            <input type="hidden" class="form-control" id="user_id" value="" disabled>
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="current_designation" class="form-label">Current Designation</label>
                            <input type="text" class="form-control" id="current_designation" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="new_designation" class="form-label">New Designation</label>
                            <input type="date" class="form-control" id="new_designation" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="current_salary" class="form-label">Current Salary</label>
                            <input type="text" class="form-control" id="current_salary" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="new_salary" class="form-label">New Salary</label>
                            <input type="text" class="form-control" id="new_salary" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="effective_date" class="form-label">Effective Date</label>
                            <input type="date" class="form-control" id="effective_date" rows="3">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <input type="text" class="form-control" id="remarks" rows="3">
                        </div>

                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="promotion_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="promotion_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="promotion_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary" id="promotion-submit-confirm">Submit</button>
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

            if (userId) {
                $('#add_new_promotion_btn').prop('disabled', false);
            } else {
                $('#add_new_promotion_btn').prop('disabled', true);
            }

            // Render promotion table for the selected user
            await renderPromotionTable();
        });


        //===========================================================================
        // render table
        //===========================================================================

        async function renderPromotionTable() {
            if (!userId) {
                $('#promotion-table-body').html(
                    '<tr><td colspan="10" class="text-center">No Employee Selected</td></tr>');
                return;
            }

            let users_promotion = await commonFetchData(`/employee/promotion/${userId}`);
            console.log('Fetched Promotions:', users_promotion);

            let list = '';

            if (users_promotion && users_promotion.length > 0) {
                users_promotion.forEach((item, i) => {
                    list += `
                        <tr promotion_id="${item.id}">
                            <td>${i + 1}</td>
                            <td>${item.current_designation}</td>
                            <td>${item.new_designation}</td>
                            <td>${item.current_salary}</td>
                            <td>${item.new_salary}</td>
                            <td>${item.effective_date}</td>
                            <td>${item.remarks}</td>
                            <td class="text-capitalize">${item.status === 'active'
                                ? `<span class="badge border border-success text-success">${item.status}</span>`
                                : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-promotion" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_promotion" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr> `;
                    });
            } else {
                list = `<tr><td colspan="10" class="text-danger text-center">No Promotion Yet!</td></tr>`;
            }

            $('#promotion-table-body').html(list);
        }


        //===========================================================================
        // get dropdown list
        //===========================================================================

        async function getEmployeeList() {
            let users = await commonFetchData('/employee/promotion/dropdown');

            // Check if users data is valid
            if (users && users.length > 0) {
                // Target the dropdown element
                let dropdown = $('#userDropdown');

                // Clear existing options (optional)
                dropdown.empty();
                dropdown.append('<option value="">Select Employee</option>'); // Add a default option

                // Loop through the users and add options
                users.forEach(user => {
                    let isSelected = user.id == userId ? "selected" : "";
                    let option = `<option value="${user.id}" ${isSelected}>${user.first_name} ${user.last_name}</option>`;
                    dropdown.append(option);

                    if (isSelected) {
                        userName = `${user.first_name} ${user.last_name}`;
                    }

                });

                if (userId) {
                    $('#userDropdown').val(userId); // Set the dropdown value
                    $('#user_name').val(userName); // Display the name in the input field
                    await renderPromotionTable(userId);
                }
            } else {
                console.log('No users found');
            }

        }


        //===========================================================================
        // Add & Edit
        //===========================================================================

        $(document).on('click', '#add_new_promotion_btn', function() {
            resetForm();
            title = `Add New Promotion`;
            $('#promotion-form-title').html(title);
            $('#promotion-form-modal').modal('show');
        });


        $(document).on('click', '#promotion-submit-confirm', async function() {
            const promotion_id = $('#promotion_id').val();
            const current_designation = $('#current_designation').val();
            const new_designation = $('#new_designation').val();
            const current_salary = $('#current_salary').val();
            const new_salary = $('#new_salary').val();
            const effective_date = $('#effective_date').val();
            const remarks = $('#remarks').val(); // Correctly fetch the remarks value
            const promotion_status = $('#promotion_status').val();

            let createUrl = `/employee/promotion/create`;
            let updateUrl = `/employee/promotion/update/${promotion_id}`;

            let formData = new FormData();

            if (!current_designation || !new_designation ||!current_salary || !new_salary || !effective_date) {
                $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }

            formData.append('user_id', userId);
            formData.append('current_designation', current_designation);
            formData.append('new_designation', new_designation);
            formData.append('current_salary', current_salary);
            formData.append('new_salary', new_salary);
            formData.append('effective_date', effective_date);
            formData.append('remarks', remarks); // Use the fetched remarks value here
            formData.append('promotion_status', promotion_status);

            const isUpdating = Boolean(promotion_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    $('#promotion-form-modal').modal('hide');
                    await renderPromotionTable(); // Re-render table on success
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        // edit click event
        $(document).on('click', '.click-edit-promotion', async function() {
            // resetForm();
            let promotion_id = $(this).closest('tr').attr('promotion_id');


            // Get branch data by id
            try {
                let promotion_data = await commonFetchData(`/employee/single_promotion/${promotion_id}`);
                    promotion_data = promotion_data[0];
                console.log('promotion_data', promotion_data);

                // Set initial form values
                $('#promotion_id').val(promotion_id);
                $('#current_designation').val(promotion_data?.current_designation || '');
                $('#new_designation').val(promotion_data?.new_designation || '');
                $('#current_salary').val(promotion_data?.current_salary || '');
                $('#new_salary').val(promotion_data?.new_salary || '');
                $('#effective_date').val(promotion_data?.effective_date || '');
                $('#remarks').val(promotion_data?.remarks || '');
                $('#promotion_status').val(promotion_data?.status || '');
                // Load the country, province, and city accordingly


            } catch (error) {
                console.error('error at getWorkExperienceById: ', error);
            } finally {
                title = `Edit Promotion`;
                $('#promotion-form-title').html(title);
                $('#promotion-form-modal').modal('show');
            }
        });


        $(document).on('click', '.click_delete_promotion', async function() {
            let promotion_id = $(this).closest('tr').attr('promotion_id');

            try {
                let url = `/employee/promotion/delete`;
                const res = await commonDeleteFunction(promotion_id, url, 'Promotion'); // Await the promise here

                if (res) {
                    await renderPromotionTable();
                }
            } catch (error) {
                console.error(`Error during Promotion deletion:`, error);
            }
        })

    });



    function resetForm() {
        $('#promotion_id').val('');
        $('#current_designation').val('');
        $('#new_designation').val('');
        $('#current_salary').val('');
        $('#new_salary').val('');
        $('#effective_date').val('');
        $('#remarks').val('');
        $('#promotion_status').val('active'); // Reset status to default
        $('#error-msg').html(''); // Clear error messages
    }


</script>

</x-app-layout>
