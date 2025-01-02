<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Company Deduction</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            href="/payroll/company_deduction/form">New Company Deduction <i
                                class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Type</th>
                                <th class="col">Name</th>
                                <th class="col">Calculation</th>
                                <th class="col">Calculation Order</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="company_deduction_table_body">
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
            getAllCompanyDeduction();
        })

        async function getAllCompanyDeduction() {
            try {
                const company_deduction = await commonFetchData('/payroll/company_deduction/AllCompanyDeduction');
                let list = '';
                if (company_deduction && company_deduction.length > 0) {
                    company_deduction.map((item, i) => {
                        list += `
                            <tr company_deduction_id="${item.id}">
                                <td>${i+1}</td>  
                                <td>${item.type == 'tax' ? 'tax' 
                                    : item.type == 'deduction' ? 'Deduction' 
                                    : item.type == 'other' ? 'Other' 
                                    : item.type == 'advanced_percent_range_bracket' ? 'Advanced Percent (Range Bracket)' 
                                    : 'Percent'}</td>   
                                <td>${item.name}</td>  
                                <td>${item.calculation_type == 'fixed_amount' ? 'Fixed Amount' 
                                    : item.calculation_type == 'fixed_amount_range_bracket' ? 'Fixed Amount (Range Bracket)' 
                                    : item.calculation_type == 'advanced_percent' ? 'Advanced Percent' 
                                    : item.calculation_type == 'advanced_percent_range_bracket' ? 'Advanced Percent (Range Bracket)' 
                                    : 'Percent'}</td>   
                                <td>${item.calculation_order}</td> 
                                <td class="text-capitalize">${item.status === 'active' ? `${item.status}` : `${item.status}`}</td>
                                
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_company_deduction" title="Edit Company Deduction" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger wa.0
                                    ves-effect waves-light btn-sm click-delete-pay-period-schedule" title="Delete Company Deduction" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                } else {
                    list += `<tr><td colspan="4" class="text-center">No Company Deduction Found!</td></tr>`;
                }

                $('#company_deduction_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at payroll->pay-period-schedule->index->getAllCompanyDeduction: ', error);
            }
        }
       
        $(document).on('click', '#new_company_deduction_click', function() {
            resetForm();
            $('#pay-period-schedule-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_company_deduction', function() {
            let company_deduction_id = $(this).closest('tr').attr('company_deduction_id');

            window.location.href = '/payroll/company_deduction/form?id=' + company_deduction_id;
        })


        $(document).on('click', '.click-delete-pay-period-schedule', async function() {
            let company_deduction_id = $(this).closest('tr').attr('company_deduction_id');

            try {
                let url = `/payroll/company_deduction/delete`;
                const res = await commonDeleteFunction(company_deduction_id, url,
                    'Company Deduction'); // Await the promise here

                if (res) {
                    await getAllCompanyDeduction();
                }
            } catch (error) {
                console.error(`Error during Company Deduction deletion:`, error);
            }
        })
    </script>

</x-app-layout>
