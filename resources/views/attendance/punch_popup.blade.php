<div id="punch-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="punch-form-title">New Punch</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="punch-form-body" class="row">

                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="employee_name" class="form-label mb-1">Employee Name</label>
                        <input type="text" class="form-control" id="employee_name" value="" disabled>
                    </div>

                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="time" class="form-label">Time</label>
                        <input type="text" class="form-control" id="time" rows="3">
                    </div>

                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" rows="3">
                    </div>
                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="punch_type" class="form-label">Punch Type</label>
                        <select class="form-select" id="punch_type">
                            <option value="normal">Normal</option>
                            <option value="lunch">Lunch</option>
                            <option value="break">Break</option>
                        </select>
                    </div>
                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="punch_status" class="form-label">In/Out</label>
                        <select class="form-select" id="punch_status">
                            <option value="in">In</option>
                            <option value="out">Out</option>
                        </select>
                    </div>
                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="branch_id" class="form-label mb-1 req">Branch</label>
                        <select class="form-select" id="branch_id">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="department_id" class="form-label mb-1 req">Department</label>
                        <select class="form-select" id="department_id">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-xxl-3 col-md-6 mb-3">
                        <label for="station" class="form-label">Station</label>
                        <input type="text" class="form-control" id="station" rows="3">
                    </div>
                    <div class="col-xxl-4 col-md-6 mb-3">
                        <label for="emp_punch_status" class="form-label mb-1 req">Status</label>
                        <select class="form-select" id="emp_punch_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-xxl-3 col-md-12 mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="form-control" id="note" rows="3"></textarea>
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
