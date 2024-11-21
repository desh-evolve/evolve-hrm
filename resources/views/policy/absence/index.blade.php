<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Absence Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_absence_click">New Absence Policy<i class="ri-add-line"></i></button>
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
                        <tbody id="ex_pol_table_body">
                            <tr><td colspan="4" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="absence-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="absence-form-title">Add Absence Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="absence-form-body">
                        <div class="row mb-3">
                            <label for="name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type_id" class="form-label mb-1 col-md-3">Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="type_id">
                                    <option value="paid">Paid</option>
                                    <option value="paid_above_salary">Paid (above salary)</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="dock">Dock</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="rate" class="form-label mb-1 col-md-3">Rate</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="rate" placeholder="Select Rate (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="wage_group_id" class="form-label mb-1 col-md-3">Wage Group</label>
                            <div class="col-md-9">
                                <select class="form-select" id="wage_group_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="pay_stub_entry_account_id" class="form-label mb-1 col-md-3">Pay Stub Account</label>
                            <div class="col-md-9">
                                <select class="form-select" id="pay_stub_entry_account_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="accrual_policy_id" class="form-label mb-1 col-md-3">Accrual Policy</label>
                            <div class="col-md-9">
                                <select class="form-select" id="accrual_policy_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="accrual_rate" class="form-label mb-1 col-md-3">Accrual Rate</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="accrual_rate" placeholder="Select Accrual Rate (hh:mm)">
                            </div>
                        </div>
                    </div>                    
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="absence_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="absence-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllAbsences();
        })

        async function getAllAbsences(){
            try {
                const absences = await commonFetchData('/policy/absences');
                let list = '';
                if(absences && absences.length > 0){
                    absences.map((ab, i) => {
                        let time = convertSecondsToHoursAndMinutes(ab.trigger_time);
                        list += `
                            <tr absence_policy_control_id="${ab.id}">
                                <td>${i+1}</td>    
                                <td>${ab.name}</td>    
                                <td>${ab.type == 'paid' ? 'Paid' : ab.type == 'unpaid' ? 'Unpaid' : ab.type == 'dock' ? 'Dock' : 'Paid (above salary)'}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_ab_pol" title="Edit Absence Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_ab_pol" title="Delete Absence Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="4" class="text-center">No Absence Policies Found!</td></tr>`;
                }

                $('#ex_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->absence->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_edit_ab_pol', function(){
            let ex_pol_id = $(this).closest('tr').attr('absence_policy_control_id');
            
            window.location.href = '/policy/absence/form?id='+ex_pol_id;
        })

        $(document).on('click', '.click_delete_ab_pol', async function(){
            let ex_pol_id = $(this).closest('tr').attr('absence_policy_control_id');

            try {
                let url = `/policy/absence/delete`;
                const res = await commonDeleteFunction(ex_pol_id, url, 'Absence Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during absence policy deletion:`, error);
            }
        })


    </script>

    <script>
        $(document).ready(function(){
            getDropdownData();
            $('#accrual_rate').closest('.row').hide();
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/absence/dropdown');

                // Populate wage groups dropdown
                let wageGroupsList = (dropdownData?.wage_groups || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#wage_group_id').html('<option value="0">--Default--</option>' + wageGroupsList);

                // Populate pay stub accounts dropdown
                let payStubsList = (dropdownData?.pay_stubs || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#pay_stub_entry_account_id').html('<option value="0">--</option>' + payStubsList);

                // Populate accrual policies dropdown
                let accrualPolicies = (dropdownData?.accrual_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#accrual_policy_id').html('<option value="0">--None--</option>' + accrualPolicies);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('change', '#accrual_policy_id', function(){
            if($(this).val() == '0'){
                $('#accrual_rate').closest('.row').hide();
            }else{
                $('#accrual_rate').closest('.row').show();
            }
        })

        $(document).on('click', '#new_absence_click', function(){
            resetForm();
            $('#absence-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_round_pol', function(){
            resetForm();
            let absence_policy_id = $(this).closest('tr').attr('absence_policy_id');
            $('#absence-form-modal').modal('show');
        })

        function resetForm(){
            $('#round_type_id').val('');
            $('#interval_time').val('');
            $('#grace_period').val('');
            $('#strict_schedule').val('');
            $('#absence_id').val('');
        }
    </script>

</x-app-layout>