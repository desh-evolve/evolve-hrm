<x-app-layout :title="'Input Example'">

    <style>
        th, td{
           padding: 2px 10px !important; 
        }
        
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Timesheet</h5>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-body">

                    {{-- ----------------------------------------------------------------------------------- --}}
                    {{-- filter section --}}
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">

                                <div class="row mb-3 col-md-4">
                                    <label for="group_filter" class="form-label col-md-3">Group</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="group_filter">
                                            <option value="-1">-- All --</option>
                                            <option value="0">Root</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 col-md-4">
                                    <label for="branch_filter" class="form-label col-md-3">Branch</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="branch_filter">
                                            <option value="-1">-- All --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 col-md-4">
                                    <label for="department_filter" class="form-label mb-1 col-md-3">Department</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="department_filter">
                                            <option value="-1">-- All --</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="row mb-3 col-md-4">
                                    <label for="employee_filter" class="form-label mb-1 col-md-3">Employee</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="employee_filter">
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3 col-md-4">
                                    <label for="date_filter" class="form-label mb-1 col-md-3">Date</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" id="date_filter" value="<?=date('Y-m-d')?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-primary me-2">Punch</button>
                                    <button type="button" class="btn btn-outline-warning">Absence</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- timesheet section --}}
                    <div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-success text-white text-center"><i class="ri-printer-line cursor-pointer" data-toggle="tooltip" aria-label="print" data-bs-original-title="print" style="font-size: 18px;"></i></th>
                                    <th class="bg-success text-white text-center">Mon <br><span>Dec 9</span></th>
                                    <th class="bg-success text-white text-center">Tue <br><span>Dec 10</span></th>
                                    <th class="bg-success text-white text-center">Wed <br><span>Dec 11</span></th>
                                    <th class="bg-success text-white text-center">Thu <br><span>Dec 12</span></th>
                                    <th class="bg-success text-white text-center">Fri <br><span>Dec 13</span></th>
                                    <th class="bg-success text-white text-center">Sat <br><span>Dec 14</span></th>
                                    <th class="bg-success text-white text-center">Sun <br><span>Dec 15</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-end bg-success text-white">In</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-end bg-success text-white">Out</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-center bg-success text-white">Accumulated Time</td>
                                </tr>
                                <tr>
                                    <td class="text-end bg-success text-white">Total Time</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-center bg-success text-white">Branch</td>
                                </tr>
                                <tr>
                                    <td class="text-end bg-success text-white">Head Office</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-center bg-success text-white">
                                        <strong>Pay Period: </strong>
                                        @if(isset($payPeriod))
                                            {{ date('Y-m-d', strtotime($payPeriod->start_date)) }} to 
                                            {{ date('Y-m-d', strtotime($payPeriod->end_date)) }}
                                        @else
                                            NONE
                                        @endif
                                    </td>                                                                       
                                </tr> 
                            </tbody>
                        </table>
                    </div>

                    {{-- totals & actions section --}}
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="2" class="bg-success text-white text-center">Exception Legend</th></tr>
                                    <tr>
                                        <th class="bg-success text-white text-center">Code</th>
                                        <th class="bg-success text-white text-center">Exception</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger fw-bold">M1</td>
                                        <td>Missing In Punch</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="2" class="bg-success text-white text-center">Paid Time</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Worked Time</td>
                                        <td>133:20</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total Time</td>
                                        <td>133:20</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="2" class="bg-success text-white text-center">Accumulated Time</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Regular Time</td>
                                        <td>50:55</td>
                                    </tr>
                                    <tr>
                                        <td>Daily OT</td>
                                        <td>03:55</td>
                                    </tr>
                                    <tr>
                                        <td>Daily OT 3</td>
                                        <td>04:45</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total Time</td>
                                        <td>133:20</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ----------------------------------------------------------------------------------- --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(function(){
            getDropdownDataFunc();
        })

        async function getDropdownDataFunc(){
            try {
                dropdownData = await commonFetchData('/employee/timesheet/dropdown');

                branches = dropdownData.branches.length > 0 && dropdownData.branches.map(e => `<option value="${e.id}">${e.branch_name}</option>`).join('');
                departments = dropdownData.departments.length > 0 && dropdownData.departments.map(e => `<option value="${e.id}">${e.department_name}</option>`).join('');
                employee_groups = dropdownData.employee_groups.length > 0 && dropdownData.employee_groups.map(e => `<option value="${e.id}">${e.emp_group_name}</option>`).join('');
                employees = dropdownData.employees.length > 0 && dropdownData.employees.map(e => `<option value="${e.id}">${e.first_name+' '+e.last_name}</option>`).join('');

                $('#group_filter').append(branches);
                $('#branch_filter').append(departments);
                $('#department_filter').append(employee_groups);
                $('#employee_filter').append(employees);
                
            }catch(error){
                console.error('error at attendance->timesheet->index->getDropdownDataFunc: ', error);
            } 
        }

    </script>
</x-app-layout>