<!-- pawanee(2024-11-18) -->
<x-app-layout :title="'Branch Bank Details'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Manage Branch Bank Details') }}</h4>

          <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->

    </x-slot>

    {{-- Generate Lists table --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">

                    <div>
                        <h4 class="card-title mb-0 flex-grow-1">
                            Bank Details for: <span class="text-info fw-bold">{{ $branch->branch_name }}</span>
                        </h4>
                    </div>


                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New<i class="ri-add-line"></i></button>
                            <a href="/company/branch" class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    <div class="card-body">
                        <table class="table table-nowrap" id="bankdetails_table">
                            <thead class="table-light" id="table_head">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Bank Code</th>
                                    <th scope="col">Bank Name</th>
                                    <th scope="col">Bank Branch</th>
                                    <th scope="col">Bank Account</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">
                                <!-- Render brnch bank details dynamically -->
                                <tr><td colspan="6" class="text-center">Loading...</td></tr>

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
                            <input type="hidden" class="form-control" id="branch_id" value="" disabled>
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
                            <label for="bank_account" class="form-label mb-1">Account Number</label>
                            <input type="text" class="form-control" id="bank_account" placeholder="Enter Account Number" value="">
                        </div>

                    </div>


                    <div id="error-msg"></div>

                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="bank_id" value="">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




<script>


    const branch_Id = "{{ $branch->id }}"; // Fetch from server-rendered view


    // Fetch and Render Bank Details
        async function renderBankDetails() {

            const bankDetails = await commonFetchData(`/branch/bank/${branch_Id}`);
            let list = '';

                if (bankDetails && bankDetails.length > 0) {
                        bankDetails.map((detail, i) =>{
                            list += `
                                <tr bank_id ="${detail.id}">
                                    <td>${i + 1}</td>
                                    <td>${detail.bank_code}</td>
                                    <td>${detail.bank_name}</td>
                                    <td>${detail.bank_branch}</td>
                                    <td>${detail.bank_account}</td>
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
                        })
                } else {
                    list = '<tr><td colspan="6" class="text-center text-danger">No bank details found!</td></tr>';
                }

                $('#table_body').html(list);

        }

        // Initialize on Load
        $(document).ready(function () {
            renderBankDetails();
        });


//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

$(document).on('click', '.click_delete', function() {
        const $row = $(this).closest('tr');
        const id = $row.attr('bank_id');

            deleteItem(id, $row);

    });

    async function deleteItem(id, $row) {
        const url ='/branch/bank/delete';
        const title ='Branch Bank Details';
        try {
                    const res = await commonDeleteFunction(id, url, title, $row);
                    if(res){
                        renderBankDetails()
                    }
                } catch (error) {
                    console.error('Error deleting item:', error);
                }
    }


//==================================================================================================
// ADD & EDIT FUNCTION
//==================================================================================================

    // Add
    $(document).on('click', '#add_new_btn', function () {
        resetForm();

        $('#bank_id').val('');
        const title = `Add Branch Bank Details`;
        $('.modal-title').html(title);
        $('#form_modal').modal('show');
    });


     // Edit
    $(document).on('click', '.click_edit', async function () {
        resetForm();
        const title = `Edit Branch Bank Details`;
        $('.modal-title').html(title);

        let bank_id = $(this).closest('tr').attr('bank_id');

        try {
            let bank_data = await commonFetchData(`/branch/single_bankdetail/${bank_id}`);
            bank_data = bank_data[0];
                console.log('branch_bank_data', bank_data);

                // Set form values with fetched data
                $('#bank_id').val(bank_id);
                $('#bank_name').val(bank_data.bank_name || '');
                $('#bank_code').val(bank_data.bank_code || '');
                $('#bank_branch').val(bank_data.bank_branch || '');
                $('#bank_account').val(bank_data.bank_account || '');

            } catch (error) {
                console.error('Error at getBranchBankSingle:', error);
                $('#error-msg').html('<p class="text-danger">Error fetching branch bank data. Please try again.</p>');
            } finally {
                $('#form_modal').modal('show');
            }
        });


    // Submit (Add/Edit)
    $(document).on('click', '#submit-confirm', async function () {
        const bank_id = $('#bank_id').val();

        const isUpdating = Boolean(bank_id);
        const url = isUpdating ? `/branch/bank/update/${bank_id}` : `/branch/bank/create`;
        const method = isUpdating ? 'PUT' : 'POST';

        const formFields = {
            bank_code: 'required',
            bank_name: 'required',
            bank_branch: 'required',
            bank_account: 'required',
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
            $('#error-msg').html('<p class="text-danger">The following fields are required: ' +
                missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>');
            return;
        } else {
            $('#error-msg').html('');
        }

            formData.append('branch_id', branch_Id);

        try {
            let res = await commonSaveData(url, formData, method);
            console.log('Response:', res); // Debugging response data
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                renderBankDetails();
                $('#form_modal').modal('hide');
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
            $('#bank_id').val('');
            $('#bank_name').val('');
            $('#branch_id').val('');
            $('#bank_code').val('');
            $('#bank_branch').val('');
            $('#bank_account').val('');
            $('#error-msg').html('');
        }

</script>

</x-app-layout>
