<!-- pawanee(2024-10-22) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Bank Details') }}</h4>

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
                        <h4 class="card-title mb-0 flex-grow-1">Employees</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                        <table class="table table-nowrap" id="user_table">
                            <thead class="table-light" id="table_head">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table_body">

                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--form modal -->


        <div id="form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xxl-3 col-md-12 mb-3">
                                <input type="hidden" class="form-control-plaintext" id="user_name" value="" disabled>
                                <input type="hidden" class="form-control" id="user_id" value="" disabled>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="bank_code" class="form-label mb-1">Bank Code</label>
                                <input type="text" class="form-control" id="bank_code" placeholder="Enter Bank Code" value="">
                            </div>

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="bank_name" class="form-label mb-1">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name" value="">
                            </div>

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="bank_branch" class="form-label mb-1">Bank Branch</label>
                                <input type="text" class="form-control" id="bank_branch" placeholder="Enter Bank Branch" value="">
                            </div>

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="account_number" class="form-label mb-1">Account Number</label>
                                <input type="text" class="form-control" id="account_number" placeholder="Enter Account Number" value="">
                            </div>

                        </div>



                        <div id="error-msg"></div>
                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="emp_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary" id="submit-confirm">Submit</button>
                            <button type="button" class="btn w-sm btn-danger" id="click_delete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<script>


//==================================================================================================
// RENDER TABLE
//==================================================================================================


    async function renderTableBody() {
        try {

            const users = await commonFetchData('/company/allemplyee');

            let list = '';

            if (users.length === 0) {
                $('#table_body').html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                return;
            } else {
                list = users.map((item, i) => {
                    return `
                        <tr emp_id="${item.id}">
                            <th scope="row">${i + 1}</th>
                            <td>${item.name_with_initials}</td>

                            <td>
                                <button type="button" class="btn btn-success btn-sm click_bank">Manage Bank Details</button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // Update the HTML content of the table body with the new list
            $('#table_body').html(list);

        } catch (error) {

            $('#table_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
            console.error('Error fetching data:', error);

        }

    }



//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

$(document).on('click', '#click_delete', async function () {
    const bankRecordId = $('#emp_id').val(); // Get the bank record ID
    const userId = $('#user_id').val(); // Get the user ID

    if (!bankRecordId) {
        // If no bank record ID is found, show an error message
        $('#error-msg').html('<p class="text-danger">No bank record found. Please fill the record first.</p>');
        return;
    }

    try {
        // Perform the delete operation
        let url = `/user/bank/delete`;
        const title ='Bank Details';

        const res = await commonDeleteFunction(bankRecordId,url,title);

        // Log the result for debugging
        console.log('Delete Response:', res);

        if (res) {

            resetForm();
            $('#form_modal').modal('hide');
            await renderTableBody();
        }
    } catch (error) {
        console.error(`Error during Bank Details deletion:`, error);
    }
});


//======================================================================================================
// ADD & EDIT FUNCTION
//======================================================================================================

    $(document).ready(async function () {
        // Initialize and render the user table
        await renderTableBody();

        // Event handler for "Add or Edit Bank" button
        $(document).on('click', '.click_bank', async function () {
            resetForm();

            const $row = $(this).closest('tr');
            const userId = $row.attr('emp_id'); // Get user ID
            const userName = $row.find('td:nth-child(2)').text(); // Get user name

            // Set user name and ID in the modal
            $('#user_name').val(userName);
            $('#user_id').val(userId);


            // Update modal title with styled user name
            $('.modal-title').html(`Bank Account for <span class="text-info fw-bold">${userName}</span>`);


            try {
                // Attempt to fetch bank details for the user
                let bank_data = await commonFetchData(`/user/bank/${userId}`);

                if (bank_data && bank_data[0]) {
                    bank_data = bank_data[0];
                    console.log('users_bank_data', bank_data);

                    // Populate modal with bank details
                    $('#emp_id').val(bank_data.id || ''); // Existing bank ID
                    $('#bank_name').val(bank_data.bank_name || '');
                    $('#bank_code').val(bank_data.bank_code || '');
                    $('#bank_branch').val(bank_data.bank_branch || '');
                    $('#account_number').val(bank_data.account_number || '');
                } else {
                    console.warn('No bank details found for this user. You can add new details.');
                }
            } catch (error) {
                console.error('Error fetching bank details:', error);
                $('#error-msg').html('<p class="text-danger">No bank details found. You can add new details.</p>');
            } finally {
                // Show the modal for adding or editing details
                $('#form_modal').modal('show');
            }
        });

        // Submit (Add/Edit Bank Details)
        $(document).on('click', '#submit-confirm', async function () {
            const userId = $('#user_id').val();
            const empId = $('#emp_id').val();
            const isUpdating = Boolean(empId); // Determine if it's an update or a new entry
            const url = isUpdating ? `/user/bank/update/${empId}` : `/user/bank/create`;
            const method = isUpdating ? 'PUT' : 'POST';

            const formFields = {
                bank_code: 'required',
                bank_name: 'required',
                bank_branch: 'required',
                account_number: 'required',
            };

            let formData = new FormData();
            let missingFields = [];

            for (const key in formFields) {
                const value = $('#' + key).val();
                if (formFields[key] === 'required' && !value) {
                    missingFields.push(key);
                }
                formData.append(key, value || ''); // Append all fields to formData
            }

            if (missingFields.length > 0) {
                $('#error-msg').html(
                    '<p class="text-danger">The following fields are required: ' +
                        missingFields.map((field) => field.replace('_', ' ')).join(', ') +
                        '.</p>'
                );
                return;
            } else {
                $('#error-msg').html('');
            }

            formData.append('user_id', userId);

            // Debug the FormData being sent
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('Response:', res);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    $('#form_modal').modal('hide'); // Hide the modal
                    await renderTableBody(); // Refresh table if needed
                } else {
                    $('#error-msg').html('<p class="text-danger">' + res.message + '</p>');
                }
            } catch (error) {
                console.error('Error saving data:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


    });


    // Reset Function
    function resetForm() {
            $('#emp_id').val('');
            $('#bank_name').val('');
            $('#user_id').val('');
            $('#bank_code').val('');
            $('#bank_branch').val('');
            $('#account_number').val('');
            $('#error-msg').html('');
        }


</script>


</x-app-layout>
