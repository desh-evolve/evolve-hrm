<!-- desh(2024-10-15) -->
<x-app-layout :title="'Input Example'">
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Company Information') }}</h4>

        <!--
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Basic Elements</li>
            </ol>
        </div>
        -->
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Company Details</h4>
                </div>
                <div class="card-body">

                    <form method="POST" id="company_form">
                        <div class="row">
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="company_name" class="form-label mb-1">Company Name</label>
                                <input type="text" class="form-control" id="company_name" placeholder="Enter Company Name" required>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="company_short_name" class="form-label mb-1">Company Short Name</label>
                                <input type="text" class="form-control" id="company_short_name" placeholder="Enter Company Short Name">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="industry_id" class="form-label mb-1">Industry</label>
                                <select class="form-select" id="industry_id">
                                    <option value="">Select Industry</option>
                                    <option value="1">Industry 1</option>
                                    <option value="2">Industry 2</option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="business_registration_no" class="form-label mb-1">Business / Employer Identification Number</label>
                                <input type="text" class="form-control" id="business_registration_no" placeholder="Enter Business ID No">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="contact_1" class="form-label mb-1">Contact 1</label>
                                <input type="text" class="form-control" id="contact_1" placeholder="Enter Contact 1">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="contact_2" class="form-label mb-1">Contact 2</label>
                                <input type="text" class="form-control" id="contact_2" placeholder="Enter Contact 2">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="email" class="form-label mb-1">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter Email">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="website" class="form-label mb-1">Website Link</label>
                                <input type="text" class="form-control" id="website" placeholder="Enter Website Link">
                            </div>

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="epf_reg_no" class="form-label mb-1">EPF Reg No</label>
                                <input type="text" class="form-control" id="epf_reg_no" placeholder="Enter EPF Reg No">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="tin_no" class="form-label mb-1">TIN No</label>
                                <input type="text" class="form-control" id="tin_no" placeholder="Enter TIN No">
                            </div>
                        
                        </div>
                        <div class="row">

                            <div class="col-xxl-6 col-md-6 mb-3">
                                <label for="address_1" class="form-label mb-1">Address Line 1</label>
                                <input type="text" class="form-control" id="address_1" placeholder="Enter Address Line 1">
                            </div>
                            <div class="col-xxl-6 col-md-6 mb-3">
                                <label for="address_2" class="form-label  mb-1">Address Line 2</label>
                                <input type="text" class="form-control" id="address_2" placeholder="Enter Address Line 2">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="postal_code" class="form-label mb-1">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" placeholder="Enter Postal Code">
                            </div>   

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="country_id" class="form-label mb-1">Country</label>
                                <select class="form-select" id="country_id">
                                    <option value="">Select Country</option>
                                    <option value="1">Country 1</option>
                                    <option value="2">Country 2</option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="province_id" class="form-label mb-1">Province/State</label>
                                <div class="input-group">
                                    <select class="form-select" id="province_id">
                                        <option selected>Select Province</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button">Add New</button>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="city_id" class="form-label mb-1">City</label>
                                <div class="input-group">
                                    <select class="form-select" id="city_id">
                                        <option selected>Select City</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button">Add New</button>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="admin_contact_id" class="form-label mb-1">Admin Contact</label>
                                <select class="form-select" id="admin_contact_id">
                                    <option value="">Select Admin Contact</option>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="billing_contact_id" class="form-label mb-1">Billing Contact</label>
                                <select class="form-select" id="billing_contact_id">
                                    <option value="">Select Billing Contact</option>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="primary_contact_id" class="form-label mb-1">Primary Contact</label>
                                <select class="form-select" id="primary_contact_id">
                                    <option value="">Select Primary Contact</option>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div>
                           
                        </div>
                        <div class="row">
                            
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="logo_img" class="form-label mb-1">Logo Large</label>
                                <input type="file" class="form-control" id="logo_img">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="logo_small_img" class="form-label mb-1">Logo Small</label>
                                <input type="file" class="form-control" id="logo_small_img">
                            </div>

                        </div>
                        <div class="row">

                            <div class="d-flex justify-content-end">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>