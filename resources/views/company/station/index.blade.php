<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Company Station</h5>
                    </div>
                    <div>
                        <a type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1"
                            href="/company/station/form">New Station<i class="ri-add-line"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Type</th>
                                <th class="col">Station ID</th>
                                <th class="col">Source</th>
                                <th class="col">Description</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="station_table_body">
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
            getAllStation();
        })

        async function getAllStation() {
            try {
                const station = await commonFetchData('/company/station/allStation');
                let list = '';
                if (station && station.length > 0) {
                    station.map((item, i) => {
                        list += `
                            <tr station_id="${item.id}">
                                <td>${i+1}</td>  
                                <td>${item.station_type_id == '1' ? 'PC' 
                                    : item.station_type_id == '2' ? '' 
                                    : ''}</td>   
                                <td>${item.station_customer_id}</td>  
                                <td>${item.source}</td>  
                                <td>${item.description}</td>  
                                 <td class="text-capitalize">${item.status === 'active'
                                ? `<span class="badge border border-success text-success">${item.status}</span>`
                                : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                            <td>
                                <td>
                                    <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_edit_station" title="Edit Company Station" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger wa.0
                                    ves-effect waves-light btn-sm click-delete-station" title="Delete Company Station" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>    
                                </td>    
                            </tr>
                        `;
                    })
                } else {
                    list += `<tr><td colspan="4" class="text-center">No Station Found!</td></tr>`;
                }

                $('#station_table_body').html(list);
                $('[data-tooltip="tooltip"]').tooltip();
            } catch (error) {
                console.error('error at payroll->station->index->getAllStation: ', error);
            }
        }

        $(document).on('click', '.click_edit_station', function() {
            let station_id = $(this).closest('tr').attr('station_id');

            window.location.href = '/company/station/form?id=' + station_id;
        })

        $(document).on('click', '.click-delete-station', async function() {
            let station_id = $(this).closest('tr').attr('station_id');

            try {
                let url = `/company/station/delete`;
                const res = await commonDeleteFunction(station_id, url,
                    'Company Station'); // Await the promise here

                if (res) {
                    await getAllStation();
                }
            } catch (error) {
                console.error(`Error during Company Station deletion:`, error);
            }
        })
    </script>

</x-app-layout>
