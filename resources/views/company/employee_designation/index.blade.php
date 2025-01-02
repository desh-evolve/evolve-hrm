<x-app-layout :title="'Input Example'">

    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Designation') }}</h4>
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
                        <h5 class="mb-0">Designation</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="add_new_designation_btn">New Designation <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white"/>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Employee Designation Name</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="designation_table_body">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="designation-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="designation-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="branch-form-body" class="row">
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="country_name" class="form-label mb-1 req">Designation Name</label>
                            <input type="text" class="form-control" id="emp_designation_name" placeholder="Enter Designation Name" value="" >
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="designation_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="designation_status" >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="designation_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="designation-submit-confirm">Submit</button>
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
            renderDesignationTable();
        });


        async function renderDesignationTable() {
            $('#add_new_province_btn').hide();
            $('#add_new_city_btn').hide();

            let list = '';
            $('#table_body').html('<p>Loading...</p>');
            let items = await commonFetchData('/company/user_designations');

       
            if (items && items.length > 0) {
                items.map((item, i) => {
                    list += `
                            <tr designation_id="${item.id}"">
                            <td>${i + 1}</td>
                            <td>${item.emp_designation_name}</td>
                            <td class="text-capitalize">${item.status === 'active' ? `<span class="badge border border-success text-success">${item.status}</span>` : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_designation" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_designation" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            } else {
                list = `<p class="text-danger">No Designation Yet!</p>`;
            }

            $('#designation_table_body').html(list);
        }

        //=================================================================================================
        // Form Modal
        //=================================================================================================

        $(document).on('click', '#add_new_designation_btn', function() {
            // formModal('designation');

            resetForm();
            title = `Add New Designation`;
            $('#designation-form-title').html(title);
            $('#designation-form-modal').modal('show');
        });

        // edit click event
        $(document).on('click', '.click_edit_designation', async function() {
            resetForm();
            let designation_id = $(this).closest('tr').attr('designation_id');
            

            // Get branch data by id
            try {
                let designation_data = await commonFetchData(`/company/user_designation/${designation_id}`);
                designation_data = designation_data[0];
                console.log('designation_data', designation_data);

                // Set initial form values
                $('#designation_id').val(designation_id);
                $('#emp_designation_name').val(designation_data?.emp_designation_name || '');
                $('#designation_status').val(designation_data?.status || '');
                // Load the country, province, and city accordingly
                

            } catch (error) {
                console.error('error at getBranchById: ', error);
            } finally {
                title = `Edit Designation`;
                $('#designation-form-title').html(title);
                $('#designation-form-modal').modal('show');
            }
        });

        $(document).on('click', '#designation-submit-confirm', async function() {
            const designation_id = $('#designation_id').val();
            const emp_designation_name = $('#emp_designation_name').val();
            const designation_status = $('#designation_status').val();

            let createUrl = `/company/user_designation/create`;
            let updateUrl = `/company/user_designation/update/${designation_id}`;

            let formData = new FormData();
            

            if(!emp_designation_name || !designation_status){
                $('#error-msg').html('<p class="text-danger">All fields are required: ');
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }
            
            formData.append('emp_designation_name', emp_designation_name );
            formData.append('designation_status', designation_status );

            // Append branch_id if updating
            const isUpdating = Boolean(designation_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('id', designation_id);
                method = 'PUT';
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    renderDesignationTable();
                    $('#designation-form-modal').modal('hide');
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
        $(document).on('click', '.click_delete_designation', async function(){
            let designation_id = $(this).closest('tr').attr('designation_id');

            try {
                let url = `/company/user_designation/delete`;
                const res = await commonDeleteFunction(designation_id, url, 'Designation');  // Await the promise here

                if (res) {
                    await renderDesignationTable();
                }
            } catch (error) {
                console.error(`Error during Designation deletion:`, error);
            }
        })

        function resetForm() {
            $('#designation_id').val('');
            $('#emp_designation_name').val('');
            $('#designation_status').val('active');
        }
    </script>
</x-app-layout>
