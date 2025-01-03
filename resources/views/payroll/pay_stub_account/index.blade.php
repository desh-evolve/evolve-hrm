<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Pay Stub Account List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            id="new_pay_stub_account_click">New Pay Stub Account<i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white"/>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Type</th>
                                <th class="col">Name</th>
                                <th class="col">Order</th>
                                <th class="col">Debit Account</th>
                                <th class="col">Credit Account</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="pay_stub_account_table_body">
                            <tr>
                                <td colspan="4" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="pay-stub-account-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="pay-stub-account-form-title">Add Pay Stub Account</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pay-stub-account-form-body">

                        <div class="row mb-3">
                            <label for="active" class="form-label mb-1 col-md-3">Status</label>
                            <div class="col-md-9">
                                <select class="form-select" id="active">
                                    <option value="enabled">Enabled</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type" class="form-label mb-1 col-md-3">Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="type" required>
                                    <option value="">Select a type...</option>
                                    <option value="earning">Earning</option>
                                    <option value="user_deduction">Employee Deduction</option>
                                    <option value="employer_deduction">Employer Deduction</option>
                                    <option value="total">Total</option>
                                    <option value="accrual">Accrual</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ps_order" class="form-label mb-1 col-md-3">Order</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ps_order">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="accrual_id" class="form-label mb-1 col-md-3">Accrual</label>
                            <div class="col-md-9">
                                <select class="form-select" id="accrual_id" required>
                                    <option value="">Select an accrual...</option>
                                    <option value="1">Accrual - Annual Leave</option>
                                    <option value="2">Accrual - Non Cash Benefits (payee)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="debit_account" class="form-label mb-1 col-md-3">Debit Account</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="debit_account">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="credit_account" class="form-label mb-1 col-md-3">Credit Account</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="credit_account">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="pay_stub_account_status" class="form-label mb-1 col-md-3">Status</label>
                            <div class="col-md-9">
                                <select class="form-select" id="pay_stub_account_status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="pay_stub_account_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="form_submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            getAllPayStubAccount();
        })

        async function getAllPayStubAccount() {
            try {
                const pay_stub_account = await commonFetchData('/payroll/pay_stub_account/allPayStubAccount');
                let list = '';
                if (pay_stub_account && pay_stub_account.length > 0) {
                    pay_stub_account.map((ab, i) => {
                        list += `
                            <tr pay_stub_account_id="${ab.id}">
                                <td>${i+1}</td>      
                                <td>${ab.type == 'earning' ? 'Earning' : ab.type == 'user_deduction' ? 'Employee Deduction' : ab.type == 'employer_deduction' ? 'Employer Deduction' : ab.type == 'total' ? 'Total' : 'Accrual'}</td>    
                                <td>${ab.name}</td>  
                                <td>${ab.ps_order}</td>  
                                <td>${ab.debit_account}</td>  
                                <td>${ab.credit_account}</td>  
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_pay_stub_account" title="Edit Pay Stub Account" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger wa.0
                                    ves-effect waves-light btn-sm click_delete_pay_stub_account" title="Delete Pay Stub Account" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                } else {
                    list += `<tr><td colspan="4" class="text-center">No Pay Stub Account Found!</td></tr>`;
                }

                $('#pay_stub_account_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at payroll->pay-stub-account->index->getAllPayStubAccount: ', error);
            }
        }

        $(document).on('change', '#type', function() {
            if ($(this).val() == 'accrual') {
                $('#accrual_id').closest('.row').hide();
            } else {
                $('#accrual_id').closest('.row').show();
            }
        })

        $(document).on('click', '#new_pay_stub_account_click', function() {
            resetForm();
            $('#pay-stub-account-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_pay_stub_account', async function() {
            resetForm();
            let pay_stub_account_id = $(this).closest('tr').attr('pay_stub_account_id');

            $('#pay_stub_account_id').val(pay_stub_account_id); // Set the ID in the hidden field

            try {
                // Fetch the pay stub accounty data
                let response = await commonFetchData(`/payroll/pay_stub_account/${pay_stub_account_id}`);
                let data = response[0]; // Extract the first object

                if (data) {
                    // Populate form fields
                    $('#active').val(data.active);
                    $('#name').val(data.name);
                    $('#type').val(data.type);
                    $('#ps_order').val(data.ps_order);
                    $('#accrual_id').val(data.accrual_pay_stub_entry_account_id);
                    $('#debit_account').val(data.debit_account);
                    $('#credit_account').val(data.credit_account);
                    $('#user_family_status').val(data.status);

                }
            } catch (error) {
                console.error('Error while fetching pay stub accounty data:', error);
                $('#error-msg').html('<p class="text-danger">Failed to load data. Please try again.</p>');
            }

            $('#pay-stub-account-form-modal').modal('show');
        })

        $(document).on('click', '#form_submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let pay_stub_account_id = $('#pay_stub_account_id').val();

            formData.append('active', $('#active').val());
            formData.append('type', $('#type').val());
            formData.append('name', $('#name').val());
            formData.append('ps_order', $('#ps_order').val());
            formData.append('accrual_pay_stub_entry_account_id', $('#accrual_id').val());
            formData.append('debit_account', $('#debit_account').val());
            formData.append('credit_account', $('#credit_account').val());
            formData.append('pay_stub_account_status', $('#pay_stub_account_status').val());

            let createUrl = `/payroll/pay_stub_account/create`;
            let updateUrl = `/payroll/pay_stub_account/update/${pay_stub_account_id}`;

            const isUpdating = Boolean(pay_stub_account_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', pay_stub_account_id);
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    $('#pay-stub-account-form-modal').modal('hide');
                    getAllPayStubAccount(); // Refresh the list of pay_stub_account
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm() {
            $('#active').val('enabled');
            $('#name').val('');
            $('#type').val('');
            $('#ps_order').val('');
            $('#accrual_id').val('');
            $('#debit_account').val('');
            $('#credit_account').val('');
            $('#pay_stub_account_status').val('active');;
        }
    </script>

</x-app-layout>
