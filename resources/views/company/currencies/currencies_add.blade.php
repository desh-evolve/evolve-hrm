<!-- pawanee(2024-10-24) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Currencies') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>




{{-- Generate Currency Lists table --}}


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div >
                            <h4 class="card-title mb-0 flex-grow-1">Currencies Lists</h4>
                    </div>

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New<i class="ri-add-line"></i></button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-body">
                        <table class="table table-nowrap" id="currency_table">
                            <thead class="table-light" id="table_head">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Currency Name</th>
                                    <th scope="col">ISO Code</th>
                                    <th scope="col">Conversion Rate</th>
                                    <th scope="col">Previous Rate</th>
                                    <th scope="col">Is Default</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">

                                <tr><td colspan="7" class="text-center">Loading...</td></tr>

                            </tbody>
                        </table>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>



     <!-- currency form modal -->

     <div id="currency_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" ></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="currency-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="currency_name" class="form-label mb-1">Currency Name</label>
                            <input type="text" class="form-control" id="currency_name" placeholder="Enter Currency Name" value="">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="iso_code" class="form-label mb-1">ISO Code</label>
                            <input type="text" class="form-control" id="iso_code" placeholder="Enter ISO Code" value="">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="conversion_rate" class="form-label mb-1">Currency Conversion Rate</label>
                            <input type="text" class="form-control" id="conversion_rate" placeholder="Enter Conversion Rate" value="">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="previous_rate" class="form-label mb-1">Previous Rate</label>
                            <input type="text" class="form-control" id="previous_rate" placeholder="Enter Previous Rate" value="">
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="form-check-label" for="is_default">Is Default</label>&nbsp;&nbsp;
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                            </div>
                        </div>


                        <div id="error-msg"></div>
                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="currency_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary" id="currency-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





<script>


//==================================================================================================
// RENDER TABLE
//==================================================================================================


    // Initialize the function to render the table (Refactored Version)
    $(document).ready(async function () {
        await renderTableBody();
    });


    async function renderTableBody() {

        try {

            const currItems = await commonFetchData('/company/allcurrency');
            console.log(currItems);

            let list = '';

            if (currItems.length === 0) {
                $('#table_body').html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                return;
            } else {
                list = currItems.map((currItem, i) => {
                    return `
                        <tr currency_id="${currItem.id}">
                            <th scope="row">${i + 1}</th>
                            <td>${currItem.currency_name}</td>
                            <td>${currItem.iso_code}</td>
                            <td>${currItem.conversion_rate}</td>
                            <td>${currItem.previous_rate}</td>
                            <td class="text-capitalize">${currItem.is_default === 1 ? `<span class="badge bg-success">Yes</span>` : `<span class="badge bg-warning">No</span>`}</td>
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

            // Update the HTML content of the table body with the new list
            $('#table_body').html(list);

        } catch (error) {

            $('#table_body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
            console.error('Error fetching data:', error);

        }

    }



//==================================================================================================
// ADD & EDIT FUNCTION
//==================================================================================================

    // Add currency
    $(document).on('click', '#add_new_btn', function () {
        resetForm(); // Reset the form
        $('.modal-title').text('Add Currency');  // Set modal title for adding
        $('#currency_form_modal').modal('show'); // Show the modal for adding a new currency
    });


     // Edit Currency
    $(document).on('click', '.click_edit', async function () {
        resetForm();

        let currency_id = $(this).closest('tr').attr('currency_id');

        // Set modal title for editing the currency
        if (currency_id) {
            $('.modal-title').text('Edit Currency');
        }

        // Get currency data by ID
        try {
            let currency_data = await commonFetchData(`/company/currency/${currency_id}`);
            currency_data = currency_data[0];
            console.log('currency_data', currency_data);

            // Set form values with fetched data
            $('#currency_id').val(currency_id);
            $('#currency_name').val(currency_data?.currency_name || '');
            $('#iso_code').val(currency_data?.iso_code || '');
            $('#conversion_rate').val(currency_data?.conversion_rate || '');
            $('#previous_rate').val(currency_data?.previous_rate || '');

            // Check the checkbox based on the is_default value (assuming it's a number)
            $('#is_default').prop('checked', currency_data?.is_default === 1); // Compare with the number 1

        } catch (error) {
            console.error('Error at getCurrencyById:', error);
            $('#error-msg').html('<p class="text-danger">Error fetching currency data. Please try again.</p>');
        } finally {
            $('#currency_form_modal').modal('show');
        }
    });


    // Submit currency (Add/Edit)
    $(document).on('click', '#currency-submit-confirm', async function () {
        const currency_id = $('#currency_id').val();

        let createUrl = `/company/currency/create`;
        let updateUrl = `/company/currency/update/${currency_id}`;

        const formFields = {
            currency_name: 'required',
            iso_code: 'required',
            conversion_rate: 'required',
            previous_rate: 'required',
            is_default: 'required',
        };

        let formData = new FormData();
        let missingFields = [];

        // Validate only required fields
        for (const key in formFields) {
            const fieldId = key;
            const value = $('#' + fieldId).val(); // Fetch value using the ID

            // Check only required fields
            if (formFields[key] === 'required' && !value) {
                missingFields.push(fieldId);
            }

            // Append all fields to formData
            formData.append(key, value || ''); // Append empty string if no value for optional fields
        }

        // Capture the actual value of is_default from the form
        formData.append('is_default', $('#is_default').is(':checked') ? '1' : '0');

        // If there are missing required fields, display an error message
        if (missingFields.length > 0) {
                let errorMsg = '<p class="text-danger">The following fields are required: ';
                errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
                $('#error-msg').html(errorMsg);
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }


        // Check if updating
        const isUpdating = Boolean(currency_id);
        let url = isUpdating ? updateUrl : createUrl;
        let method = isUpdating ? 'PUT' : 'POST'; // Set method based on update/create

        if (isUpdating) {
            formData.append('id', currency_id); // Append ID if updating
        }

        try {
            // Send data and handle response
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                renderTableBody();
                $('#currency_form_modal').modal('hide');
            } else {
                $('#error-msg').html('<p class="text-danger">' + res.message + '</p>');
            }

        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });

    // Reset Function
    function resetForm() {
        $('#currency_id').val('');
        $('#currency_name').val('');
        $('#iso_code').val('');
        $('#conversion_rate').val('');
        $('#previous_rate').val('');
        $('#is_default').prop('checked', false); // Uncheck the checkbox
        $('#error-msg').html('');
    }


//==================================================================================================
// DELETE FUNCTION
//==================================================================================================


$(document).on('click', '.click_delete', function() {
        const $row = $(this).closest('tr');
        const id = $row.attr('currency_id');

            deleteItem(id, $row);

    });

    async function deleteItem(id, $row) {
        const url ='company/currency/delete';
        const title ='Currency';


        try {
                    const res = await commonDeleteFunction(id, url, title, $row);
                    if(res){
                        renderTableBody()
                    }
                } catch (error) {
                    console.error('Error deleting item:', error);
                }
    }

//===========================================================================================================

</script>


</x-app-layout>
