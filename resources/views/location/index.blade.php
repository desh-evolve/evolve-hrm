<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Locations') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1 type-btn" data-type="country">Countries</button>
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light material-shadow-none me-1 type-btn" data-type="province">Provinces</button>
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light material-shadow-none type-btn" data-type="city">Cities</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-nowrap">
                        <thead id="table_head">
                            
                        </thead>
                        <tbody id="table_body">
                            
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="location-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="location-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="location-form-body" class="row">


                    </div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger" id="location-submit-confirm">Submit</button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        
        //======================================================================================================
        // render tables
        //======================================================================================================
        let type = 'country';
        let countries = [];
        let provinces = [];

        $(document).ready(function() {
            initFunction();
        });
        
        // Handle button clicks for countries, provinces, and cities
        $(document).on('click', '.type-btn', function() {
            $('.type-btn').removeClass('btn-primary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
            type = $(this).data('type');
            renderTableBody(type);
        });

        async function initFunction(){
            await renderTableHeader(type);
            await renderTableBody(type);
        }

        async function renderTableHeader(type){
            // Define table headings based on the type
            const tableHeadings = {
                country: ['Country', 'Country Code'],
                province: ['Country', 'Province'],
                city: ['Province', 'City']
            };
            
            // Dynamically set the table headers based on the type
            const head = `
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">${tableHeadings[type][0]}</th>
                    <th scope="col">${tableHeadings[type][1]}</th>
                    <th scope="col">Action</th>
                </tr>
            `;
            $('#table_head').html(head);
            $('#table_body').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
        }

        // Common function to generate table rows
        function generateTableRows(items, type) {
            return items.map((item, i) => {
                const firstCol = type === 'country' ? item.country_name : type === 'province' ? item.country_name : item.province_name;
                const secondCol = type === 'country' ? item.country_code : item[type === 'province' ? 'province_name' : 'city_name'];
                return `
                    <tr type="${type}" id="${item.id}">
                        <th scope="row">${i + 1}</th>
                        <td>${firstCol}</td>
                        <td>${secondCol}</td>
                        <td>
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete">
                                <i class="ri-delete-bin-fill"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Render table based on the type
        async function renderTableBody(type) {
            try {
                let items = [];
                if (type === 'country') {
                    items = await commonFetchData(`/location/countries`);
                } else if (type === 'province') {
                    countries = await commonFetchData(`/location/countries`);
                    items = await commonFetchData(`/location/provinces`);
                } else if (type === 'city') {
                    provinces = await commonFetchData(`/location/provinces`);
                    items = await commonFetchData(`/location/cities`);
                }

                if (items.length === 0) {
                    $('#table_body').html('<tr><td colspan="4" class="text-center">No data available</td></tr>');
                    return;
                }

                const list = generateTableRows(items, type);
                $('#table_body').html(list);
            } catch (error) {
                $('#table_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
                console.error('Error fetching data:', error);
            }
        }

        //======================================================================================================
        // delete items
        //======================================================================================================
        $(document).on('click', '.click_delete', function() {
            const $row = $(this).closest('tr'); 
            const type = $row.attr('type');
            const id = $row.attr('id');
            
            deleteItem(type, id, $row); 
        });

        async function deleteItem(type, id, $row) {
            const urls = {
                country: '/location/country/delete',
                province: '/location/province/delete',
                city: '/location/city/delete'
            };
            
            const titles = {
                country: 'Country',
                province: 'Province',
                city: 'City'
            };

            // Ensure type is valid; default to 'country' if not found
            const url = urls[type] || urls['country'];
            const title = titles[type] || titles['country'];

            try {
                const res = await commonDeleteFunction(id, url, title, $row);
                if(res){
                    renderTableBody(type)
                }
            } catch (error) {
                console.error('Error deleting item:', error);
            }
        }

        //======================================================================================================
        // add/edit items
        //======================================================================================================

        $(document).on('click', '#add_new_btn', function(){
            loadForm();
        })

        $(document).on('click', '.click_edit', async function(){
            let id = $(this).closest('tr').attr('id');
            await loadForm(type, id);
        })

        $(document).on('click', '#location-submit-confirm', async function () {
            const location_id = $('#location_id').val();
            let url = '';
            let formData = new FormData();

            if (type === 'country') {
                const country_name = $('#country_name').val();
                const country_code = $('#country_code').val();

                if (!country_name || !country_code) return;

                url = '/location/country/create';
                formData.append('country_name', country_name);
                formData.append('country_code', country_code);
            } 
            else if (type === 'province') {
                const country_id = $('#country_id').val();
                const province_name = $('#province_name').val();

                if (!country_id || !province_name) return;

                url = '/location/province/create';
                formData.append('country_id', country_id);
                formData.append('province_name', province_name);
            } 
            else {
                const province_id = $('#province_id').val();
                const city_name = $('#city_name').val();

                if (!province_id || !city_name) return;

                url = '/location/city/create';
                formData.append('province_id', province_id);
                formData.append('city_name', city_name);
            }

            // Append common data
            if (location_id) {
                formData.append('location_id', location_id);
            }

            let res = await commonSaveData(url, formData);
            
            if(res.status === 'success'){
                await commonAlert('success', res.message);
                await renderTableBody(type);
            }else{
                await commonAlert('error', res.message);
            }
            
            $('#location-form-modal').modal('hide');

        });

        async function loadForm(type, id = "") {
            let title = `${id === "" ? 'Add' : 'Edit'} ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            let list = `<input type="hidden" id="location_id" value="${id}">`;

            let values = {};
            if (id !== "") {
                let data = await commonFetchData(`/location/${type}/${id}`);
                if(data && data.length > 0){
                    values = data[0];
                }
            }

            switch (type) {
                case 'country':
                    list += `
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="country_name" class="form-label mb-1 req">Country Name</label>
                            <input type="text" class="form-control" id="country_name" placeholder="Enter Country Name" value="${values.country_name || ''}" required>
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="country_code" class="form-label mb-1 req">Country Code</label>
                            <input type="text" class="form-control" id="country_code" placeholder="Enter Country Code" value="${values.country_code || ''}" required>
                        </div>`;
                    break;

                case 'province':
                    list += `
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="country_id" class="form-label mb-1">Country</label>
                            <select class="form-select" id="country_id">
                                <option value="">Select Country</option>
                                ${countries.map(country => `<option value="${country.id}" ${country.id === values.country_id ? 'selected' : ''}>${country.country_name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="province_name" class="form-label mb-1 req">Province Name</label>
                            <input type="text" class="form-control" id="province_name" placeholder="Enter Province Name" value="${values.province_name || ''}" required>
                        </div>`;
                    break;

                case 'city':
                    list += `
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="province_id" class="form-label mb-1">Province/State</label>
                            <select class="form-select" id="province_id">
                                <option value="">Select Province</option>
                                ${provinces.map(province => `<option value="${province.id}" ${province.id === values.province_id ? 'selected' : ''}>${province.province_name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-xxl-6 col-md-6 mb-3">
                            <label for="city_name" class="form-label mb-1 req">City Name</label>
                            <input type="text" class="form-control" id="city_name" placeholder="Enter City Name" value="${values.city_name || ''}" required>
                        </div>`;
                    break;

                default:
                    console.error("Unknown type:", type);
                    return;
            }

            $('#location-form-title').html(title);
            $('#location-form-body').html(list);
            $('#location-form-modal').modal('show');
        }


    </script>

</x-app-layout>