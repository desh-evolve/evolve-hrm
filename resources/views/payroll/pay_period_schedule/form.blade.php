<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8 {
            width: 8% !important;
        }

        .custom-width {
            width: 50%;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Add Pay Period Schedule</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="form-label mb-1 col-md-3">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="description">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="start_week_day" class="form-label mb-1 col-md-3">Overtime Week</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="start_week_day">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="time_zone" class="form-label mb-1 col-md-3">Time Zone</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="time_zone">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="new_day_trigger_time" class="form-label mb-1 col-md-3">Minimum Time-Off
                                    Between Shifts</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50" id="new_day_trigger_time">
                                    <span class="ms-2 text-muted">hh:mm (2:15) (Only for shifts that span
                                        midnight)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="maximum_shift_time" class="form-label mb-1 col-md-3">Maximum Shift
                                    Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50" id="maximum_shift_time">
                                    <span class="ms-2 text-muted">hh:mm (2:15)</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="shift_assigned_day" class="form-label mb-1 col-md-3">Assign Shifts
                                    To</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="shift_assigned_day">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="timesheet_verification_section">
                            <u>
                                <h5 class="bg-primary text-white">TimeSheet Verification</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="timesheet_verify_type" class="form-label mb-1 col-md-3">TimeSheet
                                    Verification</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="timesheet_verify_type">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="timesheet_verify_before_end_date"
                                    class="form-label mb-1 col-md-3">Verification Window Starts</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50"
                                        id="timesheet_verify_before_end_date">
                                    <span class="ms-2 text-muted">Day(s) (Before Pay Period End Date)</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="timesheet_verify_before_transaction_date"
                                    class="form-label mb-1 col-md-3">Verification Window Ends</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50"
                                        id="timesheet_verify_before_transaction_date">
                                    <span class="ms-2 text-muted">Day(s) (Before Pay Period Transaction Date)</span>
                                </div>
                            </div>
                        </div>

                        <div id="Options_pay_period_date">
                            <u>
                                <h5 class="bg-primary text-white">Pay Period Dates</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="type">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="annual_pay_periods" class="form-label mb-1 col-md-3">Annual Pay
                                    Periods</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="annual_pay_periods">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="start_day_of_week" class="form-label mb-1 col-md-3">Pay Period Starts
                                    On</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="start_day_of_week">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="transaction_date" class="form-label mb-1 col-md-3">Transaction
                                    Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50" id="transaction_date">
                                    <span class="ms-2 text-muted">(days after end of pay period)</span>
                                </div>
                            </div>
                        </div>

                        <div id="primary_section">
                            <u>
                                <h5 class="bg-primary text-white">Primary</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="primary_day_of_month" class="form-label mb-1 col-md-3">Pay Period Start
                                    Day Of Month</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50" id="primary_day_of_month">
                                    <span class="ms-2 text-muted">at 00:00</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="primary_transaction_day_of_month"
                                    class="form-label mb-1 col-md-3">Transaction Day Of Month</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50"
                                        id="primary_transaction_day_of_month">
                                </div>
                            </div>
                        </div>

                        <div id="secondary_section">
                            <u>
                                <h5 class="bg-primary text-white">Secondary</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="secondary_day_of_month" class="form-label mb-1 col-md-3">Pay Period Start
                                    Day Of Month</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50" id="secondary_day_of_month">
                                    <span class="ms-2 text-muted">at 00:00</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="secondary_transaction_day_of_month"
                                    class="form-label mb-1 col-md-3">Transaction Day Of Month</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50"
                                        id="secondary_transaction_day_of_month">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="timesheet_verify_before_transaction_date"
                                    class="form-label mb-1 col-md-3">Verification Window Ends</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50"
                                        id="timesheet_verify_before_transaction_date">
                                    <span class="ms-2 text-muted">Day(s) (Before Pay Period Transaction Date)</span>
                                </div>
                            </div>
                        </div>

                        <div id="always_show_section">
                            <div class="row mb-3">
                                <label for="transaction_date_bd" class="form-label mb-1 col-md-3">Transaction Always
                                    on Business Day</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="transaction_date_bd">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="anchor_date" class="form-label mb-1 col-md-3">Create Initial Pay Periods
                                    From</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control w-50" id="anchor_date">
                                </div>
                            </div>
                        </div>

                        <div id="employee_section">
                            <div class="row mb-3">
                                <label for="employee_ids" class="form-label mb-1 col-md-3">Employees</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="employeeContainer">
                                        {{-- render employees dynamically --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="pay_period_schedule_status"
                                    class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select" id="pay_period_schedule_status" style="width: 50%;">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>



                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="holiday_policy_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        const employeeIds = '';
        let dropdownData = [];

        $(document).ready(async function() {
            await getDropdownData();

            // Check if the `id` parameter is available in the query string
            <?php if (isset($_GET['id'])): ?>
            const id = <?= json_encode($_GET['id']) ?>; // Safely pass PHP variable to JavaScript
            getUpdateData(id);
            <?php endif; ?>
        });

        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/payroll/pay_period_schedule/dropdown')

                console.log("Employees Data:", dropdownData?.employees);

                // Time Zone dropdown
                let timeZoneList = (dropdownData?.time_zones || [])
                    .map(time_zone => `<option value="${time_zone.id}">${time_zone.name}</option>`)
                    .join('');
                $('#time_zone').html('<option value="">Select Time Zone</option>' +
                    timeZoneList);

                // Initialize the multiSelector for employees
                $('#employeeContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.employees || [],
                });

                // Populate timesheet_verify_type dropdown
                let timesheetVerifyOnList = (dropdownData?.timesheet_verify_on || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#timesheet_verify_type').html('<option value="">Select TimeSheet Verification </option>' +
                    timesheetVerifyOnList);

                // Populate overtime_week dropdown
                let overtimeWeekList = (dropdownData?.overtime_week || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#start_week_day').html('<option value="">Select Overtime Week</option>' + overtimeWeekList);

                // Populate type dropdown
                let typeList = (dropdownData?.type || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#type').html('<option value="">Select Overtime Week</option>' + typeList);


                // Populate start_day_of_week dropdown
                let startDayOfWeekList = (dropdownData?.start_day_of_week || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#start_day_of_week').html('<option value="">Select Start Day Of Week</option>' + startDayOfWeekList);

                // Populate start_day_of_week dropdown
                let transactionDateBdList = (dropdownData?.transaction_date_bd || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#transaction_date_bd').html('<option value="">Select Transaction Date</option>' +
                    transactionDateBdList);

                // Populate assign_shift_to dropdown
                let assignShiftToList = (dropdownData?.assign_shift_to || [])
                    .map(assign => `<option value="${assign.value}">${assign.name}</option>`)
                    .join('');
                $('#shift_assigned_day').html('<option value="">Select Overtime Week List</option>' +
                    assignShiftToList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        //  click event
        $(document).on('click', '#form_submit', async function() {

            const pay_period_schedule_id = $('#pay_period_schedule_id').val();

            // Collect selected employee IDs from the multiSelector component
            const selectedIds = $('#employeeContainer .selected-list option').map(function () {
                return $(this).val();
            }).get();

            let createUrl = `/payroll/pay_period_schedule/create`;
            let updateUrl = `/payroll/pay_period_schedule/update/${pay_period_schedule_id}`;

            let formData = new FormData();

            // if (!pay_stub_entry_name_id || !selectedIds) {
            //     $('#error-msg').html('<p class="text-danger">All fields are required</p>');
            //     return;
            // } else {
            //     $('#error-msg').html(''); // Clear error message if no issues
            // }

            formData.append('employee_ids', JSON.stringify(selectedIds));
            formData.append('name', $('#name').val());
            formData.append('description', $('#description').val());
            formData.append('start_week_day', $('#start_week_day').val());
            formData.append('time_zone', $('#time_zone').val());
            formData.append('new_day_trigger_time', $('#new_day_trigger_time').val());
            formData.append('maximum_shift_time', $('#maximum_shift_time').val());
            formData.append('shift_assigned_day', $('#shift_assigned_day').val());
            formData.append('timesheet_verify_type', $('#timesheet_verify_type').val());
            formData.append('type', $('#type').val());
            formData.append('start_day_of_week', $('#start_day_of_week').val());
            formData.append('transaction_date', $('#transaction_date').val());
            formData.append('transaction_date_bd', $('#transaction_date_bd').val());
            formData.append('anchor_date', $('#anchor_date').val());
            formData.append('timesheet_verify_before_end_date', $('#timesheet_verify_before_end_date').val());
            formData.append('timesheet_verify_before_transaction_date', $(
                '#timesheet_verify_before_transaction_date').val());
            formData.append('primary_day_of_month', $('#primary_day_of_month').val());
            formData.append('primary_transaction_day_of_month', $('#primary_transaction_day_of_month').val());
            formData.append('secondary_day_of_month', $('#secondary_day_of_month').val());
            formData.append('secondary_transaction_day_of_month', $('#secondary_transaction_day_of_month')
                .val());
            formData.append('transaction_date_bd', $('#transaction_date_bd').val());
            formData.append('pay_period_schedule_status', $('#pay_period_schedule_status').val());


            const isUpdating = Boolean(pay_period_schedule_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'payroll/pay_period_schedule';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('payroll.pay_period_schedule') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        // ================================ Type change ====================================
        $(document).on('change', '#type', function() {

            if ($(this).val() == 'manual') {
                $('#annual_pay_periods').closest('.row').show();
                $('#start_day_of_week').closest('.row').hide();
                $('#transaction_date').closest('.row').hide();
                $('#always_show_section').hide();
                $('#primary_section').hide();
                $('#secondary_section').hide();
            } else if ($(this).val() === 'weekly' || $(this).val() === 'bi-weekly') {
                $('#transaction_date').closest('.row').show();
                $('#start_day_of_week').closest('.row').show();
                $('#annual_pay_periods').closest('.row').hide();
                $('#always_show_section').show();
                $('#primary_section').hide();
                $('#secondary_section').hide();
            } else if ($(this).val() === 'semi-monthly') {
                $('#transaction_date').closest('.row').hide();
                $('#start_day_of_week').closest('.row').hide();
                $('#annual_pay_periods').closest('.row').hide();
                $('#always_show_section').show();
                $('#primary_section').show();
                $('#secondary_section').show();
            } else {
                $('#transaction_date').closest('.row').hide();
                $('#start_day_of_week').closest('.row').hide();
                $('#annual_pay_periods').closest('.row').hide();
                $('#always_show_section').show();
                $('#primary_section').show();
                $('#secondary_section').hide();
            }
        })

        // ====================== timesheet_verify_type change ====================================
        $(document).on('change', '#timesheet_verify_type', function() {

            if ($(this).val() == 'disabled') {
                $('#timesheet_verify_before_end_date').closest('.row').hide();
                $('#timesheet_verify_before_transaction_date').closest('.row').hide();
            } else {
                $('#timesheet_verify_before_end_date').closest('.row').show();
                $('#timesheet_verify_before_transaction_date').closest('.row').show();
            }
        })

        async function getUpdateData(id) {
            try {
                let response = await commonFetchData(`/payroll/pay_period_schedule/${id}`);
                let data = response?.[0]; // Assuming the API returns an array with one item

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                console.log('Fetched pay period schedule data:', data);

                // Set the name and status fields
                // format date
                let rawDate = data.anchor_date;
                let formattedDate = new Date(rawDate).toISOString().split('T')[0]; // 'YYYY-MM-DD'
                $('#anchor_date').val(formattedDate);
                // Populate form fields
                $('#name').val(data.name || '');
                $('#description').val(data.description || '');
                $('#start_week_day').val(data.start_week_day || '');
                $('#time_zone').val(data.time_zone || '');
                $('#new_day_trigger_time').val(data.new_day_trigger_time || '');
                $('#maximum_shift_time').val(data.maximum_shift_time || '');
                $('#shift_assigned_day').val(data.shift_assigned_day || '');
                $('#timesheet_verify_type').val(data.timesheet_verify_type || '');
                $('#type').val(data.type || '');
                $('#start_day_of_week').val(data.start_day_of_week);
                $('#transaction_date').val(data.transaction_date);
                $('#transaction_date_bd').val(data.transaction_date_bd);
                $('#timesheet_verify_before_end_date').val(data.timesheet_verify_before_end_date);
                $('#timesheet_verify_before_transaction_date').val(data.timesheet_verify_before_transaction_date);
                $('#primary_day_of_month').val(data.primary_day_of_month);
                $('#primary_transaction_day_of_month').val(data.primary_transaction_day_of_month);
                $('#secondary_day_of_month').val(data.secondary_day_of_month);
                $('#secondary_transaction_day_of_month').val(data.secondary_transaction_day_of_month);
                $('#transaction_date_bd').val(data.transaction_date_bd);
                $('#pay_period_schedule_status').val(data.status);

                employeeIds = data.employees.map(emp => emp.employee_id);
                
                // Initialize the multiSelector for employees
                $('#employeeContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.employees || [],
                    selectedIds: employeeIds,
                });

            } catch (error) {
                console.error('Error fetching policy group data:', error);
            }
        }

        function resetForm() {
            $('#name').val('');
            $('#description').val('');
            $('#start_week_day').val('');
            $('#time_zone').val('');
            $('#new_day_trigger_time').val('');
            $('#maximum_shift_time').val('');
            $('#shift_assigned_day').val('');
            $('#timesheet_verify_type').val('');
            $('#type').val('');
            $('#start_day_of_week').val('');
            $('#transaction_date').val('');
            $('#transaction_date_bd').val('');
            $('#anchor_date').val('');
            $('#timesheet_verify_before_end_date').val('');
            $('#timesheet_verify_before_transaction_date').val('');
            $('#primary_day_of_month').val('');
            $('#primary_transaction_day_of_month').val('');
            $('#secondary_day_of_month').val('');
            $('#secondary_transaction_day_of_month').val('');
            $('#transaction_date_bd').val('');
            $('#pay_period_schedule_status').val('');

            // getDropdownData();
        }
    </script>

</x-app-layout>
