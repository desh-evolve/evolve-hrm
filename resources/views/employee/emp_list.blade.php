<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">
    <style>
        td {
            padding: 5px 10px !important;
        }
    </style>

    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee List') }}</h4>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Employees</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/user/form">New Employee <i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="user_table" class="table table-bordered dt-responsive nowrap table-striped align-middle datatable-example" style="width:100%">
                        <thead class="bg-primary text-white"/>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Employee ID</th>
                                <th class="col">Name</th>
                                <th class="col">NIC</th>
                                <th class="col">Contact</th>
                                <th class="col">Status</th>
                                <th class="col">
                                    Functions
                                    <br>
                                    <a href="#" id="show_user_functions">[ Employee ]</a>
                                    <a href="#" id="show_payroll_functions">[ Payroll ]</a>
                                </th>
                                <th class="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="user_table_body">
                            
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            renderEmployeeTable();
        })

        async function renderEmployeeTable() {
            const userList = [
                {
                    id: 1,
                    userId: "0001",
                    name: "Deshan Dissanayake",
                    nic: "980451785V",
                    contact: "0714567894",
                    status: "active",
                },
                {
                    id: 2,
                    userId: "0002",
                    name: "Indika Manori",
                    nic: "9704231325V",
                    contact: "0767734894",
                    status: "inactive",
                },
            ];

            const rows = userList.map((user, index) => {
                let stt = user.status === 'active' ? '<span class="badge rounded-pill border border-success text-success">Active</span>' : '<span class="badge rounded-pill border border-warning text-warning">Inactive</span>';
                return ( `
                    <tr emp_id="${user.id}">
                        <td>${index + 1}</td>
                        <td>${user.userId}</td>
                        <td>${user.name}</td>
                        <td>${user.nic}</td>
                        <td>${user.contact}</td>
                        <td>${stt}</td>
                        <td>
                            <div class="user-functions button-se">
                                <a href="#" >[ Qualifications ]</a>
                                <a href="#" >[ Work Experience ]</a>
                                <a href="#" >[ Promotion ]</a>
                                <a href="#" >[ Family ]</a>
                                <a href="#" >[ Job History ]</a>
                                <a href="#" >[ KPI ]</a>
                            </div>
                            <div class="payroll-functions button-set" style="display: none;">
                                <a href="#" >[ Bank ]</a>
                                <a href="#" >[ Wage ]</a>
                                <a href="#" >[ Tax ]</a>
                                <a href="#" >[ PS Amendments ]</a>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_user" title="Edit Employee" data-tooltip="tooltip" data-bs-placement="top">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_user" title="Delete Employee" data-tooltip="tooltip" data-bs-placement="top">
                                <i class="ri-delete-bin-fill"></i>
                            </button>
                        </td>
                    </tr>
                `);
            }).join('');

            DataTablesForAjax.destroy();
            $('#user_table_body').html(rows);
            init_dataTable('#user_table');
            $('[data-tooltip="tooltip"]').tooltip();
        }

        $(document).ready(function() {
            $('#show_user_functions').on('click', function(event) {
                event.preventDefault();
                $('.user-functions').show();
                $('.payroll-functions').hide();
            });

            $('#show_payroll_functions').on('click', function(event) {
                event.preventDefault();
                $('.payroll-functions').show();
                $('.user-functions').hide();
            });
        });

        $(document).on('click', '.click_edit_user', function(){
            let emp_id = $(this).closest('tr').attr('emp_id');
            window.location.href = '/user/form?emp_id=' + emp_id
        })

        $(document).on('click', '.click_delete_user', async function(){
            let emp_id = $(this).closest('tr').attr('emp_id');

            try {
                let url = `/user/delete`;
                const res = await commonDeleteFunction(emp_id, url, 'Employee');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during branch deletion:`, error);
            }
        })


    </script>

</x-app-layout>