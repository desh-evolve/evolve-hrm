<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Meal Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_meal_click">New Meal Policy<i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- warning Alert -->
                    <div class="alert bg-warning border-warning text-white material-shadow" role="alert" id="check_unassigned_policies">
                        <strong> Policies highlighted in yellow may not be active yet because they are not assigned to a <u><a href="/policy/policy_group">Policy Group</a></u>. </strong>
                    </div>
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white"/>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Type</th>
                                <th class="col">Meal Time</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="meal_pol_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="meal-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="meal-form-title">Add Meal Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="meal-form-body">
                        <div class="row mb-3">
                            <label for="name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type" class="form-label mb-1 col-md-3">Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="type">
                                    <option value="auto_deduct">Auto Deduct</option>
                                    <option value="auto_add">Auto Add</option>
                                    <option value="normal">Normal</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="trigger_time" class="form-label mb-1 col-md-3">Active After</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="trigger_time" placeholder="Select Active After (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="amount" class="form-label mb-1 col-md-3">Deduction/Addition Time</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="amount" placeholder="Select Deduction/Addition Time (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="auto_detect_type" class="form-label mb-1 col-md-3">Auto-Detect Meals By</label>
                            <div class="col-md-9">
                                <select class="form-select" id="auto_detect_type">
                                    <option value="time_window">Time Window</option>
                                    <option value="punch_time">Punch Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="time_window_section">
                            <div class="row mb-3">
                                <label for="start_window" class="form-label mb-1 col-md-3">Start Window</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="start_window" placeholder="Select Start Window (hh:mm)">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="window_length" class="form-label mb-1 col-md-3">Window Length</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="window_length" placeholder="Select Window Length (hh:mm)">
                                </div>
                            </div>
                        </div>
                        <div class="punch_time_section">
                            <div class="row mb-3">
                                <label for="minimum_punch_time" class="form-label mb-1 col-md-3">Minimum Punch Time</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="minimum_punch_time" placeholder="Select Minimum Punch Time (hh:mm)">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="maximum_punch_time" class="form-label mb-1 col-md-3">Maximum Punch Time</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="maximum_punch_time" placeholder="Select Maximum Punch Time (hh:mm)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="include_lunch_punch_time" class="form-label mb-1 col-md-3">Include Any Punched Time for Lunch</label>
                            <div class="col-md-9">
                                <input type="checkbox" class="form-check-input" id="include_lunch_punch_time">
                            </div>
                        </div>
                    </div>                    
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="meal_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="form_submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllMeals();

            $('.time_window_section').show();
            $('.punch_time_section').hide();
        })

        async function getAllMeals(){
            try {
                const meals = await commonFetchData('/policy/meals');
                let list = '';
                let showWarning = false;
                if(meals && meals.length > 0){
                    meals.map((meal, i) => {
                        showWarning = showWarning ? true : meal.policy_groups.length === 0 ? true : false;
                        let interval = convertSecondsToHoursAndMinutes(meal.window_length);
                        list += `
                            <tr meal_policy_id="${meal.id}" class="${meal.policy_groups.length > 0 ? '' : 'bg-warning'}">
                                <td>${i+1}</td>    
                                <td>${meal.name}</td>    
                                <td>${meal.type == 'auto_deduct' ? 'Auto Deduct' : meal.type == 'auto_add' ? 'Auto Add' : 'Normal'}</td>    
                                <td>${interval}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_meal_pol" title="Edit Meal Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_meal_pol" title="Delete Meal Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Meal Policies Found!</td></tr>`;
                }
                
                if(showWarning){
                    $('#check_unassigned_policies').show();
                }else{
                    $('#check_unassigned_policies').hide();
                }

                $('#meal_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->meal->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_delete_meal_pol', async function(){
            let meal_pol_id = $(this).closest('tr').attr('meal_policy_id');

            try {
                let url = `/policy/meal/delete`;
                const res = await commonDeleteFunction(meal_pol_id, url, 'Meal Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during meal policy deletion:`, error);
            }
        })

        $(document).on('change', '#type', function(){
            if($(this).val() == 'normal'){
                $('#include_lunch_punch_time').closest('.row').hide();
                $('label[for="amount"]').text('Meal Time');
            } else {
                $('#include_lunch_punch_time').closest('.row').show();
                $('label[for="amount"]').text('Deduction/Addition Time');
            }
        })

        $(document).on('change', '#auto_detect_type', function() {
            if ($(this).val() == 'time_window') {
                $('.time_window_section').show();
                $('.punch_time_section').hide();
            }else{
                $('.time_window_section').hide();
                $('.punch_time_section').show();
            }
        });

    </script>

    <script>

        $(document).on('click', '#new_meal_click', function(){
            resetForm();
            $('#meal-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_meal_pol', async function(){
            resetForm();
            let meal_policy_id = $(this).closest('tr').attr('meal_policy_id');

            $('#meal_id').val(meal_policy_id); // Set the ID in the hidden field

            try {
                // Fetch the meal policy data
                let response = await commonFetchData(`/policy/meal/${meal_policy_id}`);
                let data = response[0]; // Extract the first object

                if (data) {
                    let name = data.name;
                    let type = data.type;
                    let auto_detect_type = data.auto_detect_type;
                    let include_lunch_punch_time = data.include_lunch_punch_time;
                    let trigger_time = convertSecondsToHoursAndMinutes(data.trigger_time || 0);
                    let amount = convertSecondsToHoursAndMinutes(data.amount || 0);
                    let start_window = convertSecondsToHoursAndMinutes(data.start_window || 0);
                    let window_length = convertSecondsToHoursAndMinutes(data.window_length || 0);
                    let minimum_punch_time = convertSecondsToHoursAndMinutes(data.minimum_punch_time || 0);
                    let maximum_punch_time = convertSecondsToHoursAndMinutes(data.maximum_punch_time || 0);

                    // Populate form fields
                    $('#name').val(data.name || '');
                    $('#type').val(data.type || '0').trigger('change');
                    $('#auto_detect_type').val(data.auto_detect_type || '').trigger('change');
                    $('#strict_schedule').prop('checked', include_lunch_punch_time === 1);
                    $('#trigger_time').val(trigger_time);
                    $('#amount').val(amount);
                    $('#start_window').val(start_window);
                    $('#window_length').val(window_length);
                    $('#minimum_punch_time').val(minimum_punch_time);
                    $('#maximum_punch_time').val(maximum_punch_time);
                }
            } catch (error) {
                console.error('Error while fetching meal policy data:', error);
                $('#error-msg').html('<p class="text-danger">Failed to load data. Please try again.</p>');
            }

            $('#meal-form-modal').modal('show');
        })

        $(document).on('click', '#form_submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let meal_id = $('#meal_id').val();
            let name = $('#name').val();
            let type = $('#type').val();
            let auto_detect_type = $('#auto_detect_type').val();
            let include_lunch_punch_time = $('#include_lunch_punch_time').is(':checked') ? 1 : 0;
            let trigger_time = convertHoursAndMinutesToSeconds($('#trigger_time').val() || '0:00');
            let amount = convertHoursAndMinutesToSeconds($('#amount').val() || '0:00');
            let start_window = convertHoursAndMinutesToSeconds($('#start_window').val() || '0:00');
            let window_length = convertHoursAndMinutesToSeconds($('#window_length').val() || '0:00');
            let minimum_punch_time = convertHoursAndMinutesToSeconds($('#minimum_punch_time').val() || '0:00');
            let maximum_punch_time = convertHoursAndMinutesToSeconds($('#maximum_punch_time').val() || '0:00');

            formData.append('name', name);
            formData.append('type', type);
            formData.append('auto_detect_type', auto_detect_type);
            formData.append('include_lunch_punch_time', include_lunch_punch_time);
            formData.append('trigger_time', trigger_time);
            formData.append('amount', amount);
            formData.append('start_window', start_window);
            formData.append('window_length', window_length);
            formData.append('minimum_punch_time', minimum_punch_time);
            formData.append('maximum_punch_time', maximum_punch_time);
            
            let createUrl = `/policy/meal/create`;
            let updateUrl = `/policy/meal/update/${meal_id}`;

            const isUpdating = Boolean(meal_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', meal_id);
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    $('#meal-form-modal').modal('hide');
                    getAllMeals(); // Refresh the list of meals
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm(){
            $('#name').val('');
            $('#type').val('auto_deduct').trigger('change');
            $('#trigger_time').val('');
            $('#amount').val('');
            $('#auto_detect_type').val('time_window').trigger('change');
            $('#start_window').val('');
            $('#window_length').val('');
            $('#minimum_punch_time').val('');
            $('#maximum_punch_time').val('');
            $('#include_lunch_punch_time').prop('checked', false);
            $('#meal_id').val('');
        }
    </script>

</x-app-layout>