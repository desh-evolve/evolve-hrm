<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Punches') }}</h4>
    </x-slot>

    <style>
        .card-header:hover {
            background-color: #ddd;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header align-items-center d-flex justify-content-between">
                    <h5 class="mb-0">Punches</h5>
                    {{-- <div class="text-end">
                        <button type="button" class="btn btn-primary" id="add_new_punch_btn">
                            New Punch <i class="ri-add-line"></i>
                        </button>
                        <p class="text-danger emp_error m-0 d-none">Please select an Employee</p>
                    </div> --}}
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
                                <th>Employee</th>
                                <th>Punch Type</th>
                                <th>Punch Status</th>
                                <th>Time</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="punch_table_body">
                            <tr>
                                <td colspan="8" class="text-center">Please Select an Employee...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('attendance.punch_popup')

    <script>
        let userId = '';

        $(document).ready(async function () {
            await getEmployeeData();
        });

        async function getEmployeeData() {
            try {
                const employeeData = await commonFetchData('/company/employee_punch/get_employees');
                const employeeList = (employeeData?.users || [])
                    .map(emp => `<option value="${emp.user_id}">${emp.first_name} ${emp.last_name} (Emp ID: ${emp.id})</option>`)
                    .join('');
                $('#userId').html('<option value="">Select Employee Name</option>' + employeeList);
            } catch (error) {
                console.error('Error fetching employee data:', error);
            }
        }

        $(document).on('change', '#userId', async function () {
            $('.emp_error').removeClass('d-block').addClass('d-none');
            userId = $(this).val();

            if (!userId) {
                $('#punch_table_body').html(
                    '<tr><td colspan="8" class="text-center text-info">Please Select an Employee</td></tr>'
                );
            } else {
                await renderPunchTable();
            }
        });

        async function renderPunchTable() {
            if (!userId) {
                $('#punch_table_body').html(
                    '<tr><td colspan="8" class="text-center">No Employee Selected</td></tr>'
                );
                return;
            }

            try {
                const punches = await commonFetchData(`/company/employee_punch/${userId}`);
                let rows = '';

                if (punches && punches.length > 0) {
                    punches.forEach((item, i) => {
                        rows += `
                            <tr punch_id="${item.id}">
                                <td>${i + 1}</td>
                                <td>${item.name_with_initials}</td>
                                <td>${item.punch_type}</td>
                                <td>${item.punch_status}</td>
                                <td>${item.time_stamp}</td>
                                <td>${item.date}</td>
                                <td class="text-capitalize">
                                    <span class="badge border ${item.status === 'active' ? 'border-success text-success' : 'border-warning text-warning'}">
                                        ${item.status}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm click-edit-punch" title="Edit">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm click_delete_punch" title="Delete">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = '<tr><td colspan="8" class="text-danger text-center">No Punches Yet!</td></tr>';
                }

                $('#punch_table_body').html(rows);
            } catch (error) {
                console.error('Error fetching punch data:', error);
            }
        }

        $(document).on('click', '#add_new_punch_btn', function () {
            resetForm();

            let userId = $('#userId').val();
            if(!userId){
                $('.emp_error').removeClass('d-none').addClass('d-block');
                $('#user_name').val('');
                $('#user_id').val('');
            }else{
                $('.emp_error').removeClass('d-block').addClass('d-none');
                $('#punch-form-title').text('Add New Punch');
                
                const userName = $('#userId option:selected').text();
                $('#user_name').val(userName);
                $('#user_id').val(userId);
                
                $('#punch-form-modal').modal('show');
            }
        });

        $(document).on('click', '.click-edit-punch', async function () {
            const punchId = $(this).closest('tr').attr('punch_id');

            try {
                let punchData = await commonFetchData(`/company/single_employee_punch/${punchId}`);
                punchData = punchData[0];

                const [datePart, timePart] = punchData.time_stamp.split(' ');
                $('#punch_id').val(punchId);
                $('#date').val(datePart);
                $('#time').val(timePart);
                $('#punch_type').val(punchData.punch_type);
                $('#punch_status').val(punchData.punch_status);
                $('#branch_id').val(punchData.branch_id);
                $('#department_id').val(punchData.department_id);
                $('#note').val(punchData.note);
                $('#emp_punch_status').val(punchData.status);

                $('#punch-form-title').text('Edit Punch');
                $('#punch-form-modal').modal('show');
            } catch (error) {
                console.error('Error fetching punch data:', error);
            }
        });

        $(document).on('click', '.click_delete_punch', async function () {
            const punchId = $(this).closest('tr').attr('punch_id');

            try {
                const res = await commonDeleteFunction(punchId, '/company/employee_punch/delete', 'Punch');
                if (res) await renderPunchTable();
            } catch (error) {
                console.error('Error deleting punch:', error);
            }
        });
    </script>
</x-app-layout>
