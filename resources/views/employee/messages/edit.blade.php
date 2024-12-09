<!-- pawanee(2024-11-20) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Messages') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>



{{-- Generate Messages --}}



    <div class="col-lg-12">

    <!-- start email-menu-sidebar -->

        <div class="email-wrapper d-lg-flex gap-1 mx-n4 mt-n4 mb-n4 p-2">
            <div class="minimal-border w-25" style="background-color: white;">
                <div class="p-4 d-flex flex-column h-100">
                    <div class="pb-4 border-bottom border-bottom-solid pt-1">
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" id="compose-btn"><i data-feather="plus-circle" class="icon-xs me-1 icon-dual-light"></i> Compose</button>
                    </div>

                    <div class="mx-n4 px-4 email-menu-sidebar-scroll" data-simplebar>
                        <div class="mail-list mt-3">

                            <a href="#" class="active" id="all-message">
                                <i class="ri-mail-fill me-3 align-middle fw-medium"></i>
                                <span class="mail-list-link">All</span>
                                <span class="badge bg-success-subtle text-success ms-auto" id="msg-count"></span>
                            </a>

                            <a href="#" id="inbox-message">
                                <i class="ri-inbox-archive-fill me-3 align-middle fw-medium"></i>
                                <span class="mail-list-link">Inbox</span>
                                <span class="badge bg-success-subtle text-success ms-auto" id="msg-count"></span>
                            </a>

                            <a href="#" id="sent-message">
                                <i class="ri-send-plane-2-fill me-3 align-middle fw-medium"></i>
                                <span class="mail-list-link">Sent</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Massege minimal border --}}

            <div class="minimal-border w-100" style="background-color: white;">
                <div class="p-3 pb-0 h-100">
                    <div class="border-bottom border-bottom-solid">
                        <div class="row mt-n2 mb-4 mb-sm-0">

                            {{-- Message side bar menu button --}}
                            <div class="col col-sm-auto order-1 d-block d-lg-none">
                                <button type="button" class="btn btn-soft-success btn-icon btn-sm fs-16 email-menu-btn material-shadow-none">
                                    <i class="ri-menu-2-fill align-bottom"></i>
                                </button>
                            </div>

                            <div class="col-sm order-3 order-sm-2">
                                <div class="hstack gap-sm-1 align-items-center flex-wrap email-topbar-link">

                                    {{-- checkbox --}}
                                    <div class="form-check fs-14 m-0">
                                        <input class="form-check-input" type="checkbox" value="" id="checkall">
                                        <label class="form-check-label" for="checkall"></label>
                                    </div>

                                    {{-- delete icon --}}
                                    <div id="email-topbar-actions">
                                        <div class="hstack gap-sm-1 align-items-center flex-wrap">
                                            <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Trash">
                                                <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm fs-16 material-shadow-none" data-bs-toggle="modal" data-bs-target="#removeItemModal">
                                                    <i class="ri-delete-bin-5-fill align-bottom"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Refresh button --}}
                            <div class="col-auto order-2 order-sm-3">
                                <div class="d-flex gap-sm-1 email-topbar-link">
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm fs-16 material-shadow-none" id="refresh-btn">
                                        <i class="ri-refresh-line align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-end mt-3">
                            <div class="col">
                                <div id="mail-filter-navlist">
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success gap-1 text-center border-bottom-0" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link fw-semibold active" id="pills-primary-tab" data-bs-toggle="pill" data-bs-target="#pills-primary" type="button" role="tab" aria-controls="pills-primary" aria-selected="true">
                                                <i class="ri-inbox-fill align-bottom d-inline-block"></i>
                                                <span class="ms-1 d-none d-sm-inline-block">Primary</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>



                    {{-- Message content --}}

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-primary" role="tabpanel" aria-labelledby="pills-primary-tab">
                            <div class="message-list-content mx-n4 px-4 message-list-scroll">

                                <div id="elmLoader">
                                    <div class="spinner-border text-primary avatar-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <ul class="message-list pt-3" id="msg-list">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--message box-->

            <div class="pt-1 w-50" id="message_details_box" style="display: none; background-color: white;">
                <div class="p-4 d-flex flex-column h-75">
                    <!-- Top Actions Section -->
                    <div class="pb-4 border-bottom border-bottom-solid">
                        <div class="row">
                            <div class="col">
                                <div>
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm fs-16 close-btn-email material-shadow-none" id="close-btn">
                                        <i class="ri-close-fill align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="hstack gap-sm-1 align-items-center flex-wrap email-topbar-link">
                                    {{-- delete button --}}
                                    <button class="btn btn-ghost-secondary btn-icon btn-sm fs-16 material-shadow-none remove-mail" data-remove-id="" data-bs-toggle="modal">
                                        <i class="ri-delete-bin-5-fill align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Scroll Section -->
                    <div class="mx-n4 px-4 email-detail-content-scroll" data-simplebar>
                        <div class="message-item">
                             <!-- Subject -->
                            <div class="mt-4 mb-3">
                                <h4 class="fw-bold email-subject-title" id="message-box-subject"></h4>
                                <h6 class="text-info pb-2" id="message-box-type"></h6>
                            </div>

                            <!-- Email Details -->
                            <div id="message_content">

                            </div>
                        </div>
                    </div>

                    <div id="error-msg"></div>
                    <input type="hidden" id="msg_id" value="">

                    <!-- Reply Section -->
                    <div class="mt-auto">
                        <form class="mt-2">
                            <label for="replyTextarea" class="form-label">Reply</label>
                            <textarea class="form-control border-bottom-0 rounded-top rounded-0 border" id="replyTextarea" rows="5" placeholder="Enter message"></textarea>
                            <div class="bg-light px-2 py-1 rounded-bottom border">
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-sm btn-success">
                                            <i class="ri-send-plane-2-fill align-bottom"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- end email wrapper -->
    </div>
    <!-- end col -->



    <!-- Compose Modal -->
    <div class="modal fade zoomIn" id="compose-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3 bg-light">
                    <h5 class="modal-title">New Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="compose-body">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="employees">To</label>
                            <div style="flex: 1;">
                                <select class="select2-multiple form-control" id="employees" name="employees[]" multiple="multiple">

                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 mb-3">
                            <input type="text" class="form-control" id="subject" placeholder="Subject" value="">
                        </div>
                        <div class="col-xxl-12 col-md-12 mb-2">
                            <textarea class="form-control" id="description" rows="15"></textarea>
                        </div>
                    </div>
                    <div id="compose-error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="msg_id" value=""></button>
                        <button type="button" class="btn w-sm btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-success" id="message-send-confirm">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->


   

<script>

//===========================================================================================
// Render Messages
//===========================================================================================

let dropdownData = [];


$(document).ready(async function () {
    // Render Messages on Page Load
    await renderMessages();
    await getDropdownData();


    // All Messages
    $(document).on('click', '#all-message', async function () {
        await renderMessages();
    });

    // Refresh button
    $(document).on('click', '#refresh-btn', function () {
        location.reload();
    });


//===========================================================================================
// dropdown employee name
//===========================================================================================

    async function getDropdownData() {
        try {
            dropdownData = await commonFetchData('/employee/name/dropdown');
            // Populate employees dropdown
            let employeeList = (dropdownData?.employees || [])
                .map(employee => `<option value="${employee.id}">${employee.work_email}</option>`)
                .join('');
            $('#employees').html(employeeList);
            $(".select2-multiple").select2();
        } catch (error) {
            console.error('Error fetching dropdown data:', error);
        }
    }

//===========================================================================================
// Generate date & time format
//===========================================================================================

    // Utility Function: Format Date
    function formatDate1(datetime) {
        return dayjs(datetime).format('MMM DD');
    }


     // Utility Function: Format Date & Time
     function formatDate2(datetime) {
        return dayjs(datetime).format('YYYY MM DD hh.mm A');
    }

//===========================================================================================
// Render Messageses
//===========================================================================================

    // Render Email List
    async function renderMessages() {
        try {
            const messages = await commonFetchData('/employee/allmessages');

            if (messages.length === 0) {
                $('#msg-list').html('<li class="text-center">No messages available</li>');
                return;
            }

            const list = messages.map((message) => {
                return `
                    <li msg_id="${message.id}" class="email-item">
                        <div class="col-mail col-mail-1">
                            <div class="form-check checkbox-wrapper-mail fs-14">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="checkbox"
                                    value="${message.id}"
                                    onclick="event.stopPropagation()"
                                    >
                                <label class="form-check-label" for="checkbox-${message.id}"></label>
                            </div>
                            <div id="type-name" class="title cursor-pointer">
                                <span class="title-name">${message.type}</span>
                            </div>
                        </div>
                        <div class="col-mail col-mail-2 cursor-pointer">
                            <div id="type-sub">
                                <span class="subject-title">${message.subject}</span>
                            </div>
                            <div class="date" id="sent-date">${formatDate1(message.created_at)}</div>
                        </div>
                    </li>
                `;
            }).join('');

            $('#msg-list').html(list);
            $('#elmLoader').hide();

        } catch (error) {
            $('#msg-list').html('<li class="text-center text-danger">Error loading messages</li>');
            console.error('Error fetching messages:', error);
        }
    }


//===========================================================================================
// update message count
//===========================================================================================

      // Update Message Count
      async function updateMessageCount() {
        try {
            const messages = await commonFetchData('/employee/allmessages');
            const messageCount = messages.length;
            $('#msg-count').text(messageCount);
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }

    updateMessageCount();

//===========================================================================================
// Open chat Message box
//===========================================================================================

    // Open the Message Details Box
    $(document).on('click', '.email-item', async function () {
        resetForm();

        // Get the subject directly from the clicked row
        const $row = $(this);
        const subjectName = $row.find('.subject-title').text();
        const typeName = $row.find('.title-name').text();

        // Set the subject in the message details box
        $('#message-box-subject').text(subjectName || 'No Subject');
        $('#message-box-type').text(typeName || 'No message type');

        // Get the message ID from the row attribute
        let messageId = $row.attr('msg_id');

        if (!messageId) {
            console.error('No message ID found for the selected row.');
            $('#error-msg').html('<p class="text-danger">Unable to load message details. Please try again.</p>');
            return;
        }

        try {
            let messageData = await commonFetchData(`/employee/messages/${messageId}`);

            if (Array.isArray(messageData) && messageData.length > 0) {
                console.log('Fetched Message Data:', messageData);

                // Generate HTML for all messages
                const messagesHTML = messageData.map((message) => {
                    // Truncate receiver emails if more than 3
                    let displayedReceivers = message.receiver_emails || 'Receiver Unknown';
                    if (typeof displayedReceivers === 'string') {
                        displayedReceivers = displayedReceivers.split(','); // Ensure it's treated as an array
                    }

                    if (Array.isArray(displayedReceivers)) {
                        displayedReceivers =
                            displayedReceivers.length > 3
                                ? displayedReceivers.slice(0, 3).join(', ') + ', .....'
                                : displayedReceivers.join(', ');
                    }

                    return `
                        <div class="message-item-container">
                            <div class="message-item p-2 d-flex flex-column mb-1 border-bottom border-bottom-dashed rounded-lg cursor-pointer bg-primary-subtle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fs-14 mb-0 text-truncate" style="max-width: 150px;" title="${message?.sender_email || 'Sender Unknown'}">
                                        ${truncateText(message?.sender_email || 'Sender Unknown', 12)}
                                    </h6>
                                    <div class="text-muted fs-12 text-nowrap">${formatDate2(message?.sent_at || '')}</div>
                                </div>
                                <div class="mt-1">
                                    <p class="text-muted fs-12 mb-0">To: ${displayedReceivers}</p>
                                </div>
                            </div>
                            <div class="email-description p-1 pb-2 pt-2 mb-2">
                                <p class="fs-12 mb-0">From: ${message?.sender_email || 'Sender Unknown'}</p>
                                <p class="text-secondary-emphasis fs-12">To: ${message?.receiver_emails || 'Receiver Unknown'}</p>
                                <p>${message?.message_description || 'No description available.'}</p>
                            </div>
                        </div>
                    `;
                }).join('');

                // Render the generated HTML
                $('#message_content').html(messagesHTML);
                $('.email-description').hide(); // Hide descriptions initially
            } else {
                console.warn('No message details found for this message.');
                $('#error-msg').html('<p class="text-warning">No message details found for the selected item.</p>');
            }
        } catch (error) {
            console.error('Error fetching message details:', error);
            $('#error-msg').html('<p class="text-danger">Failed to load message details. Please try again.</p>');
        } finally {
            $('#message_details_box').show(); // Show the message details box
        }
    });


    // Show sender message email EX:sanduni@evolve...
    function truncateText(text, length) {
        return text.length > length ? text.substring(0, length) + '...' : text;
    }

    // Show Description when Clicking Message Content
    $(document).on('click', '.message-item', function () {
        let $descElement = $(this).next('.email-description');

        if ($descElement.length === 0) {
            console.error("Description element not found.");
            return;
        }
        $descElement.toggle();
    });


    // Close Button Handler
    $(document).on('click', '#close-btn', function () {
        $('#message_details_box').hide();
    });

//===========================================================================================
// compose message (Send New Messages)
//===========================================================================================

    $(document).on('click', '#compose-btn', async function(){
        //reset
        $('#subject').val('');
        $('#description').val('');
        $('#employees').val([]).trigger('change');

        //show modal
        $('#compose-modal').modal('show');
    })


    $(document).on('click', '#message-send-confirm', async function() {
        let createUrl = `/employee/messages/create`;

        let subject = $('#subject').val();
        let description = $('#description').val();
        let employees = $('#employees').val();

        let formData = new FormData();
        let missingFields = [];

        // Check for missing fields and add them to the array
        if (!subject) missingFields.push('subject');
        if (!description) missingFields.push('description');
        if (!employees || employees.length == 0) missingFields.push('employees');

        // If there are any missing fields, display the error message and stop execution
        if (missingFields.length > 0) {
            let errorMsg = '<p class="text-danger">The following fields are required: ';
            errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
            $('#compose-error-msg').html(errorMsg);
            return;
        } else {
            $('#compose-error-msg').html('');
        }

        // Append form data
        formData.append('subject', subject);
        formData.append('description', description);
        formData.append('employees', employees);


        let url = createUrl;
        let method = 'POST';


        try {
            // Send data and handle response
            let res = await commonSaveData(url, formData, method);

            if (res && res.status === 'success') {
                await commonAlert(res.status, res.message);
                renderMessages();
                $('#compose-modal').modal('hide');
            } else {
                let errorMessage = res && res.message ? res.message : 'An unexpected error occurred.';
                $('#compose-error-msg').html('<p class="text-danger">' + errorMessage + '</p>');
            }
        } catch (error) {
            console.error('Error:', error);
            $('#compose-error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });


   // Reset Function
    function resetForm() {
        $('#msg_id').val('');
        $('#created_at').text('');
        $('#type').text('');
        $('#message_content').html('');
        $('#email-description').html('');
        $('#error-msg').html('');
        $('#compose-error-msg').html('');
    }

});



//==

message box has 3 table to store data related messages.
table 1 - messages (it has message_control_id, sender_id, description)
table 2 - message_employees (it has message_id, received_id)
table 3 - message_control (it has type_id, subject)

already has created send mails part. now i need to build reply in this messages. so when click message details box the open modal with fetch related message details with empty body part. then send rely it is will store these table how to build this

</script>


</x-app-layout>
