<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Designations') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>

{{-- Generate Currency Lists table --}}


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between">
                <div >
                        <h4 class="card-title mb-0 flex-grow-1">Employee Title List</h4>
                </div>


                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New<i class="ri-add-line"></i></button>
                        </div>

                    </div>

            </div>


            <div class="card-body">
                    <div class="card-body">
                        <table class="table table-nowrap" id="designation_table">
                            <thead class="table-light">
                                <tr>

                                    <th>NO</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">
                                <tr>


                                    <td>01</td>
                                    <td>marycousar@velzon.com</td>
                                    <td>580-464-4694</td>

                                    <td>
                                        <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit me-1">
                                           <i class="ri-pencil-fill"></i>
                                       </button>

                                       <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete" id="delete_btn">
                                           <i class="ri-delete-bin-fill"></i>
                                       </button>
                                   </td>

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


<!-- designation add form modal -->

<div id="designation_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Add Designations</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <form action="" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="designation_name" class="form-label mb-1">Name</label>
                                <input type="text" class="form-control" id="designation_name" placeholder="Enter Designation Name" required>
                            </div>


                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="designation_type" class="form-label mb-1">Type</label>
                                <select class="form-select" id="designation_type">
                                    <option value="">Select Designation Type</option>
                                    <option value="1">Type 1</option>
                                    <option value="2">Type 2</option>
                                </select>
                            </div>

                        </div>



                        <div class="row">

                            <div class="d-flex justify-content-end">
                                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn w-sm btn-danger" id="designation_submit_confirm">Submit</button>
                                </div>
                            </div>



                        </div>

                    </form>

                </div>


            </div>

        </div>
    </div>
</div>

<!-- designation edit form modal -->

<div id="designation_edit_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Edit Designations</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <form action="" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="designation_name" class="form-label mb-1">Designation Name</label>
                                <input type="text" class="form-control" id="designation_name" placeholder="Enter Designation Name" required>
                            </div>



                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="designation_type" class="form-label mb-1">Type</label>
                                <select class="form-select" id="designation_type">
                                    <option value="">Select Designation Type</option>
                                    <option value="1">Type 1</option>
                                    <option value="2">Type 2</option>
                                </select>
                            </div>

                        </div>



                        <div class="row">

                            <div class="d-flex justify-content-end">
                                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn w-sm btn-danger" id="designation_submit_confirm">Submit</button>
                                </div>
                            </div>



                        </div>

                    </form>

                </div>


            </div>

        </div>
    </div>
</div>


<script>


//======================================================================================================
// ADD MODAL FORM
//======================================================================================================


$(document).on('click', '#add_new_btn', function(){

// Reset the form fields inside the modal
$('#designation_form_modal').find('form')[0].reset();

// Display the modal
$('#designation_form_modal').modal('show');
});


//======================================================================================================
// EDIT MODAL FORM
//======================================================================================================

$(document).on('click', '.click_edit', function(){

// Reset the form fields inside the modal
$('#designation_edit_form_modal').find('form')[0].reset();

// Display the modal
$('#designation_edit_form_modal').modal('show');
});



//======================================================================================================
//  Initialize DataTable
//======================================================================================================


$(document).ready(function() {

    const table = $('#designation_table').DataTable();
});


</script>


</x-app-layout>
