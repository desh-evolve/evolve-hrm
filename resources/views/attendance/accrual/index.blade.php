<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Accrual Balance') }}</h4>
    </x-slot>

    <style>
        .card-header:hover {
            background-color: #ddd;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="view_accrual_balance_table_body">

                <div class="card-header align-items-center d-flex justify-content-between">
                    <h5 class="mb-0">Accrual Balance List</h5>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" id="add_new_accrual_btn">
                            New Accrual Balance<i class="ri-add-line"></i>
                        </button>
                        <p class="text-danger emp_error m-0 d-none">Please select an Employee</p>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-2 d-flex align-items-center">
                            <label for="userId" class="form-label mb-1 req">Employee Name</label>
                        </div>
                        <div class="col-lg-10">
                            <select class="form-select" id="userId">
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="accrual_table_body">
                            <tr>
                                <td colspan="8" class="text-center">Please Select an Employee...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card" id="view_accrual_table_body" style="display:none;">

                <div class="card-header align-items-center d-flex justify-content-between">
                    <h5 class="mb-0">Accrual List</h5>

                </div>

                <div class="card-body">

                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="all_accrual_table_body">
                            <tr>
                                <td colspan="8" class="text-center">Please Select an Employee...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('attendance.accrual.add_accrual_popup')

    <script>
        let userId = '';
        let accrualBalanceId = '';
        let accrualId = '';

        $(document).ready(async function() {
            await getEmployeeDropdownData();
        });

        async function getEmployeeDropdownData() {
            try {
                const dropdownData = await commonFetchData('/accrual/dropdown');
                const employeeList = (dropdownData?.users || [])
                    .map(emp =>
                        `<option value="${emp.user_id}">${emp.first_name} ${emp.last_name} (Emp ID: ${emp.id})</option>`
                    )
                    .join('');
                $('#userId').html('<option value="">Select Employee Name</option>' + employeeList);


            } catch (error) {
                console.error('Error fetching employee data:', error);
            }
        }

        $(document).on('change', '#userId', async function() {
            $('.emp_error').removeClass('d-block').addClass('d-none');
            userId = $(this).val();

            if (!userId) {
                $('#accrual_table_body').html(
                    '<tr><td colspan="8" class="text-center text-info">Please Select an Employee</td></tr>'
                );
            } else {
                await renderAccrualTable();
            }
        });

        async function renderAccrualTable() {
            if (!userId) {
                $('#accrual_table_body').html(
                    '<tr><td colspan="8" class="text-center">No Employee Selected</td></tr>'
                );
                return;
            }

            try {
                const accruals = await commonFetchData(`/accrual/accrual_list/all/${userId}`);
                let rows = '';

                if (accruals && accruals.length > 0) {
                    accruals.forEach((item, i) => {
                        rows += `
                            <tr accrual_balance_id="${item.accrual_policy_id}">
                                <td>${i + 1}</td>
                                <td>${item.accrual_policy_name}</td>
                                <td>${item.balance}</td>
                                <td class="text-capitalize">
                                    <span class="badge border ${item.status === 'active' ? 'border-success text-success' : 'border-warning text-warning'}">
                                        ${item.status}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm click-view-accrual" title="Edit">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = '<tr><td colspan="8" class="text-danger text-center">No accruals Yet!</td></tr>';
                }

                $('#accrual_table_body').html(rows);
            } catch (error) {
                console.error('Error fetching accrual data:', error);
            }
        }


        $(document).on('click', '#add_new_accrual_btn', function() {
            resetForm();

            let userId = $('#userId').val();
            if (!userId) {
                $('.emp_error').removeClass('d-none').addClass('d-block');
                $('#user_name').val('');
                $('#user_id').val('');
            } else {
                $('.emp_error').removeClass('d-block').addClass('d-none');
                $('#accrual-form-title').text('Add New Accrual Balance');

                const userName = $('#userId option:selected').text();
                $('#user_name').val(userName);
                $('#user_id').val(userId);

                $('#accrual-form-modal').modal('show');
            }
        });

        $(document).on('click', '.click-edit-accrual', async function() {
             accrualId = $(this).closest('tr').attr('accrual_id');

            try {
                let accrualData = await commonFetchData(`/accrual/single_accrual/${accrualId}`);
                accrualData = accrualData[0];

                const [datePart, timePart] = accrualData.time_stamp.split(' ');
                $('#accrual_id').val(accrualId);
                $('#amount').val(accrualData.amount);
                $('#type').val(accrualData.type);
                $('#accrual_policy_id').val(accrualData.accrual_policy_id);
                $('#user_id').val(accrualData.user_id);
                $('#accrual_status').val(accrualData.status);
                $('#accrual_balance_id').val(accrualBalanceId);

                let userId = $('#userId').val();
                const userName = $('#userId option:selected').text();
                $('#user_name').val(userName);


                $('#accrual-form-title').text('Edit accrual');
                $('#accrual-form-modal').modal('show');
            } catch (error) {
                console.error('Error fetching accrual data:', error);
            }
        });


        $(document).on('click', '.click-view-accrual', async function() {
             accrualBalanceId = $(this).closest('tr').attr('accrual_balance_id');

            try {
                const accruals = await commonFetchData(`/accrual/accrual_list/${accrualBalanceId}`);
                console.log(accruals);
                
                let rows = '';

                if (accruals && accruals.length > 0) {
                    accruals.forEach((item, i) => {
                        rows += `
                    <tr accrual_id="${item.id}">
                        <td>${i + 1}</td>
                        <td>${item.type == '1' ? 'Awarded'
                            : item.type == '2' ? 'Un-Awarded'
                            : item.type == '3' ? 'Gift'
                            : item.type == '4' ? 'Paid Out'
                            : item.type == '5' ? 'Rollover Adjustment'
                            : item.type == '6' ? 'Initial Balance'
                            : 'Other'}</td>
                        <td>${item.amount}</td>
                        <td>${item.time_stamp}</td>
                        <td class="text-capitalize">
                            <span class="badge border ${item.status === 'active' ? 'border-success text-success' : 'border-warning text-warning'}">
                                ${item.status}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm click-edit-accrual" title="Edit">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm click_delete_accrual" title="Delete">
                                <i class="ri-delete-bin-fill"></i>
                            </button>
                        </td>
                    </tr>
                `;
                    });
                } else {
                    rows = '<tr><td colspan="8" class="text-danger text-center">No accruals Yet!</td></tr>';
                }

                // Append rows to the accrual table body
                $('#all_accrual_table_body').html(rows);

                // Show the accrual table and hide the balance table
                $('#view_accrual_table_body').show();
                $('#view_accrual_balance_table_body').hide();
            } catch (error) {
                console.error('Error fetching accrual data:', error);
            }
        });


        $(document).on('click', '.click_delete_accrual', async function() {
            const accrualId = $(this).closest('tr').attr('accrual_id');

            try {
                const res = await commonDeleteFunction(accrualId, '/accrual/delete/delete', 'accrual');
                if (res) await renderAccrualTable();
            } catch (error) {
                console.error('Error deleting accrual:', error);
            }
        });
    </script>
</x-app-layout>
