<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Schedule Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_schedule_click">New Schedule Policy<i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Meal Policy</th>
                                <th class="col">Absence Policy</th>
                                <th class="col">Window</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="schd_pol_table_body">
                            <tr><td colspan="6" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="schedule-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="schedule-form-title">Add Schedule Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="schedule-form-body">
                        <div class="row mb-3">
                            <label for="schedule_name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="schedule_name" placeholder="Enter Name" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="meal_policy_id" class="form-label mb-1 col-md-3">Meal Policy</label>
                            <div class="col-md-9">
                                <select class="form-select" id="meal_policy_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="break_policy_ids" class="form-label mb-1 col-md-3">Break Policies</label>
                            <div class="col-md-9">
                                <select class="form-select" id="break_policy_ids" multiple>
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="absence_policy_id" class="form-label mb-1 col-md-3">Undertime Absence Policy</label>
                            <div class="col-md-9">
                                <select class="form-select" id="absence_policy_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="overtime_policy_id" class="form-label mb-1 col-md-3">Overtime Policy</label>
                            <div class="col-md-9">
                                <select class="form-select" id="overtime_policy_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="start_stop_window" class="form-label mb-1 col-md-3">Start / Stop Window</label>
                            <div class="col-md-9 d-flex align-items-center">
                                <input type="text" class="form-control numonly w-75" id="start_stop_window" value="01:00">
                                <span class="ps-4">(hh:mm (2:15))</span>
                            </div>
                        </div>
                    </div>                    
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="schedule_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="schedule-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllSchedules();
        })

        async function getAllSchedules(){
            try {
                const schedules = await commonFetchData('/policy/schedules');
                let list = '';
                if(schedules && schedules.length > 0){
                    schedules.map((schd, i) => {
                        let interval = convertSecondsToHoursAndMinutes(schd.start_window);
                        list += `
                            <tr schedule_policy_id="${schd.id}">
                                <td>${i+1}</td>    
                                <td>${schd.name}</td>    
                                <td>${schd.meal_policy}</td>    
                                <td>${schd.absence_policy}</td>    
                                <td>${interval}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_round_pol" title="Edit Schedule Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_round_pol" title="Delete Schedule Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="6" class="text-center">No Schedule Policies Found!</td></tr>`;
                }

                $('#schd_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->schedule->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_delete_round_pol', async function(){
            let schd_pol_id = $(this).closest('tr').attr('schedule_policy_id');

            try {
                let url = `/policy/schedule/delete`;
                const res = await commonDeleteFunction(schd_pol_id, url, 'Schedule Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during schedule policy deletion:`, error);
            }
        })


    </script>

    <script>
        $(document).ready(function(){
            getDropdownData();
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/schedule/dropdown');

                // Populate meal policy dropdown
                let mealPolicyList = (dropdownData?.absence_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#meal_policy_id').html('<option value="">--</option>' + mealPolicyList);

                // Populate break policy dropdown
                let breakPolicyList = (dropdownData?.break_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#break_policy_ids').html('<option value="">--</option>' + breakPolicyList);

                // Populate absence policy dropdown
                let absencePolicyList = (dropdownData?.meal_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#absence_policy_id').html('<option value="">--</option>' + absencePolicyList);

                // Populate overtime policy dropdown
                let otPolicyList = (dropdownData?.overtime_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#overtime_policy_id').html('<option value="">--</option>' + otPolicyList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('click', '#new_schedule_click', function(){
            resetForm();
            $('#schedule-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_round_pol', function(){
            resetForm();
            let schedule_policy_id = $(this).closest('tr').attr('schedule_policy_id');
            $('#schedule-form-modal').modal('show');
        })

        function resetForm(){
            $('#round_type_id').val('');
            $('#interval_time').val('');
            $('#grace_period').val('');
            $('#strict_schedule').val('');
            $('#schedule_id').val('');
        }
    </script>

</x-app-layout>