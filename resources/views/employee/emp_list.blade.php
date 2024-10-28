<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">

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
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_add_employee">New Employee <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="employee_table" class="table table-bordered dt-responsive nowrap table-striped align-middle datatable-example" style="width:100%">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Employee ID</th>
                                <th class="col">Name</th>
                                <th class="col">NIC</th>
                                <th class="col">Contact</th>
                                <th class="col">Status</th>
                                <th class="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employee_table_body">
                            
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

        async function renderEmployeeTable(){
            let list = '';

            list += `
                <tr>
                    <td>1</td>    
                    <td>0001</td>    
                    <td>Deshan Dissanayake</td>    
                    <td>980451785V</td>    
                    <td>0714567894</td>    
                    <td>active</td>    
                    <td>
                        <button class="btn btn-info btn-sm"><i class="fa fa-edit"></i></button>
                    </td>    
                </tr>
            `;

            DataTablesForAjax.destroy();
            $('#employee_table_body').html(list);
            init_dataTable('#employee_table');
        }

    </script>

</x-app-layout>