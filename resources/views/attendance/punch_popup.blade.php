<div id="punch-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="punch-form-title">New Punch</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="punch-form-body" class="row">

                    <div class="col-xxl-12 col-md-12 mb-3">
                        <label for="user_name" class="form-label mb-1">Employee Name</label>
                        <input type="hidden" id="user_id" value="" readonly>
                        <input type="text" class="form-control" id="user_name" value="" disabled>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="time" class="form-label mb-1 ">Time</label>
                        <input type="time" class="form-control" id="time">
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="date" class="form-label mb-1 ">Date</label>
                        <input type="date" class="form-control" id="date" value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="punch_type" class="form-label mb-1 ">Punch Type</label>
                        <select class="form-select" id="punch_type">
                            <option value="normal">Normal</option>
                            <option value="lunch">Lunch</option>
                            <option value="break">Break</option>
                        </select>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="punch_status" class="form-label mb-1 ">In/Out</label>
                        <select class="form-select" id="punch_status">
                            <option value="in">In</option>
                            <option value="out">Out</option>
                        </select>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="branch_id" class="form-label mb-1 req">Branch</label>
                        <select class="form-select" id="branch_id">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="department_id" class="form-label mb-1 req">Department</label>
                        <select class="form-select" id="department_id">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3 d-none">
                        <label for="station" class="form-label mb-1 ">Station</label>
                        <input type="text" class="form-control" id="station" value="1">
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="emp_punch_status" class="form-label mb-1 req">Status</label>
                        <select class="form-select" id="emp_punch_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-xxl-6 col-md-12 mb-3">
                        <label for="note" class="form-label mb-1 ">Notes</label>
                        <input type="text" class="form-control" id="note">
                    </div>

                    <div id="error-msg"></div>

                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                    <input type="hidden" id="punch_id" value="">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-primary" id="punch-submit-confirm">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    
    $(document).ready(async function() {
        await getDropdownData();
    });

    //get dropdown data
    async function getDropdownData() {
        try {
            let dropdownData = await commonFetchData('/company/employee_punch/dropdown');
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
        } catch (error) {
            console.error('Error fetching dropdown data:', error);
        }
    }

    
    //  click event
    $(document).on('click', '#punch-submit-confirm', async function() {
        const punch_id = $('#punch_id').val();
        const time = $('#time').val();
        const date = $('#date').val();
        const punch_type = $('#punch_type').val();
        const branch_id = $('#branch_id').val();
        const department_id = $('#department_id').val();
        const station = $('#station').val();
        const note = $('#note').val();
        const punch_status = $('#punch_status').val();
        const emp_punch_status = $('#emp_punch_status').val();

        const time_stamp = `${date}T${time}`; // Format: "2024-11-11T16:19"

        let createUrl = `/company/employee_punch/create`;
        let updateUrl = `/company/employee_punch/update/${punch_id}`;

        let formData = new FormData();

        if (!punch_type || !punch_status) {
            $('#error-msg').html('<p class="text-danger">All fields are required</p>');
            return;
        } else {
            $('#error-msg').html(''); // Clear error message if no issues
        }

        formData.append('user_id', userId);
        formData.append('punch_id', punch_id);
        formData.append('punch_type', punch_type);
        formData.append('punch_status', punch_status);
        formData.append('branch_id', branch_id);
        formData.append('department_id', department_id);
        formData.append('station', station);
        formData.append('note', note);
        formData.append('time_stamp', time_stamp);
        formData.append('emp_punch_status', emp_punch_status);
        formData.append('date', date);
        formData.append('time', time);

        const isUpdating = Boolean(punch_id);
        let url = isUpdating ? updateUrl : createUrl;
        let method = isUpdating ? 'PUT' : 'POST';

        try {
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                $('#punch-form-modal').modal('hide');
                await renderPunchTable(); // Re-render table on success
            }
        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });

    function resetForm() {
        $('#punch_id').val('');
        $('#punch').val('');
        $('#institute').val('');
        $('#year').val('');
        $('#remarks').val('');
        $('#punch_status').val('in'); // Reset status to default
        $('#error-msg').html(''); // Clear error messages
    }

</script>
