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
                        <h5 class="mb-0">Add Exception Policy</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="col-md-2">Exception Policy Name</label>
                            <input type="text" class="form-control" id="ex_pol_name" placeholder="Enter Exception Policy Name Here" required />
                        </div>
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white"/>
                                <tr>
                                    <th class="col">Active</th>
                                    <th class="col">Code</th>
                                    <th class="col">Name</th>
                                    <th class="col">Severity</th>
                                    <th class="col">Grace</th>
                                    <th class="col">Watch Window</th>
                                    <th class="col">Email Notification</th>
                                </tr>
                            </thead>
                            <tbody id="ex_pol_form_table_body">
                                <tr><td colspan="7" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            <input type="hidden" id="exception_id" value="">
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ex_pol = [
            { active: 1, code: 'S1', name: 'Unscheduled Absence', grace: '', severity: 'low', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'S2', name: 'Not Scheduled', grace: '', severity: 'low', watch_window: '', email_notification: 'none' },
            { active: 1, code: 'S3', name: 'In Early', grace: '00:15', severity: 'low', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 1, code: 'S4', name: 'In Late', grace: '00:15', severity: 'high', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 1, code: 'S5', name: 'Out Early', grace: '00:15', severity: 'medium', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 1, code: 'S6', name: 'Out Late', grace: '00:15', severity: 'low', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 0, code: 'S7', name: 'Over Daily Scheduled Time', grace: '00:15', severity: 'low', watch_window: '', email_notification: 'none' },
            { active: 0, code: 'S8', name: 'Under Daily Scheduled Time', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0, code: 'S9', name: 'Over Weekly Scheduled Time', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'O1', name: 'Over Daily Time', grace: '', severity: 'medium', watch_window: '08:00', email_notification: 'both' },
            { active: 0, code: 'O2', name: 'Over Weekly Time', grace: '', severity: 'medium', watch_window: '40:00', email_notification: 'both' },
            { active: 1, code: 'M1', name: 'Missing In Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 1, code: 'M2', name: 'Missing Out Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 1, code: 'M3', name: 'Missing Lunch In/Out Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 1, code: 'M4', name: 'Missing Break In/Out Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'L1', name: 'Long Lunch', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0, code: 'L2', name: 'Short Lunch', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0, code: 'L3', name: 'No Lunch', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'B1', name: 'Long Break', grace: '00:05', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0, code: 'B2', name: 'Short Break', grace: '00:05', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0, code: 'B3', name: 'Too Many Breaks', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'B4', name: 'Too Few Breaks', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'B5', name: 'No Break', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0, code: 'V1', name: 'TimeSheet Not Verified', grace: '48:00', severity: 'high', watch_window: '', email_notification: 'both' },
        ];


        $(document).ready(function() {

            <?php if (isset($_GET['id'])): ?>
                let exception_policy_id = <?= json_encode($_GET['id']); ?>;
                getUpdateData(exception_policy_id);
            <?php else: ?>
                populateFormData(ex_pol);
            <?php endif; ?>

            // Bind form submission or button click for sending data
            $('#form_submit').click(function() {
                sendDataToBackend();
            });
        });

        // Function to generate row HTML for the form data
        function createRowHTML(ex) {
            return `
                <tr class="exception-policy-row">
                    <td><input type="checkbox" class="active-checkbox" ${ex.active ? 'checked' : ''} /></td>    
                    <td>${ex.code}</td>    
                    <td>${ex.name}</td>    
                    <td>
                        <select class="severity-dropdown">
                            <option value="low" ${ex.severity === 'low' ? 'selected' : ''}>Low</option>
                            <option value="medium" ${ex.severity === 'medium' ? 'selected' : ''}>Medium</option>
                            <option value="high" ${ex.severity === 'high' ? 'selected' : ''}>High</option>
                            <option value="critical" ${ex.severity === 'critical' ? 'selected' : ''}>Critical</option>
                        </select>
                    </td>    
                    <td>${ex.grace ? `<input type="text" class="grace-input" value="${ex.grace}" />` : ''}</td>    
                    <td>${ex.watch_window ? `<input type="text" class="watch-window-input" value="${ex.watch_window}" />` : ''}</td>    
                    <td>
                        <select class="email-notification-dropdown">
                            <option value="none" ${ex.email_notification === 'none' ? 'selected' : ''}>None</option>
                            <option value="employee" ${ex.email_notification === 'employee' ? 'selected' : ''}>Employee</option>
                            <option value="supervisor" ${ex.email_notification === 'supervisor' ? 'selected' : ''}>Supervisor</option>
                            <option value="both" ${ex.email_notification === 'both' ? 'selected' : ''}>Both</option>
                        </select>
                    </td>    
                </tr>
            `;
        }

        // Function to populate the form with data
        function populateFormData(data) {
            let list = '';

            data.forEach(ex => {
                list += createRowHTML(ex);
            });

            $('#ex_pol_form_table_body').html(list);
        }

        async function getUpdateData(exception_policy_id){

            $('#exception_id').val(exception_policy_id); // Set the ID in the hidden field

            try {
                // Fetch the exception policy data
                let response = await commonFetchData(`/policy/exception/${exception_policy_id}`);
                let data = response[0]; // Extract the first object

                //console.log('data', data)
                let x = ex_pol;
                if (data) {
                    $('#ex_pol_name').val(data.name);
                    
                    x = ex_pol.map((val, i) => {
                        // Filter matching exceptions
                        let exception = data.exceptions.find((e) => e.type_id === val.code); 
                        
                        let res = val;
                        if (exception) {
                            res = {
                                active: exception.active,
                                code: exception.type_id,
                                name: val.name,
                                grace: val.grace !== '' ? convertSecondsToHoursAndMinutes(exception.grace) : '',
                                severity: exception.severity,
                                watch_window: val.watch_window !== '' ? convertSecondsToHoursAndMinutes(exception.watch_window) : '',
                                email_notification: exception.email_notification
                            };
                        }

                        return res; 
                    }).filter(Boolean); // Remove null entries
                }

                //load update data
                populateFormData(x);
                
            } catch (error) {
                console.error('Error while fetching holiday policy data:', error);
                $('#error-msg').html('<p class="text-danger">Failed to load data. Please try again.</p>');
            }
        }


        // Function to collect data from the form
        function collectFormData() {
            // Return the collected data
            return {
                policy_data: formDataArr,
                ex_pol_name: ex_pol_name
            };
        }

        // Function to send the collected data to the backend
        async function sendDataToBackend() {
            const ex_pol_name = $('#ex_pol_name').val();

            if (ex_pol_name === '') {
                alert('Exception policy name is required!');
                return false;
            }

            let formDataArr = [];

            $('#ex_pol_form_table_body tr').each(function () {
                const row = $(this);
                const isActive = row.find('.active-checkbox').prop('checked') ? 1 : 0;
                const code = row.find('td:nth-child(2)').text();
                const name = row.find('td:nth-child(3)').text();
                const severity = row.find('.severity-dropdown').val();
                const grace = convertHoursAndMinutesToSeconds(row.find('.grace-input').val() || '0:00');
                const watchWindow = convertHoursAndMinutesToSeconds(row.find('.watch-window-input').val() || '0:00');
                const emailNotification = row.find('.email-notification-dropdown').val();

                formDataArr.push({
                    active: isActive,
                    code: code,
                    name: name,
                    severity: severity,
                    grace: grace,
                    watch_window: watchWindow,
                    email_notification: emailNotification
                });
            });


            const formData = new FormData();

            formData.append('name', ex_pol_name);
            formData.append('policy_data', JSON.stringify(formDataArr));

            const exception_id = $('#exception_id').val();
            
            let createUrl = '/policy/exception/create';
            let updateUrl = `/policy/exception/update/${exception_id}`;
            
            const isUpdating = Boolean(exception_id);
            const url = isUpdating ? updateUrl : createUrl;
            const method = isUpdating ? 'PUT' : 'POST';

            // Add exception_id if updating
            if (isUpdating) {
                formData.append('id', exception_id);
            }

            try {
                // Send data and handle the response
                const res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    window.location.href = '/policy/exception';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        }


    </script>

</x-app-layout>