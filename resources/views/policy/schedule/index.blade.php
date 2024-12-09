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
                        <thead class="bg-primary text-white"/>
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
                        <button type="button" class="btn w-sm btn-primary" id="form_submit">Submit</button>
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
                let mealPolicyList = (dropdownData?.meal_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#meal_policy_id').html('<option value="0">--</option>' + mealPolicyList);

                // Populate break policy dropdown
                let breakPolicyList = (dropdownData?.break_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#break_policy_ids').html('<option value="0">--</option>' + breakPolicyList);

                // Populate absence policy dropdown
                let absencePolicyList = (dropdownData?.absence_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#absence_policy_id').html('<option value="0">--</option>' + absencePolicyList);

                // Populate overtime policy dropdown
                let otPolicyList = (dropdownData?.overtime_policy || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#overtime_policy_id').html('<option value="0">--</option>' + otPolicyList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('click', '#new_schedule_click', function(){
            resetForm();
            $('#schedule-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_round_pol', async function(){
            resetForm();
            let schedule_policy_id = $(this).closest('tr').attr('schedule_policy_id');
            $('#schedule_id').val(schedule_policy_id);

            try {
                let response = await commonFetchData(`/policy/schedule/${schedule_policy_id}`);
                let data = response[0];
                //console.log('data', data)
                let start_stop_window = convertSecondsToHoursAndMinutes(data?.start_stop_window || 0);
                $('#schedule_name').val(data?.name || '');
                $('#absence_policy_id').val(data?.absence_policy_id || '0');
                $('#overtime_policy_id').val(data?.over_time_policy_id || '0');
                $('#start_stop_window').val(start_stop_window);
                $('#meal_policy_id').val(data?.meal_policy_id || '0');

               // Extract break_policy_ids from the array
                const breakPolicyIds = data?.break_policies?.map(policy => policy.break_policy_id) || [];
                $('#break_policy_ids').val(breakPolicyIds);

            }catch(error){
                console.error('error at schedule index: ', error)
            }


            $('#schedule-form-modal').modal('show');
        })

        $(document).on('click', '#form_submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            let formData = new FormData();
            let start_stop_window = $('#start_stop_window').val();
            let start_stop_window_seconds = convertHoursAndMinutesToSeconds(start_stop_window);

            formData.append('schedule_name', $('#schedule_name').val());
            formData.append('meal_policy_id', $('#meal_policy_id').val());
            formData.append('break_policy_ids', $('#break_policy_ids').val());
            formData.append('absence_policy_id', $('#absence_policy_id').val());
            formData.append('overtime_policy_id', $('#overtime_policy_id').val());
            formData.append('start_stop_window', start_stop_window_seconds);

            let schedule_id = $('#schedule_id').val();
            let createUrl = `/policy/schedule/create`;
            let updateUrl = `/policy/schedule/update/${schedule_id}`;

            const isUpdating = Boolean(schedule_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('schedule_id', schedule_id);
                method = 'PUT';
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    $('#schedule-form-modal').modal('hide');
                    getAllSchedules();
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }

        });


        function resetForm(){
            $('#schedule_name').val('');
            $('#meal_policy_id').val('0').trigger('change');
            $('#break_policy_ids').val([]).trigger('change');
            $('#absence_policy_id').val('0').trigger('change');
            $('#overtime_policy_id').val('0').trigger('change');
            $('#start_stop_window').val('01:00');
            $('#schedule_id').val('');
        }
    </script>

</x-app-layout>