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
                        <h5 class="mb-0 title-form">Add Accrual Policy</h5>
                    </div>

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <a href="/policy/accrual" class="btn btn-danger">Back</a>
                        </div>
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
                            <h5 class="bg-primary text-white p-1 mb-3">Frequency In Which To Apply Time to Employee Records</h5>
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

                                <div class="row mb-3" id="month_section">
                                    <label for="apply_frequency_month" class="form-label mb-1 col-md-3">Month</label>
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
                            <h5 class="bg-primary text-white p-1 mb-3">Milestone Rollover Based On</h5>
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
                            <h5 class="bg-primary text-white p-1 mb-3">Length Of Service Milestones </h5>
                            <div class="d-flex justify-content-end mb-2">
                                <button type="button" class="btn btn-info btn-sm add_milestone">Add Milestone</button>
                            </div>
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Length Of Service</th>
                                        <th>Accrual Rate/Year</th>
                                        <th>Accrual Total Maximum	</th>
                                        <th>Annual Maximum Rollover</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="accrual_form_tbody">

                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <input type="hidden" id="accrual_policy_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function(){
            resetForm();
            $('#frequency_section').hide();
            $('#milestone_rollover_section').hide();
            $('#length_section').hide();
            $('#accrual_form_tbody').html('');

            // Bind form submission or button click for sending data
            $('#form_submit').click(function() {
                sendDataToBackend();
            });


            // Check if there's a stored title in localStorage
            let storedTitle = localStorage.getItem('editTitle');
            console.log('title', storedTitle);
            if (storedTitle) {
                $('.title-form').html(storedTitle);
                localStorage.removeItem('editTitle');
            } else {
                $('.title-form').html('Add Accrual Policy');  // Default title
            }

            // Check if the `id` parameter is available in the query string
            <?php if (isset($_GET['id'])): ?>
                let accrual_policy_id = <?= json_encode($_GET['id']); ?>;
                getUpdateData(accrual_policy_id);
            <?php endif; ?>

        });



        $(document).on('click', '.add_milestone', function(){
            appendMilestoneRow();
        });


        function appendMilestoneRow(milestone = {}, index = null) {
            const id = milestone.id || `milestone_${index || Date.now()}`;
            const maximumTime = convertSecondsToHoursAndMinutes(milestone.maximum_time || 0);
            const rolloverTime = convertSecondsToHoursAndMinutes(milestone.rollover_time || 0);

            const milestoneRow = `
                <tr class="milestones_list" id="${id}">
                    <td>
                        <span>After: </span>
                        <input type="text" class="numonly length_of_service" value="${milestone.length_of_service || 0}" />
                        <select class="length_of_service_unit">
                            <option value="days" ${milestone.length_of_service_unit === 'days' ? 'selected' : ''}>Day(s)</option>
                            <option value="weeks" ${milestone.length_of_service_unit === 'weeks' ? 'selected' : ''}>Week(s)</option>
                            <option value="months" ${milestone.length_of_service_unit === 'months' ? 'selected' : ''}>Month(s)</option>
                            <option value="years" ${milestone.length_of_service_unit === 'years' ? 'selected' : ''}>Year(s)</option>
                            <option value="hours" ${milestone.length_of_service_unit === 'hours' ? 'selected' : ''}>Hour(s)</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="numonly accrual_rate" value="${milestone.accrual_rate || 0}" />
                        <span>ie: 0.0192</span>
                    </td>
                    <td>
                        <input type="text" class="numonly maximum_time" value="${maximumTime}" />
                        <span>ie: hh:mm (2:15)</span>
                    </td>
                    <td>
                        <input type="text" class="numonly rollover_time" value="${rolloverTime}" />
                        <span>ie: hh:mm (2:15)</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm remove_milestone" title="Delete Accrual Milestone" data-tooltip="tooltip" data-bs-placement="top">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>`;
            $('#accrual_form_tbody').append(milestoneRow); // Append to the table body
        }


        $(document).on('click', '.remove_milestone', function(){
            $(this).closest('tr').remove();
        });


        // Change Type
        $(document).on('change', '#type', function(){
            // let type = $(this).val();
            showSections($(this).val());
        });


        //==
        function showSections(type) {
            $('#accrual_form_tbody').html(''); // Clear all milestones
            if (type == 'standard') {
                $('#frequency_section').hide();
                $('#milestone_rollover_section').hide();
                $('#length_section').hide();
            } else {
                $('#frequency_section').show();
                $('#milestone_rollover_section').show();
                $('#length_section').show();

                if (type == 'calendar_based') {
                    $('#apply_frequency_section').show();
                    $('#appointment_date_section').hide();
                    $('#day_of_month_section').hide();
                    $('#month_section').hide();
                    $('#apply_frequency_month').hide();
                    $('#day_of_week_section').hide();
                    $('#min_emp_days_section').show();
                } else { // Hour Based
                    $('#apply_frequency_section').hide();
                    $('#appointment_date_section').hide();
                    $('#day_of_month_section').hide();
                    $('#month_section').hide();
                    $('#apply_frequency_month').hide();
                    $('#day_of_week_section').hide();
                    $('#min_emp_days_section').show();
                }
            }
        }


        // Handle frequency change
        $('#apply_frequency').on('change', function () {
            const frequency = $(this).val();
            showFrequency(frequency);
        });

        // Handle hire date checkbox
        $('#apply_frequency_hire_date').on('change', function () {
            showAppointmentDateChanges(this.checked);
        });

        function showFrequency(frequency) {
            if (frequency === 'pay_period') {
                $('#appointment_date_section').hide();
                $('#month_section').hide();
                $('#apply_frequency_month').hide();
                $('#day_of_month_section').hide();
                $('#day_of_week_section').hide();
                $('#min_emp_days_section').show();
            } else if (frequency === 'annually') {
                $('#min_emp_days_section').show();
                $('#month_section').show();
                $('#apply_frequency_month').show();
                $('#day_of_month_section').show();
                $('#appointment_date_section').show();
                $('#day_of_week_section').hide();
            } else if(frequency === 'monthly') {
                $('#month_section').hide();
                $('#day_of_month_section').show();
                $('#min_emp_days_section').show();
                $('#day_of_week_section').hide();
                $('#appointment_date_section').hide();
            } else if (frequency === 'weekly') {
                $('#month_section').hide();
                $('#apply_frequency_month').hide();
                $('#day_of_month_section').hide();
                $('#appointment_date_section').hide();
                $('#day_of_week_section').show();
                $('#min_emp_days_section').show();
            } else {
                console.error('wrong frequency selected');
            }
        }

        function showAppointmentDateChanges(isDateChecked) {
            if (isDateChecked) {
                $('#month_section').hide();
                $('#apply_frequency_month').hide();
                $('#day_of_month_section').hide();
            } else {
                showFrequency($('#apply_frequency').val());
            }
        }


        // Change Employee's Appointment Date (Milestone)
        $(document).on('change', '#milestone_rollover_hire_date', function () {
            let isChecked = $(this).prop('checked'); // Use prop to check the checkbox status
            showAppointDateMilestone(isChecked);
        });

        function showAppointDateMilestone(isChecked) {
            if (isChecked) {
                $('#rollover_month_section').hide();
                $('#rollover_date_section').hide();
            } else {
                $('#rollover_month_section').show();
                $('#rollover_date_section').show();
            }
        }


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

            const accrual_policy_id = $('#accrual_policy_id').val();

            let createUrl = '/policy/accrual/create';
            let updateUrl = `/policy/accrual/update/${accrual_policy_id}`;

            const isUpdating = Boolean(accrual_policy_id);
            const url = isUpdating ? updateUrl : createUrl;
            const method = isUpdating ? 'PUT' : 'POST';

            // Add accrual_policy_id if updating
            if (isUpdating) {
                formData.append('id', accrual_policy_id);
            }

            try {
                // Send data and handle the response
                const res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '/policy/accrual';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        }



        async function getUpdateData(accrual_policy_id) {

            $('#accrual_policy_id').val(accrual_policy_id); // Set the ID in the hidden field

            try {
                // Fetch the accrual policy data
                const response = await commonFetchData(`/policy/accrual/${accrual_policy_id}`);
                const data = response[0]; // Extract the first object

                if (data) {
                    // Populate main form fields
                    $('#name').val(data.name);
                    $('#type').val(data.type || '').trigger('change');
                    showSections(data.type);

                    $('#enable_pay_stub_balance_display').prop('checked', data.enable_pay_stub_balance_display === 1);
                    $('#apply_frequency').val(data.apply_frequency || '').trigger('change');
                    showFrequency(data.apply_frequency);

                    $('#apply_frequency_hire_date').prop('checked', data.apply_frequency_hire_date === 1);
                    $('#apply_frequency_month').val(data.apply_frequency_month || 0);
                    $('#apply_frequency_day_of_month').val(data.apply_frequency_day_of_month || 0);
                    $('#apply_frequency_day_of_week').val(data.apply_frequency_day_of_week || 0);
                    $('#minimum_employed_days').val(data.minimum_employed_days || 0);
                    $('#milestone_rollover_hire_date').prop('checked', data.milestone_rollover_hire_date === 1);
                    $('#milestone_rollover_month').val(data.milestone_rollover_month || 0);
                    $('#milestone_rollover_day_of_month').val(data.milestone_rollover_day_of_month || 0);

                    // Populate milestones
                    if (data.milestones && Array.isArray(data.milestones)) {
                        $('#accrual_form_tbody').html(''); // Clear existing milestones
                        data.milestones.forEach((milestone, i) => {
                            appendMilestoneRow(milestone, i); // Use the separate function to add rows
                        });
                    }

                    // Handle "Type" change manually for correct UI adjustments
                    showAppointDateMilestone(data.milestone_rollover_hire_date);
                    showAppointmentDateChanges(data.apply_frequency_hire_date);
                }
            } catch (error) {
                console.error('Error while fetching accrual policy data:', error);
                $('#error-msg').html('<p class="text-danger">Failed to load data. Please try again.</p>');
            }
        }

        function resetForm() {
            // Reset main fields
            $('#name').val('');
            $('#type').val('standard').trigger('change');
            $('#enable_pay_stub_balance_display').prop('checked', false);
            $('#apply_frequency').val('pay_period').trigger('change');
            $('#apply_frequency_hire_date').prop('checked', false);
            $('#apply_frequency_month').val(1);
            $('#apply_frequency_day_of_month').val(1);
            $('#apply_frequency_day_of_week').val(1);
            $('#minimum_employed_days').val(0);
            $('#milestone_rollover_hire_date').prop('checked', false);
            $('#milestone_rollover_month').val(1);
            $('#milestone_rollover_day_of_month').val(1);

            // Clear milestones
            $('#accrual_form_tbody').html('');

            // Reset dynamic sections visibility
            $('#frequency_section').hide();
            $('#milestone_rollover_section').hide();
            $('#length_section').hide();

            // Reset checkboxes and radios to their default state
            $('#apply_frequency_hire_date').prop('checked', false);
            $('#milestone_rollover_hire_date').prop('checked', false);

            // Reset the title in the form
            $('.title-form').html('');

            // Reset the error message if there was one
            $('#error-msg').html('');

        }



    </script>

</x-app-layout>
