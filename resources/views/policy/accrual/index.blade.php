<!-- desh(2024-11-12) -->
<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Accrual Policy List</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" href="/policy/accrual/form">New Accrual Policy <i class="ri-add-line"></i></a>
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
                        <tbody id="accr_pol_table_body">
                            <tr><td colspan="4" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllAccruals();
        })

        async function getAllAccruals(){
            try {
                const accruals = await commonFetchData('/policy/accruals');
                let list = '';
                let showWarning = false;
                if(accruals && accruals.length > 0){
                    accruals.map((accr, i) => {
                        showWarning = showWarning ? true : accr.policy_groups.length === 0 ? true : false;
                        list += `
                            <tr accrual_policy_control_id="${accr.id}" class="${accr.policy_groups.length > 0 ? '' : 'bg-warning'}">
                                <td>${i+1}</td>
                                <td>${accr.name}</td>
                                <td>${accr.type == 'standard' ? 'Standard' : accr.type == 'calendar_based' ? 'Calendar Based' : 'Hour Based'}</td>
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_accr_pol" title="Edit Accrual Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_accr_pol" title="Delete Accrual Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="5" class="text-center">No Accrual Policies Found!</td></tr>`;
                }

                if(showWarning){
                    $('#check_unassigned_policies').show();
                }else{
                    $('#check_unassigned_policies').hide();
                }

                $('#accr_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->accrual->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_edit_accr_pol', function(){
            let accr_pol_id = $(this).closest('tr').attr('accrual_policy_control_id');
            let title = `Edit Accrual Policy`;

            localStorage.setItem('editTitle', title);

            window.location.href = '/policy/accrual/form?id='+accr_pol_id;
        });


        $(document).on('click', '.click_delete_accr_pol', async function(){
            let accr_pol_id = $(this).closest('tr').attr('accrual_policy_control_id');

            try {
                let url = `/policy/accrual/delete`;
                const res = await commonDeleteFunction(accr_pol_id, url, 'Accrual Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during accrual policy deletion:`, error);
            }
        });


    </script>

</x-app-layout>
