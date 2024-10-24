<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">
   
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Department Employees') }}</h4>
    </x-slot>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card info-card" style="display: none">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_back_btn"><i class="ri-arrow-left-line"></i></button>
                        <h5 class="mb-0 ms-4" id="dep_emp_page_title">Department Employees</h5>
                    </div>
                </div>
                <div class="card-body">

                </div>
            </div>
            <div class="card loading-card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <h4>Loading...</h4>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            // Get the department_id from the URL query parameter
            let urlParams = new URLSearchParams(window.location.search);
            let department_id = urlParams.get('dep_id');
            
            if (department_id) {
                getDepartmentById(department_id);
            } else {
                console.error('No department ID found in the URL');
            }
        });

        
        async function getDepartmentById(department_id) {
            $('.info-card').hide();
            $('.loading-card').show();
            try {
                let department_data = await commonFetchData(`/company/department/${department_id}`);
                department_data = department_data[0];
                
                console.log(department_data);
                $('#dep_emp_page_title').html(`Employees of ${department_data.department_name} Department`);
            } catch (error) {
                console.error('error fetching department by id: ', error);
            } finally {
                $('.info-card').show();
                $('.loading-card').hide();
            }
        }

        $(document).on('click', '#click_back_btn', function(){
            window.history.back();
        })


    </script>

</x-app-layout>