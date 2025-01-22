<x-app-layout :title="'Input Example'">

    <style>
        th, td{
           padding: 2px 10px !important; 
        }
        
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Timesheet</h5>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-body">

                    {{-- ----------------------------------------------------------------------------------- --}}
                    {{-- filter section --}}
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">

                                <div class="row mb-3 col-md-4">
                                    <label for="group_filter" class="form-label col-md-3">Group</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="group_filter">
                                            <option value="-1">-- All --</option>
                                            <option value="0">Root</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 col-md-4">
                                    <label for="branch_filter" class="form-label col-md-3">Branch</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="branch_filter">
                                            <option value="-1">-- All --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 col-md-4">
                                    <label for="department_filter" class="form-label mb-1 col-md-3">Department</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="department_filter">
                                            <option value="-1">-- All --</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="row mb-3 col-md-4">
                                    <label for="user_filter" class="form-label mb-1 col-md-3">Employee</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="user_filter">
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3 col-md-4">
                                    <label for="date_filter" class="form-label mb-1 col-md-3">Date</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" id="date_filter" value="<?=date('Y-m-d')?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-primary me-2">Punch</button>
                                    <button type="button" class="btn btn-outline-warning">Absence</button>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <table class="table">
                        @if ($pay_period_is_locked)
                            <tr class="bg-danger text-white">
                                <td colspan="8">
                                    <b>{{ __('NOTICE:') }}</b> 
                                    {{ __('This pay period is currently') }} 
                                    @if ($pay_period_status_id == 'closed')
                                        {{ __('closed') }}
                                    @else
                                        {{ __('locked') }}
                                    @endif, 
                                    {{ __('modifications are not permitted.') }}
                                </td>
                            </tr>
                        @elseif ($pay_period_status_id == 'post_adjustment')
                            <tr class="bg-warning text-white">
                                <td colspan="8">
                                    <b>{{ __('NOTICE:') }}</b> 
                                    {{ __('This pay period is currently in the post adjustment state.') }}
                                </td>
                            </tr>
                        @endif

                    </table>

                    {{-- timesheet section --}}
                    <div>
                        <table class="table table-bordered">
                            {{-- header working -----------------------------------------------}}
                            <thead>
                                <tr>
                                    @foreach ($calendar_array as $index => $calendar)
                                        @if ($loop->first)
                                        <th class="bg-primary text-white text-center">
                                                <i class="ri-printer-line cursor-pointer" data-toggle="tooltip" aria-label="print" data-bs-original-title="print" style="font-size: 18px;"></i>
                                            </th>
                                            @endif
                                        <th class="bg-primary text-white text-center" 
                                        @if ($calendar['epoch'] == $filter_data['date']) style="background-color: #33CCFF;" @endif
                                        onclick="changeDate('{{ date('Y-m-d', $calendar['epoch']) }}')">
                                        {{ $calendar['day_of_week'] }} <br>
                                        <span>{{ $calendar['month_short_name'] }} {{ $calendar['day_of_month'] }}</span>
                                        @if (isset($holidays[$calendar['epoch']]))
                                        <br><span>({{ $holidays[$calendar['epoch']] }})</span>
                                        @endif
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            {{-----------------------------------------------------------------}}
                            
                            <tbody>

                                @foreach ($rows as $row_num => $row)
                                    @php
                                        $row_class = $row['background'] == 0 ? ($loop->iteration % 2 == 0 ? 'table-secondary' : 'table-light') : '';
                                    @endphp
                                    <tr class="{{ $row_class }}">
                                        <td class="fw-bold text-end">
                                            {{ $row['status'] }}
                                        </td>
                                        @foreach ($row['data'] as $epoch => $day)
                                            <td nowrap>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        @php
                                                            $exception_arr = [];
                                                        @endphp
                                                        @if(isset($punch_exceptions[$day['id'] ?? null]) || isset($punch_control_exceptions[$day['punch_control_id'] ?? null]))
                                                            @if(isset($punch_exceptions[$day['id']]))
                                                                @php
                                                                    $exception_arr = $punch_exceptions[$day['id']];
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $exception_arr = $punch_control_exceptions[$day['punch_control_id']];
                                                                @endphp
                                                            @endif
                                                        @endif
                                                        @if (count($exception_arr) > 0)
                                                            <span class="text-start">
                                                                @foreach ($exception_arr as $exception_data)
                                                                    <span class="badge text-bg-warning" style="color: {{ $exception_data['color'] }};">
                                                                        <b>{{ $exception_data['exception_policy_type_id'] }}</b>
                                                                    </span>
                                                                @endforeach
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="text-center">
                                                        @if (isset($day['time_stamp']) && $day['time_stamp'] != '')
                                                            @if ($day['has_note'] === true)*@endif
                                                            {{ date('H:i', $day['time_stamp']) }}
                                                        @else
                                                            <span class="text-muted">&ndash;</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-end">
                                                        {{ $day['type_code'] ?? '' }}
                                                    </div>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                @foreach ($date_break_total_rows as $date_break_total_row)
                                    @php
                                        $row_class = $loop->iteration % 2 == 0 ? 'table-secondary' : 'table-light'; // Alternating row classes
                                    @endphp
                                    <tr class="{{ $row_class }}">
                                        <td class="fw-bold text-end">
                                            {{ $date_break_total_row['name'] }}
                                        </td>
                                        @foreach ($date_break_total_row['data'] as $date_break_total_epoch => $date_break_total_day)
                                            <td>
                                                {{ sprintf('%02d:%02d', floor(($date_break_total_day['total_time'] ?? 0) / 60), ($date_break_total_day['total_time'] ?? 0) % 60) }}
                                                @if (isset($date_break_total_day['total_breaks']) && $date_break_total_day['total_breaks'] > 1)
                                                    ({{ $date_break_total_day['total_breaks'] }})
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                
                                @foreach ($date_break_policy_total_rows as $date_break_policy_total_row)
                                    @php
                                        $row_class = $loop->iteration % 2 == 0 ? 'table-secondary' : 'table-light'; // Alternating row classes
                                    @endphp
                                    <tr class="{{ $row_class }}">
                                        <td class="fw-bold text-end">
                                            {{ $date_break_policy_total_row['name'] }}
                                        </td>
                                        @foreach ($date_break_policy_total_row['data'] as $date_break_policy_total_epoch => $date_break_policy_total_day)
                                            <td>
                                                @if (isset($date_break_policy_total_day['total_time']) && $date_break_policy_total_day['total_time'] < 0)
                                                    <span class="text-danger">
                                                        {{ sprintf('%02d:%02d', floor(($date_break_policy_total_day['total_time_display'] ?? 0) / 60), ($date_break_policy_total_day['total_time_display'] ?? 0) % 60) }}
                                                    </span>
                                                @else
                                                    {{ sprintf('%02d:%02d', floor(($date_break_policy_total_day['total_time_display'] ?? 0) / 60), ($date_break_policy_total_day['total_time_display'] ?? 0) % 60) }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                @foreach ($date_meal_policy_total_rows as $date_meal_total_row)
                                    @php
                                        $row_class = $loop->iteration % 2 == 0 ? 'table-secondary' : 'table-light'; // Alternating row classes
                                    @endphp
                                    <tr class="{{ $row_class }}">
                                        <td class="fw-bold text-end">
                                            {{ $date_meal_total_row['name'] }}
                                        </td>
                                        @foreach ($date_meal_total_row['data'] as $date_meal_total_epoch => $date_meal_total_day)
                                            <td>
                                                @if (isset($date_meal_total_day['total_time']) && $date_meal_total_day['total_time'] < 0)
                                                    <span class="text-danger">
                                                        {{ $date_meal_total_day['total_time_display'] }}
                                                    </span>
                                                @else
                                                    {{ $date_meal_total_day['total_time_display'] ?? '' }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                @if (isset($date_exception_total_rows))
                                    <tr>
                                        @foreach ($date_exception_total_rows as $date_exception_total_row)
                                        @if ($loop->first)
                                            <td class="fw-bold text-end">
                                                {{ __('Exceptions') }}
                                            </td>
                                        @endif
                                        <td>
                                            <b>
                                                @if(isset($date_exception_total_row) && count($date_exception_total_row) > 0)
                                                    @foreach ($date_exception_total_row as $date_exception_total_day)
                                                        <span style="color: {{ $date_exception_total_day['color'] }}">
                                                            {{ $date_exception_total_day['exception_policy_type_id'] }}
                                                            @if (!$loop->last)
                                                            ,
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </b>
                                        </td>
                                        @endforeach
                                    </tr>
                                @endif

                                @if (isset($date_request_total_rows))
                                    <tr class="">
                                        @foreach ($date_request_total_rows as $request_epoch => $date_request_total_row)
                                            @if ($loop->first)
                                                <td class="fw-bold text-end">
                                                    {{ __('Pending Requests') }}
                                                </td>
                                            @endif
                                            <td>
                                                @if (isset($date_request_total_row))
                                                    <a href="#">
                                                        {{ __('Yes') }}
                                                    </a>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif

                                @if (!empty($date_total_rows))
                                    @foreach ($date_total_rows as $date_total_row)
                                        @if ($loop->first)
                                            <tr class="table-primary">
                                                <td colspan="8">
                                                    {{ __('Accumulated Time') }}
                                                </td>
                                            </tr>
                                        @endif
                                
                                        @php
                                            $row_class = $loop->iteration % 2 === 0 ? 'table-light' : 'table-secondary'; // Alternating row colors
                                        @endphp
                                
                                        <tr class="{{ $row_class }}">
                                            <td class="fw-bold text-end">
                                                {{ $date_total_row['name'] }}
                                            </td>
                                            
                                            @foreach ($date_total_row['data'] as $date_total_epoch => $date_total_day)
                                                <td class="">
                                                    @if (isset($date_total_row['type_and_policy_id']) && $date_total_row['type_and_policy_id'] == 100)
                                                        @if (!isset($pay_period_locked_rows[$date_total_epoch]) || !$pay_period_locked_rows[$date_total_epoch])
                                                            <a href="#">
                                                        @endif
                                                        @if (!empty($date_total_day['override']))
                                                            *
                                                        @endif
                                                        {{ isset($date_total_day['total_time']) ? sprintf('%02d:%02d', floor($date_total_day['total_time'] / 3600), floor(($date_total_day['total_time'] % 3600) / 60)) : '00:00' }}
                                                        @if (!isset($pay_period_locked_rows[$date_total_epoch]) || !$pay_period_locked_rows[$date_total_epoch])
                                                            </a>
                                                        @endif
                                                    @else
                                                        {{ isset($date_total_day['total_time']) ? sprintf('%02d:%02d', floor($date_total_day['total_time'] / 3600), floor(($date_total_day['total_time'] % 3600) / 60)) : '00:00' }}
                                                    @endif
                                                </td>
                                                    
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                                
                                {{-- not working - you should add the code to controller --}}
                                @if (!empty($date_branch_total_rows))
                                    @foreach ($date_branch_total_rows as $date_branch_total_row)
                                        @if ($loop->first)
                                            <tr class="table-primary">
                                                <td colspan="100">
                                                    {{ __('Branch') }}
                                                </td>
                                            </tr>
                                        @endif

                                        <tr class="">
                                            <td class="fw-bold text-end">
                                                {{ $date_branch_total_row['name'] }}
                                            </td>

                                            @foreach ($date_branch_total_row['data'] as $date_branch_total_day)
                                                <td>
                                                    {{ isset($date_branch_total_day['total_time']) ? sprintf('%02d:%02d', floor($date_branch_total_day['total_time'] / 3600), floor(($date_branch_total_day['total_time'] % 3600) / 60)) : '00:00' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif

                                {{-- not working - you should add the code to controller --}}
                                @if (!empty($date_department_total_rows))
                                    @foreach ($date_department_total_rows as $date_department_total_row)
                                        @if ($loop->first)
                                            <tr class="table-primary">
                                                <td colspan="100">
                                                    {{ __('Department') }}
                                                </td>
                                            </tr>
                                        @endif
                                
                                        <tr class="">
                                            <td class="fw-bold text-end">
                                                {{ $date_department_total_row['name'] }}
                                            </td>
                                
                                            @foreach ($date_department_total_row['data'] as $date_department_total_day)
                                                <td>
                                                    {{ isset($date_department_total_day['total_time']) ? sprintf('%02d:%02d', floor($date_department_total_day['total_time'] / 3600), floor(($date_department_total_day['total_time'] % 3600) / 60)) : '00:00' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif

                                {{-- job and job item code is not added get it from the old system if needed --}}

                                @if (!empty($date_premium_total_rows))
                                    @foreach ($date_premium_total_rows as $date_premium_total_row)
                                        @if ($loop->first)
                                            <tr class="table-primary">
                                                <td colspan="100">
                                                    {{ __('Premium') }}
                                                </td>
                                            </tr>
                                        @endif

                                        @php
                                            // Alternating row classes
                                            $row_class = $loop->iteration % 2 === 0 ? 'table-light' : 'table-secondary';
                                        @endphp

                                        <tr class="{{ $row_class }}">
                                            <td class="fw-bold text-end">
                                                {{ $date_premium_total_row['name'] }}
                                            </td>

                                            @foreach ($date_premium_total_row['data'] as $date_premium_total_epoch => $date_premium_total_day)
                                                <td>
                                                    {{ isset($date_premium_total_day['total_time']) ? sprintf('%02d:%02d', floor($date_premium_total_day['total_time'] / 3600), floor(($date_premium_total_day['total_time'] % 3600) / 60)) : '00:00' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif

                                @if (!empty($date_absence_total_rows))
                                    @foreach ($date_absence_total_rows as $date_absence_total_row)
                                        @if ($loop->first)
                                            <tr class="table-primary">
                                                <td colspan="100">
                                                    {{ __('Absence') }}
                                                </td>
                                            </tr>
                                        @endif

                                        <tr class="">
                                            <td class="fw-bold text-end">
                                                {{ $date_absence_total_row['name'] }}
                                            </td>

                                            @foreach ($date_absence_total_row['data'] as $date_absence_total_epoch => $date_absence_total_day)
                                                @php
                                                //print_r($date_absence_total_day);exit;
                                                @endphp
                                                <td>
                                                    @if (isset($date_absence_total_day['override']) && $date_absence_total_day['override'])* @endif
                                                    {{ isset($date_absence_total_day['total_time']) ? sprintf('%02d:%02d', floor($date_absence_total_day['total_time'] / 3600), floor(($date_absence_total_day['total_time'] % 3600) / 60)) : '00:00' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif

                                <tr class="text-center {{ $is_assigned_pay_period_schedule ? 'bg-primary' : 'bg-danger' }} text-white">
                                    <td colspan="8">
                                        @if ($is_assigned_pay_period_schedule)
                                            {{ __('Pay Period:') }}
                                            @if (!empty($pay_period_start_date))
                                                {{ date('Y-m-d', $pay_period_start_date) }}
                                                {{ __('to') }}
                                                {{ date('Y-m-d', $pay_period_end_date) }}
                                            @else
                                                {{ __('NONE') }}
                                            @endif
                                        @else
                                            <b>{{ __('Employee is not assigned to a Pay Period Schedule.') }}</b>
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr valign="top">
                                    <td colspan="2">
                                        @if ($time_sheet_verify['previous_pay_period_verification_display'] ?? false)
                                            <table class="table table-bordered">
                                                <tr class="tblDataWarning">
                                                    <td colspan="3">
                                                        <b>{{ __('Previous pay period is not verified!') }}</b>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif
                            
                                        @if (!empty($pay_period_end_date))
                                            <table class="table table-bordered">
                                                <tr class="tblHeader">
                                                    <td>{{ __('Verification') }}</td>
                                                </tr>
                                                <tr class="tblDataWhiteNH">
                                                    <td @if (!empty($time_sheet_verify['verification_box_color'])) bgcolor="{{ $time_sheet_verify['verification_box_color'] }}" @endif>
                                                        {{ $time_sheet_verify['verification_status_display'] ?? '' }}
                                                    </td>
                                                </tr>
                            
                                                @if ($time_sheet_verify['display_verify_button'] ?? false)
                                                    <tr class="tblDataWhiteNH">
                                                        <td colspan="2" @if (!empty($time_sheet_verify['verification_box_color'])) bgcolor="{{ $time_sheet_verify['verification_box_color'] }}" @endif>
                                                            <input type="submit" class="button" name="action:verify" value="{{ __('Verify') }}"
                                                                onclick="return confirm('{{ __('By pressing OK, I hereby certify that this timesheet for the pay period of :start_date to :end_date is accurate and correct.', [
                                                                    'start_date' => \Carbon\Carbon::createFromTimestamp($pay_period_start_date)->format('Y-m-d'),
                                                                    'end_date' => \Carbon\Carbon::createFromTimestamp($pay_period_end_date)->format('Y-m-d')
                                                                ]) }}');">
                                                        </td>
                                                    </tr>
                                                @endif
                                            </table>
                                        @endif
                                
                                        <table class="table table-bordered">
                                            @foreach ($exception_legend as $key => $exception_legend_row)
                                                @if ($loop->first)
                                                    <thead>
                                                        <tr><th colspan="2" class="bg-primary text-white text-center">Exception Legend</th></tr>
                                                        <tr>
                                                            <th class="bg-primary text-white text-center">Code</th>
                                                            <th class="bg-primary text-white text-center">Exception</th>
                                                        </tr>
                                                    </thead>
                                                @endif
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <p class="mb-0" style="{{'color:'. $exception_legend_row['color'] }}">
                                                                <b>{{ $exception_legend_row['exception_policy_type_id'] }}</b>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            {{ $exception_legend_row['name'] }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            @endforeach
                                        </table>
                                    </td>
                                    <td colspan="3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr><th colspan="2" class="bg-primary text-white text-center">Paid Time</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('Worked Time') }}</td>
                                                    <td>{{ $pay_period_worked_total_time ?? 0 }}</td>
                                                </tr>
                                                @if ($pay_period_paid_absence_total_time > 0)
                                                    <tr>
                                                        <td>{{ __('Paid Absences') }}</td>
                                                        <td>{{ $pay_period_paid_absence_total_time }}</td>
                                                    </tr>
                                                @endif
                                                <tr style="font-weight: bold;">
                                                    <td>{{ __('Total Time') }}</td>
                                                    <td width="75">{{ $pay_period_worked_total_time + $pay_period_paid_absence_total_time }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                
                                        @if ($pay_period_dock_absence_total_time > 0)
                                            <table class="table table-bordered">
                                                <tr class="tblHeader">
                                                    <td colspan="2">{{ __('Docked Time') }}</td>
                                                </tr>
                                                <tr style="font-weight: bold;">
                                                    <td>{{ __('Docked Absences') }}</td>
                                                    <td>{{ $pay_period_dock_absence_total_time }}</td>
                                                </tr>
                                            </table>
                                        @endif
                                    </td>
                                    <td colspan="3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr><th colspan="2" class="bg-primary text-white text-center">Accumulated Time</th></tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pay_period_total_rows as $pay_period_total_row)
                                                    <tr>
                                                        <td>{{ $pay_period_total_row['name'] }}</td>
                                                        <td>{{ $pay_period_total_row['total_time'] }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr style="font-weight: bold;">
                                                    <td>{{ __('Total Time') }}</td>
                                                    <td>{{ $pay_period_worked_total_time + $pay_period_paid_absence_total_time }}</td>
                                                </tr>
                                                {{-- @if (!$pay_period_is_locked)
                                                    <tr>
                                                        <td colspan="2" align="center">
                                                            <select name="action_option" id="select_action">
                                                                @foreach ($action_options as $key => $value)
                                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="submit" class="button" name="action:submit" value="{{ __('Submit') }}" onclick="return confirmAction();">
                                                        </td>
                                                    </tr>
                                                @endif --}}
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>

                    {{-- ----------------------------------------------------------------------------------- --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        let dropdownData = [];

        $(document).ready(function(){
            getDropdownDataFunc();
        })

        async function getDropdownDataFunc(){
            try {
                dropdownData = await commonFetchData('/employee/timesheet/dropdown');

                branches = dropdownData.branches.length > 0 && dropdownData.branches.map(e => `<option value="${e.id}">${e.branch_name}</option>`).join('');
                departments = dropdownData.departments.length > 0 && dropdownData.departments.map(e => `<option value="${e.id}">${e.department_name}</option>`).join('');
                employee_groups = dropdownData.employee_groups.length > 0 && dropdownData.employee_groups.map(e => `<option value="${e.id}">${e.emp_group_name}</option>`).join('');
                users = dropdownData.users.length > 0 && dropdownData.users.map(e => `<option value="${e.id}">${e.first_name+' '+e.last_name}</option>`).join('');

                $('#group_filter').append(branches);
                $('#branch_filter').append(departments);
                $('#department_filter').append(employee_groups);
                $('#user_filter').append(users);
                
            }catch(error){
                console.error('error at attendance->timesheet->index->getDropdownDataFunc: ', error);
            } 
        }

    </script>
</x-app-layout>