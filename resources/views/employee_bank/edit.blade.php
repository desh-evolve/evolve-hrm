<!-- pawanee(2024-10-22) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Bank Details') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>

    <!--form -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">

                    <div>
                        <h4 class="card-title mb-0 flex-grow-1">
                            Bank Details for <span class="text-info fw-bold">{{ $employee->name_with_initials }}</span>
                        </h4>
                    </div>


                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <a href="/employee/bank" class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </div>

                <!--Employee Bank details Form -->

                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="card-body">
                                <form id="bank-details-form">
                                    @csrf
                                    <input type="hidden" id="employee_name" value="">
                                    <input type="hidden" id="employee_id" value="">


                                    <div class="row">
                                        <div class="col-xxl-6 col-md-6 mb-6">
                                            <label for="bank_code" class="form-label mb-1">Bank Code</label>
                                            <input type="text" class="form-control" id="bank_code" placeholder="Enter Bank Code" value="">
                                        </div>

                                        <div class="col-xxl-6 col-md-6 mb-3">
                                            <label for="bank_name" class="form-label mb-1">Bank Name</label>
                                            <input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name" value="">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xxl-6 col-md-6 mb-3">
                                            <label for="bank_branch" class="form-label mb-1">Bank Branch</label>
                                            <input type="text" class="form-control" id="bank_branch" placeholder="Enter Bank Branch" value="">
                                        </div>

                                        <div class="col-xxl-6 col-md-6 mb-3">
                                            <label for="account_number" class="form-label mb-1">Account Number</label>
                                            <input type="text" class="form-control" id="account_number" placeholder="Enter Account Number" value="">
                                        </div>
                                    </div>

                                    <div id="error-msg" class="text-danger mb-3"></div>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <input type="hidden" id="emp_id" value="">
                                        <button type="button" class="btn btn-danger" id="click-delete">Delete</button>
                                        <button type="button" class="btn btn-primary" id="submit-confirm">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>




<script>


//==================================================================================================
// ADD & EDIT FUNCTION
//==================================================================================================

$(document).ready(function () {

    const employeeId = "{{ $employee->id }}";

    async function getBankDetails() {
        try {
            // Fetch bank details for the employee
            let bank_data = await commonFetchData(`/employee/bank/${employeeId}`);

            if (bank_data && bank_data[0]) {
                bank_data = bank_data[0];
                console.log('employees_bank_data', bank_data);

                // Populate form fields with existing data
                $('#emp_id').val(bank_data.id || '');
                $('#bank_name').val(bank_data.bank_name || '');
                $('#bank_code').val(bank_data.bank_code || '');
                $('#bank_branch').val(bank_data.bank_branch || '');
                $('#account_number').val(bank_data.account_number || '');
            } else {
                console.warn('No bank details found for this employee. You can add new details.');
                resetForm();
            }
        } catch (error) {
            console.error('Error fetching bank details:', error);
            $('#error-msg').text('Error fetching bank details. Please try again later.');
        }
    }

    // Submit Bank Details
    $(document).on('click', '#submit-confirm', async function () {
        const empId = $('#emp_id').val();

        const isUpdating = Boolean(empId);
        const url = isUpdating ? `/employee/bank/update/${empId}` : `/employee/bank/create`;
        const method = isUpdating ? 'PUT' : 'POST';

        const formFields = {
            bank_code: 'required',
            bank_name: 'required',
            bank_branch: 'required',
            account_number: 'required',
        };

        let formData = new FormData();
        let missingFields = [];

        // Validate and append form fields
        for (const key in formFields) {
            const value = $('#' + key).val();
            if (formFields[key] === 'required' && !value) {
                missingFields.push(key);
            }
            formData.append(key, value || ''); // Append field values to formData
        }

        if (missingFields.length > 0) {
            $('#error-msg').html(
                '<p class="text-danger">The following fields are required: ' +
                    missingFields.map((field) => field.replace('_', ' ')).join(', ') +
                    '.</p>'
            );
            return;
        } else {
            $('#error-msg').html('');
        }

        formData.append('employee_id', employeeId); // Always append employee ID

        // Debugging FormData
        for (let pair of formData.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        try {
            let res = await commonSaveData(url, formData, method);
            console.log('Response:', res);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                resetForm();
                await getBankDetails(); // Refresh form
            } else {
                $('#error-msg').html('<p class="text-danger">' + res.message + '</p>');
            }
        } catch (error) {
            console.error('Error saving data:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });

    // Fetch bank details on page load
    getBankDetails();

});


//======================================================================================================
// DELETE FUNCTION
//======================================================================================================

$(document).on('click', '#click-delete', async function () {
    const bankRecordId = $('#emp_id').val(); 
    const employeeId = $('#employee_id').val();

    if (!bankRecordId) {
        // If no bank record ID is found, show an error message
        $('#error-msg').html('<p class="text-danger">No bank record found. Please fill the record first.</p>');
        return;
    }

    try {
        // Perform the delete operation
        let url = `/employee/bank/delete`;
        const title ='Bank Details';

        const res = await commonDeleteFunction(bankRecordId,url,title);

        // Log the result for debugging
        console.log('Delete Response:', res);

        if (res) {

            resetForm();
            await getBankDetails();
        }
    } catch (error) {
        console.error(`Error during Bank Details deletion:`, error);
    }

});



// Reset Form Function
function resetForm() {
        $('#emp_id').val('');
        $('#employee_id').val('');
        $('#bank_name').val('');
        $('#bank_code').val('');
        $('#bank_branch').val('');
        $('#account_number').val('');
        $('#error-msg').html('');
    }


</script>

</x-app-layout>
