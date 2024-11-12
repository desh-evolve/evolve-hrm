<!-- desh(2024-11-12) -->
<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Exception Policy List</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="click_add_exception">New Exception <i class="ri-add-line"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="branch_table_body">
                            <tr><td colspan="3" class="text-center">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){

        })

        async function getAllExceptions(){
            try {
                const exceptions = await commonFetchData('/policy/exceptions');
            } catch (error) {
                console.error('error at policy->exception->index->getAllExeptions: ', error);
            }
        }

    </script>

</x-app-layout>