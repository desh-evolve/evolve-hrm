<!-- pawanee(2024-12-17) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h3 class="mb-sm-0 text-uppercase fw-bold">{{ __('Employee Dashboard') }}</h3>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>


    <style>
     .header{
            position:sticky;
            top: 0 ;
        }
    </style>


    <div class="row">

        {{-- <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissible fade show material-shadow" role="alert">
                <strong>Locations</strong> list is empty. Click here to add new locations.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div> --}}

        <!-- Left Side -->
        <div class="col-lg-9">
            <div class="row">
                {{-- pie chart --}}

                <div class="col-xl-4">
                    <div class="card" style="height: 420px">
                        <div class="p-3 bg-primary align-items-center d-flex">
                            <h4 class="card-title mb-2 mt-1 text-uppercase fw-semibold flex-grow-1 text-white">Attendance Rate (monthly)</h4>
                        </div>
                        <div class="card-body">

                            <div id="store-visits-source" class="apex-charts" dir="ltr"></div>

                            <div class="table-responsive mt-4">
                                <table class="table table-borderless table-sm table-centered align-middle table-nowrap mb-0">
                                    <tbody class="border-0">
                                        <tr>
                                            <td>
                                                <h4 class="text-truncate fs-14 fs-medium mb-0">
                                                    <i class="ri-stop-fill align-middle fs-18 text-success me-2"></i>Attendance
                                                </h4>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-0">
                                                    <i data-feather="users" class="me-2 icon-sm"></i>
                                                    <span class="employee-count-pie">0</span>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h4 class="text-truncate fs-14 fs-medium mb-0">
                                                    <i class="ri-stop-fill align-middle fs-18 text-danger me-2"></i>Approved Leaves
                                                </h4>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-0">
                                                    <i data-feather="external-link" class="me-2 icon-sm"></i>
                                                    <span class="leaves-count-pie">0</span>
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>



                <div class="col-xl-8">

                    <!-- New Messages -->
                    <div class="card" style="height: 420px">
                        <div class="card-header align-items-center d-flex ">
                            <h4 class="card-title mb-1 flex-grow-1">New Messages</h4>
                        </div>
                        <div>
                            <div data-simplebar style="height: 310px;" class="pt-3">
                                <div class="table-card ps-3">
                                    <table class="table table-borderless table-centered align-middle table-nowrap mb-5">
                                        <thead class="text-muted table-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th class="header" scope="col">#</th>
                                                <th class="header" scope="col">From</th>
                                                <th class="header" scope="col">Type</th>
                                                <th class="header" scope="col">Subject</th>
                                                <th class="header" scope="col">Date</th>
                                            </tr>
                                        </thead>

                                        <tbody id="newMessage_table">
                                            <tr>
                                                <td colspan="5" class="text-center">Loading...</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="mt-2 mb-3 text-center">
                                <a href="/employee/messages" class="text-info text-decoration-underline fs-6">View More</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>


        <!-- Right Side -->
        <div class="col-lg-3 d-flex flex-column">
            <div class="card rounded-0 h-100">
                <div class="card-body p-0">
                    <div class="p-3 bg-primary">
                        <h6 class="text-white mb-2 mt-1 text-uppercase fw-semibold fs-5">Recent Activity</h6>
                    </div>
                    <div data-simplebar style="height: 350px;" class="p-3 pt-0">

                        <!-- Activity Timeline -->
                        <div class="acitivity-timeline acitivity-main mt-4">


                        </div>
                    </div>
                </div>
            </div>
        </div>


            <!-- First Table -->
            <div class="col-lg-6">
                <div class="card" style="height: 355px">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-1 pt-2 flex-grow-1">New Request</h4>
                    </div>
                    <div>
                        <div data-simplebar style="height: 240px;" class="pt-3">
                            <div class="table-card ps-3">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-5">
                                    <thead class="text-muted table-light" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Date</th>
                                        </tr>
                                    </thead>

                                    <tbody id="request_table_body">

                                        <tr>
                                            <td colspan="4" class="text-center">Loading...</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- view button --}}
                        <div class="mt-2 mb-3 text-center">
                            <a href="/employee/messages" class="text-info text-decoration-underline fs-6">View More</a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Leave Request Table -->
            <div class="col-lg-6">
                <div class="card" style="height: 355px">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-1 pt-2 flex-grow-1">Leave Requests</h4>
                    </div>
                    <div>
                        <div data-simplebar style="height: 240px;" class="pt-3">
                            <div class="table-card ps-3">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-5">
                                    <thead class="text-muted table-light" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Leave Type</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">End Date</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>

                                    <tbody id="leave_request_table_body">
                                        <tr>
                                            <td colspan="7" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                         {{-- view button --}}
                         <div class="mt-2 mb-3 text-center">
                            <a href="/employee/apply_leaves" class="text-info text-decoration-underline fs-6">View More</a>
                        </div>
                    </div>

                </div>

            </div>

            {{-- 6 month attendence chart --}}
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="card-title mb-0 text-white">Overall Attendance</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="line_chart_basic" data-colors='["--vz-primary"]' class="apex-charts" dir="ltr"></div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>

        </div>

    </div>


<script>


    $(document).ready(async function () {
        await updateEmployeeCount();
        await updateApprovedLeaveCount();
        await renderNewMessages();
        await renderRequest();
        await renderPieChart();
        await renderLeaveRequest();

        // Update employee count every 30 seconds
        // setInterval(updateEmployeeCount, 30000);

        // Utility Function: Format Date
        function formatDate1(datetime) {
            return dayjs(datetime).format('MMM DD, YYYY');
        }

//======================================================================================================
//get count employees and approved leaves
//======================================================================================================

        async function updateEmployeeCount() {
            try {
                let empCount = await commonFetchData('/dashboard/count/employee');
                let employeeCount = empCount || 0;
                console.log('Employee Count:', employeeCount);

                $('.employee-count').text(employeeCount).trigger('change');
            } catch (error) {
                console.error('Error updating employee count:', error);
            }
        }


        async function updateApprovedLeaveCount() {
            try {
                let approvedLeave = await commonFetchData('/dashboard/count/leave');

                let Count = approvedLeave || 0;
                console.log('Approved Leave Count:', Count);

                $('.leaves-count').text(Count).trigger('change');
            } catch (error) {
                console.error('Error updating approved leave count:', error);
            }
        }

//======================================================================================================
//render Pie Chart
//======================================================================================================

        async function renderPieChart() {
            try {
                let empCount = await commonFetchData(`/dashboard/count/employee`);
                let empLeaveCount = await commonFetchData(`/employee/dashboard/count/leave`);

                let employeeCount = empCount || 0;
                let leaveCount = empLeaveCount || 0;

                console.log('Rendering Pie Chart - Employee:', employeeCount, 'Leaves:', leaveCount);

                // Update the HTML elements with counts
                $('.employee-count-pie').text(employeeCount);
                $('.leaves-count-pie').text(leaveCount);

            var options = {
                series: [employeeCount, leaveCount],
                labels: ["Attendance", "Approved Leaves"],
                chart: {
                    type: 'pie',
                    height: 250
                },
                colors: ['#00AE98', '#e15d44'],
                legend: {
                    show: false
                }
            };

            // Destroy the previous chart instance if it exists
            if (window.attendanceChart) {
                window.attendanceChart.destroy();
            }

            window.attendanceChart = new ApexCharts(document.querySelector("#store-visits-source"), options);
            window.attendanceChart.render();

            } catch (error) {
                console.error('Error rendering pie chart:', error);
            }
        }


//======================================================================================================
//render new messages
//======================================================================================================

        async function renderNewMessages() {
            try {
                const messages = await commonFetchData('/employee/dashboard/messages');
                console.log('messages data', messages);

                let list = '';

                if (messages.length === 0) {
                    $('#newMessage_table').html('<tr><td colspan="7" class="text-center">No unread messages available</td></tr>');
                    return;
                }

                const unreadMessages = messages.filter(message => {
                    return Array.isArray(message.message_details) &&
                    message.message_details.some(detail => detail.read_status === 0);
                });

                if (unreadMessages.length === 0) {
                    $('#newMessage_table').html('<tr><td colspan="7" class="text-center">No unread messages available</td></tr>');
                    return;
                }

                const sortedMessages = unreadMessages.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                // display only the first 4 messages
                const latestMessages = sortedMessages.slice(0, 4);

                list = latestMessages.map((message,i) => {

                    // Extract sender_email from the first detail in message_details
                    const senderEmail = Array.isArray(message.message_details) && message.message_details.length > 0
                        ? message.message_details[0].sender_email
                        : 'Unknown';

                    return `
                        <tr id="${message.id}">
                            <th scope="row">${i + 1}</th>
                            <td>${senderEmail || 'N/A'}</td>
                            <td>${message.type_name || 'N/A'}</td>
                            <td>${message.subject || 'N/A'}</td>
                            <td>${formatDate1(message.created_at) || 'N/A'}</td>
                        </tr>
                    `;
                }).join('');

                $('#newMessage_table').html(list);

            } catch (error) {
                $('#newMessage_table').html('<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>');
                console.error('Error fetching messages:', error);
            }
        }

//======================================================================================================
//render Requests
//======================================================================================================

        async function renderRequest() {
            try {
                const requests = await commonFetchData('/employee/dashboard/requests');

                let list = '';
                const pendingRequests = requests.filter(req => req.status === 'pending');

                if (pendingRequests.length === 0) {
                    $('#request_table_body').html('<tr><td colspan="4" class="text-center">No request available</td></tr>');
                    return;
                } else {

                    const sortedRequest = pendingRequests.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                    // display only the first 4 messages
                    const latestRequest = sortedRequest.slice(0, 4);


                    list = latestRequest.map((req,i) => {
                        return`
                            <tr request_id="${req.id}">
                                <th scope="row">${i + 1}</th>
                                <td>${req.employee_name	}</td>
                                <td>${req.type_name}</td>
                                <td>${formatDate1(req.created_at)}</td>
                            </tr>
                        `;
                    }).join('');

                }

                $('#request_table_body').html(list);

            } catch (error) {
                $('#request_table_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
                console.error('Error fetching messages:', error);
            }
        }

//======================================================================================================
//render leave request
//======================================================================================================

        async function renderLeaveRequest() {
            try {
                const leaveRequset = await commonFetchData('/employee/dashboard/leave_request');
                console.log('leave requests', leaveRequset);

                let list = '';

                if (leaveRequset.length === 0) {
                    $('#leave_request_table_body').html('<tr><td colspan="6" class="text-center">No Leave Request Data</td></tr>');
                    return;
                }

                const currentDate = new Date();
                const currentYear = currentDate.getFullYear();
                const currentMonth = currentDate.getMonth();

                const monthLeaves = leaveRequset.filter(leave => {
                    const createLeaveDate = new Date(leave.created_at);
                    return (
                        (createLeaveDate.getFullYear() === currentYear && createLeaveDate.getMonth() === currentMonth)
                    );
                });

                if (monthLeaves.length === 0) {
                    $('#leave_request_table_body').html('<tr><td colspan="6" class="text-center">No Leave Request this Month</td></tr>');
                    return;
                }

                list = monthLeaves.map((leave,i) => {
                    return`
                        <tr leave_request_id ="${leave.id}">
                            <td>${i + 1}</td>
                            <td>${leave.type_name}</td>
                            <td>${leave.amount}</td>
                            <td>${leave.leave_from}</td>
                            <td>${leave.leave_to}</td>
                            <td class="text-capitalize">${leave.status === 'pending'
                                ? `<span class="badge border border-warning text-warning">${leave.status}</span>`
                                : `<span class="badge border border-success text-success">${leave.status}</span>`}</td>
                            <td>
                        </tr>
                    `;
                }).join('');

                $('#leave_request_table_body').html(list);

            } catch (error) {
                $('#leave_request_table_body').html('<tr><td colspan="6" class="text-center text-danger">Error Loading Data</td></tr>');
                console.error('Error fetching message: ',error);
            }
        }

//================================================================================================================
// Recent Activity
//================================================================================================================

        function logActivity(action, description) {
            let activities = JSON.parse(localStorage.getItem('recentActivities')) || [];

            // Add new activity
            activities.unshift({
                action: action,
                description: description,
                timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + " Today"
            });

            // Limit to the last 10 activities
            activities = activities.slice(0, 10);

            // Save back to localStorage
            localStorage.setItem('recentActivities', JSON.stringify(activities));

            renderRecentActivity();
        }

        function renderRecentActivity() {
            let activities = JSON.parse(localStorage.getItem('recentActivities')) || [];
            const activityContainer = document.querySelector('.acitivity-main');

            if (activities.length > 0) {
                // Clear existing items
                activityContainer.innerHTML = '';

                activities.forEach(activity => {
                    activityContainer.innerHTML += `
                        <div class="acitivity-item d-flex mb-4">
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 lh-base">${activity.action}</h6>
                                <p class="text-muted mb-1">${activity.description}</p>
                                <small class="mb-0 text-muted">${activity.timestamp}</small>
                            </div>
                        </div>
                        <hr>
                    `;
                });
            } else {
                activityContainer.innerHTML = '<p class="text-muted">No recent activity.</p>';
            }
        }

        // Example usage
        logActivity("Login", "User logged into the dashboard");
        logActivity("Viewed Attendance", "Checked attendance chart for the month");
        logActivity("Updated Profile", "Changed profile picture");

        // Render on page load
        document.addEventListener('DOMContentLoaded', renderRecentActivity);


    });



</script>

</x-app-layout>
