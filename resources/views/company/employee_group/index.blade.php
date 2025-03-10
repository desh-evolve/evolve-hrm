<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Group') }}</h4>
    </x-slot>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Employee Group</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add_new_group_btn">New Employee Group <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Employee Group Name</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="group_table_body">
                            <tr>
                                <td colspan="5" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="group-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="group-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="branch-form-body" class="row">
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="country_name" class="form-label mb-1 req">Group Name</label>
                            <input type="text" class="form-control" id="emp_group_name"
                                placeholder="Enter Group Name" value="">
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="group_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="group_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="group_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="group-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        //=================================================================================================
        // Render Tables
        //=================================================================================================
        let clicked_country_id = '';
        let clicked_province_id = '';

        $(document).ready(async function() {
            renderGroupTable();
        });


        async function renderGroupTable() {
            let list = '';
            $('#table_body').html('<p>Loading...</p>');
            let items = await commonFetchData('/company/employee_groups');


            if (items && items.length > 0) {
                items.map((item, i) => {
                    list += `
                            <tr group_id="${item.id}"">
                            <td>${i + 1}</td>
                            <td>${item.emp_group_name}</td>
                            <td class="text-capitalize">${item.status === 'active' ? `<span class="badge border border-success text-success">${item.status}</span>` : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_group" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_group" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            } else {
                list = `<tr><td  colspan="5" class="text-center text-danger">No Employee Group Yet!</td></tr>`;
            }

            $('#group_table_body').html(list);
        }

        //=================================================================================================
        // Form Modal
        //=================================================================================================

        $(document).on('click', '#add_new_group_btn', function() {
           resetForm();
            title = `Add New Employee Group`;
            $('#group-form-title').html(title);
            $('#group-form-modal').modal('show');
        });

        // edit click event
        $(document).on('click', '.click_edit_group', async function() {
            resetForm();
            let group_id = $(this).closest('tr').attr('group_id');


            // Get branch data by id
            try {
                let group_data = await commonFetchData(`/company/employee_group/${group_id}`);
                group_data = group_data[0];
                console.log('group_data', group_data);

                // Set initial form values
                $('#group_id').val(group_id);
                $('#emp_group_name').val(group_data?.emp_group_name || '');
                $('#group_status').val(group_data?.status || '');
                // Load the country, province, and city accordingly


            } catch (error) {
                console.error('error at getGroupById: ', error);
            } finally {
                title = `Edit Employee Group`;
                $('#group-form-title').html(title);
                $('#group-form-modal').modal('show');
            }
        });

        $(document).on('click', '#group-submit-confirm', async function() {
            const group_id = $('#group_id').val();
            const emp_group_name = $('#emp_group_name').val();
            const group_status = $('#group_status').val();

            let createUrl = `/company/employee_group/create`;
            let updateUrl = `/company/employee_group/update/${group_id}`;

            let formData = new FormData();


            if (!emp_group_name || !group_status) {
                $('#error-msg').html('<p class="text-danger">All fields are required!');
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }

            formData.append('emp_group_name', emp_group_name);
            formData.append('group_status', group_status);

            // Append branch_id if updating
            const isUpdating = Boolean(group_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('id', group_id);
                method = 'PUT';
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    renderGroupTable();
                    $('#group-form-modal').modal('hide');
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        //=================================================================================================
        // delete items
        //=================================================================================================

        // Generalized delete function
        $(document).on('click', '.click_delete_group', async function() {
            let group_id = $(this).closest('tr').attr('group_id');

            try {
                let url = `/company/employee_group/delete`;
                const res = await commonDeleteFunction(group_id, url,
                'Employee Group'); // Await the promise here

                if (res) {
                    await renderGroupTable();
                }
            } catch (error) {
                console.error(`Error during Group deletion:`, error);
            }
        })

        function resetForm() {
            $('#group_id').val('');
            $('#emp_group_name').val('');
            $('#group_status').val('active');
            $('#error-msg').html('');
        }
    </script>
</x-app-layout>
