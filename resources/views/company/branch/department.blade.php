<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">
   
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Departments') }}</h4>
    </x-slot>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Departments</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_add_department">New Department <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Department Name</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="department_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="department-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="department-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="department-form-body" class="row">
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="department_name" class="form-label mb-1 req">Department Name</label>
                            <input type="text" class="form-control" id="department_name" placeholder="Enter Department Name" value="" >
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="department_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="department_status" >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <hr>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="branches" class="form-label mb-1 req">Branches</label>
                            <select class="form-select" id="branches" >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="department_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="department-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            renderDepartmentTable();
        })

        async function renderDepartmentTable(){
            let list = '';

            const departments = await commonFetchData('/company/departments');

            if(departments && departments.length > 0){
                departments.map((department, i) => {
                    list += `
                        <tr department_id="${department.id}"">
                            <td>${i + 1}</td>
                            <td>${department.department_name}</td>
                            <td class="text-capitalize">${department.status === 'active' ? `<span class="badge border border-success text-success">${department.status}</span>` : `<span class="badge border border-warning text-warning">${department.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_department" title="Edit Department" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_department" title="Delete Department" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            }else{
                list = '<tr><td colspan="7" class="text-center">No Departments Found!</td></tr>';
            }
            

            $('#department_table_body').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        }


        // check here
        $(document).on('click', '#click_add_department', function(){
            resetForm();
            $('#department-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_department', async function() {
            resetForm();
            let department_id = $(this).closest('tr').attr('department_id');

            // Get department data by id
            try {
                let department_data = await commonFetchData(`/company/department/${department_id}`);
                department_data = department_data[0];
                console.log('department_data', department_data);

                // Set initial form values
                $('#department_id').val(department_id);
                $('#department_name').val(department_data?.department_name || '');
                $('#short_name').val(department_data?.short_name || '');
                $('#address_1').val(department_data?.address_1 || '');
                $('#address_2').val(department_data?.address_2 || '');
                $('#contact_1').val(department_data?.contact_1 || '');
                $('#contact_2').val(department_data?.contact_2 || '');
                $('#email').val(department_data?.email || '');
                $('#currency_id').val(department_data?.currency_id || '');
                $('#department_status').val(department_data?.status || '');

                // Load the country, province, and city accordingly
                const countryId = department_data?.country_id || '';
                const provinceId = department_data?.province_id || '';
                const cityId = department_data?.city_id || '';

                // Load countries and set selected country
                $('#country_id').val(countryId).trigger('change');

                // Load provinces based on the selected country, and set selected province
                await loadProvinces(countryId); // Ensures provinces are loaded before proceeding
                $('#province_id').val(provinceId).trigger('change');

                // Load cities based on the selected province, and set selected city
                await loadCities(provinceId); // Ensures cities are loaded before proceeding
                $('#city_id').val(cityId);

            } catch (error) {
                console.error('error at getDepartmentById: ', error);
            } finally {
                $('#department-form-modal').modal('show');
            }
        });

        $(document).on('click', '.click_delete_department', async function(){
            let department_id = $(this).closest('tr').attr('department_id');

            try {
                let url = `/company/department/delete`;
                const res = await commonDeleteFunction(department_id, url, 'Department');  // Await the promise here

                if (res) {
                    await renderDepartmentTable();
                }
            } catch (error) {
                console.error(`Error during department deletion:`, error);
            }
        })

        $(document).on('click', '#department-submit-confirm', async function() {
            const department_id = $('#department_id').val();

            let createUrl = `/company/department/create`;
            let updateUrl = `/company/department/update/${department_id}`;

            const formFields = {
                department_name: 'required',
                short_name: '', // Not required
                address_1: 'required',
                address_2: '', // Not required
                country_id: 'required',
                province_id: 'required',
                city_id: 'required',
                contact_1: 'required',
                contact_2: '', // Not required
                email: '', // Not required
                currency_id: 'required',
                department_status: 'required'
            };

            let formData = new FormData();
            let missingFields = [];

            // Validate only required fields
            for (const key in formFields) {
                const fieldId = key;
                const value = $('#' + fieldId).val(); // Fetch value using the ID

                // Check only required fields
                if (formFields[key] === 'required' && !value) {
                    missingFields.push(fieldId); // Collect missing required fields
                } 

                // Append all fields to formData (even optional ones)
                formData.append(key, value || ''); // Append empty string if no value for optional fields
            }

            // If there are missing required fields, display an error message
            if (missingFields.length > 0) {
                let errorMsg = '<p class="text-danger">The following fields are required: ';
                errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
                $('#error-msg').html(errorMsg);
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }

            // Append department_id if updating
            const isUpdating = Boolean(department_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('department_id', department_id);
                method = 'PUT';
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    renderDepartmentTable();
                    $('#department-form-modal').modal('hide');
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm(){
            
        }

    </script>
</x-app-layout>