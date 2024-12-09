<!-- desh(2024-11-12) -->
<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Holiday Policy List</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/policy/holiday/form">New Holiday Policy <i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- warning Alert -->
                    <div class="alert bg-warning border-warning text-white material-shadow" role="alert" id="check_unassigned_policies">
                        <strong> Policies highlighted in yellow may not be active yet because they are not assigned to a <u><a href="/policy/policy_group">Policy Group</a></u>. </strong>
                    </div>
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Type</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="holi_pol_table_body">
                            <tr><td colspan="4" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllHolidays();
        })

        async function getAllHolidays(){
            try {
                const holidays = await commonFetchData('/policy/holidays');
                let list = '';
                let showWarning = false;
                if(holidays && holidays.length > 0){
                    holidays.map((holi, i) => {
                        showWarning = showWarning ? true : holi.policy_groups.length === 0 ? true : false;
                        list += `
                            <tr holiday_policy_control_id="${holi.id}" class="${holi.policy_groups.length > 0 ? '' : 'bg-warning'}">
                                <td>${i+1}</td>    
                                <td>${holi.name}</td>    
                                <td>${holi.type == 'standard' ? 'Standard' : holi.type == 'advanced_fixed' ? 'Advanced - Fixed' : 'Advanced - Average'}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_holi_pol" title="Edit Holiday Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_holi_pol" title="Delete Holiday Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Holiday Policies Found!</td></tr>`;
                }

                if(showWarning){
                    $('#check_unassigned_policies').show();
                }else{
                    $('#check_unassigned_policies').hide();
                }

                $('#holi_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->holiday->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_edit_holi_pol', function(){
            let holi_pol_id = $(this).closest('tr').attr('holiday_policy_control_id');
            
            window.location.href = '/policy/holiday/form?id='+holi_pol_id;
        })

        $(document).on('click', '.click_delete_holi_pol', async function(){
            let holi_pol_id = $(this).closest('tr').attr('holiday_policy_control_id');

            try {
                let url = `/policy/holiday/delete`;
                const res = await commonDeleteFunction(holi_pol_id, url, 'Holiday Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during holiday policy deletion:`, error);
            }
        })


    </script>

</x-app-layout>