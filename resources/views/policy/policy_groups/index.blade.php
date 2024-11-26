<!-- desh(2024-11-12) -->
<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Policy Groups List</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/policy/policy_group/form">New Policy Group<i class="ri-add-line"></i></a>
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
                        <tbody id="policy_group_table_body">
                            <tr><td colspan="3" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllPolicyGroups();
        })

        async function getAllPolicyGroups(){
            try {
                const policy_groups = await commonFetchData('/policy/policy_groups');
                let list = '';
                if(policy_groups && policy_groups.length > 0){
                    policy_groups.map((pol_grp, i) => {
                        list += `
                            <tr policy_group_id="${pol_grp.id}">
                                <td>${i+1}</td>    
                                <td>${pol_grp.name}</td>   
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_pol_grp" title="Edit Policy Group" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_pol_grp" title="Delete Policy Group" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Policy Groups Found!</td></tr>`;
                }

                $('#policy_group_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->policy_group->index->getAllPolicyGroups: ', error);
            }
        }

        $(document).on('click', '.click_edit_pol_grp', function(){
            let policy_group_id = $(this).closest('tr').attr('policy_group_id');
            
            window.location.href = '/policy/policy_group/form?id='+policy_group_id;
        })

        $(document).on('click', '.click_delete_pol_grp', async function(){
            let policy_group_id = $(this).closest('tr').attr('policy_group_id');

            try {
                let url = `/policy/policy_group/delete`;
                const res = await commonDeleteFunction(policy_group_id, url, 'Policy Group');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during policy_group deletion:`, error);
            }
        })


    </script>

</x-app-layout>