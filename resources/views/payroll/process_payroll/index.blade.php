<x-app-layout :title="'Input Example'">

    <style>
        th, td{
           padding: 4px 10px !important; 
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Process Payroll</h5>
                    </div>
                </div>
                <div class="card-body">
                    
                    {{-- =============================================================================== --}}
                    
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td colspan="10"><br></td>
                        </tr>
                    
                        @foreach ($pay_periods as $index => $pay_period)
                            @if ($loop->first)
                                <tr class="table-header bg-primary text-white">
                                    <th colspan="8">
                                        Step 1: Confirm all requests are authorized, and exceptions are handled.
                                    </th>
                                </tr>
                    
                                <tr class="table-header bg-primary text-white">
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th colspan="2">Pending Requests</th>
                                    <th>Exceptions<br>Low / Medium / High / Critical</th>
                                    <th>Verified TimeSheets<br>Pending / Verified / Total</th>
                                    <th colspan="2">Functions</th>
                                </tr>
                            @endif
                    
                            @php
                                $row_class = $pay_period['status'] == 'delete' ? 'table-danger' : ($loop->odd ? 'table-light' : 'table-white');
                            @endphp
                    
                            <tr class="{{ $row_class }}">
                                <td>{{ $pay_period['name'] }}</td>
                                <td>{{ $pay_period['type'] }}</td>
                    
                                <td colspan="2">
                                    <table class="w-100 text-center">
                                        <tr>
                                            <td width="20" class="bg-{{ $pay_period['pending_requests'] > 0 ? 'danger' : 'success' }}"></td>
                                            <td><b>{{ $pay_period['pending_requests'] }}</b></td>
                                        </tr>
                                    </table>
                                </td>
                    
                                <td>
                                    <table class="w-100 text-center">
                                        <tr>
                                            <td width="20" class="bg-{{ $pay_period['critical_severity_exceptions'] > 0 ? 'danger' : 'success' }}"></td>
                                            <td>
                                                <b>
                                                    {{ $pay_period['low_severity_exceptions'] }}
                                                    / <span class="text-primary">{{ $pay_period['med_severity_exceptions'] }}</span>
                                                    / <span class="text-warning">{{ $pay_period['high_severity_exceptions'] }}</span>
                                                    / <span class="text-danger">{{ $pay_period['critical_severity_exceptions'] }}</span>
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                    
                                <td>
                                    <table class="w-100 text-center">
                                        <tr>
                                            @php
                                                $statusClass = $pay_period['verified_time_sheets'] >= $pay_period['total_worked_users'] ? 'success' :
                                                               (($pay_period['verified_time_sheets'] + $pay_period['pending_time_sheets']) >= $pay_period['total_worked_users'] ? 'warning' : 'danger');
                                            @endphp
                                            <td width="20" class="bg-{{ $statusClass }}"></td>
                                            <td>
                                                <b>
                                                    {{ $pay_period['pending_time_sheets'] }}
                                                    / {{ $pay_period['verified_time_sheets'] }}
                                                    / {{ $pay_period['total_worked_users'] }}
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                    
                                <td colspan="2">
                                    <span class="d-inline-block">[ <a href="#">Exceptions</a> ]</span>
                                    <span class="d-inline-block">[ <a href="#">Requests</a> ]</span>
                                    <span class="d-inline-block">[ <a href="#">Verifications</a> ]</span>
                                </td>
                            </tr>
                        @endforeach

                        <form>
                            <tr>
                                <td class="text-end" colspan="10">
                                    <br>
                                </td>
                            </tr>
                        
                            @if (!$open_pay_periods)
                                <tr class="table-header bg-primary text-white">
                                    <th colspan="8">
                                        @if ($total_pay_periods == 0)
                                            There are no Pay Periods past their end date yet.
                                        @else
                                            All pay periods are currently closed.
                                        @endif
                                    </th>
                                </tr>
                            @else
                                @foreach ($pay_periods as $pay_period)
                                    @if ($loop->first)
                                        <tr class="table-header bg-primary text-white">
                                            <th colspan="8">
                                                Step 2: Lock Pay Period to prevent changes.
                                            </th>
                                        </tr>
                        
                                        <tr class="table-header bg-primary text-white">
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Transaction</th>
                                            <th>Functions</th>
                                            <th>
                                                <input type="checkbox" class="checkbox step_2_select_all" name="select_all" onClick="CheckAll('step_2')" checked/>
                                            </th>
                                        </tr>
                                    @endif
                                    @php
                                        $row_class = $pay_period['status'] == 'delete' ? 'table-danger' : ($loop->odd ? 'table-light' : 'table-white');
                                    @endphp
                        
                                    <tr class="{{ $row_class }}">
                                        <td>{{ $pay_period['name'] }}</td>
                                        <td>{{ $pay_period['type'] }}</td>
                                        <td>{{ $pay_period['status'] }}</td>
                                        <td>{{ $pay_period['start_date'] }}</td>
                                        <td>{{ $pay_period['end_date'] }}</td>
                                        <td>{{ $pay_period['transaction_date'] }}</td>
                                        <td>
                                            @if (isset($pay_period['id']))
                                                <a href="#">View</a>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="checkbox" class="checkbox step_2_checkbox" name="pay_period_ids[]" value="{{ $pay_period['id'] }}" checked>
                                        </td>
                                    </tr>
                                @endforeach
                        
                                <tr class="table-header bg-primary text-white">
                                    <th colspan="6">
                                        <br>
                                    </th>
                                    <th colspan="2" class="text-center">
                                        <button type="button" id="lock_click" class="action-btn" data-action="locked">Lock</button>
                                        <button type="button" id="unlock_click" class="action-btn" data-action="open">UnLock</button>
                                    </th>
                                </tr>
                            @endif
                        </form>
                        
                        <form>
                            <tr>
                                <td class="text-end" colspan="10">
                                    <br>
                                </td>
                            </tr>
                        
                            @foreach ($pay_periods as $pay_period)
                                @if ($loop->first)
                                    <tr class="table-header bg-primary text-white">
                                        <th colspan="8">
                                            Step 3: Submit all Pay Stub Amendments.
                                        </th>
                                    </tr>
                        
                                    <tr class="table-header bg-primary text-white">
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th colspan="4">Pay Stub Amendments</th>
                                        <th colspan="2">Functions</th>
                                    </tr>
                                @endif
                                @php
                                    $row_class = $pay_period['status'] == 'delete' ? 'table-danger' : ($loop->odd ? 'table-light' : 'table-white');
                                @endphp
                        
                                <tr class="{{ $row_class }}">
                                    <td>{{ $pay_period['name'] }}</td>
                                    <td>{{ $pay_period['type'] }}</td>
                                    <td colspan="4">{{ $pay_period['total_ps_amendments'] }}</td>
                                    <td colspan="2">
                                        <a href="#">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                        
                        <form>
                            <tr>
                                <td class="text-end" colspan="10">
                                    <br>
                                </td>
                            </tr>
                        
                            @foreach ($pay_periods as $pay_period)
                                @if ($loop->first)
                                    <tr class="table-header bg-primary text-white">
                                        <th colspan="8">
                                            Step 4: Generate and Review Pay Stubs.
                                        </th>
                                    </tr>
                        
                                    <tr class="table-header bg-primary text-white">
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th colspan="4">Pay Stubs</th>
                                        <th>Functions</th>
                                        <th>
                                            <input type="checkbox" class="checkbox step_4__select_all" name="select_all" onClick="CheckAll('step_4')" checked/>
                                        </th>
                                    </tr>
                                @endif
                                @php
                                    $row_class = $pay_period['status'] == 'delete' ? 'table-danger' : ($loop->odd ? 'table-light' : 'table-white');
                                @endphp
                        
                                <tr class="{{ $row_class }}">
                                    <td>{{ $pay_period['name'] }}</td>
                                    <td>{{ $pay_period['type'] }}</td>
                                    <td colspan="4">{{ $pay_period['total_pay_stubs'] }}</td>
                                    <td>
                                        @if ($pay_period['id'])
                                            <a href="#">View</a>
                                            <a href="#">Summary</a>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="checkbox" class="checkbox step_4_checkbox" name="pay_stub_pay_period_ids[]" value="{{ $pay_period['id'] }}" checked>
                                    </td>
                                </tr>
                            @endforeach
                        
                            <tr class="table-header bg-primary text-white">
                                <th colspan="6">
                                    <br>
                                </th>
                                <th colspan="2" class="text-center">
                                    <input type="button" id="generate_paystubs" value="Generate Final Pay">
                                </th>
                            </tr>
                        </form>
                        
                        <form>
                            <tr>
                                <td class="text-end" colspan="10">
                                    <br>
                                </td>
                            </tr>
                        
                            @foreach ($pay_periods as $pay_period)
                                @if ($loop->first)
                                    <tr class="table-header bg-primary text-white">
                                        <th colspan="8">
                                            Step 5: Transfer Funds or Write Checks.
                                        </th>
                                    </tr>
                        
                                    <tr class="table-header bg-primary text-white">
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Transaction</th>
                                        <th>Functions</th>
                                        <th>
                                            <input type="checkbox" class="checkbox step_5_select_all" name="select_all" onClick="CheckAll('step_5')" checked />
                                        </th>
                                    </tr>
                                @endif
                                @php
                                    $row_class = $pay_period['status'] == 'delete' ? 'table-danger' : ($loop->odd ? 'table-light' : 'table-white');
                                @endphp
                        
                                <tr class="{{ $row_class }}">
                                    <td>{{ $pay_period['name'] }}</td>
                                    <td>{{ $pay_period['type'] }}</td>
                                    <td>{{ $pay_period['status'] }}</td>
                                    <td>{{ $pay_period['start_date'] }}</td>
                                    <td>{{ $pay_period['end_date'] }}</td>
                                    <td>{{ $pay_period['transaction_date'] }}</td>
                                    <td>
                                        @if ($pay_period['id'])
                                            <a href="#">View</a>
                                            <a href="#">Summary</a>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="checkbox" class="checkbox step_5_checkbox" name="pay_period_ids[]" value="{{ $pay_period['id'] }}" checked>
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                        
                        <form>
                            <tr>
                                <td class="text-end" colspan="10">
                                    <br>
                                </td>
                            </tr>
                        
                            @foreach ($pay_periods as $pay_period)
                                @if ($loop->first)
                                    <tr class="table-header bg-primary text-white">
                                        <th colspan="8">
                                            Step 6: Close Pay Period.
                                        </th>
                                    </tr>
                        
                                    <tr class="table-header bg-primary text-white">
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Transaction</th>
                                        <th>Functions</th>
                                        <th>
                                            <input type="checkbox" class="checkbox step_6_select_all" name="select_all" onClick="CheckAll('step_6')" checked />
                                        </th>
                                    </tr>
                                @endif
                        
                                @php
                                    $row_class = $pay_period['status'] == 'delete' ? 'table-danger' : ($loop->odd ? 'table-light' : 'table-white');
                                @endphp
                        
                                <tr class="{{ $row_class }}">
                                    <td>{{ $pay_period['name'] }}</td>
                                    <td>{{ $pay_period['type'] }}</td>
                                    <td>{{ $pay_period['status'] }}</td>
                                    <td>{{ $pay_period['start_date'] }}</td>
                                    <td>{{ $pay_period['end_date'] }}</td>
                                    <td>{{ $pay_period['transaction_date'] }}</td>
                                    <td>
                                        @if ($pay_period['id'])
                                            <a href="#">View</a>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="checkbox" class="checkbox step_6_checkbox" name="pay_period_ids[]" value="{{ $pay_period['id'] }}" checked>
                                    </td>
                                </tr>
                            @endforeach
                        
                            <tr class="table-header bg-primary text-white">
                                <th colspan="6">
                                    <br>
                                </th>
                                <th colspan="2" class="text-center">
                                    <button type="button" id="close_click" class="action-btn" data-action="closed">Close</button>
                                </th>
                            </tr>
                        </form>
                        
                    </table>
                    

                    {{-- =============================================================================== --}}

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.action-btn', async function (e) {
            e.preventDefault(); // Prevent default form submission

            let action = $(this).data('action');
            let pay_period_ids = [];
            
            // Correctly check for data attributes
            if (action == 'locked') {
                // Collect selected checkboxes
                $('.step_2_checkbox:checked').each(function () {
                    pay_period_ids.push($(this).val());
                });
            } else if (action == 'open') {
                // Collect selected checkboxes
                $('.step_2_checkbox:checked').each(function () {
                    pay_period_ids.push($(this).val());
                });
            } else if (action == 'closed') {
                // Collect selected checkboxes
                $('.step_6_checkbox:checked').each(function () {
                    pay_period_ids.push($(this).val());
                });
            }


            if (pay_period_ids.length === 0) {
                alert('Please select at least one pay period.');
                return;
            }

            let url = `/payroll/change_status`;
            await submitFunc(url, action, pay_period_ids);
        });

        $(document).on('click', '#generate_paystubs', async function(){
            let url = `/payroll/generate_paystubs`;
            let action = 'generate_paystubs';
            let pay_period_ids = [];
            $('.step_4_checkbox:checked').each(function () {
                pay_period_ids.push($(this).val());
            });

            await submitFunc(url, action, pay_period_ids);
        })

        async function submitFunc(url, action, pay_period_ids) {
            let formData = new FormData();
            formData.append('action', action);

            pay_period_ids.forEach(id => {
                formData.append('pay_period_ids[]', id);
            });

            let method = 'POST';

            try {
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    location.reload(); // Reload page on success
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function CheckAll(name) {
            let checkboxes = $('.' + name + '_checkbox');
            let masterCheckbox = $('.' + name + '_select_all');

            checkboxes.prop('checked', masterCheckbox.prop('checked'));
        }



    </script>

</x-app-layout>