<!-- desh(2024-10-23) -->
<x-app-layout :title="'Input Example'">
    <style>
        td {
            padding: 5px 10px !important;
        }

        .step-arrow-nav .nav .nav-link {
            background-color: rgb(1 19 78 / 15%);
        }
        .step-arrow-nav .nav .nav-link.active {
            background-color: rgb(1 19 78 / 50%);
            color: #ffffff;
        }
        .step-arrow-nav .nav .nav-link.active::before {
            border-left-color: rgb(1 19 78 / 50%);
        }
    </style>

    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Form') }}</h4>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="#" class="form-steps" autocomplete="off">
                    <div class="card-header">
                        <div class="step-arrow-nav mb-4">
                            <ul class="nav nav-pills custom-nav nav-justified" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="steparrow-basic-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-basic-info" type="button" role="tab" aria-controls="steparrow-basic-info" aria-selected="true">Employee Identification</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="steparrow-gen-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-contact-info" type="button" role="tab" aria-controls="steparrow-contact-info" aria-selected="true">Contact Information</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="steparrow-document-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-document-info" type="button" role="tab" aria-controls="steparrow-document-info" aria-selected="false">Documents</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">

                            <!-- start employee identification tab pane -->
                            <div class="tab-pane fade show active" id="steparrow-basic-info" role="tabpanel" aria-labelledby="steparrow-basic-info-tab">
                                <div class="row border-bottom">
                                    <div class="col-lg-6 border-end">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employee_no">Employee Number</label>
                                                    <input type="text" class="form-control" id="employee_no" placeholder="Enter Employee Number" />
                                                    <div class="invalid-feedback">Please enter an employee number</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="punch_machine_user_id">Punch Machine User ID</label>
                                                    <input type="text" class="form-control" id="punch_machine_user_id" placeholder="Enter Punch Machine User ID" />
                                                    <div class="invalid-feedback">Please enter a punch machine user ID</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Branch</label>
                                                    <select class="form-select" id="branch_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a branch</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="department_id">Department</label>
                                                    <select class="form-select" id="department_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a department</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employment_group_id">Employment Group</label>
                                                    <select class="form-select" id="employment_group_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter an employment group</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="designation_id">Designation</label>
                                                    <select class="form-select" id="designation_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a designation</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="policy_group_id">Policy Group</label>
                                                    <select class="form-select" id="policy_group_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a policy_group</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employee_status">Employee Status</label>
                                                    <select class="form-select" id="employee_status">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter an employee status</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">    
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="currency_id">Currency</label>
                                                    <select class="form-select" id="currency_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a currency</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="pay_period_schedule_id">Pay Period Schedule</label>
                                                    <select class="form-select" id="pay_period_schedule_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a Pay Period</div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row border-bottom">
                                            <div class="col-lg-6 border-end">
                                                <div class="mb-3">
                                                    <label class="form-label" for="appointment_date">Appointment Date</label>
                                                    <input type="date" class="form-control" id="appointment_date" />
                                                    <div class="invalid-feedback">Please enter an appointment date</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="appointment_note">Appointment Note</label>
                                                    <textarea class="form-control" id="appointment_note" placeholder="Enter Appointment Note"></textarea>
                                                    <div class="invalid-feedback">Please enter an appointment note</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="termination_date">Termination Date</label>
                                                    <input type="date" class="form-control" id="termination_date" />
                                                    <div class="invalid-feedback">Please enter an termination date</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="termination_note">Termination Note</label>
                                                    <textarea class="form-control" id="termination_note" placeholder="Enter Termination Note"></textarea>
                                                    <div class="invalid-feedback">Please enter an termination note</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label" for="confirmed_date">Confirmed Date</label>
                                                    <input type="date" class="form-control" id="confirmed_date" />
                                                    <div class="invalid-feedback">Please enter a confirmed date</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label" for="retirement_date">Retirement Date</label>
                                                    <input type="date" class="form-control" id="retirement_date" />
                                                    <div class="invalid-feedback">Please enter a retirement date</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row border-top">
                                            <div class="col-lg-6">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label" for="employee_status">Basis of Employment</label>
                                                    <div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="employment_type" id="contract" value="Contract">
                                                            <label class="form-check-label" for="contract">Contract</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="employment_type" id="training" value="Training">
                                                            <label class="form-check-label" for="training">Training</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="employment_type" id="permanentProbation" value="PermanentProbation">
                                                            <label class="form-check-label" for="permanentProbation">Permanent (With Probation)</label>
                                                        </div>
                                                        <hr class="mt-1 mb-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="employment_type" id="permanentConfirmed" value="PermanentConfirmed">
                                                            <label class="form-check-label" for="permanentConfirmed">Permanent (Confirmed)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="employment_type" id="resign" value="Resign">
                                                            <label class="form-check-label" for="resign">Resign</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="employment_type" id="external" value="External">
                                                            <label class="form-check-label" for="external">External</label>
                                                        </div>
                                                    </div>
                                                    <div class="invalid-feedback">Please enter an employee status</div>
                                                </div>
                                            </div>
                                        
                                            <div class="col-lg-6" id="month-selection" style="display: none;">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label" for="months">Select Duration in Months</label>
                                                    <input type="number" class="form-control numonly" id="months" name="months" min="1" max="12" placeholder="Enter months">
                                                    <div class="invalid-feedback">Please enter a valid month duration</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="permission_group_id">Permission Group</label>
                                            <select class="form-select" id="permission_group_id">
                                                <option value="">Select</option>
                                            </select>
                                            <div class="invalid-feedback">Please enter a permission group</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control" id="email" placeholder="Enter email" required autocomplete="new-password" >
                                            <div class="invalid-feedback">Please enter an email address</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" class="form-control" id="password" placeholder="Enter password" required autocomplete="new-password" >
                                            <div class="invalid-feedback">Please enter a password</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="confirm_password">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm_password" placeholder="Enter confirm password" required >
                                            <div class="invalid-feedback">Please enter a confirm password</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="steparrow-contact-info-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to contact info</button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <!-- start contact information tab pane -->
                            <div class="tab-pane fade" id="steparrow-contact-info" role="tabpanel" aria-labelledby="steparrow-contact-info-tab">
                                <div class="row border-bottom">
                                    <div class="col-lg-6 border-end">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">Title</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                        <option value="Mr">Mr</option>
                                                        <option value="Mrs">Mrs</option>
                                                        <option value="Miss">Miss</option>
                                                        <option value="Hon">Hon</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name_with_initials">Name with initials</label>
                                                    <input type="text" class="form-control" id="name_with_initials" placeholder="Enter Name with initials" />
                                                    <div class="invalid-feedback">Please enter name with initials</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" placeholder="Enter First Name" />
                                                    <div class="invalid-feedback">Please enter first name</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" placeholder="Enter Last Name" />
                                                    <div class="invalid-feedback">Please enter last name</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="full_name">Full Name</label>
                                                    <textarea class="form-control" id="full_name" placeholder="Enter Full Name" ></textarea>
                                                    <div class="invalid-feedback">Please enter full name</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">DOB</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter full name</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">NIC</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter full name</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="employee_status">Gender</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter full name</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">Religion</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">Marital Status</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                        <option value="single">Single</option>
                                                        <option value="married">Married</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Personal Email</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Contact 1</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Contact 2</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Address Line 1</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Address Line 2</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Address Line 3</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Postal Code</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">Country</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">Province</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">City</label>
                                                    <select class="form-select" id="title">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Work Email</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Work Contact</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Immediate Contact Person</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Immediate Contact No</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">Home Contact</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="branch_id">EPF Reg No</label>
                                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="steparrow-basic-info-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to Employee Identification</button>
                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="steparrow-document-info-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to Documents</button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <!-- start documents tab pane -->
                            <div class="tab-pane fade" id="steparrow-document-info" role="tabpanel" aria-labelledby="steparrow-document-info-tab">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="title">Document Type</label>
                                            <select class="form-select" id="title">
                                                <option value="">Select</option>
                                                <option value="1">Appointment Letter</option>
                                                <option value="2">Personal Files</option>
                                                <option value="3">ID Copy</option>
                                                <option value="4">Birth Certificate</option>
                                                <option value="5">GS Letter</option>
                                                <option value="6">Police Report</option>
                                                <option value="7">NDA</option>
                                                <option value="8">Bond</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="name_with_initials">Title</label>
                                            <input type="text" class="form-control" id="name_with_initials" placeholder="Enter Document Title" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Select File</label>
                                            <input class="form-control" type="file" id="formFile" />
                                        </div>
                                    </div>
                                    <div class="col-lg-2 d-flex align-items-end mb-3">
                                        <div>
                                            <button class="btn btn-primary"><i class="ri-add-line"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Document Type</th>
                                            <th>Document Title</th>
                                            <th>Document</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="document_tbody">
                                        <tr>
                                            <td colspan="5" class="text-center">No Documents Selected</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Appointment Letter</td>
                                            <td>Appointment Letter</td>
                                            <td>file.pdf</td>
                                            <td>
                                                <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_download_document" title="Download Document" data-tooltip="tooltip" data-bs-placement="top">
                                                    <i class="ri-download-2-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_document" title="Remove Document" data-tooltip="tooltip" data-bs-placement="top">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="steparrow-gen-info-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to General</button>
                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab" data-nexttab="pills-experience-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                        </div>
                        <!-- end tab content -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- javascript functions -->
    @include('employee.emp_form_js')

</x-app-layout>