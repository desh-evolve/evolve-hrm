<x-app-layout :title="'Attendance Report'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Employee Attendance Report</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if (!empty($dataList))
                        <table class="table table-bordered datatable-example">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="col">#</th>
                                    <th class="col">Full Name</th>
                                    <th class="col">Employee Number</th>
                                    <th class="col">Date</th>
                                    <th class="col">Total Time Worked</th>
                                    <th class="col">Schedule Start</th>
                                    <th class="col">Schedule End</th>
                                    <th class="col">Hourly Rate</th>
                                    <th class="col">Verified Time Sheet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataList as $item)
                                    @foreach ($item['data']['date_totals'] as $index => $dateTotal)
                                        <tr>
                                            <td>{{ $item['user_id'] }}</td>
                                            <td>{{ $item['full_name'] }}</td>
                                            <td>{{ $item['employee_number'] ?? '-' }}</td>
                                            <td>{{ $dateTotal['date_stamp'] }}</td>
                                            <td>
                                                @php
                                                    $totalTime = $dateTotal['total_time'];
                                                    $hours = floor($totalTime / 3600);
                                                    $minutes = floor(($totalTime % 3600) / 60);
                                                    echo "{$hours} hours {$minutes} minutes";
                                                @endphp
                                            </td>
                                            <td>
                                                {{ isset($item['data']['schedules'][$index]['start_time']) ? 
                                                    date('H:i:s', strtotime($item['data']['schedules'][$index]['start_time'])) : '-' }}
                                            </td>
                                            <td>
                                                {{ isset($item['data']['schedules'][$index]['end_time']) ? 
                                                    date('H:i:s', strtotime($item['data']['schedules'][$index]['end_time'])) : '-' }}
                                            </td>
                                            <td>
                                                {{ isset($item['data']['wages'][0]['hourly_rate']) ? 
                                                    number_format($item['data']['wages'][0]['hourly_rate'], 2) : '-' }}
                                            </td>
                                            <td>{{ $item['verified_time_sheet'] }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No attendance records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>