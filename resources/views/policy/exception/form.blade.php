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
                            <thead>
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
                            <button type="button" class="btn btn-primary" id="submitForm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ex_pol = [
            { active: 1,code: 'S1', name: 'Unscheduled Absence', grace: '', severity: 'low', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'S2', name: 'Not Scheduled', grace: '', severity: 'low', watch_window: '', email_notification: 'none' },
            { active: 1,code: 'S3', name: 'In Early', grace: '00:15', severity: 'low', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 1,code: 'S4', name: 'In Late', grace: '00:15', severity: 'high', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 1,code: 'S5', name: 'Out Early', grace: '00:15', severity: 'medium', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 1,code: 'S6', name: 'Out Late', grace: '00:15', severity: 'low', watch_window: '02:00', email_notification: 'supervisor' },
            { active: 0,code: 'S7', name: 'Over Daily Scheduled Time', grace: '00:15', severity: 'low', watch_window: '', email_notification: 'none' },
            { active: 0,code: 'S8', name: 'Under Daily Scheduled Time', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0,code: 'S9', name: 'Over Weekly Scheduled Time', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'O1', name: 'Over Daily Time', grace: '', severity: 'medium', watch_window: '08:00', email_notification: 'both' },
            { active: 0,code: 'O2', name: 'Over Weekly Time', grace: '', severity: 'medium', watch_window: '40:00', email_notification: 'both' },
            { active: 1,code: 'M1', name: 'Missing In Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 1,code: 'M2', name: 'Missing Out Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 1,code: 'M3', name: 'Missing Lunch In/Out Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 1,code: 'M4', name: 'Missing Break In/Out Punch', grace: '', severity: 'critical', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'L1', name: 'Long Lunch', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0,code: 'L2', name: 'Short Lunch', grace: '00:15', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0,code: 'L3', name: 'No Lunch', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'B1', name: 'Long Break', grace: '00:05', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0,code: 'B2', name: 'Short Break', grace: '00:05', severity: 'medium', watch_window: '', email_notification: 'none' },
            { active: 0,code: 'B3', name: 'Too Many Breaks', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'B4', name: 'Too Few Breaks', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'B5', name: 'No Break', grace: '', severity: 'medium', watch_window: '', email_notification: 'both' },
            { active: 0,code: 'V1', name: 'TimeSheet Not Verified', grace: '48:00', severity: 'high', watch_window: '', email_notification: 'both' },
        ];


        $(document).ready(function() {
            const exceptionPolicyForm = new ExceptionPolicyForm(ex_pol);
            exceptionPolicyForm.populateFormData();

            // Bind form submission or button click for sending data
            $('#submitForm').click(function() {
                exceptionPolicyForm.sendDataToBackend();
            });
        });

        class ExceptionPolicyForm {
            constructor(data) {
                this.data = data;
            }

            // Method to populate table with data
            populateFormData() {
                let list = '';

                this.data.map(ex => {
                    list += `
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
                            <td>
                                ${ex.grace ? `<input type="text" class="grace-input" value="${ex.grace}" />` : ''}
                            </td>    
                            <td>
                                ${ex.watch_window ? `<input type="text" class="watch-window-input" value="${ex.watch_window}" />` : ''}
                            </td>    
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
                });

                $('#ex_pol_form_table_body').html(list);
            }

            // Method to collect data from the table
            collectFormData() {
                const formData = [];

                $('#ex_pol_form_table_body tr').each(function() {
                    const row = $(this);
                    const isActive = row.find('.active-checkbox').prop('checked') ? 1 : 0;
                    const code = row.find('td:nth-child(2)').text();
                    const name = row.find('td:nth-child(3)').text();
                    const severity = row.find('.severity-dropdown').val();
                    const grace = row.find('.grace-input').val();
                    const watchWindow = row.find('.watch-window-input').val();
                    const emailNotification = row.find('.email-notification-dropdown').val();

                    formData.push({
                        active: isActive,
                        code: code,
                        name: name,
                        severity: severity,
                        grace: grace,
                        watch_window: watchWindow,
                        email_notification: emailNotification
                    });
                });

                //check here
                formData.push('ex_pol_name', $('#ex_pol_name').val());

                return formData;
            }

            // Method to send collected data to the backend
            sendDataToBackend() {
                const formData = this.collectFormData();

                fetch('/your-backend-endpoint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ data: formData })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // Handle success (e.g., show a message, reset form, etc.)
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Handle error (e.g., show error message)
                });
            }
        }


    </script>

</x-app-layout>