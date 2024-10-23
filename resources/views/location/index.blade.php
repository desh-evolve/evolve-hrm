<x-app-layout :title="'Input Example'">
   
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Location Management') }}</h4>
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
                        <h5 class="mb-0">Countries</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_country_btn">New Country <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="country_list">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0" id="province_title">Provinces</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_province_btn">New Province <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="province_list">
                    <p>Click on a country to see provinces</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0" id="city_title">Cities</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_city_btn">New City <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="city_list">
                    <p>Click on a country to see provinces</p>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="location-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="location-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="location-form-body" class="row"></div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="location-type" value="" />
                        <input type="hidden" id="location-id" value="" />
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="location-submit-confirm">Submit</button>
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

        $(document).ready(async function(){
            renderCountryTable();
        });

        $(document).on('click', '.country_click', async function(){
            let country_id = $(this).attr('country_id');
            let country_name = $(this).find('.card-title').text();
            clicked_country_id = country_id;

            $('#province_title').html(`Provinces of ${country_name}`);
            $('#province_list').html('<p>Loading...</p>');
            $('#city_title').html(`Cities`);
            $('#city_list').html('<p>Click on a province to see cities</p>');
            $('.country_click').closest('.card-header').css({'background-color': 'white'});
            $(this).closest('.card-header').css({'background-color': '#ddd'});
            
            await renderProvinceTable(country_id);
        });

        $(document).on('click', '.province_click', async function(){
            let province_id = $(this).attr('province_id');
            let province_name = $(this).find('.card-title').text();
            clicked_province_id = province_id;

            $('#city_title').html(`Cities of ${province_name}`);
            $('#city_list').html('<p>Loading...</p>');
            $('.province_click').closest('.card-header').css({'background-color': 'white'});
            $(this).closest('.card-header').css({'background-color': '#ddd'});

            await renderCityTable(province_id);
        });

        async function renderCountryTable(){
            $('#add_new_province_btn').hide();
            $('#add_new_city_btn').hide();
            
            let list = '';
            $('#country_list').html('<p>Loading...</p>');
            let items = await commonFetchData('/location/countries');

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 p-2 cursor-pointer country_click" country_id="${item.id}">
                                            <h6 class="card-title mb-0">${item.country_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0 p-2">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_country" title="Edit Country" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_country" title="Delete Country" data-tooltip="tooltip" data-bs-placement="top">
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
                list = `<p class="text-danger">No Countries Yet!</p>`;
            }

            $('#country_list').html(list);
            $('#province_list').html('Click on a country to see provinces');
            $('#city_list').html('Click on a country to see provinces');
            $('[data-tooltip="tooltip"]').tooltip();
        }

        async function renderProvinceTable(country_id){
            $('#add_new_province_btn').show();
            $('#add_new_city_btn').hide();
            
            let list = '';
            let items = await commonFetchData(`/location/provinces/${country_id}`);

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 p-2 cursor-pointer province_click" province_id="${item.id}" country_id="${country_id}">
                                            <h6 class="card-title mb-0">${item.province_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0 p-2">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_province" title="Edit Province" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_province" title="Delete Province" data-tooltip="tooltip" data-bs-placement="top">
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
                list = `<p class="text-danger">No provinces for this country yet!</p>`;
            }

            $('#province_list').html(list);
            $('#city_list').html('Click on a province to see cities');
            $('[data-tooltip="tooltip"]').tooltip();
        }

        async function renderCityTable(province_id){
            $('#add_new_city_btn').show();

            let list = '';
            let items = await commonFetchData(`/location/cities/${province_id}`);

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary">
                                <div class="card-header p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 p-2 cursor-pointer city_click" city_id="${item.id}" province_id="${province_id}">
                                            <h6 class="card-title mb-0">${item.city_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0 p-2">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_city" title="Edit City" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_city" title="Delete City" data-tooltip="tooltip" data-bs-placement="top">
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
                list = `<p class="text-danger">No cities for this province yet!</p>`;
            }

            $('#city_list').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        }

        //=================================================================================================
        // Form Modal
        //=================================================================================================

        $(document).on('click', '#add_new_country_btn', function(){
            formModal('country');
        });

        $(document).on('click', '#add_new_province_btn', function(){
            formModal('province');
        });

        $(document).on('click', '#add_new_city_btn', function(){
            formModal('city');
        });

        // edit click event
        $(document).on('click', '.click_edit_country', async function(){
            let id = $(this).closest('.card-header').find('.country_click').attr('country_id');
            let url = `/location/country/${id}`;
            let values = await commonFetchData(url);
            await formModal('country', id, values[0]);
        });

        $(document).on('click', '.click_edit_province', async function(){
            let id = $(this).closest('.card-header').find('.province_click').attr('province_id');
            let url = `/location/province/${id}`;
            let values = await commonFetchData(url);
            await formModal('province', id, values[0]);
        });

        $(document).on('click', '.click_edit_city', async function(){
            let id = $(this).closest('.card-header').find('.city_click').attr('city_id');
            let url = `/location/city/${id}`;
            let values = await commonFetchData(url);
            await formModal('city', id, values[0]);
        });

        // Form Rendering Logic
        async function formModal(type, id = '', values = null){
            let form = '';
            let title = `Add New ${type.charAt(0).toUpperCase() + type.slice(1)}`;

            if(id) {
                title = `Edit ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            }

            if(type === 'country'){
                form = `
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="country_name" class="form-label mb-1 req">Country Name</label>
                        <input type="text" class="form-control" id="country_name" placeholder="Enter Country Name" value="${values?.country_name || ''}" required>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="country_code" class="form-label mb-1 req">Country Code</label>
                        <input type="text" class="form-control" id="country_code" placeholder="Enter Country Code" value="${values?.country_code || ''}" required>
                    </div>
                `;
            }

            if(type === 'province'){
                form = `
                    <div class="col-xxl-12 col-md-12 mb-3">
                        <input type="hidden" id="country_id" value="${clicked_country_id}">
                        <input type="text" class="form-control" id="province_name" placeholder="Enter Province Name" value="${values?.province_name || ''}" required>
                    </div>
                `;
            }

            if(type === 'city'){
                form = `
                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="city_name" class="form-label mb-1 req">City Name</label>
                        <input type="hidden" id="province_id" value="${clicked_province_id}">
                        <input type="text" class="form-control" id="city_name" placeholder="Enter City Name" value="${values?.city_name || ''}" required>
                    </div>
                `;
            }

            $('#location-type').val(type);
            $('#location-id').val(id);
            $('#location-form-title').html(title);
            $('#location-form-body').html(form);
            $('#location-form-modal').modal('show');
        }

        $(document).on('click', '#location-submit-confirm', async function(){
            const type = $('#location-type').val();
            const location_id = $('#location-id').val();
            const formData = new FormData();
            
            const typeToUrl = {
                country: { create: '/location/country/create', update: `/location/country/update/${location_id}` },
                province: { create: '/location/province/create', update: `/location/province/update/${location_id}` },
                city: { create: '/location/city/create', update: `/location/city/update/${location_id}` }
            };

            const formFields = {
                country: { name: 'country_name', code: 'country_code' },
                province: { country_id: 'country_id', name: 'province_name' },
                city: { province_id: 'province_id', name: 'city_name' }
            };

            const data = formFields[type];
            if (!data) return;

            // Validate required fields
            for (const key in data) {
                const value = $('#' + data[key]).val(); // If any required field is missing, return
                if (!value){
                    $('#error-msg').html('<p class="text-danger">All fields are required!</p>')
                    return;
                }else{
                    $('#error-msg').html('');
                }  
                formData.append(data[key], value);
            }

            // Append location_id if updating
            const isUpdating = Boolean(location_id);
            let url = isUpdating ? typeToUrl[type].update : typeToUrl[type].create;
            let method = 'POST';
            if (isUpdating){
                formData.append('location_id', location_id);
                method = 'PUT';
            }

            // Send data and handle response
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                if(type === 'country'){
                    renderCountryTable();
                }else if(type === 'province'){
                    renderProvinceTable(clicked_country_id);
                }else{
                    renderCityTable(clicked_province_id);
                }
                $('#location-form-modal').modal('hide');
            }
        });

        //=================================================================================================
        // delete items
        //=================================================================================================

        // Generalized delete function
        async function deleteLocation(type, id, parent_id = null) {
            let url = `/location/${type}/delete`;
            let title = type.charAt(0).toUpperCase() + type.slice(1);  // Capitalize type

            try {
                const res = await commonDeleteFunction(id, url, title);  // Await the promise here

                if (res) {
                    switch (type) {
                        case 'country':
                            await renderCountryTable();  // Refresh country table
                            $('#province_title').html(`Provinces`);
                            $('#city_title').html(`Cities`);
                            break;
                        case 'province':
                            await renderProvinceTable(parent_id);  // Refresh province table with the country_id
                            $('#city_title').html(`Cities`);
                            break;
                        case 'city':
                            await renderCityTable(parent_id);  // Refresh city table with the province_id
                            break;
                    }
                }
            } catch (error) {
                console.error(`Error during ${type} deletion:`, error);
            }
        }

        // Event listener for country deletion
        $(document).on('click', '.click_delete_country', async function() {
            let id = $(this).closest('.card-header').find('.country_click').attr('country_id');
            await deleteLocation('country', id);
        });

        // Event listener for province deletion
        $(document).on('click', '.click_delete_province', async function() {
            let country_id = $(this).closest('.card-header').find('.province_click').attr('country_id');
            let id = $(this).closest('.card-header').find('.province_click').attr('province_id');
            await deleteLocation('province', id, country_id);
        });

        // Event listener for city deletion
        $(document).on('click', '.click_delete_city', async function() {
            let province_id = $(this).closest('.card-header').find('.city_click').attr('province_id');
            let id = $(this).closest('.card-header').find('.city_click').attr('city_id');
            await deleteLocation('city', id, province_id);
        });


    </script>
</x-app-layout>
