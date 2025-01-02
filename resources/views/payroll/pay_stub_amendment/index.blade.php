<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Pay Stub Amendment</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            href="/payroll/pay_stub_amendment/form">New Pay Stub Amendment <i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">First Name</th>
                                <th class="col">Last Name</th>
                                <th class="col">Status</th>
                                <th class="col">Account</th>
                                <th class="col">Effective Date</th>
                                <th class="col">Amount</th>
                                <th class="col">Description</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="pay_stub_amendment_table_body">
                            <tr>
                                <td colspan="4" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
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
                const pay_stub_amendment = await commonFetchData('/payroll/pay_stub_amendment/allPayStubAmendment');
                let list = '';
                if (pay_stub_amendment && pay_stub_amendment.length > 0) {
                    pay_stub_amendment.map((ab, i) => {
                        list += `
                            <tr pay_stub_amendment_id="${ab.id}">
                                <td>${i+1}</td>      
                                <td>${ab.first_name}</td>  
                                <td>${ab.last_name}</td>  
                                <td class="text-capitalize">${ab.status === 'active' ? `${ab.status}` : `${ab.status}`}</td>
                                <td class="text-capitalize">${ab.account_type} - ${ab.account_name}</td>  
                                <td>${ab.effective_date}</td> 
                                <td>${(ab.amount ? parseFloat(ab.amount).toFixed(2) : '0.00')}</td>
                                <td>${ab.description}</td>  
                                
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_pay_stub_amendment" title="Edit Pay Stub Amendment" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger wa.0
                                    ves-effect waves-light btn-sm click-delete-pay-stub-amendment" title="Delete Pay Stub Amendment" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                } else {
                    list += `<tr><td colspan="4" class="text-center">No Pay Stub Amendment Found!</td></tr>`;
                }

                $('#pay_stub_amendment_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at payroll->pay-stub-amendment->index->getAllPayStubAccount: ', error);
            }
        }
        // <td>${ab.type == 'earning' ? 'Earning' : ab.type == 'user_deduction' ? 'Employee Deduction' : ab.type == 'employer_deduction' ? 'Employer Deduction' : ab.type == 'total' ? 'Total' : 'Accrual'}</td>    

        $(document).on('click', '#new_pay_stub_amendment_click', function() {
            resetForm();
            $('#pay-stub-amendment-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_pay_stub_amendment', function() {
            let pay_stub_amendment_id = $(this).closest('tr').attr('pay_stub_amendment_id');

            window.location.href = '/payroll/pay_stub_amendment/form?id=' + pay_stub_amendment_id;
        })


        $(document).on('click', '.click-delete-pay-stub-amendment', async function() {
            let pay_stub_amendment_id = $(this).closest('tr').attr('pay_stub_amendment_id');

            try {
                let url = `/payroll/pay_stub_amendment/delete`;
                const res = await commonDeleteFunction(pay_stub_amendment_id, url,
                'Pay Stub Amendment'); // Await the promise here

                if (res) {
                    await getAllPayStubAccount();
                }
            } catch (error) {
                console.error(`Error during Pay Stub Amendment deletion:`, error);
            }
        })
    </script>

</x-app-layout>
