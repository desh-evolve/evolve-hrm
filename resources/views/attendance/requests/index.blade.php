<!-- pawanee(2024-12-09) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Requests') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>



{{-- Generate Lists table --}}


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between">
                <div >
                    <h4 class="card-title mb-0 flex-grow-1">Requests List</h4>
                </div>

                <div class="justify-content-md-end">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_request">New Request<i class="ri-add-line"></i></button>
                    </div>
                </div>
            </div>


            <div class="card-body">
                    <div class="card-body">

                        <div class="row mb-3 mb-4">
                            <div class="col-lg-2">
                                <label for="user_id" class="form-label mb-1 req">Employee Name</label>
                            </div>

                            <div class="col-lg-10">
                                <select class="form-select" id="user_id" >

                                </select>
                            </div>

                        </div>


                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">

                                <tr>
                                    <td colspan="8" class="text-center">Please Select a Employee...</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>



<!-- Request Modal -->
<div class="modal fade zoomIn" id="request-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-3 bg-light">
                <h5 class="modal-title">Edit Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Alert Message --}}
            <div class="alert alert-warning alert-dismissible" id="warning_alert" style="display: none;">

            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="mb-3">
                        <label for="user_name" class="form-label mb-1">Employee Name</label>
                        <input type="text" class="form-control" id="user_name" value="" disabled>
                        <input type="hidden" class="form-control" id="user_id" value="" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-6 col-md-6 mb-2">
                        <label for="type_id" class="form-label mb-1 req">Type</label>
                        <select class="form-select" id="type_id" >
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="user_date_id" class="form-label mb-1 req">Date</label>
                        <input type="date" class="form-control" id="user_date_id" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-md-12 mb-2">
                        <textarea class="form-control" id="description" rows="8"></textarea>
                    </div>
                </div>

                <div id="error-msg"></div>
                <div class="d-flex gap-2 justify-content-end mt-3 mb-2">
                    <input type="hidden" id="request_id" value=""></button>
                    <button type="button" class="btn w-sm btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-success" id="request-send-confirm">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->



<script>



//======================================================================================================
// RENDER TABLE
//======================================================================================================
let user_id = '';

let dropdownCache = [];

// Get today's date
let today = new Date().toISOString().split('T')[0];


    $(document).ready(async function(){
        await getDropdownData();
        $('#add_request').prop('disabled', true);


        // Get user data when selecting user name
        $(document).on('change', '#user_id', async function () {
            user_id = $(this).val();
            let userName = $('#user_id option:selected').text();
            $('#user_name').val(userName);
            $('#user_id').val(user_id);

            // Enable button if user is selected
            if (user_id) {
                $('#add_request').prop('disabled', false);
            } else {
                $('#add_request').prop('disabled', true);
            }

            // Render the table if employee is selected
            if (user_id === "") {
                $('#table_body').html('<tr><td colspan="8" class="text-center">Please Select a Employee...</td></tr>');
                $('#user_name').val('');
                $('#user_id').val('');
            } else {
                await renderRequestTable();
            }
        });




        async function renderRequestTable() {
            let list = '';
            try {
                const requestData = await commonFetchData(`/attendance/request/${user_id}`);
                console.log('Fetched Data:', requestData);

                // Flatten nested arrays
                const requests = requestData && Array.isArray(requestData)
                    ? requestData.reduce((acc, curr) => acc.concat(curr), [])
                    : [];

                console.log('Flattened Requests:', requests);

                if (requests.length > 0) {
                    requests.forEach((request, i) => {
                        const message = request.status_details?.[0]?.request_status || 'N/A';
                        const date = request.date_details?.[0]?.date_stamp || 'N/A';

                        list += `
                            <tr request_id="${request.id}">
                                <td>${i + 1}</td>
                                <td>${date}</td>
                                <td>${request.type_name}</td>
                                <td>${message}</td>
                                <td class="text-capitalize">
                                    ${
                                        request.status === 'authorized'
                                            ? `<span class="badge border border-success text-success">${request.status}</span>`
                                            : `<span class="badge border border-warning text-warning">${request.status}</span>`
                                    }
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    list = '<tr><td colspan="8" class="text-center text-danger">No Request Found!</td></tr>';
                }
            } catch (error) {
                console.error('Error fetching or rendering data:', error);
                list = '<tr><td colspan="8" class="text-center text-danger">Error loading data!</td></tr>';
            }

            $('#table_body').html(list);
        }


//======================================================================================================
//get dropdown data
//======================================================================================================

        async function getDropdownData() {
            try {
              let dropdownCache = await commonFetchData('/attendance/request/dropdown');

                // Populate user name dropdown
                let userList = (dropdownCache?.users || [])
                    .map(user => `<option value="${user.id}">${user.name_with_initials}</option>`)
                    .join('');
                $('#user_id').html('<option value="">Select Employee Name</option>' + userList);

                // Populate message type dropdown
                let typeList = (dropdownCache?.types || [])
                    .filter(type => type.type_category === 'request')
                    .map(type => `<option value="${type.id}">${type.type_name}</option>`)
                    .join('');
                $('#type_id').html('<option value="">Select Type</option>' + typeList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }


//======================================================================================================
// ADD NEW REQUEST
//======================================================================================================

        $(document).on('click', '#add_request', async function(){
            resetForm();
            $('#request-modal').modal('show');
        })


        $(document).on('click', '#request-send-confirm', async function () {
            const createUrl = `/attendance/request/create`;

            const type = $('#type_id').val();
            const description = $('#description').val();
            const date = $('#user_date_id').val();

            const formData = new FormData();
            let missingFields = [];

            // Check for missing fields
            if (!type) missingFields.push('Type');
            if (!description) missingFields.push('Description');
            if (!date) missingFields.push('Date');

            if (missingFields.length > 0) {
                $('#error-msg').html(
                    `<p class="text-danger">The following fields are required: ${missingFields.join(', ')}.</p>`
                );
                return;
            } else {
                $('#error-msg').html('');
            }

            formData.append('user_id', user_id);
            formData.append('type_id', type);
            formData.append('description', description);
            formData.append('user_date_id', date);

            try {
                const res = await commonSaveData(createUrl, formData, 'POST');

                if (res && res.status === 'success') {
                    await commonAlert(res.status, res.message);

                    $('#request-modal').modal('hide');
                    $('#warning_alert').hide();
                    renderRequestTable();

                } else if (res && res.status === 'error') {
                    if (res.message === 'No matching record found for the user and date.') {

                        // Append the error message into the warning alert
                        $('#warning_alert').html(`
                            <button type="button" class="btn-close" aria-label="Close"></button>
                            <strong>Warning! </strong> ${res.message}
                        `).show();

                    } else {

                        $('#error-msg').html(`<p class="text-danger">${res.message}</p>`);
                    }
                } else {
                    const errorMessage = res && res.message ? res.message : 'An unexpected error occurred.';
                    $('#error-msg').html(`<p class="text-danger">${errorMessage}</p>`);
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        // Reset alert visibility
        $(document).on('click', '#warning_alert .btn-close', function () {
            $('#warning_alert').hide();
        });

//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

        $(document).on('click', '.click_delete', function() {
            const $row = $(this).closest('tr');
            const id = $row.attr('request_id');

                deleteItem(id, $row);
        });

        async function deleteItem(id, $row) {
            const url ='/attendance/request/delete';
            const title ='Request';
            try {
                const res = await commonDeleteFunction(id, url, title, $row);
                if(res){
                    renderRequestTable()
                }
            } catch (error) {
                console.error('Error deleting item:', error);
            }
        }




    });



//reset function
function resetForm() {

    $('#request_id').val('');
    $('#type_id').val('');
    $('#user_date_id').val(today);
    $('#description').val('');
    $('#error-msg').html('');
    $('#warning_alert').hide();

}



</script>


</x-app-layout>
