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
                                <span class="badge bg-success-subtle text-success ms-auto" id="inbox-msg-count"></span>
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
                <div class="p-4 d-flex flex-column h-100">
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
                                {{-- delete button --}}
                                <div class="hstack gap-sm-1 align-items-center flex-wrap email-topbar-link delete-chat">

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Scroll Section -->
                    <div class="mx-n4 px-4 email-detail-content-scroll" data-simplebar style="height: 400px;">
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
                    <div class="mt-auto reply-chat">

                    </div>
                </div>
            </div>

        </div>
        <!-- end email wrapper -->
    </div>
    <!-- end col -->



    <!-- details message Modal -->
    <div class="modal fade zoomIn" id="message-details-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3 bg-light">
                    <h5 class="modal-title">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="compose-body">

                        <div id="message-details-error-msg"></div>

                        <div class="input-group mb-2">
                            <label class="input-group-text" for="date">Date</label>
                            <div style="flex: 1;">
                                <input type="text" class="form-control" id="date" value="">
                            </div>
                        </div>

                        <div class="input-group mb-2">
                            <label class="input-group-text" for="sender-email">From</label>
                            <div style="flex: 1;">
                                <input type="text" class="form-control" id="sender-email" value="">
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text" for="receivers">To</label>
                            <div style="flex: 1;">
                                <select class="select2-multiple form-control" id="receivers" name="receivers[]" multiple="multiple">

                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 mb-2">
                            <input type="text" class="form-control fw-bold" id="msg-subject" placeholder="Subject" value="" disabled>
                        </div>
                        <div class="col-xxl-12 col-md-12 mb-2">
                            <textarea class="form-control" id="msg-description" rows="5"></textarea>
                        </div>

                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="message_id" value=""></button>
                        <button type="button" class="btn w-sm btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>





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
                            <label class="input-group-text" for="users">To</label>
                            <div style="flex: 1;">
                                <select class="select2-multiple form-control" id="users" name="users[]" multiple="multiple">

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


     <!-- Reply Modal -->
    <div class="modal fade zoomIn" id="reply-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3 bg-light">
                    <h5 class="modal-title">Reply Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="compose-body">

                        <div id="reply-error-msg"></div>

                        <div class="row">
                            <div class="col-xxl-3 col-md-12 mb-3">
                                <input type="hidden" class="form-control" id="message_control_id" value="">
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text" for="reply_receivers">To</label>
                            <div style="flex: 1;">
                                <select class="select2-multiple form-control" id="reply_receivers" name="reply_receivers[]" multiple="multiple">

                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 mb-3">
                            <input type="text" class="form-control" id="reply_subject" placeholder="Subject" value="" disabled>
                        </div>

                        <!-- Reply Section -->
                        <div class="mt-auto">
                            <div class="mt-2">
                                <label for="reply-body" class="form-label">Reply</label>
                                <textarea class="form-control" id="reply_body" rows="7" placeholder="Enter message"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="msg_id" value=""></button>
                        <button type="button" class="btn w-sm btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-success" id="message-reply-confirm">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>

//===========================================================================================
// Render Messages
//===========================================================================================

const loggedUserId = {{ Auth::id() }};
let dropdownData = [];
let message_controller_id = '';


$(document).ready(async function () {
    // Render Messages on Page Load
    await renderAllMessages();
    await getDropdownData();
    await updateAllMessageCount();
    await updateInboxMessageCount();


    // All Messages
    $(document).on('click', '#all-message', async function () {
        await renderAllMessages();
        $('#message_details_box').hide();
    });

    // All sent Messages
    $(document).on('click', '#sent-message', async function () {
        await renderSentMessages();
        $('#message_details_box').hide();
    });

    // All received Messages
    $(document).on('click', '#inbox-message', async function () {
        await renderReceivedMessages();
        $('#message_details_box').hide();
    });

    // Refresh button
    $(document).on('click', '#refresh-btn', function () {
        location.reload();
    });


//===========================================================================================
// dropdown user name
//===========================================================================================

    async function getDropdownData() {
        try {
            dropdownData = await commonFetchData('/employee/name/dropdown');
            // Populate users dropdown
            let userList = (dropdownData?.users || [])
                .map(user => `<option value="${user.id}">${user.work_email}</option>`)
                .join('');
            $('#users').html(userList);
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
// Render All Messageses
//===========================================================================================


    async function renderAllMessages() {
        try {
            // Fetch messages from the API
            const response = await fetch('/employee/allmessages', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status} ${response.statusText}`);
            }

            const data = await response.json();
            console.log('API Response:', data);

            // Extract sent and received messages
            const sentMessages = data.sentMessages || [];
            const receivedMessages = data.receivedMessages || [];

            if (!sentMessages.length && !receivedMessages.length) {
                $('#msg-list').html('<li class="text-center">No messages available</li>');
                return;
            }

            // Combine sent and received messages
            const allMessages = [...sentMessages, ...receivedMessages];

            // unread first, then by created_at in descending order
            allMessages.sort((a, b) => {
                const aUnread = !sentMessages.includes(a) && (a.message_details || []).some(detail => detail.read_status === 0);
                const bUnread = !sentMessages.includes(b) && (b.message_details || []).some(detail => detail.read_status === 0);

                if (aUnread !== bUnread) {
                    return aUnread ? -1 : 1;
                }
                return new Date(b.created_at) - new Date(a.created_at);
            });


            const messageList = allMessages.map((message, i) => {
                const isSent = sentMessages.includes(message);
                const messageDetails = message.message_details || [];
                const hasUnreadMessages = !isSent && messageDetails.some(detail => detail.read_status === 0);

                return `
                    <li msg_id="${message.id}" data-type="${isSent ? 'sent' : 'received'}" class="email-item ${!isSent && hasUnreadMessages ? 'fw-bold text-dark' : ''}">
                        <div class="col-mail col-mail-1">
                            <div class="checkbox-wrapper-mail fs-14">${i + 1}</div>
                            <div id="type-name" class="title cursor-pointer">
                                <span class="title-name">${message.type_name}</span>
                            </div>
                        </div>
                        <div class="col-mail col-mail-2 cursor-pointer">
                            <div id="type-sub">
                                <span class="subject-title">${message.subject}</span>
                            </div>
                            <div class="date" id="message-date">${formatDate1(message.created_at)}</div>
                        </div>
                    </li>
                `;
            }).join('');

            $('#msg-list').html(messageList);
            $('#elmLoader').hide();
        } catch (error) {
            $('#msg-list').html('<li class="text-center text-danger">Error loading messages</li>');
            console.error('Error fetching messages:', error);
        }
    }




//===========================================================================================
// Render All Sent Messageses
//===========================================================================================

    async function renderSentMessages() {
        try {
            const messages = await commonFetchData('/employee/sent/messages');

            if (messages.length === 0) {
                $('#msg-list').html('<li class="text-center">No sent messages available</li>');
                return;
            }

            // messages by created_at in descending order
            const storeMessages = messages.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));

            const list = storeMessages.map((message, i) => {
                return `
                    <li msg_id="${message.id}" class="email-item">
                        <div class="col-mail col-mail-1">
                            <div class="checkbox-wrapper-mail fs-14">
                                ${i + 1}
                            </div>
                            <div id="type-name" class="title cursor-pointer">
                                <span class="title-name">${message.type_name}</span>
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

//==========================================================================================
// Render All Received Messageses
//===========================================================================================


    async function renderReceivedMessages() {
        try {
            const messages = await commonFetchData('/employee/inbox/messages');

            if (messages.length === 0) {
                $('#msg-list').html('<li class="text-center">No inbox messages available</li>');
                return;
            }

            // unread first, then by created_at in descending order
            const storeMessages = messages.sort((a, b) => {
                const aUnread = (a.message_details || []).some(detail => detail.read_status === 0);
                const bUnread = (b.message_details || []).some(detail => detail.read_status === 0);

                if (aUnread !== bUnread) {
                    return aUnread ? -1 : 1; // Unread messages come first
                }

                return new Date(b.created_at) - new Date(a.created_at);
            });


            const list = storeMessages.map((message, i) => {

                const messageDetails = message.message_details || [];
                const hasUnreadMessages = messageDetails.some(detail => detail.read_status === 0);

                return `
                    <li msg_id="${message.id}" data-type="received" class="email-item ${hasUnreadMessages ? 'fw-bold text-dark' : ''}">
                        <div class="col-mail col-mail-1">
                            <div class="checkbox-wrapper-mail fs-14">
                                ${i + 1}
                            </div>
                            <div id="type-name" class="title cursor-pointer">
                                <span class="title-name">${message.type_name}</span>
                            </div>
                        </div>
                        <div class="col-mail col-mail-2 cursor-pointer">
                            <div id="type-sub">
                                <span class="subject-title">${message.subject}</span>
                            </div>
                            <div class="date" id="sent-date">
                                ${formatDate1(message.created_at)}
                            </div>
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

    // Update All Message Count
    async function updateAllMessageCount() {
        try {
            const response = await fetch('/employee/allmessages', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status} ${response.statusText}`);
            }

            const data = await response.json();
            const sentMessages = data.sentMessages || [];
            const receivedMessages = data.receivedMessages || [];

            $('#msg-count').text(sentMessages.length + receivedMessages.length);
        } catch (error) {
            console.error('Error fetching messages:', error);
            $('#msg-count').text('Error');
        }
    }


     // Update All Received Message Count
    async function updateInboxMessageCount() {
        try {
            const inbox = await commonFetchData('/employee/inbox/messages');
            const inboxCount = inbox.length;
            $('#inbox-msg-count').text(inboxCount);
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }


//===========================================================================================
// update message read or not
//===========================================================================================


    $(document).on('click', '.email-item', async function () {
        $('#error-msg').html('');

        let $emailItem = $(this);
        let messageControlId = $emailItem.attr('msg_id');
        let messageType = $emailItem.data('type');

        if (!messageControlId) {
            console.error('No messageControlId found for this item.');
            return;
        }

        if (messageType !== 'received') {
            console.warn('This action is only for received messages.');
            $('#error-msg').html('<p class="text-danger">This action is only for received messages.</p>');
            return;
        }

        let formData = new FormData();
        formData.append('message_control_id', messageControlId);

        const updateUrl = '/employee/messages/mark-as-read';

        try {
            let response = await commonSaveData(updateUrl, formData);

            if (response.status === 'success') {
                if (response.message === 'All messages are already marked as read.') {
                    console.log(`All messages ${messageControlId} are already read.`);
                } else {
                    console.log(`Messages for message_control_id ${messageControlId} marked as read.`);

                    // Remove the bold style to indicate it's read
                    $emailItem.removeClass('fw-bold text-dark');
                    $('#error-msg').html('');
                }
            } else {
                console.error('Error updating read status:', response.message);
                $('#error-msg').html('<p class="text-danger">Failed to mark messages as read. Please try again.</p>');
            }
        } catch (error) {
            console.error('Error processing read status update:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again later.</p>');
        }
    });



//===========================================================================================
// Open chat Message box
//===========================================================================================

    // Open the Message Details Box
    $(document).on('click', '.email-item', async function () {
        resetForm();

        const $row = $(this);
        const subjectName = $row.find('.subject-title').text();
        const typeName = $row.find('.title-name').text();

        // Set the subject in the message details box
        $('#message-box-subject').text(subjectName || 'No Subject');
        $('#message-box-type').text(typeName || 'No message type');


        let msg_id = $row.attr('msg_id');

        if (!msg_id) {
            console.error('No message ID found for the selected row.');
            $('#error-msg').html('<p class="text-danger">Unable to load message details. Please try again.</p>');
            return;
        }

        try {
            let messageData = await commonFetchData(`/employee/messages/${msg_id}`);

            if (Array.isArray(messageData) && messageData.length > 0) {
                console.log('Fetched Message Data:', messageData);


                const messagesHTML = messageData.map((message) => {
                    // Extract receiver emails
                    let displayedReceivers = 'Receiver Unknown';
                    if (Array.isArray(message.receiver_details)) {
                        let receiverEmail = message.receiver_details.map(receiver => receiver.receiver_email);
                       if (receiverEmail.length > 3) {
                            displayedReceivers = receiverEmail.slice(0,3).join(', ') + ', ....'; // Show first 3 and append ...
                       } else {
                            displayedReceivers = receiverEmail.join(', ');
                       }
                    }

                    return `
                        <div class="message-item-container">
                            <div message_id="${message.message_id}"
                                 message_control_id="${message.message_control_id}"
                                 class="message-item p-2 d-flex flex-column mb-1 border-bottom border-bottom-dashed rounded-lg cursor-pointer bg-primary-subtle">
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
                        </div>
                    `;
                }).join('');


                // Add the Reply Button after all messages
                const replyButtonHTML = `
                    <div class="mt-auto">
                        <div class="mt-4 d-grid gap-2">
                            <button type="button" class="reply-button btn w-sm btn-success" data-message-control-id="${msg_id}">Reply</button>
                        </div>
                    </div>
                `;

                // Delete Button
                const deleteButtonHTML = `
                    <div class="mt-auto">
                        <button class="remove-mail btn btn-ghost-secondary btn-icon btn-sm fs-16 material-shadow-none" data-message-control-id="${msg_id}">
                            <i class="ri-delete-bin-5-fill align-bottom"></i>
                        </button>
                    </div>
                `;


                $('.delete-chat').append(deleteButtonHTML);
                 $('.reply-chat').append(replyButtonHTML);
                $('#message_content').html(`${messagesHTML}`);

            } else {
                console.warn('No message details found for this message.');
                $('#error-msg').html('<p class="text-warning">No message details found for the selected item.</p>');
            }
        } catch (error) {
            console.error('Error fetching message details:', error);
            $('#error-msg').html('<p class="text-danger">Failed to load message details. Please try again.</p>');
        } finally {
            $('#message_details_box').show();
        }
    });



    // Show sender message email EX:sanduni@evolve...
    function truncateText(text, length) {
        return text.length > length ? text.substring(0, length) + '...' : text;
    }


    // Close Button Handler
    $(document).on('click', '#close-btn', function () {
        $('#message_details_box').hide();
    });


//===========================================================================================
// Show message details
//===========================================================================================

    $(document).on('click', '.message-item-container', async function () {
        //reset
        $('#sender-email').val('');
        $('#receivers').val('');
        $('#date').val('');
        $('#msg-subject').val('');
        $('#msg-description').val('');
        $('#message-details-error-msg').html('');

        const $container = $(this);

        // Find the `.message-item` within the container
        const $messageItem = $container.find('.message-item');
        let message_id = $messageItem.attr('message_id');


        try {
            let message_data = await commonFetchData(`/employee/single_message/${message_id}`);

            if (message_data && message_data[0]) {
                message_data = message_data[0];
                console.log('processed message_data', message_data);

                let receivers = message_data?.receiver_details?.map(receiver => ({
                    id: receiver.receiver_id,
                    email: receiver.receiver_email
                })) || [];

                // Extract subject from subject_details
                const subjectDetails = message_data?.subject_details || {};
                const messageSubject = subjectDetails[0]?.message_subject || 'No Subject';

                // Populate modal fields
                $('#message_id').val(message_id);
                $('#sender-email').val(message_data?.sender_email || '');
                $('#date').val(message_data?.sent_at || '');
                $('#msg-subject').val(messageSubject);
                $('#msg-description').val(message_data?.message_description || '');

                // ==============================================================
                // Populate #users select field with only the fetched emails
                // ==============================================================
                const $usersSelect = $('#receivers');
                $usersSelect.empty();

                // Add only fetched receiver emails to the dropdown
                receivers.forEach(receiver => {
                    $usersSelect.append(
                        `<option value="${receiver.id}">${receiver.email}</option>`
                    );
                });

                // Preselect fetched receivers
                const receiverIds = receivers.map(receiver => receiver.id.toString());
                $usersSelect.val(receiverIds).trigger('change');


                $(".select2-multiple").select2();
                //==============================================================

            } else {
                console.warn('No message details found for this message.');
                $('#message-details-error-msg').html('<p class="text-warning">No message details found for the selected item.</p>');
            }
        } catch (error) {
            console.error('Error fetching message details:', error);
            $('#message-details-error-msg').html('<p class="text-danger">Failed to load message details. Please try again.</p>');
        } finally {
            $('#message-details-modal').modal('show');
        }
    });


//===========================================================================================
// compose message (Send New Messages)
//===========================================================================================

    $(document).on('click', '#compose-btn', async function(){
        //reset
        $('#subject').val('');
        $('#description').val('');
        $('#users').val([]).trigger('change');
        $('#compose-error-msg').html('');

        //show modal
        $('#compose-modal').modal('show');
    })


    $(document).on('click', '#message-send-confirm', async function() {
        let createUrl = `/employee/messages/create`;

        let subject = $('#subject').val();
        let description = $('#description').val();
        let users = $('#users').val();

        let formData = new FormData();
        let missingFields = [];

        // Check for missing fields and add them to the array
        if (!subject) missingFields.push('subject');
        if (!description) missingFields.push('description');
        if (!users || users.length == 0) missingFields.push('users');

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
        formData.append('users', users);


        let url = createUrl;
        let method = 'POST';


        try {
            // Send data and handle response
            let res = await commonSaveData(url, formData, method);

            if (res && res.status === 'success') {
                await commonAlert(res.status, res.message);

                $('#compose-modal').modal('hide');
                $('#message_details_box').hide();
                await allRender();

            } else {
                let errorMessage = res && res.message ? res.message : 'An unexpected error occurred.';
                $('#compose-error-msg').html('<p class="text-danger">' + errorMessage + '</p>');
            }
        } catch (error) {
            console.error('Error:', error);
            $('#compose-error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });


//===========================================================================================
// Reply modal (Reply to the received Messages)
//===========================================================================================

    $(document).on('click', '.reply-button', async function () {
        // Reset the modal fields
        $('#message_control_id').val('');
        $('#reply_receivers').val(null).trigger('change');
        $('#reply_subject').val('');
        $('#reply_body').val('');
        $('#reply-error-msg').html('');
        $('#message-details-error-msg').html('');

        // Get the message_control_id from the Reply Button
        const messageControlId = $(this).data('message-control-id');

        if (!messageControlId) {
            console.error('No message control ID found.');
            return;
        }

        try {
            let replyMessageData = await commonFetchData(`/employee/messages/${messageControlId}`);

            if (replyMessageData && Array.isArray(replyMessageData)) {
                replyMessageData = replyMessageData[0];
                console.log('Fetched Reply Message Data:', replyMessageData);

                let receivers = replyMessageData?.receiver_details?.map(receiver => ({
                    id: receiver.receiver_id,
                    email: receiver.receiver_email
                })) || [];


                if (replyMessageData.sender_id !== loggedUserId) {
                    receivers.push({
                        id: replyMessageData.sender_id,
                        email: replyMessageData.sender_email
                    });
                }


                receivers = receivers.filter((receiver, index, self) =>
                    receiver.id !== loggedUserId &&
                    index === self.findIndex(r => r.id === receiver.id)
                );


                const subjectDetails = replyMessageData?.subject_details || {};
                const messageSubject = subjectDetails[0]?.message_subject || 'No Subject';

                const replySubject = `Re: ${messageSubject}`;


                // Populate modal fields
                $('#message_control_id').val(messageControlId);
                $('#reply_subject').val(replySubject);

                console.log('Subject Details:', subjectDetails);
                console.log('Message Subject:', replySubject);

                // ==============================================================
                // Populate #users select field with only the fetched emails
                // ==============================================================
                const $replyReceivers = $('#reply_receivers');
                $replyReceivers.empty();

                receivers.forEach(receiver => {
                    $replyReceivers.append(`<option value="${receiver.id}">${receiver.email}</option>`);
                });

                // Preselect fetched receivers
                const receiverIds = receivers.map(receiver => receiver.id.toString());
                $replyReceivers.val(receiverIds).trigger('change');

                $(".select2-multiple").select2();
                //==============================================================

            } else {
                console.warn('No data available for this control ID.');
                $('#reply-error-msg').html('<p class="text-warning">No message details found for the selected item.</p>');
            }
        } catch (error) {
            console.error('Error fetching reply message data:', error);
            $('#reply-error-msg').html('<p class="text-danger">Failed to load message details. Please try again.</p>');
        } finally {
            $('#reply-modal').modal('show');
        }
    })


    $(document).on('click', '#message-reply-confirm', async function() {
        let createUrl = `/employee/messages/reply`;

        let message_control_id = $('#message_control_id').val();
        let reply_subject = $('#reply_subject').val();
        let reply_body = $('#reply_body').val();
        let reply_receivers = $('#reply_receivers').val();

        let formData = new FormData();
        let missingFields = [];

        // Check for missing fields and add them to the array
        if (!reply_subject) missingFields.push('reply_subject');
        if (!reply_body) missingFields.push('reply_body');
        if (!reply_receivers || reply_receivers.length == 0) missingFields.push('reply_receivers');

        if (missingFields.length > 0) {
            let errorMsg = '<p class="text-danger">The following fields are required: ';
            errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
            $('#reply-error-msg').html(errorMsg);
            return;
        } else {
            $('#reply-error-msg').html('');
        }


        formData.append('message_control_id', message_control_id);
        formData.append('reply_subject', reply_subject);
        formData.append('reply_body', reply_body);
        reply_receivers.forEach(receiver => formData.append('reply_receivers[]', receiver));

        let url = createUrl;
        let method = 'POST';


        try {
            // Send data and handle response
            let res = await commonSaveData(url, formData, method);

            if (res && res.status === 'success') {
                await commonAlert(res.status, res.message);

                await allRender();

                let $row = $(`li[msg_id="${message_control_id}"]`);
                if ($row.length) {
                    $row.trigger('click');
                }

                $('#reply-modal').modal('hide');
            } else {
                let errorMessage = res && res.message ? res.message : 'An unexpected error occurred.';
                $('#reply-error-msg').html('<p class="text-danger">' + errorMessage + '</p>');
            }
        } catch (error) {
            console.error('Error:', error);
            $('#reply-error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });


//======================================================================================================
// DELETE FUNCTION
//======================================================================================================


    $(document).on('click', '.remove-mail', async function () {
        const messageControlId = $(this).data('message-control-id');

        if (!messageControlId) {
            $('#error-msg').html('<p class="text-danger">No message control ID found.</p>');
            return;
        }

        try {
            const url = `/employee/message/delete`;
            const title = 'Chat';

            const res = await commonDeleteFunction(messageControlId, url, title);

            console.log('Delete Response:', res);

            if (res) {
                resetForm();
                $('#message_details_box').hide();
                await allRender();
            }
        } catch (error) {
            console.error(`Error during Message Details deletion:`, error);
        }
    });




   // Reset Function
    function resetForm() {
        $('#msg_id').val('');
        $('#created_at').text('');
        $('#type').text('');
        $('#message_content').html('');
        $('#email-description').html('');
        $('.delete-chat').html('');
        $('.reply-chat').html('');
        $('#error-msg').html('');
    }

    // All Render Function
    async function allRender() {
        try {
            
            if ($('#all-message').hasClass('active')) {
                await renderAllMessages();
            } else if ($('#sent-message').hasClass('active')) {
                await renderSentMessages();
            } else if ($('#inbox-message').hasClass('active')) {
                await renderReceivedMessages();
            }

            await updateAllMessageCount();
            await updateInboxMessageCount();
        } catch (error) {
            console.error('error in allRender: ',error);
        }
    }



});


</script>


</x-app-layout>
