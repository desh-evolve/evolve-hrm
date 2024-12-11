<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Pay Pay Period Schedule</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            href="/payroll/pay_period_schedule/form">New Pay Pay Period Schedule <i
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
                                <th class="col">Description</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="pay_period_schedule_table_body">
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
            getAllPayPeriodSchedule();
        })

        async function getAllPayPeriodSchedule() {
            try {
                const pay_period_schedule = await commonFetchData('/payroll/pay_period_schedule/AllPayPeriodSchedules');
                let list = '';
                if (pay_period_schedule && pay_period_schedule.length > 0) {
                    pay_period_schedule.map((item, i) => {
                        list += `
                            <tr pay_period_schedule_id="${item.id}">
                                <td>${i+1}</td>  
                                <td>${item.type == 'manual' ? 'Manual' 
                                    : item.type == 'weekly' ? 'Weekly(52/year)' 
                                    : item.type == 'bi-weekly' ? 'Bi-Weekly (26/year)' 
                                    : item.type == 'semi-monthly' ? 'Semi-Monthly (24/year)' 
                                    : 'Monthly (12/year)'}</td>   
                                <td>${item.name}</td>  
                                <td>${item.description}</td> 
                                <td class="text-capitalize">${item.status === 'active' ? `${item.status}` : `${item.status}`}</td>
                                
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_pay_period_schedule" title="Edit Pay Pay Period Schedule" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger wa.0
                                    ves-effect waves-light btn-sm click-delete-pay-period-schedule" title="Delete Pay Pay Period Schedule" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                } else {
                    list += `<tr><td colspan="4" class="text-center">No Pay Pay Period Schedule Found!</td></tr>`;
                }

                $('#pay_period_schedule_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at payroll->pay-period-schedule->index->getAllPayPeriodSchedule: ', error);
            }
        }
        // <td>${ab.type == 'earning' ? 'Earning' : ab.type == 'employee_deduction' ? 'Employee Deduction' : ab.type == 'employer_deduction' ? 'Employer Deduction' : ab.type == 'total' ? 'Total' : 'Accrual'}</td>    

        $(document).on('click', '#new_pay_period_schedule_click', function() {
            resetForm();
            $('#pay-period-schedule-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_pay_period_schedule', function() {
            let pay_period_schedule_id = $(this).closest('tr').attr('pay_period_schedule_id');

            window.location.href = '/payroll/pay_period_schedule/form?id=' + pay_period_schedule_id;
        })


        $(document).on('click', '.click-delete-pay-period-schedule', async function() {
            let pay_period_schedule_id = $(this).closest('tr').attr('pay_period_schedule_id');

            try {
                let url = `/payroll/pay_period_schedule/delete`;
                const res = await commonDeleteFunction(pay_period_schedule_id, url,
                    'Pay Pay Period Schedule'); // Await the promise here

                if (res) {
                    await getAllPayPeriodSchedule();
                }
            } catch (error) {
                console.error(`Error during Pay Pay Period Schedule deletion:`, error);
            }
        })
    </script>

</x-app-layout>
