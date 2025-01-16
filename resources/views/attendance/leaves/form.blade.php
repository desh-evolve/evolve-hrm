<x-app-layout :title="'Input Example'">

    <style>
        th, td{
           padding: 2px 10px !important; 
        }

        .numonly {
            -moz-appearance: textfield;
        }

        .calendar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 5px;
            margin-top: 10px;
            text-align: center;
        }

        .calendar-day {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .calendar-day.selected {
            background-color: #007bff;
            color: white;
        }

        .calendar-day:hover {
            background-color: #e9ecef;
        }
    </style>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Apply Employee Leaves</h5>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="{{ $user_data->first_name . ' ' . $user_data->last_name }}" disabled>
                            </div>
                            <input type="hidden" id="user_id" value="12345">
                
                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="designation" value="{{ $user_data->emp_designation_name }}" disabled>
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                {{-- leave types = accrual policies --}}
                                <select class="form-select" id="leaveType">
                                    <option value="">-- Please Choose --</option>
                                    @foreach ($leave_types as $leave_type)
                                        <option value="{{ $leave_type->id }}">{{ $leave_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="leaveMethod" class="form-label">Leave Method</label>
                                <select class="form-select" id="leaveMethod">
                                    <option value="">-- Please Choose --</option>
                                    @foreach ($leave_methods as $leave_method)
                                        <option value="{{ $leave_method->id }}">{{ $leave_method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3 pe-3">
                                        <label for="leaveDays" class="form-label">Leave Days</label>
                                        <div class="calendar-container" id="calendar"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div id="selectedDates" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="numberOfDays" class="form-label">Number of Days</label>
                                <input type="text" class="form-control numonly" id="numberOfDays" value="0" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime">
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime">
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" rows="3"></textarea>
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Address/ Tel. No While On Leave</label>
                                <textarea class="form-control" id="contact" rows="3"></textarea>
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="coverDuties" class="form-label">Agreed to Cover Duties</label>
                                <select class="form-select" id="coverDuties">
                                    <option value="">-- Please Choose --</option>
                                    @foreach ( $emp_list as $emp )
                                        <option value="{{ $emp->user_id }}">{{ $emp->first_name.' '.$emp->last_name }} (#{{ $emp->emp_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="col-md-6 mb-3">
                                <label for="supervisor" class="form-label">Supervisor</label>
                                <select class="form-select" id="supervisor">
                                    <option value="">-- Please Choose --</option>
                                    @foreach ( $emp_list as $emp )
                                        <option value="{{ $emp->user_id }}">{{ $emp->first_name.' '.$emp->last_name }} (#{{ $emp->emp_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Leaves Summary</h5>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th></th>
                            <th>Leave Entitlement</th>
                            <th>Leave Taken</th>
                            <th>Balance</th>
                        </tr>
                        @foreach ($leave_types as $leave_type)
                            <tr>
                                <th>{{ $leave_type->name }}</th>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">Leaves Requests</h5>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>#</th>
                            <th>Leave Type</th>
                            <th>Amount</th>
                            <th>Dates</th>
                            <th>Status</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- calander functions start --}}
    <script>
        $(document).ready(function() {
            const selectedDates = new Set();
            let currentYear = new Date().getFullYear();
            let currentMonth = new Date().getMonth();
    
            // Function to generate the calendar for the current month and year
            function generateCalendar(year, month, selected_dates) {
                $('#calendar').empty(); // Clear previous calendar content
    
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
    
                const monthsOfYear = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
    
                // Calendar Header (Year and Month Navigation)
                const calendarHeader = `
                    <div class="calendar-header d-flex justify-content-between w-100">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="prevMonth">Prev Month</button>
                        <span>${year} - ${monthsOfYear[month]}</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="nextMonth">Next Month</button>
                    </div>
                `;
                $('#calendar').append(calendarHeader);
    
                // Calendar Grid (Days of the Week + Days of the Month)
                const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                let grid = '<div class="calendar-grid d-grid grid-template-columns: repeat(7, 1fr); w-100">';
                
                // Days of the week
                daysOfWeek.forEach(day => {
                    grid += `<div class="text-center">${day}</div>`;
                });
    
                // Empty cells before the first day of the month
                for (let i = 0; i < firstDay.getDay(); i++) {
                    grid += `<div></div>`;
                }
    
                // Days of the month
                for (let i = 1; i <= lastDay.getDate(); i++) {
                    const formattedDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                    const isSelected = selectedDates.has(formattedDate);  // Check if the formatted date is in the selected dates set
                    grid += `
                        <div class="calendar-day text-center ${isSelected ? 'selected' : ''}" data-date="${formattedDate}">${i}</div>
                    `;
                }
    
                grid += '</div>';
                $('#calendar').append(grid);
    
                // Attach click event to the days
                $('.calendar-day').click(function() {
                    const date = $(this).data('date');
                    if (date) {
                        toggleDateSelection(date);
                    }
                });
            }
    
            // Toggle date selection (highlight/deselect)
            function toggleDateSelection(date) {
                if (selectedDates.has(date)) {
                    selectedDates.delete(date);
                    $(`.calendar-day[data-date="${date}"]`).removeClass('selected');
                } else {
                    selectedDates.add(date);
                    $(`.calendar-day[data-date="${date}"]`).addClass('selected');
                }
                updateSelectedDates();
            }
    
            // Update the selected dates list
            function updateSelectedDates() {
                console.log(selectedDates)
                $('#selectedDates').empty();
                let count = 0;
                selectedDates.forEach(date => {
                    $('#selectedDates').append(`
                        <span class="badge bg-primary-subtle text-primary fs-6 mb-2 me-2">${date} <i class="ri-close-line cursor-pointer text-danger remove-date" data-date="${date}"></i></span>
                    `);
                    count++;
                });

                $('#numberOfDays').val(count);
            }
    
            // Remove selected date
            $(document).on('click', '.remove-date', function() {
                const date = $(this).data('date');
                selectedDates.delete(date);
                $(`.calendar-day[data-date="${date}"]`).removeClass('selected');
                updateSelectedDates();
            });
    
            // Change the month (prev or next)
            $(document).on('click', '#prevMonth', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                generateCalendar(currentYear, currentMonth, selectedDates);
            });
    
            $(document).on('click', '#nextMonth', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                generateCalendar(currentYear, currentMonth, selectedDates);
            });
    
            // Initialize the calendar
            generateCalendar(currentYear, currentMonth, selectedDates);
        });
    </script>
    {{-- calander functions end --}}

    {{-- other functions start --}}
    <script>

    </script>
    {{-- other functions end --}}
</x-app-layout>
