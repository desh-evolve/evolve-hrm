<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Wage Groups') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>

{{-- Generate wageGroup Lists table --}}


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between">
                <div >
                        <h4 class="card-title mb-0 flex-grow-1">Wage Group List</h4>
                </div>


                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New<i class="ri-add-line"></i></button>
                        </div>

                    </div>

            </div>


            <div class="card-body">
                    <div class="card-body">
                        <table class="table table-nowrap" id="wageGroup_table">
                            <thead class="table-light">
                                <tr>

                                    <th>NO</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">
                                <tr>


                                    <td>01</td>
                                    <td>marycousar@velzon.com</td>
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

<div id="wageGroup_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Add Wage Group</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <form action="" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="wageGroup_name" class="form-label mb-1">Name</label>
                                <input type="text" class="form-control" id="wageGroup_name" placeholder="Enter Wage Group Name" required>
                            </div>

                        </div>



                        <div class="row">

                            <div class="d-flex justify-content-end">
                                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn w-sm btn-danger" id="wageGroup_submit_confirm">Submit</button>
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

<div id="wageGroup_edit_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Edit Wage Group</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <form action="" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="wageGroup_name" class="form-label mb-1">Name</label>
                                <input type="text" class="form-control" id="wageGroup_name" placeholder="Enter Wage Group Name" required>
                            </div>


                        </div>



                        <div class="row">

                            <div class="d-flex justify-content-end">
                                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn w-sm btn-danger" id="wageGroup_submit_confirm">Submit</button>
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
//  Initialize DataTable
//======================================================================================================


$(document).ready(function() {

const table = $('#wageGroup_table').DataTable();
});


//======================================================================================================
// ADD MODAL FORM
//======================================================================================================


$(document).on('click', '#add_new_btn', function(){

// Reset the form fields inside the modal
$('#wageGroup_form_modal').find('form')[0].reset();

// Display the modal
$('#wageGroup_form_modal').modal('show');
});


//======================================================================================================
// EDIT MODAL FORM
//======================================================================================================

$(document).on('click', '.click_edit', function(){

// Display the modal
$('#wageGroup_edit_form_modal').modal('show');
});


//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

$(document).on('click', '.click_delete', function() {
    const itemId = $(this).data('id'); // Get the ID of the item to be deleted
    const $row = $(this).closest('tr'); // Get the table row containing the delete button

    // Show a confirmation dialog
    if (confirm('Are you sure you want to delete this item?')) {
        // Call the delete function
        deleteItem(itemId, $row);
    }
});

async function deleteItem(itemId, $row) {
    try {
        // Send a DELETE request to the server
        const response = await fetch(`/delete-item/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            // If the server confirms deletion, remove the row from the table
            $row.remove();
            alert('Item deleted successfully.');
        } else {
            const errorData = await response.json();
            alert('Error: ' + errorData.message);
        }
    } catch (error) {
        console.error('Error deleting item:', error);
        alert('Failed to delete the item. Please try again.');
    }
}

</script>


</x-app-layout>
