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
                        <div class="row border-end">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="name" class="form-label mb-1 col-md-3">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="employee_ids" class="form-label mb-1 col-md-3">Employees</label>
                                    <div class="col-md-9">
                                        <div class="ps-2" id="employeeContainer">
                                            {{-- render employees dynamically --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="over_time_policy_ids" class="form-label mb-1 col-md-3">Overtime Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="over_time_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="round_interval_policy_ids" class="form-label mb-1 col-md-3">Rounding Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="round_interval_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="meal_policy_ids" class="form-label mb-1 col-md-3">Meal Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="meal_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="break_policy_ids" class="form-label mb-1 col-md-3">Break Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="break_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="accrual_policy_ids" class="form-label mb-1 col-md-3">Accrual Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="accrual_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="premium_policy_ids" class="form-label mb-1 col-md-3">Premium Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="premium_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="holiday_policy_ids" class="form-label mb-1 col-md-3">Holiday Policies</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="holiday_policy_ids" multiple>
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="exception_policy_control_id" class="form-label mb-1 col-md-3">Exception Policy</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="exception_policy_control_id" >
                                            <!-- Add options dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
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
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/policy_group/dropdown');

                // Populate overtime policies dropdown
                let overtimePoliciesList = (dropdownData?.overtime_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#over_time_policy_ids').html('<option value="0">--None--</option>' + overtimePoliciesList);

                // Populate rounding policies dropdown
                let roundingPoliciesList = (dropdownData?.rounding_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#round_interval_policy_ids').html('<option value="0">--None--</option>' + roundingPoliciesList);

                // Populate meal policies dropdown
                let mealPoliciesList = (dropdownData?.meal_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#meal_policy_ids').html('<option value="0">--None--</option>' + mealPoliciesList);

                // Populate break policies dropdown
                let breakPoliciesList = (dropdownData?.break_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#break_policy_ids').html('<option value="0">--None--</option>' + breakPoliciesList);

                // Populate accrual policies dropdown
                let accrualPoliciesList = (dropdownData?.accrual_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#accrual_policy_ids').html('<option value="0">--None--</option>' + accrualPoliciesList);

                // Populate premium policies dropdown
                let premiumPoliciesList = (dropdownData?.premium_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#premium_policy_ids').html('<option value="0">--None--</option>' + premiumPoliciesList);

                // Populate holiday policies dropdown
                let holidayPoliciesList = (dropdownData?.holiday_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#holiday_policy_ids').html('<option value="0">--None--</option>' + holidayPoliciesList);

                // Populate exception policies dropdown
                let exceptionPoliciesList = (dropdownData?.exception_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#exception_policy_control_id').html('<option value="0">--None--</option>' + exceptionPoliciesList);


                // Initialize the multiSelector for employees (multiselector is in components->hrm->multiselector.blade.php)
                $('#employeeContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.employees || [],
                    onSelectionChange: function (selectedIds) {
                        console.log("Selected IDs:", selectedIds);
                    }
                });

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

    </script>

</x-app-layout>