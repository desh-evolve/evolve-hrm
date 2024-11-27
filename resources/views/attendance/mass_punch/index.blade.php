<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Mass Punch') }}</h4>
    </x-slot>

    <style>
        .card-header:hover {
            background-color: #ddd;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">


                <div class="card-body">

                    <div class="live-preview">
                        <form action="javascript:void(0);">
                            <div class="row">
                                <div class="row mb-3">
                                    <label for="employee_ids" class="form-label">Employees</label>
                                    <div class="col-md-9">
                                        <div class="ps-2" id="employeeContainer">
                                            {{-- render employees dynamically --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="firstNameinput" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" placeholder="Enter your firstname"
                                            id="start_date">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lastNameinput" class="form-label">End Date</label>
                                        <input type="date" class="form-control" placeholder="Enter your lastname"
                                            id="end_date">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="compnayNameinput" class="form-label">Time</label>
                                        <input type="text" class="form-control" placeholder="Enter Time (ie: 20:09)"
                                            id="time">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="station" class="form-label">Station</label>
                                        <input type="text" class="form-control" id="station" rows="3">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="firstNameinput" class="form-label">Only These Day(s)</label>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckMon">
                                            <label class="day-checkbox" id="Mon" for="formCheckMon">Mon</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckTue">
                                            <label class="day-checkbox" id="Tue" for="formCheckTue">Tue</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckWed">
                                            <label class="day-checkbox" id="Wed" for="formCheckWed">Wed</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckThu">
                                            <label class="day-checkbox" id="Thu" for="formCheckThu">Thu</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckFri">
                                            <label class="day-checkbox" id="Fri" for="formCheckFri">Fri</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckSat">
                                            <label class="day-checkbox" id="Sat" for="formCheckSat">Sat</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="formCheckSun">
                                            <label class="day-checkbox" id="Sun" for="formCheckSun">Sun</label>
                                        </div>
                                    </div>
                                </div>


                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="punch_type" class="form-label">Punch Type</label>
                                        <select class="form-select" id="punch_type">
                                            <option value="normal">Normal</option>
                                            <option value="lunch">Lunch</option>
                                            <option value="break">Break</option>
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="punch_status" class="form-label">In/Out</label>
                                        <select class="form-select" id="punch_status">
                                            <option value="in">In</option>
                                            <option value="out">Out</option>
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="branch_id" class="form-label mb-1 req">Branch</label>
                                        <select class="form-select" id="branch_id">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="department_id" class="form-label mb-1 req">Department</label>
                                        <select class="form-select" id="department_id">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="emp_punch_status" class="form-label mb-1 req">Status</label>
                                        <select class="form-select" id="emp_punch_status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea class="form-control" id="note" rows="3"></textarea>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                        <button type="button" class="btn w-sm btn-primary"
                                            id="mass-punch-submit-confirm">Submit</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let employeeId = '';

        $(document).ready(async function() {
            await getDropdownData();

        });
        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/company/mass_punch/dropdown')

                // Populate branch dropdown
                let branchList = (dropdownData?.branches || [])
                    .map(branch => `<option value="${branch.id}">${branch.branch_name}</option>`)
                    .join('');
                $('#branch_id').html('<option value="">Select Branch</option>' + branchList);


                // Populate department dropdown
                let departmentList = (dropdownData?.departments || [])
                    .map(department => `<option value="${department.id}">${department.department_name}</option>`)
                    .join('');
                $('#department_id').html('<option value="">Select Department</option>' + departmentList);

                // Initialize the multiSelector for employees (multiselector is in components->hrm->multiselector.blade.php)
                $('#employeeContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.employees || [],
                    onSelectionChange: function(selectedIds) {
                        console.log("Selected IDs:", selectedIds);
                    }
                });

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        //  click event
        $(document).on('click', '#mass-punch-submit-confirm', async function() {
            const selectedDays = {};

            const punch_id = $('#punch_id').val();
            const time = $('#time').val();
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();
            const punch_type = $('#punch_type').val();
            const branch_id = $('#branch_id').val();
            const department_id = $('#department_id').val();
            const station = $('#station').val();
            const note = $('#note').val();
            const punch_status = $('#punch_status').val();
            const emp_punch_status = $('#emp_punch_status').val();

            const startDate = $("#startDate").val();
            const endDate = $("#endDate").val();

            // Collect selected days
            $('.form-check-input').each(function() {
                const day = $(this).siblings('label').attr('id'); // Get the day from the label's ID
                selectedDays[day] = $(this).is(':checked') ? 1 : 0;
            })

            let formattedTime = time.replace('.', ':');
            if (!formattedTime.includes(':')) {
                formattedTime += ':00'; // Add seconds if missing
            } else if (formattedTime.split(':').length === 2) {
                formattedTime += ':00'; // Add seconds if only HH:mm is provided
            }

            // Ensure time has two digits for hours
            if (formattedTime.split(':')[0].length === 1) {
                formattedTime = '0' + formattedTime; // Prefix single-digit hours with 0
            }

            console.log('Formatted Time:', formattedTime); // Debug output


            let createUrl = `/company/mass_punch/create`;
            let updateUrl = `/company/mass_punch/update/${punch_id}`;

            let formData = new FormData();

            if (!punch_type || !punch_status) {
                $('#error-msg').html('<p class="text-danger">All fields are required</p>');
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }


            // Collect selected employee IDs from the multiSelector component
            const selectedIds = $('#employeeContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            formData.append('employee_ids', JSON.stringify(selectedIds));
            formData.append('punch_id', punch_id);
            formData.append('punch_type', punch_type);
            formData.append('punch_status', punch_status);
            formData.append('branch_id', branch_id);
            formData.append('department_id', department_id);
            formData.append('station', station);
            formData.append('note', note);
            formData.append('time', formattedTime);
            formData.append('emp_punch_status', emp_punch_status);
            // formData.append('time_stamp', time_stamp);

            formData.append('startDate', start_date);
            formData.append('endDate', end_date);
            formData.append('selectedDays', JSON.stringify(selectedDays));

            const isUpdating = Boolean(punch_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    $('#punch-form-modal').modal('hide');
                    //await renderPunchTable(); // Re-render table on success
                    if (res.data && res.data.insertedPunchIds) {
                        window.location.href = '/company/mass_punch/list?data=' + JSON.stringify(res.data
                            .insertedPunchIds);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#time').val('');
            $('#station').val('');
            $('.form-check').val('');
            $('#punch_status').val('active');
            $('#punch_type').val('active');
            $('#branch_id').val('');
            $('#department_id').val('');
            $('#emp_punch_status').val('active');
            $('#note').val('');
            $('#error-msg').html('');

            getDropdownData();
        }
    </script>

</x-app-layout>
