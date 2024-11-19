<x-app-layout :title="'Input Example'">

    <style>
        td {
            padding: 2px 10px !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Add Accrual Policy</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group d-flex align-items-center mb-3">
                            <label class="col-md-2">Exception Policy Name</label>
                            <input type="text" class="form-control" id="ex_pol_name" placeholder="Enter Exception Policy Name Here" required />
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="col">Active</th>
                                    <th class="col">Code</th>
                                    <th class="col">Name</th>
                                    <th class="col">Severity</th>
                                    <th class="col">Grace</th>
                                    <th class="col">Watch Window</th>
                                    <th class="col">Email Notification</th>
                                </tr>
                            </thead>
                            <tbody id="ex_pol_form_table_body">
                                <tr><td colspan="7" class="text-center">Loading...</td></tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="submitForm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>