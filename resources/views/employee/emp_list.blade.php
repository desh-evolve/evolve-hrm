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
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/employee/form">Add New Employee <i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="user_table" class="table table-bordered table-striped align-middle" style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="col">#</th>
                                    <th class="col">Employee ID</th>
                                    <th class="col">Name</th>
                                    <th class="col">NIC</th>
                                    <th class="col">Contact</th>
                                    <th class="col">Status</th>
                                    <th class="col">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Function</span>
                                            <div class="text-warning">
                                                <a href="#" id="show_user_functions" class="text-warning">[ Employee ]</a> |
                                                <a href="#" id="show_payroll_functions" class="text-warning">[ Payroll ]</a>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="user_table_body">
                                <tr>
                                    <td colspan="8" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

    $(document).ready(function () {
        renderEmployeeTable();
        bindFunctionToggles();

//===================================================================================
// Render Employee Table
//===================================================================================
        async function renderEmployeeTable() {
            try {
                const users = await commonFetchData('/employees');
                console.log('data', users);

                if (!Array.isArray(users) || users.length === 0) {
                    console.warn('No users available to render');
                    $('#user_table_body').html('<tr><td colspan="8" class="text-center">No data available</td></tr>');
                    return;
                }

                const rows = users.map((user, i) => {
                    let stt =
                        user.status === 'active'
                            ? '<span class="badge rounded-pill border border-success text-success">Active</span>'
                            : '<span class="badge rounded-pill border border-warning text-warning">Inactive</span>';

                    return `
                        <tr emp_id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.user_id || 'N/A'}</td>
                            <td>${user.name_with_initials || 'N/A'}</td>
                            <td>${user.nic || 'N/A'}</td>
                            <td>${user.contact_1 || 'N/A'}</td>
                            <td>${stt}</td>
                            <td>
                                <div class="user-functions-1 button-set text-center">
                                    <a href="#" class="text-primary manage-qualification" data-id="${user.id}">[Qualifications]</a>
                                    <a href="#" class="text-primary manage-document" data-id="${user.id}">[Doc]</a>
                                    <a href="#" class="text-primary manage-work" data-id="${user.id}">[Work Experience]</a>
                                </div>
                                <div class="user-functions-2 button-set text-center">
                                    <a href="#" class="text-primary manage-promotion" data-id="${user.id}">[Promotion]</a>
                                    <a href="#" class="text-primary manage-family" data-id="${user.id}">[Family]</a>
                                    <a href="#" class="text-primary manage-jobhistory" data-id="${user.id}">[Job History]</a>
                                </div>
                                <div class="payroll-functions button-set" style="display: none;">
                                    <a href="#" class="text-primary bg-success manage-bank" data-id="${user.id}">[Bank]</a>
                                    <a href="#" class="text-primary bg-success manage-wage" data-id="${user.id}">[Wage]</a>
                                    <a href="#" class="text-primary manage-tax" data-id="${user.id}">[Tax]</a>
                                    <a href="#" class="text-primary manage-amendments" data-id="${user.id}">[PS Amendments]</a>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning waves-effect waves-light btn-sm click_view_user" title="View Employee">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_user" title="Edit Employee">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_user" title="Delete Employee">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');

                $('#user_table_body').html(rows);
                bindFunctionToggles();
            } catch (error) {
                $('#user_table_body').html('<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>');
                console.error('Error fetching data:', error);
            }
        }

//===================================================================================
 // Event Listeners for Function Toggles
 //===================================================================================
        function bindFunctionToggles() {
            $('#show_user_functions').off('click').on('click', function (event) {
                event.preventDefault();
                $('.user-functions-1').show();
                $('.user-functions-2').show();
                $('.payroll-functions').hide();
            });

            $('#show_payroll_functions').off('click').on('click', function (event) {
                event.preventDefault();
                $('.payroll-functions').show();
                $('.user-functions-1').hide();
                $('.user-functions-2').hide();
            });

        }

//===================================================================================
// Edit and Delete Event Handlers
//===================================================================================

        //edit user
        $(document).on('click', '.click_edit_user', function () {
            let emp_id = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            if (emp_id) {
                window.location.href = '/employee/form?emp_id=' + emp_id;
            }
        });


        //view user
        $(document).on('click', '.click_view_user', function () {
            let user_id = $(this).closest('tr').find('td:nth-child(2)').text().trim();
            console.log('User ID:', user_id);

            if (user_id) {
                window.location.href = `/employee/profile/${user_id}`;
            } else {
                console.error('No employee ID found!');
            }
        });



        //delete user
        $(document).on('click', '.click_delete_user', async function () {
            let emp_id = $(this).closest('tr').attr('emp_id');

            try {
                let url = `/employee/delete`;
                const res = await commonDeleteFunction(emp_id, url, 'Employee');

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during employee deletion:`, error);
            }
        });

//========================================================================================
// Navigate to Employees' payroll details
//========================================================================================

        // Navigate to Employee Bank Details Page
        $(document).on('click', '.manage-bank', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/bank/details/${userId}`;
        });

        // Navigate to Employee wage Details Page
        $(document).on('click', '.manage-wage', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/wage/details/${userId}`;
        });


        // Navigate to Employee tax Details Page
        $(document).on('click', '.manage-tax', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/tax/details/${userId}`;
        });

        // Navigate to Employee ps amendments Details Page
        $(document).on('click', '.manage-amendments', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/amendments/details/${userId}`;
        });

//========================================================================================
// Navigate to Employees' employee details
//========================================================================================

        // Navigate to Employee qualification Details Page
        $(document).on('click', '.manage-qualification', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/qualification/details/${userId}`;
        });

        // Navigate to Employee documentation Page
        $(document).on('click', '.manage-document', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/document/details/${userId}`;
        });


         // Navigate to Employee work-experience Details Page
         $(document).on('click', '.manage-work', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/work_experience/details/${userId}`;
        });

         // Navigate to Employee promotion Details Page
         $(document).on('click', '.manage-promotion', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/promotion/details/${userId}`;
        });

         // Navigate to Employee family Details Page
         $(document).on('click', '.manage-family', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/family/details/${userId}`;
        });

         // Navigate to Employee job-history Details Page
         $(document).on('click', '.manage-jobhistory', function () {
            const userId = $(this).data('id');
            window.location.href = `/employee/jobhistory/details/${userId}`;
        });

    });

</script>

</x-app-layout>
