<!-- pawanee(2025-02-03) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Documents') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>



<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between">
                <div >
                    <h4 class="card-title mb-0 flex-grow-1">Employee Documents</h4>
                </div>

                <div class="justify-content-md-end">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New Documents <i class="ri-add-line"></i></button>
                        <a href="/employee/list" class="btn btn-danger">Back</a>
                    </div>
                </div>
            </div>


            <div class="card-body">
                    <div class="card-body">

                        <div class="row mb-3 mb-4">
                            <div class="col-lg-2">
                                <label for="user_idname" class="form-label mb-1 req">Employee Name</label>
                            </div>

                            <div class="col-lg-10">
                                <select class="form-select" id="userDropdown" >

                                </select>
                            </div>

                        </div>


                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Document Type</th>
                                    <th scope="col">Document Title</th>
                                    <th scope="col">Document</th>
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


<!-- form modal -->


<div id="document_form_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="employee.user_preferencetic" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-3 bg-light">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="document-form-body" class="row">

                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="user_name" class="form-label mb-1">Employee Name</label>
                        <input type="text" class="form-control" id="user_name" value="" disabled>
                        <input type="hidden" class="form-control" id="user_id" value="" disabled>
                    </div>


                    <!-- documents tab pane -->

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label class="form-label" for="doc_type_id">Document Type</label>
                        <select class="form-select" id="doc_type_id">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label class="form-label" for="doc_title">Title</label>
                        <input type="text" class="form-control" id="doc_title" placeholder="Enter Document Title" />
                    </div>

                    <div class="col-xxl-11 col-md-10 mb-2">
                        <label for="doc_file" class="form-label">Select File</label>
                        <input class="form-control" type="file" id="doc_file" multiple />
                    </div>

                    <div class="col-lg-1 d-flex align-items-end mb-2">
                        <div>
                            <button type="button" class="btn btn-success right add_doc_to_list"><i class="ri-add-line"></i></button>
                        </div>
                    </div>


                    <!-- Existing file display area -->
                    <div class="row existing-file align-items-center mb-3" style="display: none;">
                        <div class="col-md-10">
                            <div class="input-group">
                                <label class="input-group-text" for="existing_file_container"><strong>Existing File: </strong></label>
                                <input type="text" class="form-control text-primary" id="existing_file_container" readonly>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex">
                            <button type="button" class="btn btn-danger click_remove_existing_file"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>



                <div id="document-error-msg"></div>

                <hr>

                <div class="mt-4 mb-4 table-render">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Document Type</th>
                                <th>Document Title</th>
                                <th>Document</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="document_tbody">
                            <tr class="no-doc-row">
                                <td colspan="5" class="text-center">Not add any Documents...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div id="error-msg"></div>

            <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                <input type="hidden" id="document_id" value="">
                <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn w-sm btn-primary" id="document-submit-confirm">Submit</button>
            </div>
        </div>
    </div>
</div>


<script>

let userId = "{{ $user->user_id }}";
let dropdownData = [];
let filesArray = [];


    $(document).ready(async function(){
        await getDropdownData();
    });


    // Get user data when selecting user name
    $(document).on('change', '#userDropdown', async function () {
        userId = $(this).val();
        let userName = $('#userDropdown option:selected').text();
        $('#user_name').val(userName);

        // Enable button if user is selected
        if (userId) {
            $('#add_new_btn').prop('disabled', false);
        } else {
            $('#add_new_btn').prop('disabled', true);
        }


        if (userId === "") {
            $('#table_body').html('<tr><td colspan="8" class="text-center">Select Employee...</td></tr>');
            $('#employee_name').val('');
            $('#employee_id').val('');
        } else {
            await renderdocumentTable();
        }
    });


//=======================================================================================================
// Render table using user Id
//=======================================================================================================
    async function renderdocumentTable(){
        let list = '';

        const documents = await commonFetchData(`/employee/document/${userId}`);

        if(documents && documents.length > 0){
            documents.map((doc, i) => {
                list += `
                    <tr document_id="${doc.id}">
                        <td>${i + 1}</td>
                        <td>${doc.name}</td>
                        <td>${doc.title}</td>
                        <td>
                            ${doc.file ? `<a href="/employee/document/download/${doc.file}" class="text-primary" target="_blank">${doc.file}</a>` : 'No File'}
                        </td>
                        <td>
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            ${doc.file ? `
                                <button type="button" class="btn btn-success btn-sm click_download_doc" data-file="${doc.file}" title="Download Document">
                                    <i class="ri-download-2-line"></i>
                                </button>
                            ` : ''}
                            <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete">
                                <i class="ri-delete-bin-fill"></i>
                            </button>
                        </td>
                    </tr>
                `;
            })
        }else{
            list = '<tr><td colspan="8" class="text-center text-danger">No Documents Found!</td></tr>';
        }

        $('#table_body').html(list);
    }

//======================================================================================================
//Get dropdown data
//======================================================================================================

    async function getDropdownData() {
        try {
            let dropdownData = await commonFetchData('/employee/document/dropdown');

            if (dropdownData) {
            // Populate user name dropdown
            let userList = (dropdownData?.users || [])
                .map(user => `<option value="${user.user_id}">${user.first_name} ${user.last_name}</option>`)
                .join('');
            $('#userDropdown').html('<option value="">Select Employee</option>' + userList);

            // Check if a userId is already selected
            if (userId) {
                $('#userDropdown').val(userId); // Pre-select the dropdown value
                $('#user_name').val($('#userDropdown option:selected').text()); // Display name
                await renderdocumentTable(userId); // Render table for the selected user
                }
            } else {
                console.log('No users found');
            }

            // Populate doc type dropdown
            let docTypeList = (dropdownData?.doc_types || [])
                .map(doc_type => `<option value="${doc_type.id}">${doc_type.name}</option>`)
                .join('');
            $('#doc_type_id').html('<option value="">Select a doc type</option>' + docTypeList);

        } catch (error) {
            console.error('Error fetching dropdown data:', error);
        }
    }

//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

    $(document).on('click', '.click_delete', function() {
        const $row = $(this).closest('tr');
        const documentId = $row.attr('document_id');

            deleteItem(documentId, $row);
    });

    async function deleteItem(documentId, $row) {
        const url ='/employee/document/delete';
        const title ='Employee Document';
        try {
            const res = await commonDeleteFunction(documentId, url, title, $row);
            if(res){
                renderdocumentTable();
            }
        } catch (error) {
            console.error('Error deleting item:', error);
        }
    }

//=========================================================================================================
// Added document
//=========================================================================================================

    $(document).on('click', '.add_doc_to_list', function () {
        const doc_type_id = $('#doc_type_id').val();
        const doc_type_name = $('#doc_type_id option:selected').text();
        const doc_title = $('#doc_title').val().trim();
        const files = $('#doc_file')[0].files; // Get selected files

        let missingFields = [];

        if (!doc_type_id) missingFields.push('Document Type');
        if (!doc_title) missingFields.push('Document Title');
        if (files.length === 0) missingFields.push('File');

        if (missingFields.length > 0) {
            $('#document-error-msg').html(`
                <div class="alert alert-danger alert-dismissible">
                    <strong>Error!</strong> Please fill in the following fields: <strong>${missingFields.join(', ')}.</strong>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            return;
        } else {
            $('#document-error-msg').html('');
        }

        // Loop through each selected file
        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Add to filesArray
            filesArray.push({
                doc_type_id: doc_type_id,
                doc_title: doc_title,
                file: file,
                doc_type_name: doc_type_name,
            });

            // Extract the file name
            const fileName = file.name;

            // Remove the "Not any Document" row if it exists
            $('#document_tbody .no-doc-row').remove();

            $('#document_tbody').append(`
                <tr data-index="${filesArray.length - 1}">
                    <td>${filesArray.length}</td>
                    <td>${doc_type_name}</td>
                    <td>${doc_title}</td>
                    <td>${fileName}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm click_remove_document" title="Remove Document">
                            <i class="ri-delete-bin-fill"></i>
                        </button>
                    </td>
                </tr>
            `);
        }
        // Reset file input & fields
        $('#doc_type_id').val('');
        $('#doc_title').val('');
        $('#doc_file').val('');

    });

//===================================================================================================================
//Added documents remove & download
//===================================================================================================================

    // remove document
    $(document).on('click', '.click_remove_document', function () {
        const row = $(this).closest('tr');
        const index = row.data('index');

        // Remove document from array
        filesArray.splice(index, 1);
        row.remove();

        // Re-index table
        $('#document_tbody tr').each((i, tr) => {
            $(tr).attr('data-index', i);
            $(tr).find('td:first').text(i + 1);
        });

        // Show "Not any Document" row if empty
        if (filesArray.length === 0) {
            $('#document_tbody').html(`
                <tr class="no-doc-row">
                    <td colspan="5" class="text-center">Not any Document</td>
                </tr>
            `);
        }
    });


    //download document
    $(document).on('click', '.click_download_doc', function (e) {
        e.preventDefault();

        let fileName = $(this).data('file');
        if (!fileName) {
            alert('No file found.');
            return;
        }

        let downloadUrl = `/employee/document/download/${fileName}`;
        window.location.href = downloadUrl;
    });


//==================================================================================================
// ADD & EDIT FUNCTION
//==================================================================================================

    // Add document
    $(document).on('click', '#add_new_btn', function () {
        resetForm();
        title = `Add Documents`;
        $('.modal-title').html(title);

        // Show add document table and button in add mode
        $('.table-render').show();
        $('.add_doc_to_list').closest('div').show();

        // Hide existing file section when adding a new document
        $('.existing-file').hide();
        $('.click_remove_existing_file').hide();

        // Enable file input in case no existing file
        $('#doc_file').prop('disabled', false);
        $('#doc_file').closest('div').removeClass('col-md-12').addClass('col-xxl-11 col-md-10');

        $('#document_form_modal').modal('show');
    });


    // Edit document
    $(document).on('click', '.click_edit', async function () {
        resetForm();
        title = `Edit document`;
        $('.modal-title').html(title);

        let document_id = $(this).closest('tr').attr('document_id');

        if (!document_id) {
            console.error("Invalid document_id");
            return;
        }

        try {
            let doc_data = await commonFetchData(`/employee/document/single/${document_id}`);

            if (!doc_data || doc_data === 0) {
                throw new Error("No document data received");
            }

            doc_data = doc_data[0];
            console.log('document_data', doc_data);

            // Set form values with fetched data
            $('#document_id').val(document_id);
            $('#doc_type_id').val(doc_data?.doc_type_id || '');
            $('#doc_title').val(doc_data?.title || '');

            if (doc_data?.file) {
                $('#existing_file_container').val(doc_data.file);
                $('.existing-file').show(); // Show the existing file row
                $('.click_remove_existing_file').show();

                // Disable doc_file input and set full width
                $('#doc_file').prop('disabled', true);
                $('#doc_file').closest('div').removeClass('col-xxl-11 col-md-10').addClass('col-md-12');
            } else {
                $('#existing_file_container').val('');
                $('.existing-file').hide(); // Hide existing file row
                $('.click_remove_existing_file').hide();

                // Enable file input and reset width
                $('#doc_file').prop('disabled', false);
                $('#doc_file').closest('div').removeClass('col-md-12').addClass('col-xxl-11 col-md-10');
            }

            // Hide add document table and button in edit mode
            $('.table-render').hide();
            $('.add_doc_to_list').closest('div').hide();

        } catch (error) {
            console.error('Error at getSingleEmployeeDocument:', error);
            $('#error-msg').html('<p class="text-danger">Error fetching user document data. Please try again.</p>');
        } finally {
            $('#document_form_modal').modal('show');
        }
    });


    // Remove existing file
    $(document).on('click', '.click_remove_existing_file', function () {
        $('#doc_file').prop('disabled', false);
        $('#existing_file_container').val('');
        $('#doc_file').val(''); // Clear file input
    });



    // Submit documents (Add & Edit)
    $(document).on('click', '#document-submit-confirm', async function () {
        const document_id = $('#document_id').val();
        const isUpdating = Boolean(document_id);
        const url = isUpdating ? `/employee/document/update/${document_id}` : `/employee/document/create`;
        const method = isUpdating ? 'PUT' : 'POST';

        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('doc_type_id', $('#doc_type_id').val());
        formData.append('doc_title', $('#doc_title').val());

        // HANDLE CREATE (MULTIPLE FILE UPLOADS)
        if (!isUpdating) {
            if (filesArray.length === 0) {
                $('#error-msg').html(`
                    <div class="alert alert-danger alert-dismissible">
                        <strong>Error!</strong> Please add at least one document before submitting.
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                return;
            }

            // Append multiple files for create
            filesArray.forEach((doc, index) => {
                formData.append(`doc_file[${index}]`, doc.file);
                formData.append(`doc_type_id[${index}]`, doc.doc_type_id);
                formData.append(`doc_title[${index}]`, doc.doc_title);
            });

        } else {
            // HANDLE UPDATE (SINGLE FILE UPDATE ALLOWED)
            let fileInput = $('#doc_file')[0].files[0];

            if (fileInput) {
                formData.append('doc_file', fileInput); // New file provided
            } else {
                formData.append('existing_file', 'keep'); // No new file, keep old file
            }
        }

        try {
            let res = await commonSaveData(url, formData, method);
            console.log('Response:', res);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                renderdocumentTable();
                $('#document_form_modal').modal('hide');
            } else {
                $('#error-msg').html(`<p class="text-danger">${res.message}</p>`);
            }
        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });



    // Reset Function
    function resetForm() {
        $('#document_id').val('');
        $('#doc_type_id').val('');
        $('#doc_title').val('');
        $('#doc_file').val('');
        $('#existing_file_container').val('');
        $('#document-error-msg').html('');
        $('#error-msg').html('');

        filesArray = [];

        // Reset document body
        $('#document_tbody').html(`
            <tr class="no-doc-row">
                <td colspan="5" class="text-center">No Documents Added</td>
            </tr>
        `);
    }



</script>

</x-app-layout>
