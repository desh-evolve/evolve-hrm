<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Meal Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="new_meal_click">New Meal <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Type</th>
                                <th class="col">Meal Time</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="meal_pol_table_body">
                            <tr><td colspan="5" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- form modal -->
    <div id="meal-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="meal-form-title">Add Meal Policy</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="meal-form-body">

                    </div>                    
                    <div id="error-msg"></div>
                    <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                        <input type="hidden" id="meal_id" value=""></button>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-primary" id="meal-submit-confirm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            getAllMeals();
        })

        async function getAllMeals(){
            try {
                const meals = await commonFetchData('/policy/meals');
                let list = '';
                if(meals && meals.length > 0){
                    meals.map((meal, i) => {
                        let interval = convertSecondsToHoursAndMinutes(meal.window_length);
                        list += `
                            <tr meal_policy_id="${meal.id}">
                                <td>${i+1}</td>    
                                <td>${meal.name}</td>    
                                <td>${meal.type == 'auto_deduct' ? 'Auto Deduct' : meal.type == 'auto_add' ? 'Auto Add' : 'Normal'}</td>    
                                <td>${interval}</td>    
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_round_pol" title="Edit Meal Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_round_pol" title="Delete Meal Policy" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                }else{
                    list += `<tr><td colspan="3" class="text-center">No Meal Policies Found!</td></tr>`;
                }

                $('#meal_pol_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at policy->meal->index->getAllExeptions: ', error);
            }
        }

        $(document).on('click', '.click_delete_round_pol', async function(){
            let meal_pol_id = $(this).closest('tr').attr('meal_policy_id');

            try {
                let url = `/policy/meal/delete`;
                const res = await commonDeleteFunction(meal_pol_id, url, 'Meal Policy');  // Await the promise here

                if (res) {
                    $(this).closest('tr').remove();
                }
            } catch (error) {
                console.error(`Error during meal policy deletion:`, error);
            }
        })


    </script>

    <script>

        $(document).on('click', '#new_meal_click', function(){
            resetForm();
            $('#meal-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_round_pol', function(){
            resetForm();
            let meal_policy_id = $(this).closest('tr').attr('meal_policy_id');
            $('#meal-form-modal').modal('show');
        })

        function resetForm(){
            
        }
    </script>

</x-app-layout>