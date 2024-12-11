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
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_request">Add New<i class="ri-add-line"></i></button>
                    </div>
                </div>
            </div>


            <div class="card-body">
                    <div class="card-body">

                        <div class="row mb-3 mb-4">
                            <div class="col-lg-2">
                                <label for="employee_id" class="form-label mb-1 req">Employee Name</label>
                            </div>

                            <div class="col-lg-10">
                                <select class="form-select form-select-sm" id="employee_id" >

                                </select>
                            </div>

                        </div>


                        <table class="table table-nowrap" id="jobhistory_table">
                            <thead class="table-light" id="table_head">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">

                                <tr>
                                    <td colspan="8" class="text-center text-info">Please Select a Employee</td>
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



<!-- Compose Modal -->
<div class="modal fade zoomIn" id="request-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-3 bg-light">
                <h5 class="modal-title">Edit Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="compose-body" class="row">

                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="employee_name" class="form-label mb-1">Employee Name</label>
                        <input type="text" class="form-control" id="employee_name" value="" disabled>
                        <input type="hidden" class="form-control" id="employee_id" value="" disabled>
                    </div>

                    <div class="col-xxl-6 col-md-4 mb-3">
                        <label for="Type_name" class="form-label mb-1 req">Type</label>
                        <select class="form-select" id="Type_name" >
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="employee_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="employee_date" value="">
                    </div>

                    <div class="col-xxl-12 col-md-12 mb-2">
                        <textarea class="form-control" id="description" rows="15"></textarea>
                    </div>
                </div>

                <div id="error-msg"></div>
                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                    <input type="hidden" id="request_id" value=""></button>
                    <button type="button" class="btn w-sm btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-success" id="message-send-confirm">Send</button>
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
let employee_Id = '';

let dropdownData = [];


    $(document).ready(async function(){
            await getDropdownData();



        // Get employee data when selecting employee name
        $(document).on('change', '#employee_id', async function () {
            employee_Id = $(this).val();
            let employeeName = $('#employee_id option:selected').text();
            $('#employee_name').val(employeeName);
            $('#employee_id').val(employee_Id);

            if (employee_Id === "") {

                $('#table_body').html('<tr><td colspan="8" class="text-center text-info">Please Select a Employee</td></tr>');
                $('#employee_name').val('');
                $('#employee_id').val('');
            } else {
                await renderRequestTable();
            }
        });


         //render table using employee Id
        async function renderRequestTable(){
            let list = '';

            const requests = await commonFetchData(`/employee/requests/${employee_Id}`);

            if(requests && requests.length > 0){
                requests.map((request, i) => {
                    list += `
                        <tr request_id="${request.id}">
                            <td>${i + 1}</td>
                            <td>${request.employee_date_id}</td>
                            <td>${request.type_id}</td>
                            <td>${request.description}</td>
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                })
            }else{
                list = '<tr><td colspan="8" class="text-center text-danger">No Request Found!</td></tr>';
            }


            $('#table_body').html(list);
        }

//======================================================================================================
//get dropdown data
//======================================================================================================

        async function getDropdownData() {
            try {
              let dropdownData = await commonFetchData('/attendance/requests/dropdown');

                // Populate employee name dropdown
                let employeeList = (dropdownData?.employees || [])
                    .map(employee => `<option value="${employee.id}">${employee.name_with_initials}</option>`)
                    .join('');
                $('#employee_id').html('<option value="">Select Employee Name</option>' + employeeList);

                // Populate message type dropdown
                let typeList = (dropdownData?.types || [])
                    .filter(type => type.name !== 'Email') // Exclude 'email' type
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#Type_name').html('<option value="">Select Type</option>' + typeList);


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }


    // open compose modal
    $(document).on('click', '#add_request', async function(){
        resetForm(); //reset

        //show modal
        $('#request-modal').modal('show');
    })


    $(document).ready(function () {
        // Get today's date in the format 'YYYY-MM-DD'
        let today = new Date().toISOString().split('T')[0];

        // Set the value of the input field with id 'employee_date' to today's date
        $('#employee_date').val(today);
    });



});


//reset function
function resetForm() {

    $('#request_id').val('');
    $('#Type_name').val('');
    $('#employee_date').val('');
    $('#description').val('');
    $('#compose-error-msg').html('');

}




</script>


</x-app-layout>
