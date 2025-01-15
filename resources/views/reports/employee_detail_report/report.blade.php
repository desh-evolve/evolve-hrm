<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Employee Detail Report</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">First Name</th>
                                <th class="col">Last Name</th>
                                <th class="col">Title</th>
                                <th class="col">Home Phone</th>
                                <th class="col">Address 1</th>
                                <th class="col">City</th>
                                <th class="col">Province/State</th>
                                <th class="col">Postal Code</th>
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
        
    </script>

</x-app-layout>
