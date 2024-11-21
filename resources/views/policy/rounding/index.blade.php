<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Rounding Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_rounding_click">New Rounding Policy<i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Punch Type</th>
                                <th class="col">Interval</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="rnd_pol_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="rounding-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="rounding-form-title">Add Rounding Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="rounding-form-body">
                        <div class="row mb-3">
                            <label for="rounding_name" class="form-label mb-1 col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="rounding_name" placeholder="Enter Name" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="punch_type_id" class="form-label mb-1 col-md-3">Punch Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="punch_type_id">
                                    <!-- Add options dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="round_type_id" class="form-label mb-1 col-md-3">Round Type</label>
                            <div class="col-md-9">
                                <select class="form-select" id="round_type_id">
                                    <option value="">Select a round type</option>
                                    <option value="down">Down</option>
                                    <option value="average">Average</option>
                                    <option value="up">Up</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="interval_time" class="form-label mb-1 col-md-3">Interval</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="interval_time" placeholder="Select Interval (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="grace_period" class="form-label mb-1 col-md-3">Grace Period</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="grace_period" placeholder="Select Grace Period (hh:mm)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="strict_schedule" class="form-label mb-1 col-md-3">Strict Schedule</label>
                            <div class="col-md-9">
                                <input type="checkbox" class="form-check-input" id="strict_schedule">
                            </div>
                        </div>
                    </div>                    
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="rounding_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="rounding-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllRoundings();
        })

        async function getAllRoundings(){
            try {
                const roundings = await commonFetchData('/policy/roundings');
                let list = '';
                if(roundings && roundings.length > 0){
                    roundings.map((rnd, i) => {
                        let interval = convertSecondsToHoursAndMinutes(rnd.round_interval);
                        list += `
                            <tr rounding_policy_id="${rnd.id}">
                                <td>${i+1}</td>    
                                <td>${rnd.name}</td>    
                                <td>${rnd.punch_type}</td>    
                                <td>${interval}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_round_pol" title="Edit Rounding Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_round_pol" title="Delete Rounding Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Rounding Policies Found!</td></tr>`;
                }

                $('#rnd_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->rounding->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_delete_round_pol', async function(){
            let rnd_pol_id = $(this).closest('tr').attr('rounding_policy_id');

            try {
                let url = `/policy/rounding/delete`;
                const res = await commonDeleteFunction(rnd_pol_id, url, 'Rounding Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during rounding policy deletion:`, error);
            }
        })


    </script>

    <script>
        $(document).ready(function(){
            getDropdownData();
        })

        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/policy/rounding/dropdown');

                // Populate rounding punch types dropdown
                let punchTypesList = (dropdownData?.punch_types || [])
                    .map(type => `<option value="${type.id}">${type.name}</option>`)
                    .join('');
                $('#punch_type_id').html('<option value="">Select a punch type</option>' + punchTypesList);
            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('click', '#new_rounding_click', function(){
            resetForm();
            $('#rounding-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_round_pol', function(){
            resetForm();
            let rounding_policy_id = $(this).closest('tr').attr('rounding_policy_id');
            $('#rounding-form-modal').modal('show');
        })

        function resetForm(){
            $('#round_type_id').val('');
            $('#interval_time').val('');
            $('#grace_period').val('');
            $('#strict_schedule').val('');
            $('#rounding_id').val('');
        }
    </script>

</x-app-layout>