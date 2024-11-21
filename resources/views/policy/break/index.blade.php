<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Break Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_break_click">New Break Policy <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Type</th>
                                <th class="col">Break Time</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="break_pol_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="break-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="break-form-title">Add Break Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="break-form-body">
                        <div class="row mb-3">
                            <label for="name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type" class="form-label mb-1 col-md-3">Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="type">
                                    <option value="auto_deduct">Auto Deduct</option>
                                    <option value="auto_add">Auto Add</option>
                                    <option value="normal">Normal</option>
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
                            <label for="amount" class="form-label mb-1 col-md-3">Deduction/Addition Time</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="amount" placeholder="Select Deduction/Addition Time (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="auto_detect_type" class="form-label mb-1 col-md-3">Auto-Detect Breaks By</label>
                            <div class="col-md-9">
                                <select class="form-select" id="auto_detect_type">
                                    <option value="time_window">Time Window</option>
                                    <option value="punch_time">Punch Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="time_window_section">
                            <div class="row mb-3">
                                <label for="start_window" class="form-label mb-1 col-md-3">Start Window</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="start_window" placeholder="Select Start Window (hh:mm)">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="window_length" class="form-label mb-1 col-md-3">Window Length</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="window_length" placeholder="Select Window Length (hh:mm)">
                                </div>
                            </div>
                        </div>
                        <div class="punch_time_section">
                            <div class="row mb-3">
                                <label for="minimum_punch_time" class="form-label mb-1 col-md-3">Minimum Punch Time</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="minimum_punch_time" placeholder="Select Minimum Punch Time (hh:mm)">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="maximum_punch_time" class="form-label mb-1 col-md-3">Maximum Punch Time</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="maximum_punch_time" placeholder="Select Maximum Punch Time (hh:mm)">
                                </div>
                            </div>
                        </div>

                        <div class="checkbox_section">
                            <div class="row mb-3">
                                <label for="include_lunch_punch_time" class="form-label mb-1 col-md-3">Include Any Punched Time for Lunch</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="include_lunch_punch_time">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="include_multiple_breaks" class="form-label mb-1 col-md-3">Include Multiple Breaks</label>
                                <div class="col-md-9">
                                    <input type="checkbox" class="form-check-input" id="include_multiple_breaks">
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="break_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="break-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllBreaks();

            $('.time_window_section').show();
            $('.punch_time_section').hide();
        })

        async function getAllBreaks(){
            try {
                const breaks = await commonFetchData('/policy/breaks');
                let list = '';
                if(breaks && breaks.length > 0){
                    breaks.map((brk, i) => {
                        let interval = convertSecondsToHoursAndMinutes(brk.window_length);
                        list += `
                            <tr break_policy_id="${brk.id}">
                                <td>${i+1}</td>    
                                <td>${brk.name}</td>    
                                <td>${brk.type == 'auto_deduct' ? 'Auto Deduct' : brk.type == 'auto_add' ? 'Auto Add' : 'Normal'}</td>    
                                <td>${interval}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_break_pol" title="Edit Break Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_break_pol" title="Delete Break Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Break Policies Found!</td></tr>`;
                }

                $('#break_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->break->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_delete_break_pol', async function(){
            let break_pol_id = $(this).closest('tr').attr('break_policy_id');

            try {
                let url = `/policy/break/delete`;
                const res = await commonDeleteFunction(break_pol_id, url, 'Break Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during break policy deletion:`, error);
            }
        })

        $(document).on('change', '#type', function(){
            if($(this).val() == 'normal'){
                $('.checkbox_section').hide();
                $('label[for="amount"]').text('Break Time');
            } else {
                $('.checkbox_section').show();
                $('label[for="amount"]').text('Deduction/Addition Time');
            }
        })

        $(document).on('change', '#auto_detect_type', function() {
            if ($(this).val() == 'time_window') {
                $('.time_window_section').show();
                $('.punch_time_section').hide();
            }else{
                $('.time_window_section').hide();
                $('.punch_time_section').show();
            }
        });

    </script>

    <script>

        $(document).on('click', '#new_break_click', function(){
            resetForm();
            $('#break-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_break_pol', function(){
            resetForm();
            let break_policy_id = $(this).closest('tr').attr('break_policy_id');
            $('#break-form-modal').modal('show');
        })

        function resetForm(){
            
        }
    </script>

</x-app-layout>