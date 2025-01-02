<!-- pawanee(2024-10-22) -->
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
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">New WageGroup<i class="ri-add-line"></i></button>
                    </div>
                </div>
            </div>


            <div class="card-body">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Wage Group Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody id="table_body">

                                <tr><td colspan="7" class="text-center">Loading...</td></tr>

                            </tbody>
                        </table>
                    </div>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>



<!-- wageGroup form modal -->


<div id="wageGroup_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wageGroup-form-body" class="row">
                    <div class="mb-3">
                        <label for="wage_group_name" class="form-label mb-1">Name</label>
                        <input type="text" class="form-control" id="wage_group_name" placeholder="Enter Wage Group Name" value="">
                    </div>
                </div>
                <div id="error-msg"></div>
                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                    <input type="hidden" id="wage_group_id" value="">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-primary" id="wage-group-submit-confirm">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>





<script>

//======================================================================================================
// RENDER TABLE
//======================================================================================================

    $(document).ready(function() {
        initFunction();
    });

    // Initialize the function to render the table
    async function initFunction(){

        await renderTableBody();
    }


    // Render table body
    async function renderTableBody() {
        try {
            const items = await commonFetchData(`/company/allwagegroups`);

            console.log(items);
            let list="";

            if (items.length === 0) {
                $('#table_body').html('<tr><td colspan="4" class="text-center">No data available</td></tr>');
                return;
            } else{
                list = items.map((item, i) => {
                    return `
                        <tr wage_group_id="${item.id}">
                            <th scope="row">${i + 1}</th>
                            <td>${item.wage_group_name}</td>
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
                }).join('');
            }

            // Update the HTML content of the table body with the new list
            $('#table_body').html(list);

        } catch (error) {
            $('#table_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
            console.error('Error fetching data:', error);
        }
    }




//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

    $(document).on('click', '.click_delete', function() {
        const $row = $(this).closest('tr');
        const id = $row.attr('wage_group_id');

            deleteItem(id, $row);

    });

    async function deleteItem(id, $row) {
        const url ='company/wagegroups/delete';
        const title ='Wage Group';


        try {
                    const res = await commonDeleteFunction(id, url, title, $row);
                    if(res){
                        renderTableBody()
                    }
                } catch (error) {
                    console.error('Error deleting item:', error);
                }
    }


//======================================================================================================
// ADD & EDIT FUNCTION
//======================================================================================================


    // Add wage group
    $(document).on('click', '#add_new_btn', function () {
        resetForm();
        $('.modal-title').text('Add Wage Group');  // Set modal title for adding
        $('#wageGroup_form_modal').modal('show'); // Show the modalfor adding a new wage group
    });

    // Edit wage group
    $(document).on('click', '.click_edit', async function () {
        resetForm();


        let wageGroup_id = $(this).closest('tr').attr('wage_group_id');

        // Set modal title for editing the wage group
        if (wageGroup_id) {

            $('.modal-title').text('Edit Wage Group');
        }


        // Get wage group data by ID
        try {
            let wageGroup_data = await commonFetchData(`/company/wagegroups/${wageGroup_id}`);
            wageGroup_data = wageGroup_data[0];

            // Set form values with fetched data
            $('#wage_group_id').val(wageGroup_id);
            $('#wage_group_name').val(wageGroup_data?.wage_group_name || '');

        } catch (error) {
            console.error('Error fetching wage group data:', error);
            $('#error-msg').html('<p class="text-danger">Error fetching wage group data. Please try again.</p>');
        } finally {
            $('#wageGroup_form_modal').modal('show'); // Show the modal for editing
        }
    });

    // Submit wage group (add/edit)
    $(document).on('click', '#wage-group-submit-confirm', async function () {
        const wageGroup_id = $('#wage_group_id').val();

        let createUrl = `/company/wagegroups/create`;
        let updateUrl = `/company/wagegroups/update/${wageGroup_id}`;

        const wageGroupName = $('#wage_group_name').val();

        let formData = new FormData();

        // Validate the required field
        if (!wageGroupName) {
            $('#error-msg').html('<p class="text-danger">The field Wage group name is required.</p>');
            return;
        } else {
            $('#error-msg').html('');
        }

        formData.append('wage_group_name', wageGroupName);

        // Check if updating
        const isUpdating = Boolean(wageGroup_id);
        let url = isUpdating ? updateUrl : createUrl;
        let method = isUpdating ? 'PUT' : 'POST'; // Set method based on update/create

        if (isUpdating) {
            formData.append('id', wageGroup_id); // Append ID if updating
        }

        try {
            // Send data and handle response
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                renderTableBody();
                $('#wageGroup_form_modal').modal('hide');

            } else {
                $('#error-msg').html('<p class="text-danger">' + res.message + '</p>');
            }

        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });


    //Reset Function
    function resetForm() {
        $('#wage_group_id').val('');
        $('#wage_group_name').val('');
        $('#error-msg').html('');
    }



</script>




</x-app-layout>
