<!-- desh(2024-11-12) -->
<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Premium Policy List</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/policy/premium/form">New Premium Policy <i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Type</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="prem_pol_table_body">
                            <tr><td colspan="4" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllPremiums();
        })

        async function getAllPremiums(){
            try {
                const premiums = await commonFetchData('/policy/premiums');
                let list = '';
                if(premiums && premiums.length > 0){
                    premiums.map((prem, i) => {
                        list += `
                            <tr premium_policy_control_id="${prem.id}">
                                <td>${i+1}</td>    
                                <td>${prem.name}</td>    
                                <td>${prem.type == 'standard' ? 'Standard' : prem.type == 'calendar_based' ? 'Calendar Based' : 'Hour Based'}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_prem_pol" title="Edit Premium Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_prem_pol" title="Delete Premium Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Premium Policies Found!</td></tr>`;
                }

                $('#prem_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->premium->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_edit_prem_pol', function(){
            let prem_pol_id = $(this).closest('tr').attr('premium_policy_control_id');
            
            window.location.href = '/policy/premium/form?id='+prem_pol_id;
        })

        $(document).on('click', '.click_delete_prem_pol', async function(){
            let prem_pol_id = $(this).closest('tr').attr('premium_policy_control_id');

            try {
                let url = `/policy/premium/delete`;
                const res = await commonDeleteFunction(prem_pol_id, url, 'Premium Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during premium policy deletion:`, error);
            }
        })


    </script>

</x-app-layout>