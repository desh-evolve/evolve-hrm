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
                        <h5 class="mb-0">Employee Detail Report</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>

                        <div>
                            <div class="row mb-3">
                                <label for="user_ids" class="form-label mb-1 col-md-3">Employee</label>
                                <div class="col-md-9">
                                    <div class="ps-2" id="userContainer">
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="pay_stub_amendment_status" class="form-label mb-1 col-md-3">Status</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="pay_stub_amendment_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pay_stub_entry_name_id" class="form-label mb-1 col-md-3">Pay Stub
                                    Account</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="pay_stub_entry_name_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="Amount_section">
                            <u>
                                <h5 class="bg-primary text-white">Amount</h5>
                            </u>
                            <div class="row mb-3">
                                <label for="type" class="form-label mb-1 col-md-3">Amount
                                    Type</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="type">
                                        <option value="fixed">Fixed</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="rate" class="form-label mb-1 col-md-3">Rate</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="rate">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="units" class="form-label mb-1 col-md-3">Units</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="units">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="amount" class="form-label mb-1 col-md-3">Amount</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="amount">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="percent_amount" class="form-label mb-1 col-md-3">Percent (%)</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="percent_amount">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="percent_amount_entry_name_id" class="form-label mb-1 col-md-3">Percent
                                    Of</label>
                                <div class="col-md-9">
                                    <select class="form-select w-50" id="percent_amount_entry_name_id">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="Options_section">
                            <u>
                                <h5 class="bg-primary text-white">Options</h5>
                            </u>

                            <div class="row mb-3">
                                <label for="description" class="form-label mb-1 col-md-3">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control w-50" id="description">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="effective_date" class="form-label mb-1 col-md-3">Effective Date</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="date" class="form-control numonly w-50" id="effective_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="ytd_adjustment" class="form-label mb-1 col-md-3">Year to Date (YTD)
                                    Adjustment</label>
                                <div class="col-md-9 d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input" id="ytd_adjustment">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <input type="hidden" id="holiday_policy_id" value="" />
                            <button type="button" class="btn btn-primary" id="form_submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        let userId = '';
        let dropdownData = [];

        $(document).ready(async function() {
            await getDropdownData();
        });
        
        async function getDropdownData() {
            try {
                let dropdownData = await commonFetchData('/payroll/pay_stub_amendment/dropdown')

                console.log("Employees Data:", dropdownData?.users);

                // pay_stub_entry_accounts dropdown
                let payStubEntryAccountList = (dropdownData?.pay_stub_entry_accounts || [])
                    .map(account => `<option value="${account.id}">${account.type} - ${account.name}</option>`)
                    .join('');
                // Assign the generated list to #pay_stub_entry_name_id
                $('#pay_stub_entry_name_id').html('<option value="">Select Account</option>' +
                    payStubEntryAccountList);

                // Assign the same list to #percent_amount_entry_name_id
                $('#percent_amount_entry_name_id').html('<option value="">Select Account</option>' +
                    payStubEntryAccountList);

                $('#userContainer').multiSelector({
                    title: 'Employees',
                    data: dropdownData?.users || [],
                });

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        //  click event
        $(document).on('click', '#form_submit', async function() {

            const pay_stub_amendment_id = $('#pay_stub_amendment_id').val();

            let ytdAdjustment = $('#ytd_adjustment').is(':checked') ? 1 : 0;


            const selectedIds = $('#userContainer .selected-list option').map(function() {
                return $(this).val();
            }).get();

            let createUrl = `/payroll/pay_stub_amendment/create`;
            let updateUrl = `/payroll/pay_stub_amendment/update/${pay_stub_amendment_id}`;

            let formData = new FormData();

          
            formData.append('ytd_adjustment', ytdAdjustment);
            formData.append('user_ids', JSON.stringify(selectedIds));
            formData.append('pay_stub_entry_name_id', $('#pay_stub_entry_name_id').val());
            formData.append('effective_date', $('#effective_date').val());
            formData.append('rate', $('#rate').val());
            formData.append('units', $('#units').val());
            formData.append('amount', $('#amount').val());
            formData.append('description', $('#description').val());
            // formData.append('ytd_adjustment', $('#ytd_adjustment').val()); 
            formData.append('type', $('#type').val());
            formData.append('percent_amount_entry_name_id', $('#percent_amount_entry_name_id').val());
            formData.append('percent_amount', $('#percent_amount').val());
            formData.append('pay_stub_amendment_status', $('#pay_stub_amendment_status').val());
            // formData.append('time_stamp', time_stamp);


            const isUpdating = Boolean(pay_stub_amendment_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                console.log('response here', res)
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    window.location.href = 'payroll/pay_stub_amendment';
                }
                if (res.status === 'success') {
                    resetForm();
                    window.location.href = '{{ route('payroll.pay_stub_amendment') }}';
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });




        function resetForm() {
            $('#pay_stub_amendment_status').val('active');
            $('#pay_stub_entry_name_id').val('');
            $('#type').val('');
            $('#rate').val('');
            $('#units').val('');
            $('#amount').val('');
            $('#description').val('');
            $('#effective_date').val('');
            $('#percent_amount').val('');
            $('#percent_amount_entry_name_id').val('');

            $('#ytd_adjustment').prop('checked', false);

            // $('.form-check').val('');

            getDropdownData();
        }
    </script>

</x-app-layout>
