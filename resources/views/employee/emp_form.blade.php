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
                <form action="{{ url('/user/create') }}" method="POST" class="form-steps" autocomplete="on" enctype="multipart/form-data">
                    @csrf

                    <div class="card-header">
                        <div class="step-arrow-nav mb-4">
                            <ul class="nav nav-pills custom-nav nav-justified" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="steparrow-basic-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-basic-info" type="button" role="tab" aria-controls="steparrow-basic-info" aria-selected="true">Employee Identification</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="steparrow-contact-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-contact-info" type="button" role="tab" aria-controls="steparrow-contact-info" aria-selected="true">Contact Information</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="steparrow-document-info-tab" data-bs-toggle="pill" data-bs-target="#steparrow-document-info" type="button" role="tab" aria-controls="steparrow-document-info" aria-selected="false">Documents</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">

                            <!-- start user identification tab pane -->
                            <div class="tab-pane fade show active" id="steparrow-basic-info" role="tabpanel" aria-labelledby="steparrow-basic-info-tab">
                                <div class="row">
                                    <div class="col-lg-6 border-end">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="user_no">Employee Number</label>
                                                    <input type="text" class="form-control" id="user_no" name="user_no" placeholder="Enter Employee Number" required />
                                                    <div class="invalid-feedback">Please enter an user number</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="punch_machine_user_id">Punch Machine User ID</label>
                                                    <input type="text" class="form-control" id="punch_machine_user_id" name="punch_machine_user_id" placeholder="Enter Punch Machine User ID" />
                                                    <div class="invalid-feedback">Please enter a punch machine user ID</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="branch_id">Branch</label>
                                                    <select class="form-select" id="branch_id" name="branch_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a branch</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="department_id">Department</label>
                                                    <select class="form-select" id="department_id" name="department_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a department</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="employee_group_id">Employment Group</label>
                                                    <select class="form-select" id="employee_group_id" name="employee_group_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter an employment group</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="designation_id">Designation</label>
                                                    <select class="form-select" id="designation_id" name="designation_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a designation</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="policy_group_id">Policy Group</label>
                                                    <select class="form-select" id="policy_group_id" name="policy_group_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a policy_group</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="user_status">Employee Status</label>
                                                    <select class="form-select" id="user_status" name="user_status" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter an user status</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">    
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="currency_id">Currency</label>
                                                    <select class="form-select" id="currency_id" name="currency_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a currency</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="pay_period_schedule_id">Pay Period Schedule</label>
                                                    <select class="form-select" id="pay_period_schedule_id" name="pay_period_schedule_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a Pay Period</div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row border-top pt-2">
                                            <div class="col-lg-6 border-end">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="appointment_date">Appointment Date</label>
                                                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required />
                                                    <div class="invalid-feedback">Please enter an appointment date</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="appointment_note">Appointment Note</label>
                                                    <textarea class="form-control" id="appointment_note" name="appointment_note" placeholder="Enter Appointment Note"></textarea>
                                                    <div class="invalid-feedback">Please enter an appointment note</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="termination_date">Termination Date</label>
                                                    <input type="date" class="form-control" id="termination_date" name="termination_date" />
                                                    <div class="invalid-feedback">Please enter an termination date</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="termination_note">Termination Note</label>
                                                    <textarea class="form-control" id="termination_note" name="termination_note" placeholder="Enter Termination Note"></textarea>
                                                    <div class="invalid-feedback">Please enter an termination note</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label" for="confirmed_date">Confirmed Date</label>
                                                    <input type="date" class="form-control" id="confirmed_date" name="confirmed_date" />
                                                    <div class="invalid-feedback">Please enter a confirmed date</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label" for="retirement_date">Retirement Date</label>
                                                    <input type="date" class="form-control" id="retirement_date" name="retirement_date" />
                                                    <div class="invalid-feedback">Please enter a retirement date</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row border-bottom">
                                            <div class="col-lg-6">
                                                <div class="mt-3 mb-3">
                                                    <label class="form-label req" for="user_status">Basis of Employment</label>
                                                    <div id="employment_types">
                                                        
                                                    </div>
                                                    <div class="invalid-feedback">Please enter an user status</div>
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
                                        <div class="row mt-3">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="permission_group_id">Permission Group</label>
                                                    <select class="form-select" id="permission_group_id" name="permission_group_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a permission group</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required autocomplete="new-password" >
                                                    <div class="invalid-feedback">Please enter an email address</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="password">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required autocomplete="new-password" >
                                                    <div class="invalid-feedback">Please enter a password</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="confirm_password">Confirm Password</label>
                                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter confirm password" required >
                                                    <div class="invalid-feedback">Please enter a confirm password</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="error-msgs text-end"></div>
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
                                                    <label class="form-label req" for="title">Title</label>
                                                    <select class="form-select" id="title" name="title" required>
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
                                                    <label class="form-label req" for="name_with_initials">Name with initials</label>
                                                    <input type="text" class="form-control" id="name_with_initials" name="name_with_initials" placeholder="Enter Name with initials" required />
                                                    <div class="invalid-feedback">Please enter name with initials</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required />
                                                    <div class="invalid-feedback">Please enter first name</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" required />
                                                    <div class="invalid-feedback">Please enter last name</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="full_name">Full Name</label>
                                                    <textarea class="form-control" id="full_name" name="full_name" placeholder="Enter Full Name" required ></textarea>
                                                    <div class="invalid-feedback">Please enter full name</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="dob">DOB</label>
                                                    <input type="date" class="form-control" id="dob" name="dob" placeholder="Enter DOB" required />
                                                    <div class="invalid-feedback">Please enter date of birth</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="nic">NIC</label>
                                                    <input type="text" class="form-control" id="nic" name="nic" placeholder="Enter NIC" required />
                                                    <div class="invalid-feedback">Please enter NIC</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="gender">Gender</label>
                                                    <select class="form-select" id="gender" name="gender" required>
                                                        <option value="">Select</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select gender</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="religion_id">Religion</label>
                                                    <select class="form-select" id="religion_id" name="religion_id">
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select religion</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="marital_status">Marital Status</label>
                                                    <select class="form-select" id="marital_status" name="marital_status">
                                                        <option value="">Select</option>
                                                        <option value="single">Single</option>
                                                        <option value="married">Married</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select marital status</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="personal_email">Personal Email</label>
                                                    <input type="text" class="form-control" id="personal_email" name="personal_email" placeholder="Enter Personal Email" required />
                                                    <div class="invalid-feedback">Please enter an email</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="contact_1">Contact 1</label>
                                                    <input type="text" class="form-control" id="contact_1" name="contact_1" placeholder="Enter Contact 1" required />
                                                    <div class="invalid-feedback">Please enter a contact number</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="contact_2">Contact 2</label>
                                                    <input type="text" class="form-control" id="contact_2" name="contact_2" placeholder="Enter Contact 2" />
                                                    <div class="invalid-feedback">Please enter a contact number</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="user_photo">Employee Photo</label>
                                                    <input type="file" class="form-control" id="user_photo" name="user_photo" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="address_1">Address Line 1</label>
                                                    <input type="text" class="form-control" id="address_1" name="address_1" placeholder="Enter Address Line 1" required />
                                                    <div class="invalid-feedback">Please enter a address</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="address_2">Address Line 2</label>
                                                    <input type="text" class="form-control" id="address_2" name="address_2" placeholder="Enter Address Line 2" />
                                                    <div class="invalid-feedback">Please enter a address</div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="address_3">Address Line 3</label>
                                                    <input type="text" class="form-control" id="address_3" name="address_3" placeholder="Enter Address Line 3" />
                                                    <div class="invalid-feedback">Please enter a address</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="postal_code">Postal Code</label>
                                                    <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Enter Postal Code" />
                                                    <div class="invalid-feedback">Please enter a postal code</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="country_id">Country</label>
                                                    <select class="form-select" id="country_id" name="country_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a country</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="province_id">Province</label>
                                                    <select class="form-select" id="province_id" name="province_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a province</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label req" for="city_id">City</label>
                                                    <select class="form-select" id="city_id" name="city_id" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a city</div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="work_email">Work Email</label>
                                                    <input type="text" class="form-control" id="work_email" name="work_email" placeholder="Enter Work Email" />
                                                    <div class="invalid-feedback">Please enter work email</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="work_contact">Work Contact</label>
                                                    <input type="text" class="form-control" id="work_contact" name="work_contact" placeholder="Enter Work Contact" />
                                                    <div class="invalid-feedback">Please enter a title</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="immediate_contact_person">Immediate Contact Person</label>
                                                    <input type="text" class="form-control" id="immediate_contact_person" name="immediate_contact_person" placeholder="Enter Immediate Contact Person" />
                                                    <div class="invalid-feedback">Please enter an immediate contact person</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="immediate_contact_no">Immediate Contact No</label>
                                                    <input type="text" class="form-control" id="immediate_contact_no" name="immediate_contact_no" placeholder="Enter Immediate Contact No" />
                                                    <div class="invalid-feedback">Please enter an immediate contact number</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="home_contact">Home Contact</label>
                                                    <input type="text" class="form-control" id="home_contact" name="home_contact" placeholder="Enter Home Contact" />
                                                    <div class="invalid-feedback">Please enter home contact</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="epf_no">EPF Reg No</label>
                                                    <input type="text" class="form-control" id="epf_no" name="epf_no" placeholder="Enter EPF Reg No" />
                                                    <div class="invalid-feedback">Please enter EPF Reg No</div>
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
                                            <label class="form-label" for="doc_type_id">Document Type</label>
                                            <select class="form-select" id="doc_type_id">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="doc_title">Title</label>
                                            <input type="text" class="form-control" id="doc_title" placeholder="Enter Document Title" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="doc_file" class="form-label">Select File</label>
                                            <input class="form-control" type="file" id="doc_file" />
                                        </div>
                                    </div>
                                    <div class="col-lg-2 d-flex align-items-end mb-3">
                                        <div>
                                            <button type="button" class="btn btn-primary add_doc_to_list"><i class="ri-add-line"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table">
                                    <thead class="bg-primary text-white"/>
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Document Title</th>
                                            <th>Document</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="document_tbody">
                                        <!-- Documents will be appended here -->
                                    </tbody>
                                </table>

                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <input type="hidden" name="user_id" id="user_id" />
                                    <button type="button" class="btn btn-light btn-label previestab" data-previous="steparrow-gen-info-tab"><i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to General</button>
                                    <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab emp_form_submit" data-nexttab="steparrow-document-info-tab"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
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
    @include('user.emp_form_js')

</x-app-layout>