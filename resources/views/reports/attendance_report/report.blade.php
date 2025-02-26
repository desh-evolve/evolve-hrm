<x-app-layout :title="'Input Example'">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Attendance Report</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if (!empty($dataList))
                        <table class="table table-bordered datatable-example">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="col">#</th>
                                    <th class="col">First Name</th>
                                    <th class="col">Last Name</th>
                                    <th class="col">Title</th>
                                    <th class="col">Home Phone</th>
                                    <th class="col">Address 1</th>
                                    <th class="col">City</th>
                                    <th class="col">Province/State</th>
                                    <th class="col">Postal Code</th>
                                    <th class="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataList as $item)
                                    <tr>
                                        <td>{{ $item['id'] }}</td>
                                        <td>{{ $item['first_name'] }}</td>
                                        <td>{{ $item['last_name'] ?? '-' }}</td>
                                        <td>{{ $item['title'] }}</td>
                                        <td>{{ $item['home_contact'] }}</td>
                                        <td>{{ $item['address_1'] }}</td>
                                        <td>{{ $item['city_name'] }}</td>
                                        <td>{{ $item['province_name'] }}</td>
                                        <td>{{ $item['postal_code'] }}</td>
                                        <td>
                                            @php
                                                switch ($item['user_status']) {
                                                    case '1':
                                                        echo 'Active';
                                                        break;
                                                    case '2':
                                                        echo 'Leave - Illness/Injury';
                                                        break;
                                                    case '3':
                                                        echo 'Leave - Maternity/Parental';
                                                        break;
                                                    case '4':
                                                        echo 'Leave - Other';
                                                        break;
                                                    default:
                                                        echo 'Terminated';
                                                        break;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No Employees Details records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
@extends('layouts.app')

@section('content')
<div id="rowContent">
    <div id="titleTab">
        <div class="textTitle"><span class="textTitleSub">{{ __('Generate Report') }}</span></div>
    </div>

    <div id="rowContentInner">
        <form method="GET" action="{{ route('reports.generate') }}">
            @csrf

            <table class="tblList">
                <tr>
                    <td class="cellLeftEditTable">{{ __('Select Date Range') }}:</td>
                    <td class="cellRightEditTable">
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                        to
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                    </td>
                </tr>

                <tr>
                    <td class="cellLeftEditTable">{{ __('Select Pay Period') }}:</td>
                    <td class="cellRightEditTable">
                        <select name="pay_period_id">
                            <option value="">{{ __('All') }}</option>
                            @foreach($pay_period_options as $id => $period)
                                <option value="{{ $id }}" {{ request('pay_period_id') == $id ? 'selected' : '' }}>
                                    {{ $period }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <td class="cellLeftEditTable" colspan="2" align="right">
                        <button type="submit" class="btn btn-primary">{{ __('Generate Report') }}</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
@endsection
