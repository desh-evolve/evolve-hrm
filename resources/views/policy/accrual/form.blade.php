<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Add Accrual Policy</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="type">
                                        <option value="standard">Standard</option>
                                        <option value="calendar_based">Calendar Based</option>
                                        <option value="hour_based">Hour Based</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="enable_pay_stub_balance_display" class="form-label mb-1 col-md-3">Display Balance on Pay Stub</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="enable_pay_stub_balance_display">
                                </div>
                            </div>
                        </div>

                        
                        <div id="frequency_section">
                            <u><h5 class="bg-primary text-white">Frequency In Which To Apply Time to Employee Records</h5></u>
                            <div class="row mb-3" id="apply_frequency_section">
                                <label for="apply_frequency" class="form-label mb-1 col-md-3">Frequency</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="apply_frequency">
                                        <option value="pay_period">Each Pay Period</option>
                                        <option value="annually">Annually</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="row mb-3" id="appointment_date_section">
                                    <label for="apply_frequency_month" class="form-label mb-1 col-md-3">Employee's Appointment Date</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="apply_frequency_month">
                                    </div>
                                </div>
                                <div class="row mb-3" id="day_of_month_section">
                                    <label for="apply_frequency_day_of_month" class="form-label mb-1 col-md-3">Day Of Month</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="apply_frequency_day_of_month">
                                            <?php for ($i=1; $i < 31; $i++) { ?>
                                                <option value="<?=$i?>"><?=$i?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3" id="day_of_week_section">
                                    <label for="apply_frequency_day_of_week" class="form-label mb-1 col-md-3">Day Of Week</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="apply_frequency_day_of_week">
                                            <option value="sunday>">Sunday</option>
                                            <option value="monday>">Monday</option>
                                            <option value="tuesday>">Tuesday</option>
                                            <option value="wednesday>">Wednesday</option>
                                            <option value="thursday>">Thursday</option>
                                            <option value="friday>">Friday</option>
                                            <option value="saturday>">Saturday</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">After Minimum Employed Days</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control numonly" id="minimum_employed_days" placeholder="Enter Name" value="">
                                </div>
                            </div>

                        </div>

                        
                        <div id="milestone_rollover_section">
                            <u><h5 class="bg-primary text-white">Milestone Rollover Based On</h5></u>
                            <div class="row mb-3">
                                <label for="milestone_rollover_hire_date" class="form-label mb-1 col-md-3">Employee's Appointment Date</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                </div>
                            </div>

                            <div class="row mb-3" id="rollover_month_section">
                                <label for="milestone_rollover_month" class="form-label mb-1 col-md-3">Month</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="milestone_rollover_month">
                                        <option value="january">January</option>
                                        <option value="february">February</option>
                                        <option value="march">March</option>
                                        <option value="april">April</option>
                                        <option value="may">May</option>
                                        <option value="june">June</option>
                                        <option value="july">July</option>
                                        <option value="august">August</option>
                                        <option value="september">September</option>
                                        <option value="october">October</option>
                                        <option value="november">November</option>
                                        <option value="december">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="rollover_date_section">
                                <label for="milestone_rollover_day_of_month" class="form-label mb-1 col-md-3">Day Of Month</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="milestone_rollover_day_of_month">
                                        <?php for ($i=1; $i < 31; $i++) { ?>
                                            <option value="<?=$i?>"><?=$i?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        
                        <div id="length_section">
                            <u><h5 class="bg-primary text-white">Length Of Service Milestones</h5></u>
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Length Of Service</th>
                                        <th>Accrual Rate/Year</th>
                                        <th>Accrual Total Maximum	</th>
                                        <th>Annual Maximum Rollover</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="accrual_form_tbody">

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-info btn-sm add_milestone">Add Milestone</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#frequency_section').hide();
            $('#milestone_rollover_section').hide();
            $('#length_section').hide();
        })

        $(document).on('click', '.add_milestone', function(){
            let list = '';

            list += `
                <tr>
                    <td>
                        <span>After: </span>
                        <input type="text" class="numonly" value="0" />
                        <select>
                            <option value="days">Day(s)</option>    
                            <option value="weeks">Week(s)</option>    
                            <option value="months">Month(s)</option>    
                            <option value="years">Year(s)</option>    
                            <option value="hours">Hour(s)</option>    
                        </select>
                    </td>
                    <td>
                        <input type="text" class="numonly" value="00:00" />
                        <span>ie: hh:mm (2:15)</span>
                    </td>
                    <td>
                        <input type="text" class="numonly" value="00:00" />
                        <span>ie: hh:mm (2:15)</span>
                    </td>
                    <td>
                        <input type="text" class="numonly" value="00:00" />
                        <span>ie: hh:mm (2:15)</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm remove_milestone" title="Delete Accrual Milestone" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#accrual_form_tbody').append(list);
        })


        $(document).on('click', '.remove_milestone', function(){
            $(this).closest('tr').remove();
        })

        $(document).on('change', '#type', function(){
            let type = $(this).val();
            
            if(type == 'standard'){ //standard
                $('#frequency_section').hide();
                $('#milestone_rollover_section').hide();
                $('#length_section').hide();
            }else{
                $('#frequency_section').show();
                $('#milestone_rollover_section').show();
                $('#length_section').show();
                
                if(type == 'calendar_based'){ //calendar based
                    $('#apply_frequency_section').show();
                    $('#appointment_date_section').hide();
                    $('#day_of_month_section').hide();
                    $('#day_of_week_section').hide();
                    $('#min_emp_days_section').show();
                }else{ //hour based
                    $('#apply_frequency_section').hide();
                    $('#appointment_date_section').hide();
                    $('#day_of_month_section').hide();
                    $('#day_of_week_section').hide();
                    $('#min_emp_days_section').show();
                }
            }
        })
        
        $(document).on('change', '#apply_frequency', function(){
            let frequency = $(this).val();

            if(frequency == 'pay_period'){ //pay_period
                $('#appointment_date_section').hide();
                $('#day_of_month_section').hide();
                $('#day_of_week_section').hide();
            }else if(frequency == 'annually'){ //annually
                $('#appointment_date_section').show();
                $('#day_of_month_section').hide();
                $('#day_of_week_section').hide();
            }else if(frequency == 'monthly'){ //monthly
                $('#appointment_date_section').hide();
                $('#day_of_month_section').show();
                $('#day_of_week_section').hide();
            }else{ //weekly
                $('#appointment_date_section').hide();
                $('#day_of_month_section').hide();
                $('#day_of_week_section').show();
            }
        })

        $(document).on('change', '#milestone_rollover_hire_date', function () {
            if (this.checked) {
                $('#rollover_month_section').hide();
                $('#rollover_date_section').hide();
            } else {
                $('#rollover_month_section').show();
                $('#rollover_date_section').show();
            }
        });
                
        </script>

</x-app-layout>