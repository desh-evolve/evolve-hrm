<!-- desh(2024-10-18) -->
<x-app-layout :title="'Input Example'">
   
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Branch Management') }}</h4>

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
        .card-header:hover {
            background-color: #ddd;
        }
    </style>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Branches</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_branch_btn">Add New Branch <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="branch_list">

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Departments</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New Department <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body" id="department_list">

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Divisions</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_btn">Add New Division <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-nowrap">
                        <thead id="table_head">
                            
                        </thead>
                        <tbody id="table_body">
                            
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="branch-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="branch-form-title">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="branch-form-body" class="row">


                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="branch-submit-confirm">Submit</button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        
        $(document).ready(function(){
            renderBranchTable();
        })

        async function renderBranchTable(){
            let list = '';

            let items = await commonFetchData('/company/branches');
            
            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary cursor-pointer branch_click" branch_id="${item.id}">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0">${item.branch_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button class="btn btn-success btn-sm align-middle minimize-card collapsed" data-bs-toggle="collapse" href="#collapse_${item.id}" role="button" aria-expanded="false" aria-controls="collapse_${item.id}" title="Expand Branch" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="mdi mdi-arrow-down align-middle plus"></i>
                                                        <i class="mdi mdi-arrow-up align-middle minus"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_branch" title="Edit Branch" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_branch" title="Delete Branch" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse" id="collapse_${item.id}" style="">
                                    <p class="card-text mb-0">${item.address_1}, ${item.address_2}</p>
                                    <p class="card-text mb-0">${item.city_name}, ${item.province_name}, ${item.country_name}</p>
                                    <p class="card-text mb-0">${item.contact_1}/${item.contact_2}</p>
                                    <p class="card-text">${item.email}</p>
                                </div>
                            </div>
                    `;
                })
            }else{
                list = `
                    <p class="text-danger">No Active Branches!</p>
                `;
            }

            $('#branch_list').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        }

        $(document).on('click', '.branch_click', async function(){
            let list = '';
            let branch_id = $(this).attr('branch_id');
            $('#department_list').html('Loading...');

            let items = await commonFetchData(`/company/departments/${branch_id}`);

            if(items && items.length > 0){
                items.map((item, i)=>{
                    list += `
                            <div class="card border card-border-primary cursor-pointer department_click" department_id="${item.department_id}">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-0">${item.department_name}</h6>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_department" title="Edit department" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_department" title="Delete department" data-tooltip="tooltip" data-bs-placement="top">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `;
                })
            }else{
                list = `
                    <p class="text-danger">No departments for this branch yet!</p>
                `;
            }

            $('#department_list').html(list);
            $('[data-tooltip="tooltip"]').tooltip();
        })


    </script>
</x-app-layout>