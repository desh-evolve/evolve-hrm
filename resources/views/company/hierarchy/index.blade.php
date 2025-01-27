<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Hierarchy List</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            href="/company/hierarchy/form">New Hierarchy<i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Name</th>
                                <th class="col">Description</th>
                                <th class="col">Objects</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="hierarchy_table_body">
                            <tr>
                                <td colspan="4" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            getAllHierarchy();
        })

        async function getAllHierarchy() {
            try {
                const hierarchy = await commonFetchData('/company/hierarchies');
                let list = '';
                if (hierarchy && hierarchy.length > 0) {
                    hierarchy.map((item, i) => {
                        // If object_types is not null, split by newline and join with <br> for HTML line breaks
                        const objectTypes = item.object_types ? item.object_types.split('\n').join('<br>') :
                            'No object types available';

                        list += `
                        <tr hierarchy_id="${item.hierarchy_control_id}">
                            <td>${i + 1}</td>
                            <td>${item.hierarchy_name}</td>
                            <td>${item.description}</td>
                            <td>${objectTypes}</td> <!-- Updated to display object types -->
                            <td class="text-capitalize">${item.status ? item.status : 'Inactive'}</td>
                            
                            <td>
                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_hierarchy" title="Edit Company Deduction" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click-delete-pay-period-schedule" title="Delete Company Deduction" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                } else {
                    list += `<tr><td colspan="4" class="text-center">No Company Deduction Found!</td></tr>`;
                }

                $('#hierarchy_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at payroll->pay-period-schedule->index->getAllHierarchy: ', error);
            }
        }

        $(document).on('click', '#new_hierarchy_click', function() {
            resetForm();
            $('#pay-period-schedule-form-modal').modal('show');
        })

        $(document).on('click', '.click_edit_hierarchy', function() {
            let hierarchy_id = $(this).closest('tr').attr('hierarchy_id');

            window.location.href = '/company/hierarchy/form?id=' + hierarchy_id;
        })


        $(document).on('click', '.click-delete-pay-period-schedule', async function() {
            let hierarchy_id = $(this).closest('tr').attr('hierarchy_id');

            try {
                let url = `/company/hierarchy/delete`;
                const res = await commonDeleteFunction(hierarchy_id, url,
                    'Company Deduction'); // Await the promise here

                if (res) {
                    await getAllHierarchy();
                }
            } catch (error) {
                console.error(`Error during Company Deduction deletion:`, error);
            }
        })
    </script>

</x-app-layout>
