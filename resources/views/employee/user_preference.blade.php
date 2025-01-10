<x-app-layout :title="'Input Example'">


    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8 {
            width: 8% !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Edit User Preference Links</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="user_id" class="form-label mb-1 col-md-3">Employee</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="user_id" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="language" class="form-label mb-1 col-md-3">Language</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="language">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="date_format" class="form-label mb-1 col-md-3">Date Format</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="date_format">
                                        <option label="25/02/2001 (dd/mm/yyyy)" value="d/m/Y" selected="selected">
                                            25/02/2001 (dd/mm/yyyy)</option>
                                        <option label="25/02/01 (dd/mm/yy)" value="d/m/y">25/02/01 (dd/mm/yy)</option>
                                        <option label="25-02-01 (dd-mm-yy)" value="d-m-y">25-02-01 (dd-mm-yy)</option>
                                        <option label="25-02-2001 (dd-mm-yyyy)" value="d-m-Y">25-02-2001 (dd-mm-yyyy)
                                        </option>
                                        <option label="02/25/01 (mm/dd/yy)" value="m/d/y">02/25/01 (mm/dd/yy)</option>
                                        <option label="02/25/2001 (mm/dd/yyyy)" value="m/d/Y">02/25/2001 (mm/dd/yyyy)
                                        </option>
                                        <option label="02-25-01 (mm-dd-yy)" value="m-d-y">02-25-01 (mm-dd-yy)</option>
                                        <option label="02-25-2001 (mm-dd-yyyy)" value="m-d-Y">02-25-2001 (mm-dd-yyyy)
                                        </option>
                                        <option label="2001-02-25 (yyyy-mm-dd)" value="Y-m-d">2001-02-25 (yyyy-mm-dd)
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="time_format" class="form-label mb-1 col-md-3">Time Format</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="time_format">
                                        <option label="8:09 PM" value="g:i A">8:09 PM</option>
                                        <option label="8:09 pm" value="g:i a">8:09 pm</option>
                                        <option label="20:09" value="G:i" selected="selected">20:09</option>
                                        <option label="8:09 PM GMT" value="g:i A T">8:09 PM GMT</option>
                                        <option label="20:09 GMT" value="G:i T">20:09 GMT</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="time_unit_format" class="form-label mb-1 col-md-3">Time Units</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="time_unit_format">
                                        <option value="hh:mm" selected="selected">hh:mm (2:15)</option>
                                        <option value="hh:mm:ss">hh:mm:ss (2:15:59)</option>
                                        <option value="Hours">Hours (2.25)</option>
                                        <option value="Hours.Decimal">Hours (2.141)</option>
                                        <option value="Minutes">Minutes (135)</option>
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
                                <label for="start_week_day" class="form-label mb-1 col-md-3">Start Weeks on</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="start_week_day">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="total_user_deduction" class="form-label mb-1 col-md-3">Rows per Page</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="percent_amount" value="25">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="user_preference_status" class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="user_preference_status" disabled>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div id="Options_section">
                                <u>
                                    <h5 class="bg-primary text-white">Options</h5>
                                </u>
                                <div class="row mb-3">
                                    <label for="enable_email_notification_exception"
                                        class="form-label mb-1 col-md-3">Exceptions</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input"
                                            id="enable_email_notification_exception">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="enable_email_notification_message"
                                        class="form-label mb-1 col-md-3">Messages</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input"
                                            id="enable_email_notification_message">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="enable_email_notification_home" class="form-label mb-1 col-md-3">Send
                                        Notifications to Home Email</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input"
                                            id="enable_email_notification_home">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <input type="hidden" id="user_preference_id" value="" />
                                <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(async function() {
            await getDropdownData();
            await getUpdateData();
        });

        async function getDropdownData() {
            try {

                let dropdownData = await commonFetchData('/employee/user_preference/dropdown')

                console.log(dropdownData);


                // Time Zone
                let timeZoneList = (dropdownData?.time_zone_list || [])
                    .map(time_zone =>
                        `<option value="${time_zone.value}">${time_zone.name}</option>`)
                    .join('');
                $('#time_zone').html(timeZoneList);

                // Populate start_week
                let startWeekList = (dropdownData?.start_week_on_day_list || [])
                    .map(start_week => `<option value="${start_week.id}">${start_week.name}</option>`)
                    .join('');
                $('#start_week_day').html(startWeekList);

                // Populate Language
                let languageList = (dropdownData?.language_list || [])
                    .map(language => `<option value="${language.value}">${language.name}</option>`)
                    .join('');
                $('#language').html(languageList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }


        $(document).on('click', '#form_submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let enable_email_notification_exception = $('#enable_email_notification_exception').is(':checked') ? 1 : 0;
            let enable_email_notification_message = $('#enable_email_notification_message').is(':checked') ? 1 : 0;
            let enable_email_notification_home = $('#enable_email_notification_home').is(':checked') ? 1 : 0;

            let user_preference_id = $('#user_preference_id').val();

            formData.append('user_id', $('#user_id').val());
            formData.append('date_format', $('#date_format').val());
            formData.append('time_format', $('#time_format').val());
            formData.append('time_unit_format', $('#time_unit_format').val());
            formData.append('time_zone', $('#time_zone').val());
            // formData.append('items_per_page', $('#items_per_page').val());
            // formData.append('timesheet_view', $('#timesheet_view').val());

            formData.append('start_week_day', $('#start_week_day').val());
            formData.append('language', $('#language').val());
            formData.append('enable_email_notification_exception', enable_email_notification_exception);
            formData.append('enable_email_notification_message', enable_email_notification_home);
            formData.append('enable_email_notification_home', enable_email_notification_home);
            formData.append('user_preference_status', $('#user_preference_status').val());

            let createUrl = `/employee/user_preference/create`;
            let updateUrl = `/employee/user_preference/update/${user_preference_id}`;

            const isUpdating = Boolean(user_preference_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', user_preference_id);
            }

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'employee/user_preference';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('employee.user_preference') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        async function getUpdateData() {
            try {
                let response = await commonFetchData(`/employee/user_preference_by_id`);
                let data = response?.[0]; // Assuming the API returns an array with one item

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                console.log('Fetched policy  group data:', data);

                // Populate form fields
                $('#user_preference_id').val(data.id || '');
                $('#user_id').val(data.name_with_initials || '');
                $('#date_format').val(data.date_format || '');
                $('#time_format').val(data.time_format || '');
                $('#time_unit_format').val(data.time_unit_format || '');
                $('#time_zone').val(data.time_zone || '');
                // $('#items_per_page').val(data.items_per_page || '');
                // $('#timesheet_view').val(data.timesheet_view || '');
                $('#start_week_day').val(data.start_week_day || '');
                $('#language').val(data.language || '');
                $('#enable_email_notification_exception').prop('checked', data.enable_email_notification_exception === 1);
                $('#enable_email_notification_message').prop('checked',data.enable_email_notification_message === 1);
                $('#enable_email_notification_home').prop('checked',data.enable_email_notification_home === 1);

                $('#user_preference_status').val(data.status);

            } catch (error) {
                console.error('Error fetching User Preference data:', error);
            }
        }


        function resetForm() {
            $('#user_preference_id').val('');
            $('#user_id').val('');
            $('#date_format').val('');
            $('#time_format').val('');
            $('#time_unit_format').val('');
            $('#time_zone').val('');
            // $('#items_per_page').val('');
            // $('#timesheet_view').val('');
            $('#start_week_day').val('');
            $('#language').val('');
            $('#enable_email_notification_exception').val('');
            $('#enable_email_notification_message').val('');
            $('#enable_email_notification_home').val('');
            $('#user_preference_status').val('active');
        }
    </script>

</x-app-layout>
