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
                                    <label for="apply_frequency_hire_date" class="form-label mb-1 col-md-3">Employee's Appointment Date</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="apply_frequency_hire_date">
                                    </div>
                                </div>
                                <div class="row mb-3" id="day_of_month_section">
                                    <div class="row mb-3">
                                        <label for="apply_frequency_month" class="form-label mb-1 col-md-3">Day Of Month</label>
                                        <div class="col-md-9">
                                            <select class="form-select" id="apply_frequency_month">
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="apply_frequency_day_of_month" class="form-label mb-1 col-md-3">Day Of Month</label>
                                        <div class="col-md-9">
                                            <select class="form-select" id="apply_frequency_day_of_month">
                                                <?php for ($i=1; $i < 31; $i++) { ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3" id="day_of_week_section">
                                    <label for="apply_frequency_day_of_week" class="form-label mb-1 col-md-3">Day Of Week</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="apply_frequency_day_of_week">
                                            <option value="1">Sunday</option>
                                            <option value="2">Monday</option>
                                            <option value="3">Tuesday</option>
                                            <option value="4">Wednesday</option>
                                            <option value="5">Thursday</option>
                                            <option value="6">Friday</option>
                                            <option value="7">Saturday</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" id="min_emp_days_section">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">After Minimum Employed Days</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control numonly" id="minimum_employed_days" placeholder="Enter Minimum Employed Days" value="0">
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
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
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
                        
                        <div>
                            <input type="hidden" id="accrual_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
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
            $('#accrual_form_tbody').html('');

            // Bind form submission or button click for sending data
            $('#form_submit').click(function() {
                sendDataToBackend();
            });
        })

        $(document).on('click', '.add_milestone', function(){
            let list = '';

            list += `
                <tr class="milestones_list" id="">
                    <td>
                        <span>After: </span>
                        <input type="text" class="numonly length_of_service" value="0" />
                        <select class="length_of_service_unit">
                            <option value="days">Day(s)</option>    
                            <option value="weeks">Week(s)</option>    
                            <option value="months">Month(s)</option>    
                            <option value="years">Year(s)</option>    
                            <option value="hours">Hour(s)</option>    
                        </select>
                    </td>
                    <td>
                        <input type="text" class="numonly accrual_rate" value="0.0000" />
                        <span>ie: 0.0192</span>
                    </td>
                    <td>
                        <input type="text" class="numonly maximum_time" value="00:00" />
                        <span>ie: hh:mm (2:15)</span>
                    </td>
                    <td>
                        <input type="text" class="numonly rollover_time" value="00:00" />
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
            
            $('#accrual_form_tbody').html('');
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

        // Function to send the collected data to the backend
        async function sendDataToBackend() {
            const name = $('#name').val();
            const type = $('#type').val();

            if (name === '') {
                alert('Accrual policy name is required!');
                return false;
            }

            if(type === '') {
                alert('Accrual policy type is required!');
                return false;
            }

            let formData = new FormData();

            formData.append('name', $('#name').val());
            formData.append('type', $('#type').val());
            formData.append('enable_pay_stub_balance_display', $('#enable_pay_stub_balance_display').prop('checked') ? 1 : 0);
            formData.append('apply_frequency', $('#apply_frequency').val());
            formData.append('apply_frequency_hire_date', $('#apply_frequency_hire_date').prop('checked') ? 1 : 0);
            formData.append('apply_frequency_month', $('#apply_frequency_month').val() ?? 0);
            formData.append('apply_frequency_day_of_month', $('#apply_frequency_day_of_month').val() ?? 0);
            formData.append('apply_frequency_day_of_week', $('#apply_frequency_day_of_week').val() ?? 0);
            formData.append('minimum_employed_days', $('#minimum_employed_days').val() ?? 0);
            formData.append('milestone_rollover_hire_date', $('#milestone_rollover_hire_date').prop('checked') ? 1 : 0);
            formData.append('milestone_rollover_month', $('#milestone_rollover_month').val() ?? 0);
            formData.append('milestone_rollover_day_of_month', $('#milestone_rollover_day_of_month').val() ?? 0);

            // Collecting milestones data
            $('.milestones_list').each(function(i) {
                const id = $(this).attr('id') || i;
                const maximumTime = convertHoursAndMinutesToSeconds($(this).find('.maximum_time').val() || '0:00');
                const rolloverTime = convertHoursAndMinutesToSeconds($(this).find('.rollover_time').val() || '0:00');

                formData.append(`milestones[${id}][length_of_service]`, $(this).find('.length_of_service').val() ?? 0);
                formData.append(`milestones[${id}][length_of_service_unit]`, $(this).find('.length_of_service_unit').val());
                formData.append(`milestones[${id}][accrual_rate]`, $(this).find('.accrual_rate').val());
                formData.append(`milestones[${id}][maximum_time]`, maximumTime);
                formData.append(`milestones[${id}][rollover_time]`, rolloverTime);
            });

            const accrual_id = $('#accrual_id').val();
            
            let createUrl = '/policy/accrual/create';
            let updateUrl = `/policy/accrual/update/${accrual_id}`;
            
            const isUpdating = Boolean(accrual_id);
            const url = isUpdating ? updateUrl : createUrl;
            const method = isUpdating ? 'PUT' : 'POST';

            // Add accrual_id if updating
            if (isUpdating) {
                formData.append('id', accrual_id);
            }

            try {
                // Send data and handle the response
                const res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    window.location.href = '/policy/accrual';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        }
                
        </script>

</x-app-layout>