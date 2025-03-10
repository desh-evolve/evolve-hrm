<!-- pawanee(2024-12-17) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h3 class="mb-sm-0 text-uppercase fw-bold">{{ __('Dashboard') }}</h3>

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
                    <div class="card" style="height: 510px">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Employee Attendance</h4>
                        </div>
                        <div class="card-body">

                            <div id="store-visits-source" class="apex-charts" dir="ltr"></div>

                            <div class="table-responsive mt-5">
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


                {{-- == --}}
                <div class="col-xl-8">
                    <div class="row">
                        <!-- Employee Count -->
                        <div class="col-md-6">
                            <div class="card card-animate" style="height: 125px;">
                                <div class="card-body bg-success">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-semibold text-white mb-0 fs-5">Employees</p>
                                            <h2 class="mt-4 text-white fs-1 fw-semibold"><span class="employee-count"></span></h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white rounded-circle fs-2">
                                                    <i data-feather="users" class="text-info"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approved Leave Count -->
                        <div class="col-md-6">
                            <div class="card card-animate" style="height: 125px;">
                                <div class="card-body bg-danger">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-semibold text-white mb-0 fs-5">Approved Leaves</p>
                                            <h2 class="mt-4 text-white fs-1 fw-semibold"><span class="leaves-count">0</span></h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-white rounded-circle fs-2">
                                                    <i data-feather="external-link" class="text-info"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New Messages -->
                    <div class="card" style="height: 360px">
                        <div class="card-header align-items-center d-flex ">
                            <h4 class="card-title mb-1 flex-grow-1">New Messages</h4>
                        </div>
                        <div>
                            <div data-simplebar style="height: 250px;" class="pt-3">
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

            <div class="row">

                <!-- First Table -->
                <div class="col-xl-6">
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
                                <a href="/attendance/request/index" class="text-info text-decoration-underline fs-6">View More</a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Second Table -->
                <div class="col-xl-6">
                    <div class="card" style="height: 355px">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-1 pt-2 flex-grow-1">New Leave Request</h4>
                        </div>
                        <div>
                            <div data-simplebar style="height: 240px;" class="pt-3">
                                <div class="table-card ps-3">
                                    <table class="table table-borderless table-centered align-middle table-nowrap mb-5">
                                        <thead class="text-muted table-light" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
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
                                <a href="#" class="text-info text-decoration-underline fs-6">View More</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- Right Side -->
        <div class="col-lg-3 d-flex flex-column">
            <div>
                <div class="card rounded-0 h-100">
                    <div class="card-body p-0">
                        <div class="p-3 bg-primary">
                            <h6 class="text-white mb-2 mt-1 text-uppercase fw-semibold fs-5">Recent Activity</h6>
                        </div>
                        <div data-simplebar style="height: 414px;" class="p-3 pt-0">

                            <!-- Activity Timeline -->
                            <div class="acitivity-timeline acitivity-main mt-4">
                                <div class="acitivity-item d-flex mb-4">
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Purchase by James Price</h6>
                                        <p class="text-muted mb-1">Product noise evolve smartwatch</p>
                                        <small class="mb-0 text-muted">02:14 PM Today</small>
                                    </div>
                                </div>

                                <hr>

                                <div class="acitivity-item d-flex mb-4">
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Purchase by James Price</h6>
                                        <p class="text-muted mb-1">Product noise evolve smartwatch</p>
                                        <small class="mb-0 text-muted">02:14 PM Today</small>
                                    </div>
                                </div>

                                <hr>

                                <div class="acitivity-item d-flex mb-4">
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Purchase by James Price</h6>
                                        <p class="text-muted mb-1">Product noise evolve smartwatch</p>
                                        <small class="mb-0 text-muted">02:14 PM Today</small>
                                    </div>
                                </div>

                                <hr>

                                <div class="acitivity-item d-flex mb-4">
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Purchase by James Price</h6>
                                        <p class="text-muted mb-1">Product noise evolve smartwatch</p>
                                        <small class="mb-0 text-muted">02:14 PM Today</small>
                                    </div>
                                </div>

                                <hr>
                            </div>
                        </div>


                        <div class="p-0 mt-2">
                            <div class="p-3 bg-primary">
                                <h6 class="text-white mb-1 mt-1 text-uppercase fw-semibold fs-5">Exceptions</h6>
                            </div>

                            <div class="p-3">
                                <ol class="ps-3 text-muted">
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Mobile & Accessories <span class="float-end">(10,294)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Desktop <span class="float-end">(6,256)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Electronics <span class="float-end">(3,479)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Home & Furniture <span class="float-end">(2,275)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Grocery <span class="float-end">(1,950)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Fashion <span class="float-end">(1,582)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Appliances <span class="float-end">(1,037)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Beauty, Toys & More <span class="float-end">(924)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Food & Drinks <span class="float-end">(701)</span></a>
                                    </li>
                                    <li class="py-1">
                                        <a href="#" class="text-muted">Toys & Games <span class="float-end">(239)</span></a>
                                    </li>
                                </ol>
                            </div>

                        </div>


                    </div>
                </div>
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
                let empLeaveCount = await commonFetchData(`/dashboard/count/leave`);

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
                    height: 350
                },
                colors: ['#00AE98', '#e15d44'],
                legend: {
                    position: 'bottom'
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
                const messages = await commonFetchData('/dashboard/messages');

                let list = '';

                if (messages.length === 0) {
                    $('#newMessage_table').html('<tr><td colspan="7" class="text-center">No messages available</td></tr>');
                    return;
                } else {

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

                        return`
                            <tr id="${message.id}">
                                <td scope="row">${i + 1}</td>
                                <td>${senderEmail}</td>
                                <td>${message.type_name}</td>
                                <td>${message.subject}</td>
                                <td>${formatDate1(message.created_at)}</td>
                            </tr>
                        `;
                    }).join('');

                }

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
                const requests = await commonFetchData('/dashboard/requests');

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
                const leaveRequset = await commonFetchData('/dashboard/leave_request');
                console.log('all leave-requests', leaveRequset);

                let list = '';

                if (leaveRequset.length === 0) {
                    $('#leave_request_table_body').html('<tr><td colspan="6" class="text-center">No Leave Request Data</td></tr>');
                    return;
                }


                list = leaveRequset.map((leave,i) => {
                    return`
                        <tr leave_request_id ="${leave.id}">
                            <td>${i + 1}</td>
                            <td>${leave.first_name} ${leave.last_name}</td>
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


    });



</script>

</x-app-layout>
