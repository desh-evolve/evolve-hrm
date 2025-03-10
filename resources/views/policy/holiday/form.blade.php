<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8{
            width: 8% !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0 title-form">Add Holiday Policy</h5>
                    </div>

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <a href="/policy/holiday" class="btn btn-danger">Back</a>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="name" placeholder="Enter Name" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="types" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="types">
                                        <option value="standard">Standard</option>
                                        <option value="advanced_fixed">Advanced - Fixed</option>
                                        <option value="advanced_average">Advanced - Average</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="default_schedule_status" class="form-label mb-1 col-md-3">Default Schedule Status</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="default_schedule_status">
                                        <option value="working">Working</option>
                                        <option value="absent">Absent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="holiday_eligibility_section">
                            <h5 class="bg-primary text-white p-1 mb-3">Holiday Eligibility</h5>
                            <div class="row mb-3">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Employed Days</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="number" class="form-control w-50 numonly" id="minimum_employed_days" value="">
                                    <span class="ps-4"></span>
                                </div>
                            </div>
                            <div id="advanced_holiday_eligibility_section">
                                <div class="row mb-3">
                                    <label for="minimum_worked_days" class="form-label mb-1 col-md-3">Employee Must Work at Least</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-25" id="minimum_worked_days" value="15">
                                        <span class="ms-3 me-3 w-8">of the</span>
                                        <input type="text" class="form-control numonly ms-3 me-3 w-25" id="minimum_worked_period_days" value="30">
                                        <select class="form-select w-25" id="worked_scheduled_days">
                                            <option value="calendar_days">Calendar Days</option>
                                            <option value="scheduled_days">Scheduled Days</option>
                                            <option value="holiday_week_days">Holiday Week Days</option>
                                        </select>
                                        <span class="ms-3 me-3 w-100">prior to the holiday.</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="minimum_worked_after_days" class="form-label mb-1 col-md-3">Employee Must Work at Least</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-25" id="minimum_worked_after_days" value="0">
                                        <span class="ms-3 me-3 w-8">of the</span>
                                        <input type="text" class="form-control numonly ms-3 me-3 w-25" id="minimum_worked_after_period_days" value="0">
                                        <select class="form-select w-25" id="worked_after_scheduled_days">
                                            <option value="calendar_days">Calendar Days</option>
                                            <option value="scheduled_days">Scheduled Days</option>
                                            <option value="holiday_week_days">Holiday Week Days</option>
                                        </select>
                                        <span class="ms-3 me-3 w-100">following the holiday.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="holiday_time_calc_section">
                            <h5 class="bg-primary text-white p-1 mb-3">Holiday Time Calculation</h5>

                            <div class="row mb-3" id="holiday_time_section">
                                <label for="time" class="form-label mb-1 col-md-3">Holiday Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div id="avg_holiday_time_section">
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="average_time_days" class="form-label mb-1 col-md-3">Total Time over</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-50" id="average_time_days" value="30">
                                        <span class="ps-2">(days)</span>
                                    </div>
                                </div>
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="average_time_worked_days" class="form-label mb-1 col-md-3">Average Time over</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <span class="me-1 w-25">Worked Days Only</span>
                                        <input type="checkbox" class="form-check-input" id="average_time_worked_days">
                                        <span class="ms-3 me-3">or</span>
                                        <input type="text" class="form-control numonly w-25" id="average_days" value="30">
                                        <span class="ms-3 w-100">days.</span>
                                    </div>
                                </div>
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="minimum_time" class="form-label mb-1 col-md-3">Minimum Time</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-50" id="minimum_time" placeholder="hh:mm (2:15)" value="00:00">
                                        <span class="ps-4">hh:mm (2:15) (Use 0 for no minimum)</span>
                                    </div>
                                </div>
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="maximum_time" class="form-label mb-1 col-md-3">Maximum Time</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-50" id="maximum_time" placeholder="hh:mm (2:15)" value="00:00">
                                        <span class="ps-4">hh:mm (2:15) (Use 0 for no maximum)</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="force_over_time_policy" class="form-label mb-1 col-md-3">Always Apply Over Time/Premium Policies</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="force_over_time_policy">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="include_over_time" class="form-label mb-1 col-md-3">Include Over Time in Average</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="include_over_time">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="include_paid_absence_time" class="form-label mb-1 col-md-3">Include Paid Absence Time in Average</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="include_paid_absence_time">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="round_interval_policy_id" class="form-label mb-1 col-md-3">Rounding Policy</label>
                                    <div class="col-md-9">
                                        <select class="form-select w-50" id="round_interval_policy_id">
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="absence_policy_id" class="form-label mb-1 col-md-3">Absence Policy</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="absence_policy_id">
                                        <!-- Add options dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="holidays_section">
                            <h5 class="bg-primary text-white p-1 mb-3">Add Holidays</h5>
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <label for="time" class="form-label mb-1 col-md-3">Holiday Name</label>
                                    <input type="text" class="form-control w-100" id="holiday_name" placeholder="Enter holiday name here">
                                </div>
                                <div class="col-md-5">
                                    <label for="time" class="form-label mb-1 col-md-3">Holiday Date</label>
                                    <input type="date" class="form-control w-100" id="holiday_date">
                                </div>
                                <div class="col-md-1 d-flex align-items-end ms-3">
                                    <button type="button" class="btn btn-primary" id="holiday_add"><i class="ri-add-line"></i></button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="holiday_tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="holiday_policy_id" value=""/>
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function() {
            getDropdownData();
            showSections('standard');

            //change title
            let storedTitle = localStorage.getItem('editTitle');
            if (storedTitle) {
                $('.title-form').html(storedTitle);
                localStorage.removeItem('editTitle');
            }

            <?php if (isset($_GET['id'])): ?>
                let holiday_policy_id = <?= json_encode($_GET['id']); ?>;
                getUpdateData(holiday_policy_id);
            <?php endif; ?>

        });


        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/holiday/dropdown');

                // Populate rounding policies dropdown
                let roundingPoliciesList = (dropdownData?.rounding_policies || [])
                    .map(types => `<option value="${types.id}">${types.name}</option>`)
                    .join('');
                $('#round_interval_policy_id').html('<option value="0">--</option>' + roundingPoliciesList);

                // Populate absence policies dropdown
                let absencePoliciesList = (dropdownData?.absence_policies || [])
                    .map(types => `<option value="${types.id}">${types.name}</option>`)
                    .join('');
                $('#absence_policy_id').html('<option value="0">--</option>' + absencePoliciesList);


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }


        // Change Types
        $(document).on('change', '#types', function(){
            let types = $(this).val();
            console.log('Selected type:', types); // Log the selected type
            showSections(types);
        });

        function showSections(types){
            resetForm(false);

            if(types === 'standard'){
                $('#advanced_holiday_eligibility_section').hide();
                $('#avg_holiday_time_section').hide();
                $('#time').closest('.row').show();
            }else if(types === 'advanced_fixed'){
                $('#advanced_holiday_eligibility_section').show();
                $('#avg_holiday_time_section').hide();
                $('#time').closest('.row').show();
            }else if(types === 'advanced_average'){
                $('#advanced_holiday_eligibility_section').show();
                $('#avg_holiday_time_section').show();
                $('#time').closest('.row').hide();
            }else{
                console.error('wrong type selected');
            }
        }


        // Add Holiday Policy
        $(document).on('click', '#holiday_add', function(){
            let holiday_id = '';
            let holiday_name = $('#holiday_name').val();
            let holiday_date = $('#holiday_date').val();

            if(holiday_name == '' || holiday_date == ''){
                alert('Both Holiday Name & Date are required!');
                return;
            }

            renderHolidayTable(holiday_id, holiday_name, holiday_date);
            resetHolidays();
        });


        // Render Table
        function renderHolidayTable(holiday_id, holiday_name, holiday_date){
            let rowCount = $('#holiday_tbody tr').length + 1;
            let list = '';

            list = `
                <tr>
                    <td>${rowCount}</td>
                    <td>${holiday_name}</td>
                    <td>${holiday_date}</td>
                    <td>
                        <input type="hidden" class="holiday_name" id="${holiday_id}" value="${holiday_name}">
                        <input type="hidden" class="holiday_date" id="${holiday_id}" value="${holiday_date}">
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm remove_holiday" title="Remove Holiday" data-tooltip="tooltip" data-bs-placement="top"><i class="ri-delete-bin-fill"></i></button>
                    </td>
                </tr>
            `;

            $('#holiday_tbody').append(list);
        }



        async function getUpdateData(holiday_policy_id) {
            $('#holiday_policy_id').val(holiday_policy_id); // Set the ID in the hidden field

            try {
                // Fetch the holiday policy data
                let response = await commonFetchData(`/policy/holiday/${holiday_policy_id}`);
                let data = response[0]; // Extract the first object
                console.log('Fetched Data:', data);

                if (data) {
                    resetForm(false);  // Reset after setting values

                    let minimum_time = convertSecondsToHoursAndMinutes(data.minimum_time || 0);
                    let maximum_time = convertSecondsToHoursAndMinutes(data.maximum_time || 0);

                    // Populate form fields
                    $('#name').val(data.name);
                    $('#types').val(data.type || '').trigger('change');
                    showSections(data.type);  // Ensure correct section is shown
                    $('#default_schedule_status').val(data.default_schedule_status);

                    // Setting the value for minimum_employed_days
                    $('#minimum_employed_days').val(data.minimum_employed_days || 30).trigger('change');
                    $('#minimum_worked_days').val(data.minimum_worked_days || 0);
                    $('#minimum_worked_period_days').val(data.minimum_worked_period_days || 0);
                    $('#worked_scheduled_days').val(data.worked_scheduled_days || 'calendar_days').trigger('change');
                    $('#minimum_worked_after_days').val(data.minimum_worked_after_days || 0);
                    $('#minimum_worked_after_period_days').val(data.minimum_worked_after_period_days || 0);
                    $('#worked_after_scheduled_days').val(data.worked_after_scheduled_days || 'calendar_days').trigger('change');
                    $('#time').val(data.time);
                    $('#average_time_days').val(data.average_time_days);
                    $('#average_time_worked_days').val(data.average_time_worked_days);
                    $('#average_days').val(data.average_days);
                    $('#minimum_time').val(minimum_time);
                    $('#maximum_time').val(maximum_time);
                    $('#force_over_time_policy').prop('checked', data.force_over_time_policy || false);
                    $('#include_over_time').prop('checked', data.include_over_time || false);
                    $('#include_paid_absence_time').prop('checked', data.include_paid_absence_time || false);
                    $('#round_interval_policy_id').val(data.round_interval_policy_id || '0').trigger('change');
                    $('#absence_policy_id').val(data.absence_policy_id || '0').trigger('change');

                    if (data.holidays && data.holidays.length > 0) {
                        data.holidays.map((e) => {
                            renderHolidayTable(e.holiday_id, e.holiday_name, e.holiday_date);
                        })
                    }

                }
            } catch (error) {
                console.error('Error while fetching holiday policy data:', error);
                $('#error-msg').html('<p class="text-danger">Failed to load data. Please try again.</p>');
            }
        }



        // Remove button
        $(document).on('click', '.remove_holiday', function(){
            $(this).closest('tr').remove();
        });



        //==
        // Add & Edit Submit
        $(document).on('click', '#form_submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let holiday_id = $('#holiday_policy_id').val();
            console.log('holiday id', holiday_id);

            if (!holiday_id) {
                alert('Holiday ID is missing, cannot update!');
                return;
            }

            let average_time_worked_days = $('#average_time_worked_days').is(':checked') ? 1 : 0;
            let force_over_time_policy = $('#force_over_time_policy').is(':checked') ? 1 : 0;
            let include_over_time = $('#include_over_time').is(':checked') ? 1 : 0;
            let include_paid_absence_time = $('#include_paid_absence_time').is(':checked') ? 1 : 0;

            let time = convertHoursAndMinutesToSeconds($('#time').val() || '0:00');
            let minimum_time = convertHoursAndMinutesToSeconds($('#minimum_time').val() || '0:00');
            let maximum_time = convertHoursAndMinutesToSeconds($('#maximum_time').val() || '0:00');

            // Ensure correct 'types' value is being captured
            let selectedType = $('#types').val();
            console.log('Selected Type:', selectedType); // Check if the value is correct
            formData.append('types', selectedType);

            formData.append('name', $('#name').val());
            formData.append('default_schedule_status', $('#default_schedule_status').val());
            formData.append('minimum_employed_days', $('#minimum_employed_days').val());
            formData.append('minimum_worked_days', $('#minimum_worked_days').val());
            formData.append('minimum_worked_period_days', $('#minimum_worked_period_days').val());
            formData.append('worked_scheduled_days', $('#worked_scheduled_days').val());
            formData.append('minimum_worked_after_days', $('#minimum_worked_after_days').val());
            formData.append('minimum_worked_after_period_days', $('#minimum_worked_after_period_days').val());
            formData.append('worked_after_scheduled_days', $('#worked_after_scheduled_days').val());
            formData.append('time', time);
            formData.append('average_time_days', $('#average_time_days').val());
            formData.append('average_time_worked_days', average_time_worked_days);
            formData.append('average_days', $('#average_days').val());
            formData.append('minimum_time', minimum_time);
            formData.append('maximum_time', maximum_time);
            formData.append('force_over_time_policy', force_over_time_policy);
            formData.append('include_over_time', include_over_time);
            formData.append('include_paid_absence_time', include_paid_absence_time);
            formData.append('round_interval_policy_id', $('#round_interval_policy_id').val());
            formData.append('absence_policy_id', $('#absence_policy_id').val());

            let holiday_names = $('.holiday_name').map(function (i) {
                let id = $(this).attr('id');
                formData.append(`holiday_names[${id ?? i}]`, $(this).val());
            });

            let holiday_dates = $('.holiday_date').map(function (i) {
                let id = $(this).attr('id');
                formData.append(`holiday_dates[${id ?? i}]`, $(this).val());
            });

            let createUrl = `/policy/holiday/create`;
            let updateUrl = `/policy/holiday/update/${holiday_id}`;

            const isUpdating = Boolean(holiday_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', holiday_id);
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    $('#holiday-form-modal').modal('hide');
                    window.location.href = '/policy/holiday';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        function resetHolidays(){
            $('#holiday_name').val('');
            $('#holiday_date').val('');
        }

        // reset
        function resetForm(resetAll = true){
            if(resetAll){
                $('#absence_id').val('');
                $('#name').val('');
                $('#types').val('standard').trigger('change');
                $('#default_schedule_status').val('working').trigger('change');
                $('#holiday_name').val('');
                $('#holiday_date').val('');
                $('#holiday_tbody').html('');

            }
            $('#minimum_employed_days').val('30');
            $('#minimum_worked_days').val('15');
            $('#minimum_worked_period_days').val('30');
            $('#worked_scheduled_days').val('calendar_days').trigger('change');
            $('#minimum_worked_after_days').val('0');
            $('#minimum_worked_after_period_days').val('0');
            $('#worked_after_scheduled_days').val('calendar_days').trigger('change');
            $('#time').val('00:00');
            $('#average_time_days').val('30');
            $('#average_time_worked_days').prop('checked', false);
            $('#average_days').val('30');
            $('#minimum_time').val('00:00');
            $('#maximum_time').val('00:00');
            $('#force_over_time_policy').prop('checked', false);
            $('#include_over_time').prop('checked', false);
            $('#include_paid_absence_time').prop('checked', false);
            $('#round_interval_policy_id').val('0');
            $('#absence_policy_id').val('0');
        }


    </script>

</x-app-layout>
