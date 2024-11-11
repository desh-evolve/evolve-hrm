<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="{{ asset('assets/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1" id="title-name"></h3>
                    <p class="text-white text-opacity-75" id="title-role"></p>
                    <div class="text-white-50 gap-1">
                        <div class="me-2"><i class="ri-building-line me-1 text-white text-opacity-75 fs-16 align-middle" id="title-company"></i></div>
                        <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle" id="title-company-address"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-end">
                    <div class="col-lg-12 col-12">
                        <div class="p-2">
                            <p class="fs-14 mb-0">Employee No</p>
                            <h4 class="text-white mb-1" id="title-employee-no">#243</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex justify-content-between profile-wrapper">
                    <!-- Nav tabs -->
                    <div>
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                                    <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Documents</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                    <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Activities</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#qualifications" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Qualifications</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#work_experience" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Work Experience</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#promotions" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Promotions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#job_history" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Job History</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#kpi" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">KPI</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#bank" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Bank</span>
                                </a>
                            </li>
                        </ul>

                        <!--
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1 mt-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Wage</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Deductions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Paystub Amendments</span>
                                </a>
                            </li>
                        </ul>
                        -->
                    </div>
                    <div class="flex-shrink-0">
                        <a href="pages-profile-settings.html" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div>
                </div>

                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">

                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Basic Contact Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0" id="basic-contact-info">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Name :</th>
                                                        <td class="text-muted" id="name">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Full Name :</th>
                                                        <td class="text-muted" id="full-name">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Name with Initials :</th>
                                                        <td class="text-muted" id="name-initials">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Mobile :</th>
                                                        <td class="text-muted" id="mobile">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">E-mail :</th>
                                                        <td class="text-muted" id="email">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Address :</th>
                                                        <td class="text-muted" id="address">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Location :</th>
                                                        <td class="text-muted" id="location">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">NIC :</th>
                                                        <td class="text-muted" id="nic">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">DOB :</th>
                                                        <td class="text-muted" id="dob">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Gender :</th>
                                                        <td class="text-muted" id="gender">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Religion :</th>
                                                        <td class="text-muted" id="religion">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Marital Status :</th>
                                                        <td class="text-muted" id="marital-status">Loading...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Other Contact Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0" id="other-contact-info">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Work Contact :</th>
                                                        <td class="text-muted" id="work-contact">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Work E-mail :</th>
                                                        <td class="text-muted" id="work-email">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Immediate Contact Person :</th>
                                                        <td class="text-muted" id="immediate-contact-person">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Immediate Contact :</th>
                                                        <td class="text-muted" id="immediate-contact">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Home Contact :</th>
                                                        <td class="text-muted" id="home-contact">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">EPF Reg No :</th>
                                                        <td class="text-muted" id="epf-reg-no">Loading...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-xxl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Basic Employment Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0" id="basic-employment-info">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Employee Status :</th>
                                                        <td class="text-muted" id="employee-status">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Employee Number :</th>
                                                        <td class="text-muted" id="employee-number">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Punch Machine User ID :</th>
                                                        <td class="text-muted" id="punch-id">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Branch :</th>
                                                        <td class="text-muted" id="branch">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Department :</th>
                                                        <td class="text-muted" id="department">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Employment Group :</th>
                                                        <td class="text-muted" id="employment-group">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Designation :</th>
                                                        <td class="text-muted" id="designation">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Basis of Employment :</th>
                                                        <td class="text-muted" id="basis-employment">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Currency :</th>
                                                        <td class="text-muted" id="currency">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Pay Period Schedule :</th>
                                                        <td class="text-muted" id="pay-period">Loading...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Other Employment Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0" id="other-employment-info">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Appointment Date :</th>
                                                        <td class="text-muted" id="appointment-date">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Appointment Note :</th>
                                                        <td class="text-muted" id="appointment-note">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Termination Date :</th>
                                                        <td class="text-muted" id="termination-date">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Termination Note :</th>
                                                        <td class="text-muted" id="termination-note">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Confirmed Date :</th>
                                                        <td class="text-muted" id="confirmed-date">Loading...</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Retirement Date :</th>
                                                        <td class="text-muted" id="retirement-date">Loading...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <h5 class="card-title flex-grow-1 mb-0">Documents</h5>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">File Name</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Upload Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-info-subtle text-info rounded fs-20 material-shadow">
                                                                        <i class="ri-folder-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Project Screenshots Collection</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Floder File</td>
                                                        <td>08 Nov 2021</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_download_document" title="Download Document" data-tooltip="tooltip" data-bs-placement="top">
                                                                <i class="ri-download-2-line"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="activities" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Activities</h5>
                                <div class="acitivity-timeline">
                                    <div class="acitivity-item d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">Oliver Phillips</h6>
                                            <p class="text-muted mb-2">We talked about a project on linkedin.</p>
                                            <small class="mb-0 text-muted">Today</small>
                                        </div>
                                    </div>
                                    <div class="acitivity-item py-3 d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{asset('assets/images/users/avatar-6.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">Bethany Johnson</h6>
                                            <p class="text-muted mb-2">added a new member to velzon dashboard</p>
                                            <small class="mb-0 text-muted">19 Nov</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="qualifications" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Qualifications</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Qualification</th>
                                                        <th scope="col">Institute</th>
                                                        <th scope="col">Year</th>
                                                        <th scope="col">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>BSc</td>
                                                        <td>Colombo University</td>
                                                        <td>2022</td>
                                                        <td>First Class</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>MSc</td>
                                                        <td>Colombo University</td>
                                                        <td>2024</td>
                                                        <td>Batch Top</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="work_experience" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Work Experience</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Company</th>
                                                        <th scope="col">Period</th>
                                                        <th scope="col">Department</th>
                                                        <th scope="col">Designation</th>
                                                        <th scope="col">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Evolve</td>
                                                        <td>2024-10-24 to now</td>
                                                        <td>Software Department</td>
                                                        <td>Software Engineer</td>
                                                        <td>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus, enim?</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="promotions" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Promotions</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Prev Designation</th>
                                                        <th scope="col">New Designation</th>
                                                        <th scope="col">Current Salary</th>
                                                        <th scope="col">New Salary</th>
                                                        <th scope="col">Effective Date</th>
                                                        <th scope="col">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Software Engineer</td>
                                                        <td>Senior Software Engineer</td>
                                                        <td>100000.00</td>
                                                        <td>250000.00</td>
                                                        <td>2024-12-31</td>
                                                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni doloribus fuga quod dolores reprehenderit quae!</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="job_history" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Job History</h5>
                                <div class="acitivity-timeline">
                                    <div class="acitivity-item d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">Oliver Phillips</h6>
                                            <p class="text-muted mb-2">We talked about a project on linkedin.</p>
                                            <small class="mb-0 text-muted">Today</small>
                                        </div>
                                    </div>
                                    <div class="acitivity-item py-3 d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{asset('assets/images/users/avatar-6.jpg')}}" alt="" class="avatar-xs rounded-circle acitivity-avatar material-shadow" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">Bethany Johnson</h6>
                                            <p class="text-muted mb-2">added a new member to velzon dashboard</p>
                                            <small class="mb-0 text-muted">19 Nov</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="kpi" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">KPI</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">File Name</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Upload Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-info-subtle text-info rounded fs-20 material-shadow">
                                                                        <i class="ri-folder-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Project Screenshots Collection</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Floder File</td>
                                                        <td>08 Nov 2021</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_download_document" title="Download Document" data-tooltip="tooltip" data-bs-placement="top">
                                                                <i class="ri-download-2-line"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                    <div class="tab-pane fade" id="bank" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Bank Details</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <tbody>
                                                    <tr><th>Bank Code</th><td>123456789</td></tr>
                                                    <tr><th>Bank Name</th><td>Commercial Bank</td></tr>
                                                    <tr><th>Bank Branch</th><td>Kandy</td></tr>
                                                    <tr><th>Account No</th><td>78945123</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->

                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>

    <!-- javascript functions -->
    @include('employee.emp_profile_js')
</x-app-layout>