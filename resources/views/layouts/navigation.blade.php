<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="auto">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm-light.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="auto">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>

            <!-- Main Navigation -->
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span>Menu</span></li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <!-- Attendance -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#attendance" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="ri-time-line"></i> <span>Attendance</span>
                    </a>
                    <div class="collapse menu-dropdown" id="attendance">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="#" class="nav-link">My Time Sheet</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Punches</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Mass Punch</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Requests</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Apply Leaves</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Schedule -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#schedule" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="ri-calendar-line"></i> <span>Schedule</span>
                    </a>
                    <div class="collapse menu-dropdown" id="schedule">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="#" class="nav-link">My Schedule</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Mass Schedule</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Policies -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#policies" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="ri-file-paper-line"></i> <span>Policies</span>
                    </a>
                    <div class="collapse menu-dropdown" id="policies">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="#" class="nav-link">Policy Groups</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Schedule Policies</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Rounding Policies</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Meal Policies</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Employees -->
                @php
                    $checkEmployeeNav = request()->routeIs('employee.*');
                @endphp

                <li class="nav-item">
                    <a
                        class="nav-link menu-link {{ $checkEmployeeNav ? 'active' : '' }}"
                        href="#employeeMultiLevel"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ $checkEmployeeNav ? 'true' : 'false' }}"
                        aria-controls="employeeMultiLevel"
                    >
                        <i class="ri-group-line"></i> <span data-key="t-multi-level">Employees</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $checkEmployeeNav ? 'show' : '' }}" id="employeeMultiLevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('employee.create') }}" class="nav-link {{ request()->routeIs('employee.create') ? 'active' : '' }}">Add New Employee</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.list') }}" class="nav-link {{ request()->routeIs('employee.list') ? 'active' : '' }}">Employee List</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">My Details</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Messages</a>
                            </li>
                        </ul>
                    </div>
                </li>
                

                <!-- Reports -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#reports" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="ri-bar-chart-line"></i> <span>Reports</span>
                    </a>
                    <div class="collapse menu-dropdown" id="reports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="#" class="nav-link">EPF Report</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Employee Report</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Payroll -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#payroll" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="ri-money-dollar-circle-line"></i> <span>Payroll</span>
                    </a>
                    <div class="collapse menu-dropdown" id="payroll">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="#" class="nav-link">End of Pay Period</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Pay Stub Amendment</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Pay Period Schedule</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Pay Stub Account</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Settings Dropdown -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('profile.*') || request()->routeIs('permissions.*') ? 'active' : '' }}" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('profile.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }}" aria-controls="sidebarSettings">
                        <i class="ri-apps-2-line"></i> <span data-key="t-settings">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('profile.*') || request()->routeIs('permissions.*') ? 'show' : '' }}" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"> Profile </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}"> Permissions </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Company -->
                @php
                    $checkCompanyNav = request()->routeIs('company.*') || request()->routeIs('location.*');
                @endphp

                <li class="nav-item">
                    <a
                        class="nav-link menu-link {{ $checkCompanyNav ? 'active' : '' }}"
                        href="#companyMultiLevel"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded="{{ $checkCompanyNav ? 'true' : 'false' }}"
                        aria-controls="companyMultiLevel"
                    >
                        <i class="ri-building-line"></i> <span data-key="t-multi-level">Company</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $checkCompanyNav ? 'show' : '' }}" id="companyMultiLevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('location.index') }}" class="nav-link {{ request()->routeIs('location.index') ? 'active' : '' }}">
                                    Locations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('company.info') }}" class="nav-link {{ request()->routeIs('company.info') ? 'active' : '' }}">
                                    Company Information
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('company.branch.index') }}" class="nav-link {{ request()->routeIs('company.branch.index') ? 'active' : '' }}">
                                    Branches
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('company.department.index') }}" class="nav-link {{ request()->routeIs('company.department.index') ? 'active' : '' }}">
                                    Departments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('company.employee_designation.index') }}" class="nav-link {{ request()->routeIs('company.employee_designation.index') ? 'active' : '' }}">
                                    Designations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('company.wagegroups.index') }}" class="nav-link {{ request()->routeIs('company.wagegroups.index') ? 'active' : '' }}">
                                    Wage Groups
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('company.currency.index') }}" class="nav-link {{ request()->routeIs('company.currency.index') ? 'active' : '' }}">
                                    Currencies
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('company.employee_group.index') }}" class="nav-link {{ request()->routeIs('company.employee_group.index') ? 'active' : '' }}">
                                    Employee Groups
                                </a>
                            </li>
                            <li class="nav-item"><a href="#" class="nav-link">Stations</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Hierarchy</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">Permission Groups</a></li>
                        </ul>
                    </div>
                </li>


                <!-- Logout -->
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link menu-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="ri-logout-box-line"></i> <span data-key="t-logout">Log Out</span>
                        </a>
                    </form>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- ========== End App Menu ========== -->
