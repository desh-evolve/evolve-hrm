<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Overtime Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_overtime_click">New Overtime Policy<i class="ri-add-line"></i></button>
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
                                <th class="col">Active After</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ex_pol_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="overtime-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3 bg-light">
                    <h4 class="modal-title" id="overtime-form-title">Add Overtime Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="overtime-form-body">
                        <div class="row mb-3">
                            <label for="name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type_id" class="form-label mb-1 col-md-3">Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="type_id">
                                    <!-- Add options dynamically -->
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
                            <label for="max_time" class="form-label mb-1 col-md-3">Max Time</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="max_time" placeholder="Select Max Time (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="rate" class="form-label mb-1 col-md-3">Rate</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="rate" placeholder="Select Rate" value="1.00">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="wage_group_id" class="form-label mb-1 col-md-3">Wage Group</label>
                            <div class="col-md-9">
                                <select class="form-select" id="wage_group_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="pay_stub_entry_account_id" class="form-label mb-1 col-md-3">Pay Stub Account</label>
                            <div class="col-md-9">
                                <select class="form-select" id="pay_stub_entry_account_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="accrual_policy_id" class="form-label mb-1 col-md-3">Accrual Policy</label>
                            <div class="col-md-9">
                                <select class="form-select" id="accrual_policy_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="accrual_rate" class="form-label mb-1 col-md-3">Accrual Rate</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="accrual_rate" placeholder="Select Accrual Rate" value="1.00">
                            </div>
                        </div>
                    </div>
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="overtime_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="form_submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllOvertimes();
        })

        async function getAllOvertimes(){
            try {
                const overtimes = await commonFetchData('/policy/overtimes');
                let list = '';
                let showWarning = false;
                if(overtimes && overtimes.length > 0){
                    overtimes.map((ot, i) => {
                        showWarning = showWarning ? true : ot.policy_groups.length === 0 ? true : false;
                        let time = convertSecondsToHoursAndMinutes(ot.trigger_time);
                        list += `
                            <tr overtime_policy_id="${ot.id}" class="${ot.policy_groups.length > 0 ? '' : 'bg-warning'}">
                                <td>${i+1}</td>
                                <td>${ot.name}</td>
                                <td>${ot.type}</td>
                                <td>${time}</td>
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_ot_pol" title="Edit Overtime Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_ot_pol" title="Delete Overtime Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="5" class="text-center">No Overtime Policies Found!</td></tr>`;
                }

                if(showWarning){
                    $('#check_unassigned_policies').show();
                }else{
                    $('#check_unassigned_policies').hide();
                }

                $('#ex_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->overtime->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_delete_ot_pol', async function(){
            let ex_pol_id = $(this).closest('tr').attr('overtime_policy_id');

            try {
                let url = `/policy/overtime/delete`;
                const res = await commonDeleteFunction(ex_pol_id, url, 'Overtime Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during overtime policy deletion:`, error);
            }
        })


    </script>

    <script>
        $(document).ready(function(){
            getDropdownData();
            $('#accrual_rate').closest('.row').hide();
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/overtime/dropdown');

                // Populate overtime types dropdown
                let otTypesList = (dropdownData?.ot_types || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#type_id').html(otTypesList);

                // Populate wage groups dropdown
                let wageGroupsList = (dropdownData?.wage_groups || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#wage_group_id').html('<option value="0">--Default--</option>' + wageGroupsList);

                // Populate pay stub accounts dropdown
                let payStubsList = (dropdownData?.pay_stubs || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#pay_stub_entry_account_id').html('<option value="0">--</option>' + payStubsList);

                // Populate accrual policies dropdown
                let accrualPolicies = (dropdownData?.accrual_policies || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#accrual_policy_id').html('<option value="0">--None--</option>' + accrualPolicies);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('change', '#accrual_policy_id', function(){
            if($(this).val() == '0'){
                $('#accrual_rate').closest('.row').hide();
            }else{
                $('#accrual_rate').closest('.row').show();
            }
        })

        $(document).on('click', '#new_overtime_click', function(){
            resetForm();
            title = `Add Overtime Policy`;
            $('.modal-title').html(title);

            $('#overtime-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_ot_pol', async function(){
            resetForm();
            title = `Edit Overtime Policy`;
            $('.modal-title').html(title);

            let overtime_policy_id = $(this).closest('tr').attr('overtime_policy_id');

            $('#overtime_id').val(overtime_policy_id); // Set the ID in the hidden field

            try {
                // Fetch the overtime policy data
                let response = await commonFetchData(`/policy/overtime/${overtime_policy_id}`);
                let data = response[0]; // Extract the first object

                if (data) {

                    let trigger_time = convertSecondsToHoursAndMinutes(data.trigger_time || 0);
                    let max_time = convertSecondsToHoursAndMinutes(data.max_time || 0);

                    // Populate form fields
                    $('#name').val(data.name);
                    $('#type_id').val(data.type_id).trigger('change');
                    $('#trigger_time').val(trigger_time);
                    $('#max_time').val(max_time);
                    $('#rate').val(data.rate);
                    $('#wage_group_id').val(data.wage_group_id).trigger('change');
                    $('#pay_stub_entry_account_id').val(data.pay_stub_entry_account_id).trigger('change');
                    $('#accrual_policy_id').val(data.accrual_policy_id).trigger('change');
                    $('#accrual_rate').val(data.accrual_rate);
                }
            } catch (error) {
                console.error('Error while fetching overtime policy data:', error);
                $('#error-msg').html('<p class="text-danger">Failed to load data. Please try again.</p>');
            }

            $('#overtime-form-modal').modal('show');
        })

        $(document).on('click', '#form_submit', async function (e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = new FormData();

            let overtime_id = $('#overtime_id').val();

            let trigger_time = convertHoursAndMinutesToSeconds($('#trigger_time').val() || '0:00');
            let max_time = convertHoursAndMinutesToSeconds($('#max_time').val() || '0:00');

            formData.append('name', $('#name').val());
            formData.append('type_id', $('#type_id').val());
            formData.append('trigger_time', trigger_time);
            formData.append('max_time', max_time);
            formData.append('rate', $('#rate').val() || 0);
            formData.append('wage_group_id', $('#wage_group_id').val() || 0);
            formData.append('pay_stub_entry_account_id', $('#pay_stub_entry_account_id').val() || 0);
            formData.append('accrual_policy_id', $('#accrual_policy_id').val() || 0);
            formData.append('accrual_rate', $('#accrual_rate').val() || 0);

            let createUrl = `/policy/overtime/create`;
            let updateUrl = `/policy/overtime/update/${overtime_id}`;

            const isUpdating = Boolean(overtime_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = isUpdating ? 'PUT' : 'POST';

            if (isUpdating) {
                formData.append('id', overtime_id);
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    $('#overtime-form-modal').modal('hide');
                    getAllOvertimes(); // Refresh the list of overtimes
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });

        function resetForm(){
           $('#overtime_id').val('');
           $('#name').val('');
           $('#type_id').val('1');
           $('#trigger_time').val('');
           $('#max_time').val('');
           $('#rate').val('1.00');
           $('#wage_group_id').val('0');
           $('#pay_stub_entry_account_id').val('0');
           $('#accrual_policy_id').val('0');
           $('#accrual_rate').val('1.00');
        }
    </script>

</x-app-layout>
