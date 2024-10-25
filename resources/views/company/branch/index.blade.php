<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">

    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Branches') }}</h4>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Branches</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_add_branch">New Branch <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Branch Name</th>
                                <th class="col">Address</th>
                                <th class="col">Contact</th>
                                <th class="col">Currency</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="branch_table_body">
                            <tr><td colspan="7" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="branch-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="branch-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="branch-form-body" class="row">
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="branch_name" class="form-label mb-1 req">Branch Name</label>
                            <input type="text" class="form-control" id="branch_name" placeholder="Enter Branch Name" value="" >
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="short_name" class="form-label mb-1">Branch Short Name</label>
                            <input type="text" class="form-control" id="short_name" placeholder="Enter Branch Short Name" value="">
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="address_1" class="form-label mb-1 req">Address Line 1</label>
                            <input type="text" class="form-control" id="address_1" placeholder="Enter Address Line 1" value="" >
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="address_2" class="form-label mb-1">Address Line 2</label>
                            <input type="text" class="form-control" id="address_2" placeholder="Enter Address Line 2" value="">
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="country_id" class="form-label mb-1 req">Country</label>
                            <select class="form-select" id="country_id" >
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="province_id" class="form-label mb-1 req">Province</label>
                            <select class="form-select" id="province_id" >
                                <option value="">Select a country first</option>
                            </select>
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="city_id" class="form-label mb-1 req">City</label>
                            <select class="form-select" id="city_id" >
                                <option value="">Select a country first</option>
                            </select>
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="contact_1" class="form-label mb-1 req">Contact 1</label>
                            <input type="text" class="form-control" id="contact_1" placeholder="Enter Contact 1" value="" >
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="contact_2" class="form-label mb-1">Contact 2</label>
                            <input type="text" class="form-control" id="contact_2" placeholder="Enter Contact 2" value="">
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="email" class="form-label mb-1">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email" value="">
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="currency_id" class="form-label mb-1 req">Currency</label>
                            <select class="form-select" id="currency_id" >
                                <option value="">Select a currency</option>
                            </select>
                        </div>
                        <div class="col-xxl-4 col-md-4 mb-3">
                            <label for="branch_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="branch_status" >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="branch_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="branch-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(function(){
            getDropdownData();
            renderBranchTable();
        })

        async function renderBranchTable(){
            let list = '';

            const branches = await commonFetchData('/company/branches');

            if(branches && branches.length > 0){
                branches.map((branch, i) => {
                    list += `
                        <tr branch_id="${branch.id}">
                            <td>${i + 1}</td>
                            <td>${branch.branch_name +' '+ (branch.short_name && '('+ branch.short_name +')')}</td>
                            <td>
                                ${branch.address_1 + (branch.address_2 && ', '+ branch.address_2)}
                                <br>
                                ${branch.city_name + ', ' + branch.province_name + ', ' + branch.country_name}
                            </td>
                            <td>${branch.branch_name}</td>
                            <td>${branch.currency_name + (branch.iso_code && '('+ branch.iso_code + ')')}</td>
                            <td class="text-capitalize">${branch.status === 'active' ? `<span class="badge border border-success text-success">${branch.status}</span>` : `<span class="badge border border-warning text-warning">${branch.status}</span>`}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_branch" title="Edit Branch" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_branch" title="Delete Branch" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            }else{
                list = '<tr><td colspan="7" class="text-center">No Branches Found!</td></tr>';
            }


            $('#branch_table_body').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        }

        //=====================================================================================
        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/company/branch/dropdown');

                // Populate country dropdown
                let countryList = (dropdownData?.countries || [])
                    .map(country => `<option value="${country.id}">${country.country_name} (${country.country_code})</option>`)
                    .join('');
                $('#country_id').html('<option value="">Select a country</option>' + countryList);

                // Default values for province and city
                $('#province_id').html('<option value="">Select a country first</option>');
                $('#city_id').html('<option value="">Select a country first</option>');

                // Populate currency dropdown
                let currencyList = (dropdownData?.currencies || [])
                    .map(currency => `<option value="${currency.id}">${currency.currency_name} (${currency.iso_code})</option>`)
                    .join('');
                $('#currency_id').html('<option value="">Select a currency</option>' + currencyList);
            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('change', '#country_id', function () {
            let country_id = $(this).val();
            loadProvinces(country_id);
        });

        $(document).on('change', '#province_id', function () {
            let province_id = $(this).val();
            loadCities(province_id);
        });

        async function loadProvinces(countryId) {
            // Check if a country is selected
            if (!countryId) {
                $('#province_id').html('<option value="">Select a country first</option>');
                $('#city_id').html('<option value="">Select a province first</option>');
                return;
            }

            // Filter provinces by country_id
            const provinceList = (dropdownData?.provinces || [])
                .filter(province => province.country_id == countryId)
                .map(province => `<option value="${province.id}">${province.province_name}</option>`)
                .join('');

            // Populate the province dropdown
            $('#province_id').html('<option value="">Select a province</option>' + provinceList);
            $('#city_id').html('<option value="">Select a province first</option>');
        }

        async function loadCities(provinceId) {
            // Check if a province is selected
            if (!provinceId) {
                $('#city_id').html('<option value="">Select a province first</option>');
                return;
            }

            // Filter cities by province_id
            const cityList = (dropdownData?.cities || [])
                .filter(city => city.province_id == provinceId)
                .map(city => `<option value="${city.id}">${city.city_name}</option>`)
                .join('');

            // Populate the city dropdown
            $('#city_id').html('<option value="">Select a city</option>' + cityList);
        }
        //=====================================================================================

        $(document).on('click', '#click_add_branch', function(){
            resetForm();
            $('#branch-form-title').text('Add Branch');
            $('#branch-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_branch', async function() {
            resetForm();
            let branch_id = $(this).closest('tr').attr('branch_id');

            // Get branch data by id
            try {
                let branch_data = await commonFetchData(`/company/branch/${branch_id}`);
                branch_data = branch_data[0];
                console.log('branch_data', branch_data);

                // Set initial form values
                $('#branch_id').val(branch_id);
                $('#branch_name').val(branch_data?.branch_name || '');
                $('#short_name').val(branch_data?.short_name || '');
                $('#address_1').val(branch_data?.address_1 || '');
                $('#address_2').val(branch_data?.address_2 || '');
                $('#contact_1').val(branch_data?.contact_1 || '');
                $('#contact_2').val(branch_data?.contact_2 || '');
                $('#email').val(branch_data?.email || '');
                $('#currency_id').val(branch_data?.currency_id || '');
                $('#branch_status').val(branch_data?.status || '');

                // Load the country, province, and city accordingly
                const countryId = branch_data?.country_id || '';
                const provinceId = branch_data?.province_id || '';
                const cityId = branch_data?.city_id || '';

                // Load countries and set selected country
                $('#country_id').val(countryId).trigger('change');

                // Load provinces based on the selected country, and set selected province
                await loadProvinces(countryId); // Ensures provinces are loaded before proceeding
                $('#province_id').val(provinceId).trigger('change');

                // Load cities based on the selected province, and set selected city
                await loadCities(provinceId); // Ensures cities are loaded before proceeding
                $('#city_id').val(cityId);

            } catch (error) {
                console.error('error at getBranchById: ', error);
            } finally {
                $('#branch-form-title').text('Edit Branch');
                $('#branch-form-modal').modal('show');
            }
        });

        $(document).on('click', '.click_delete_branch', async function(){
            let branch_id = $(this).closest('tr').attr('branch_id');

            try {
                let url = `/company/branch/delete`;
                const res = await commonDeleteFunction(branch_id, url, 'Branch');  // Await the promise here

                if (res) {
                    await renderBranchTable();
                }
            } catch (error) {
                console.error(`Error during branch deletion:`, error);
            }
        })

        $(document).on('click', '#branch-submit-confirm', async function() {
            const branch_id = $('#branch_id').val();

            let createUrl = `/company/branch/create`;
            let updateUrl = `/company/branch/update/${branch_id}`;

            const formFields = {
                branch_name: 'required',
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
                branch_status: 'required'
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

            // Append branch_id if updating
            const isUpdating = Boolean(branch_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('branch_id', branch_id);
                method = 'PUT';
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    renderBranchTable();
                    $('#branch-form-modal').modal('hide');
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm(){
            $('#branch_id').val('');
            $('#branch_name').val('');
            $('#short_name').val('');
            $('#address_1').val('');
            $('#address_2').val('');
            $('#country_id').val('');
            $('#province_id').val('');
            $('#city_id').val('');
            $('#contact_1').val('');
            $('#contact_2').val('');
            $('#email').val('');
            $('#currency_id').val('');
            $('#branch_status').val('active');
        }

    </script>

</x-app-layout>
