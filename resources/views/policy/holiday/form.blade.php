<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8{
            width: 8% !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Add Holiday Policy</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="name" placeholder="Enter Name" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="type">
                                        <option value="standard">Standard</option>
                                        <option value="advanced_fixed">Advaned - Fixed</option>
                                        <option value="advanced_average">Advanced - Average</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="default_schedule_status" class="form-label mb-1 col-md-3">Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="default_schedule_status">
                                        <option value="working">Working</option>
                                        <option value="absent">Absent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="holiday_eligibility_section">
                            <u><h5 class="bg-primary text-white">Holiday Eligibility</h5></u>
                            <div class="row mb-3">
                                <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Employed Days</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control w-50 numonly" id="minimum_employed_days" value="30">
                                    <span class="ps-4"></span>
                                </div>
                            </div>
                            <div id="advanced_holiday_eligibility_section">
                                <div class="row mb-3">
                                    <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Employee Must Work at Least</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-25" id="minimum_employed_days" value="15">
                                        <span class="ms-3 me-3 w-8">of the</span>
                                        <input type="text" class="form-control numonly ms-3 me-3 w-25" id="minimum_employed_days" value="30">
                                        <select class="form-select w-25" id="type">
                                            <option value="calendar_days">Calendar Days</option>
                                            <option value="scheduled_days">Scheduled Days</option>
                                            <option value="holiday_week_days">Holiday Week Days</option>
                                        </select>
                                        <span class="ms-3 me-3 w-100">prior to the holiday.</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Employee Must Work at Least</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-25" id="minimum_employed_days" value="0">
                                        <span class="ms-3 me-3 w-8">of the</span>
                                        <input type="text" class="form-control numonly ms-3 me-3 w-25" id="minimum_employed_days" value="0">
                                        <select class="form-select w-25" id="type">
                                            <option value="calendar_days">Calendar Days</option>
                                            <option value="scheduled_days">Scheduled Days</option>
                                            <option value="holiday_week_days">Holiday Week Days</option>
                                        </select>
                                        <span class="ms-3 me-3 w-100">following the holiday.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="holiday_time_calc_section">
                            <u><h5 class="bg-primary text-white">Holiday Time Calculation</h5></u>

                            <div class="row mb-3" id="holiday_time_section">
                                <label for="time" class="form-label mb-1 col-md-3">Holiday Time</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="text" class="form-control numonly w-50" id="time" placeholder="hh:mm (2:15)" value="00:00">
                                    <span class="ps-4">hh:mm (2:15)</span>
                                </div>
                            </div>
                            <div id="avg_holiday_time_section">
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="rate" class="form-label mb-1 col-md-3">Total Time over</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-50" id="rate" value="30">
                                        <span class="ps-2">(days)</span>
                                    </div>
                                </div>
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Average Time over</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <span class="me-1 w-25">Worked Days Only</span>
                                        <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                        <span class="ms-3 me-3">or</span>
                                        <input type="text" class="form-control numonly w-25" id="minimum_employed_days" value="0">
                                        <span class="ms-3 w-100">days.</span>
                                    </div>
                                </div>
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Minimum Time</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                        <span class="ps-4">hh:mm (2:15) (Use 0 for no minimum)</span>
                                    </div>
                                </div>
                                <div class="row mb-3" id="min_emp_days_section">
                                    <label for="minimum_employed_days" class="form-label mb-1 col-md-3">Maximum Time</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <input type="text" class="form-control numonly w-50" id="minimum_employed_days" placeholder="hh:mm (2:15)" value="00:00">
                                        <span class="ps-4">hh:mm (2:15) (Use 0 for no maximum)</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="milestone_rollover_hire_date" class="form-label mb-1 col-md-3">Always Apply Over Time/Premium Policies</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="milestone_rollover_hire_date" class="form-label mb-1 col-md-3">Include Over Time in Average</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="milestone_rollover_hire_date" class="form-label mb-1 col-md-3">Include Paid Absence Time in Average</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" class="form-check-input" id="milestone_rollover_hire_date">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="round_interval_policy_id" class="form-label mb-1 col-md-3">Rounding Policy</label>
                                    <div class="col-md-9">
                                        <select class="form-select w-50" id="round_interval_policy_id">
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="absence_policy_id" class="form-label mb-1 col-md-3">Absence Policy</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="absence_policy_id">
                                        <!-- Add options dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="holidays_section">
                            <u><h5 class="bg-primary text-white">Add Holidays</h5></u>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="time" class="form-label mb-1 col-md-3">Holiday Name</label>
                                    <input type="text" class="form-control w-100" id="holiday_name" placeholder="Enter holiday name here">
                                </div>
                                <div class="col-md-3">
                                    <label for="time" class="form-label mb-1 col-md-3">Holiday Date</label>
                                    <input type="date" class="form-control w-100" id="holiday_date">
                                </div>
                                <div class="col-md-1 d-flex align-items-end ms-3">
                                    <button type="button" class="btn btn-primary" id="holiday_add"><i class="ri-add-line"></i></button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="holiday_tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            getDropdownData();
            showSections('standard');
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/holiday/dropdown');

                // Populate rounding policies dropdown
                let roundingPoliciesList = (dropdownData?.rounding_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#round_interval_policy_id').html('<option value="0">--</option>' + roundingPoliciesList);

                // Populate absence policies dropdown
                let absencePoliciesList = (dropdownData?.absence_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#absence_policy_id').html('<option value="0">--</option>' + absencePoliciesList);


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('change', '#type', function(){
            let type = $(this).val();
            showSections(type);
        })
        
        function showSections(type){
            if(type === 'standard'){
                $('#advanced_holiday_eligibility_section').hide();
                $('#avg_holiday_time_section').hide();
                $('#time').closest('.row').show();
            }else if(type === 'advanced_fixed'){
                $('#advanced_holiday_eligibility_section').show();
                $('#avg_holiday_time_section').hide();
                $('#time').closest('.row').show();
            }else if(type === 'advanced_average'){
                $('#advanced_holiday_eligibility_section').show();
                $('#avg_holiday_time_section').show();
                $('#time').closest('.row').hide();
            }else{
                console.error('wrong type selected');
            }
        }

        $(document).on('click', '#holiday_add', function(){
            let holiday_name = $('#holiday_name').val();
            let holiday_date = $('#holiday_date').val();
            let list = '';

            list = `
                <tr>
                    <td>${holiday_name}</td>    
                    <td>${holiday_date}</td>    
                    <td>
                        <button type="button" class="btn btn-danger waves-effect waves-light btn-sm remove_holiday" title="Remove Holiday" data-tooltip="tooltip" data-bs-placement="top"><i class="ri-delete-bin-fill"></i></button>  
                    </td>    
                </tr>
            `;

            $('#holiday_tbody').append(list);
            resetHolidays();
        })

        $(document).on('click', '.remove_holiday', function(){
            $(this).closest('tr').remove();
        })

        function resetHolidays(){
            $('#holiday_name').val('');
            $('#holiday_date').val('');
        }
    </script>

</x-app-layout>