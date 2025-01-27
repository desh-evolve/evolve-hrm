<div id="accrual-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="punch-form-title">New Accrual Balance</h4>
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
                        <label for="accrual_policy_id" class="form-label mb-1 ">Accrual Policy</label>
                        <select class="form-select" id="accrual_policy_id">
                            <option value="">Select Accrual Policy</option>
                        </select>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="type" class="form-label mb-1 ">Type</label>
                        <select class="form-select" id="type">
                            <option value="">Select Accrual Policy</option>
                        </select>
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="amount" class="form-label mb-1 ">Time Amount</label>
                        <input type="text" class="form-control" id="amount" value="00:00">
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="time_stamp" class="form-label mb-1 ">Date</label>
                        <input type="date" class="form-control" id="time_stamp" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-xxl-6 col-md-6 mb-3">
                        <label for="accrual_status" class="form-label mb-1 req">Status</label>
                        <select class="form-select" id="accrual_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div id="error-msg"></div>

                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                    <input type="hidden" id="accrual_balance_id" value="">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-primary" id="accrual-submit-confirm">Submit</button>
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
            const dropdownData = await commonFetchData('/accrual/dropdown');

            const accrualpolicyList = (dropdownData?.accrual_policy || [])
                .map(accrual => `<option value="${accrual.id}">${accrual.name}</option>`)
                .join('');
            $('#accrual_policy_id').html('<option value="">Select Accrual policy</option>' + accrualpolicyList);

            const typeList = (dropdownData?.type || [])
                .map(type => `<option value="${type.id}">${type.name}</option>`)
                .join('');
            $('#type').html('<option value="">Select Type</option>' + typeList);


        } catch (error) {
            console.error('Error fetching employee data:', error);
        }
    }


    //  click event
    $(document).on('click', '#accrual-submit-confirm', async function() {

        const accrual_balance_id = $('#accrual_balance_id').val();
        const amount = $('#amount').val();
        const time_stamp = $('#time_stamp').val();
        const type = $('#type').val();
        const accrual_policy_id = $('#accrual_policy_id').val();
        const user_id = $('#user_id').val();
        const accrual_status = $('#accrual_status').val();

        // const time_stamp = `${date}T${time}`; // Format: "2024-11-11T16:19"

        let createUrl = `/accrual/create`;
        let updateUrl = `/accrual/update/${accrualId}`;


        let formData = new FormData();

        if (!accrual_policy_id || !accrual_status) {
            $('#error-msg').html('<p class="text-danger">All fields are required</p>');
            return;
        } else {
            $('#error-msg').html(''); // Clear error message if no issues
        }

        formData.append('accrual_id', accrualId);
        formData.append('accrual_balance_id', accrual_balance_id);
        formData.append('user_id', user_id);
        formData.append('amount', amount);
        formData.append('time_stamp', time_stamp);
        formData.append('type', type);
        formData.append('accrual_policy_id', accrual_policy_id);
        formData.append('accrual_status', accrual_status);

        const isUpdating = Boolean(accrualId);
        let url = isUpdating ? updateUrl : createUrl;
        let method = isUpdating ? 'PUT' : 'POST';

        try {
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                $('#accrual-form-modal').modal('hide');
                await renderAccrualTable(); // Re-render table on success
                window.location.reload();
                // await accrual-form-modal();
            }
            //  else {
            //     window.location.reload();
            // }
        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });

    function resetForm() {
        $('#accrual_balance_id').val('');
        $('#amount').val('00:00');
        $('#time_stamp').val('');
        $('#type').val('');
        $('#accrual_policy_id').val('');
        $('#user_id').val('in'); // Reset status to default
        $('#accrual_status').val('in'); // Reset status to default
        $('#error-msg').html(''); // Clear error messages

    }
</script>
