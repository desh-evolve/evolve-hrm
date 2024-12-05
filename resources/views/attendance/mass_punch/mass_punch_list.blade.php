<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Mass Punch List</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if (!empty($insertedPunchIds))
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white"/>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Employee Name</th>
                                    <th>Punch Type</th>
                                    <th>In/Out</th>
                                    <th>Time Stamp</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($insertedPunchIds as $punch)
                                    <tr>
                                        {{-- <td>{{ $punch['id'] }}</td> --}}
                                        <td>{{ $punch['emp_name']['name'] ?? '-' }}</td>
                                        <td>{{ $punch['punch_type'] ?? '-' }}</td>
                                        <td>{{ $punch['punch_status'] }}</td>
                                        <td>{{ $punch['time_stamp'] }}</td>
                                        <td><span class="badge border border-success text-success">Success</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No punch records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
