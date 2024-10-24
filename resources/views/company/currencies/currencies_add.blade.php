<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Currencies') }}</h4>

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
                            <h4 class="card-title mb-0 flex-grow-1">Currencies Lists</h4>
                    </div>


                        <div class="justify-content-md-end">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New<i class="ri-add-line"></i></button>
                            </div>

                        </div>

                </div>


                <div class="card-body">
                        <div class="card-body">
                            <table class="table table-nowrap" id="currency_table">
                                <thead class="table-light">
                                    <tr>

                                        <th>NO</th>
                                        <th>Currency Name</th>
                                        <th>ISO Code</th>
                                        <th>Conversion Rate</th>
                                        <th>Previous Rate</th>
                                        <th>Is Default</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="table_body">
                                    <tr>


                                        <td>01</td>
                                        <td>marycousar@evolve.com</td>
                                        <td>580-464-4694</td>
                                        <td>06 Apr, 2021</td>
                                        <td>06 Apr, 2021</td>
                                        <td><span class="badge bg-success-subtle text-success text-uppercase">Yes</span></td>
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



     <!-- currency form modal -->

     <div id="currency_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Add Currencies</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <form action="" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="currency_name" class="form-label mb-1">Currency Name</label>
                                    <input type="text" class="form-control" id="currency_name" placeholder="Enter Currency Name" required>
                                </div>


                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="iso_code" class="form-label mb-1">ISO Code</label>
                                    <input type="text" class="form-control" id="iso_code" placeholder="Enter ISO Code" required>
                                </div>


                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="conversion_rate" class="form-label mb-1">Currency Conversion Rate</label>
                                    <input type="text" class="form-control" id="conversion_rate" placeholder="Enter Conversion Rate" required>
                                </div>

                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="previous_rate" class="form-label mb-1">Previous Rate</label>
                                    <input type="text" class="form-control" name="previous_rate" id="previous_rate" placeholder="Enter Previous Rate" required>
                                </div>

                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label class="form-check-label me-2" for="is_default">Is Default</label>
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" checked="" required>

                                </div>



                            </div>



                            <div class="row">

                                <div class="d-flex justify-content-end">
                                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn w-sm btn-danger" id="currency-submit-confirm">Submit</button>
                                    </div>
                                </div>



                            </div>

                        </form>

                    </div>


                </div>

            </div>
        </div>
    </div>


    <!-- currency edit form modal -->

    <div id="currency_edit_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >Edit Currencies</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <form action="" method="GET">
                            @csrf

                            <div class="row">
                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="currency_name" class="form-label mb-1">Currency Name</label>
                                    <input type="text" class="form-control" id="currency_name" placeholder="Enter Currency Name" required>
                                </div>


                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="iso_code" class="form-label mb-1">ISO Code</label>
                                    <input type="text" class="form-control" id="iso_code" placeholder="Enter ISO Code" required>
                                </div>


                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="conversion_rate" class="form-label mb-1">Currency Conversion Rate</label>
                                    <input type="text" class="form-control" id="conversion_rate" placeholder="Enter Conversion Rate" required>
                                </div>

                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label for="previous_rate" class="form-label mb-1">Previous Rate</label>
                                    <input type="text" class="form-control" name="previous_rate" id="previous_rate" placeholder="Enter Previous Rate" required>
                                </div>

                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label class="form-check-label me-2" for="is_default">Is Default</label>
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" checked="" required>

                                </div>



                            </div>



                            <div class="row">

                                <div class="d-flex justify-content-end">
                                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn w-sm btn-danger" id="currency-submit-confirm">Submit</button>
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
    $('#currency_form_modal').find('form')[0].reset();

    // Display the modal
    $('#currency_form_modal').modal('show');
});



    //======================================================================================================
    // EDIT MODAL FORM
    //======================================================================================================

    $(document).on('click', '.click_edit', function(){

    // Display the modal
    $('#currency_edit_form_modal').modal('show');
    });



    //======================================================================================================
    //  Initialize DataTable
    //======================================================================================================


    $(document).ready(function() {

        const table = $('#currency_table').DataTable();

        // // Attach event listener to the search input
        // $('#searchCurrency').on('keyup', function() {
        //     table.search(this.value); // Use .search() to filter the table
        // });
    });


</script>





</x-app-layout>
