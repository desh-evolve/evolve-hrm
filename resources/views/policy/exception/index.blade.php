<!-- desh(2024-11-12) -->
<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Exception Policy List</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/policy/exception/form">New Exception <i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ex_pol_table_body">
                            <tr><td colspan="3" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllExceptions();
        })

        async function getAllExceptions(){
            try {
                const exceptions = await commonFetchData('/policy/exceptions');
                let list = '';
                if(exceptions && exceptions.length > 0){
                    exceptions.map((ex, i) => {
                        list += `
                            <tr exception_policy_control_id="${ex.id}">
                                <td>${i+1}</td>    
                                <td>${ex.name}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_exc_pol" title="Edit Exception Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_exc_pol" title="Delete Exception Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Exception Policies Found!</td></tr>`;
                }

                $('#ex_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->exception->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_edit_exc_pol', function(){
            let ex_pol_id = $(this).closest('tr').attr('exception_policy_control_id');
            
            window.location.href = '/policy/exception/form?id='+ex_pol_id;
        })

        $(document).on('click', '.click_delete_exc_pol', async function(){
            let ex_pol_id = $(this).closest('tr').attr('exception_policy_control_id');

            try {
                let url = `/policy/exception/delete`;
                const res = await commonDeleteFunction(ex_pol_id, url, 'Exception Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during exception policy deletion:`, error);
            }
        })


    </script>

</x-app-layout>