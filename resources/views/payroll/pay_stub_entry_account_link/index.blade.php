<x-app-layout :title="'Input Example'">


    <style>
        td {
            padding: 2px 10px !important;
        }

        .w-8 {
            width: 8% !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Edit Pay Stub Account Links</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>


                            <div class="row mb-3">
                                <label for="total_gross" class="form-label mb-1 col-md-3">Total Gross</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="total_gross">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="total_user_deduction" class="form-label mb-1 col-md-3">Total Employee
                                    Deduction</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="total_user_deduction">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="total_employer_deduction" class="form-label mb-1 col-md-3">Total Employer
                                    Deduction</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="total_employer_deduction">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="total_net_pay" class="form-label mb-1 col-md-3">Total Net Pay</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="total_net_pay">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="regular_time" class="form-label mb-1 col-md-3">Regular Time Earnings</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="regular_time">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pay_stub_entry_account_link_status"
                                    class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="pay_stub_entry_account_link_status" disabled>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <input type="hidden" id="pay_stub_entry_account_link_id" value="" />
                                <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(async function() {
            await getDropdownData();
            await getUpdateData(1);
        });

        async function getDropdownData() {
            try {

                console.log('start 1');
                let dropdownData = await commonFetchData('/payroll/pay_stub_entry_account_link/dropdown')

                console.log(dropdownData);


                // Time Regular Time Earnings
                let stubEntryAccountList = (dropdownData?.pay_stub_entry_accounts || [])
                    .map(stub_entry_account =>
                        `<option value="${stub_entry_account.id}">${stub_entry_account.name}</option>`)
                    .join('');
                $('#regular_time').html('<option value="">Select Stub Entry Accounts</option>' +
                    stubEntryAccountList);

                // Populate total_gross
                let totalGrossList = (dropdownData?.type || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#total_gross').html('<option value="">Select Total Gross</option>' + totalGrossList);

                // Populate total_user_deduction
                let totalEmployeeDeductionList = (dropdownData?.type || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#total_user_deduction').html('<option value="">Select Total Employee Deduction</option>' +
                    totalEmployeeDeductionList);

                // Populate total_employer_deduction
                let totalEmployerDeductionList = (dropdownData?.type || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#total_employer_deduction').html('<option value="">Select Total Employer Deduction</option>' +
                    totalEmployerDeductionList);

                // Populate total_net_pay dropdown
                let totalNetPayList = (dropdownData?.type || [])
                    .map(over => `<option value="${over.value}">${over.name}</option>`)
                    .join('');
                $('#total_net_pay').html('<option value="">Select Total Net Pay</option>' + totalNetPayList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }


        $(document).on('click', '#form_submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let pay_stub_entry_account_link_id = $('#pay_stub_entry_account_link_id').val();

            formData.append('total_gross', $('#total_gross').val());
            formData.append('total_user_deduction', $('#total_user_deduction').val());
            formData.append('total_employer_deduction', $('#total_employer_deduction').val());
            formData.append('total_net_pay', $('#total_net_pay').val());
            formData.append('regular_time', $('#regular_time').val());

            formData.append('pay_stub_entry_account_link_status', $('#pay_stub_entry_account_link_status').val());

            let createUrl = `/payroll/pay_stub_entry_account_link/create`;
            let updateUrl = `/payroll/pay_stub_entry_account_link/update/${pay_stub_entry_account_link_id}`;

            const isUpdating = Boolean(pay_stub_entry_account_link_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', pay_stub_entry_account_link_id);
            }
            
            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'payroll/pay_stub_entry_account_link';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('payroll.pay_stub_entry_account_link') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        async function getUpdateData(id) {
            try {
                let response = await commonFetchData(`/payroll/pay_stub_entry_account_link/${id}`);
                let data = response?.[0]; // Assuming the API returns an array with one item

                if (!data) {
                    console.error('No data found for the given ID.');
                    return;
                }

                console.log('Fetched policy  group data:', data);

                // Populate form fields
                $('#pay_stub_entry_account_link_id').val(data.id || '');
                $('#total_gross').val(data.total_gross || '');
                $('#total_user_deduction').val(data.total_user_deduction || '');
                $('#total_employer_deduction').val(data.total_employer_deduction || '');
                $('#total_net_pay').val(data.total_net_pay || '');
                $('#regular_time').val(data.regular_time || '');

                $('#pay_stub_entry_account_link_status').val(data.status);



            } catch (error) {
                console.error('Error fetching Pay Stub Entry Account data:', error);
            }
        }


        function resetForm() {
            $('#pay_stub_entry_account_link_id').val('');
            $('#total_gross').val('');
            $('#total_user_deduction').val('');
            $('#total_employer_deduction').val('');
            $('#total_net_pay').val('');
            $('#regular_time').val('');
            $('#pay_stub_entry_account_link_status').val('active');
        }
    </script>

</x-app-layout>
