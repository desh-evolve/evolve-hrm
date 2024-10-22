<!-- desh(2024-10-18) -->
<x-app-layout :title="'Input Example'">
   
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Branch Management') }}</h4>

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
        .card-header:hover {
            background-color: #ddd;
        }
    </style>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Branches</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_branch_btn">New Branch <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="branch_list">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0" id="department_title">Departments</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_department_btn">New Department <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="department_list">
                    <p>Click on a branch to see departments</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0" id="division_title">Divisions</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_division_btn">New Division <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="division_list">
                    <p>Click on a branch to see departments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="branch-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="branch-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="branch-form-body" class="row">
                        
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="form_type" value="" />
                        <input type="hidden" id="form_id" value="" />
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="branch-submit-confirm">Submit</button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        let dropdownData = [];

        //=================================================================================================
        // render tables
        //=================================================================================================
        $(document).ready(async function(){
            renderBranchTable();
            $('#add_new_department_btn').hide();
            $('#add_new_division_btn').hide();
        })

        async function renderBranchTable(){
            let list = '';
            $('#branch_list').html('<p>Loading...</p>');
            let items = await commonFetchData('/company/branches');
            dropdownData = await commonFetchData('/company/branch/dropdown'); // getting dropdown data for forms

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 p-2 cursor-pointer branch_click" branch_id="${item.id}">
                                            <h6 class="card-title mb-0">${item.branch_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0 p-2">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button class="btn btn-success btn-sm align-middle minimize-card collapsed" data-bs-toggle="collapse" href="#collapse_${item.id}" role="button" aria-expanded="false" aria-controls="collapse_${item.id}" title="Expand Branch" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="mdi mdi-arrow-down align-middle plus"></i>
                                                        <i class="mdi mdi-arrow-up align-middle minus"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_branch" title="Edit Branch" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_branch" title="Delete Branch" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse" id="collapse_${item.id}" style="">
                                    <p class="card-text mb-0">${item.address_1}, ${item.address_2}</p>
                                    <p class="card-text mb-0">${item.city_name}, ${item.province_name}, ${item.country_name}</p>
                                    <p class="card-text mb-0">${item.contact_1}/${item.contact_2}</p>
                                    <p class="card-text">${item.email}</p>
                                </div>
                            </div>
                    `;
                })
            }else{
                list = `
                    <p class="text-danger">No Active Branches!</p>
                `;
            }

            $('#branch_list').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        }

        $(document).on('click', '.branch_click', async function(){
            let list = '';
            
            let branch_id = $(this).attr('branch_id');
            let branch_name = $(this).find('.card-title').text();
            $('#department_title').html(`Departments of ${branch_name}`);
            $('#department_list').html('<p>Loading...</p>');
            $('#division_title').html(`Divisions`);
            $('#division_list').html('<p>Click on a branch to see departments</p>');
            $('.branch_click').closest('.card-header').css({'background-color': 'white'});
            $(this).closest('.card-header').css({'background-color': '#ddd'});
            $('#add_new_department_btn').show();
            $('#add_new_division_btn').hide();

            let items = await commonFetchData(`/company/departments/${branch_id}`);

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 p-2 cursor-pointer department_click" department_id="${item.department_id}">
                                            <h6 class="card-title mb-0">${item.department_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0 p-2">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_department" title="Edit Department" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_department" title="Delete Department" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `;
                })
            }else{
                list = `
                    <p class="text-danger">No departments for this branch yet!</p>
                `;
            }

            $('#department_list').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        })

        $(document).on('click', '.department_click', async function(){
            let list = '';
            let department_id = $(this).attr('department_id');
            let department_name = $(this).find('.card-title').text();
            $('#division_title').html(`Divisions of ${department_name}`);
            $('#division_list').html('<p>Loading...</p>');
            $('.department_click').closest('.card-header').css({'background-color': 'white'});
            $(this).closest('.card-header').css({'background-color': '#ddd'});
            $('#add_new_division_btn').show();
            
            let items = await commonFetchData(`/company/divisions/${department_id}`);

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 p-2 cursor-pointer division_click" division_id="${item.id}">
                                            <h6 class="card-title mb-0">${item.division_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0 p-2">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_division" title="Edit Division" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_division" title="Delete Division" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `;
                })
            }else{
                list = `
                    <p class="text-danger">No divisions for this department yet!</p>
                `;
            }

            $('#division_list').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        })

    </script>

    <script>
        //=================================================================================================
        // render forms
        //=================================================================================================
        $(document).on('click', '#add_new_branch_btn', function(){
            formModal('branch');
        })

        $(document).on('click', '#add_new_department_btn', function(){
            formModal('department');
        })

        $(document).on('click', '#add_new_division_btn', function(){
            formModal('division');
        })

        $(document).on('click', '.click_edit_branch', async function(){
            let id = $(this).closest('.card-header').find('.branch_click').attr('branch_id');
            let url = `/company/branch/${id}`;
            let values = await commonFetchData(url);
            console.log('values', values)
            await formModal('branch', id, values?.data);
        })

        $(document).on('click', '.click_edit_department', async function(){
            let id = $(this).closest('.card-header').find('.department_click').attr('department_id');
            let url = `/company/department/${id}`;
            let values = await commonFetchData(url);
            console.log('values', values)
            await formModal('department', id, values?.data);
        })

        $(document).on('click', '.click_edit_division', async function(){
            let id = $(this).closest('.card-header').find('.division_click').attr('division_id');
            let url = `/company/division/${id}`;
            let values = await commonFetchData(url);
            console.log('values', values)
            await formModal('division', id, values?.data);
        })
        
        async function formModal(type, id = '', values = null){
            let form = '';
            if(type === 'branch'){
                form = `
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="branch_name" class="form-label mb-1 req">Branch Name</label>
                        <input type="text" class="form-control" id="branch_name" placeholder="Enter Branch Name" value="${values?.branch_name || ''}" required>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="short_name" class="form-label mb-1 req">Branch Short Name</label>
                        <input type="text" class="form-control" id="short_name" placeholder="Enter Branch Short Name" value="${values?.short_name || ''}" required>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="address_1" class="form-label mb-1 req">Address Line 1</label>
                        <input type="text" class="form-control" id="address_1" placeholder="Enter Address Line 1" value="${values?.address_1 || ''}" required>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="address_2" class="form-label mb-1 req">Address Line 2</label>
                        <input type="text" class="form-control" id="address_2" placeholder="Enter Address Line 2" value="${values?.address_2 || ''}" required>
                    </div>
                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="country_id" class="form-label mb-1 req">Country</label>
                        <select class="form-select" id="country_id" required>
                            <option value="">Select Country</option>
                            ${(dropdownData?.countries || []).map(country => `<option value="${country.id}">${country.country_name} (${country.country_code})</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="province_id" class="form-label mb-1 req">Province</label>
                        <select class="form-select" id="province_id" required>
                            <option value="">Select a country first</option>
                        </select>
                    </div>
                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="city_id" class="form-label mb-1 req">City</label>
                        <select class="form-select" id="city_id" required>
                            <option value="">Select a country first</option>
                        </select>
                    </div>
                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="contact_1" class="form-label mb-1 req">Contact 1</label>
                        <input type="text" class="form-control" id="contact_1" placeholder="Enter Contact 1" value="${values?.contact_1 || ''}" required>
                    </div>
                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="contact_2" class="form-label mb-1 req">Contact 2</label>
                        <input type="text" class="form-control" id="contact_2" placeholder="Enter Contact 2" value="${values?.contact_2 || ''}" required>
                    </div>
                    <div class="col-xxl-4 col-md-4 mb-3">
                        <label for="email" class="form-label mb-1 req">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" value="${values?.email || ''}" required>
                    </div>
                `;
            }else if(type === 'department'){
                form = `
                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="department_name" class="form-label mb-1 req">Department</label>
                        <div class="input-group">
                            <select class="form-select" id="department_id" required>
                                <option selected>Select from already existing departments</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <button class="btn btn-outline-secondary" type="button" id="add_new_department_to_db">Add New</button>
                        </div>
                    </div>
                `;
            }else{
                form = `
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="country_name" class="form-label mb-1 req">Department Name</label>
                        <input type="text" class="form-control" id="country_name" placeholder="Enter Department Name" value="${values?.country_name || ''}" required>
                    </div>
                `;
            }

            $('#form_type').val(type);
            $('#form_id').val(id);
            $('#branch-form-body').html(form);
            $('.req').append('<span class="text-danger">*</span>');
            $('#branch-form-modal').modal('show');

        }

        //=================================================================================================
        // select location functions in branch form
        //=================================================================================================
        
        // jQuery event handler for updating provinces based on selected country
        $(document).on('change', '#country_id', function(){
            const countryId = $(this).val();
            updateProvinces(countryId);

            if(countryId === ''){
                $('#province_id').html('<option value="">Select a country first</option>');
                $('#city_id').html('<option value="">Select a country first</option>');
            }else{
                $('#city_id').html('<option value="">Select a province first</option>'); // Reset city dropdown
            }
            
        });

        // jQuery event handler for updating cities based on selected province
        $(document).on('change', '#province_id', function(){
            const provinceId = $(this).val();
            updateCities(provinceId);
        });

        // Function to update provinces based on selected country
        function updateProvinces(countryId) {
            const provinces = dropdownData.provinces.filter(province => province.country_id == countryId);
            const provinceSelect = $('#province_id');

            if(provinces && provinces.length > 0){
                provinceSelect.html('<option value="">Select Province</option>'); // Reset provinces
                $.each(provinces, function(index, province) {
                    provinceSelect.append(`<option value="${province.id}">${province.province_name}</option>`);
                });
            }else{
                provinceSelect.html('<option value="">No Provinces</option>');
            }

        }

        // Function to update cities based on selected province
        function updateCities(provinceId) {
            const cities = dropdownData.cities.filter(city => city.province_id == provinceId);
            const citySelect = $('#city_id');

            if(cities && cities.length > 0){
                citySelect.html('<option value="">Select City</option>'); // Reset cities
                $.each(cities, function(index, city) {
                    citySelect.append(`<option value="${city.id}">${city.city_name}</option>`);
                });
            }else{
                citySelect.html('<option value="">No Cities</option>');
            }
        }

        //=================================================================================================
        // form submit
        //=================================================================================================

        $(document).on('click', '#branch-submit-confirm', function(e){
            e.preventDefault();
            let formData = new FormData();


        })

    </script>
</x-app-layout>