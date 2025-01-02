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



        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex justify-content-between">
                        <h4 class="card-title mb-0 flex-grow-1">Employees</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table_body">

                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>





<script>


//==================================================================================================
// RENDER TABLE
//==================================================================================================

$(document).ready(async function () {
       await renderTableBody();
});


    async function renderTableBody() {
        try {

            const users = await commonFetchData('/company/allemplyee');

            let list = '';

            if (users.length === 0) {
                $('#table_body').html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                return;
            } else {
                list = users.map((item, i) => {
                    return `
                        <tr emp_id="${item.id}">
                            <th scope="row">${i + 1}</th>
                            <td>${item.name_with_initials}</td>

                            <td>
                                <button type="button" class="btn btn-success btn-sm manage-bank" data-id="${item.id}">Manage Bank Details</button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // Update the HTML content of the table body with the new list
            $('#table_body').html(list);

        } catch (error) {

            $('#table_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
            console.error('Error fetching data:', error);

        }

         // Navigate to Employee Bank Details Page
         $(document).on('click', '.manage-bank', function () {
            const userId = $(this).data('id');
            window.location.href = `/user/bank/details/${userId}`;
        });

    }



</script>


</x-app-layout>
