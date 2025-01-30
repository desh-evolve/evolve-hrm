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
                    <form method="POST" id="company_form" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Company Name -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="company_name" class="form-label mb-1">Company Name</label>
                                <input type="text" class="form-control" id="company_name"
                                    placeholder="Enter Company Name" required>
                            </div>

                            <!-- Company Short Name -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="company_short_name" class="form-label mb-1">Company Short Name</label>
                                <input type="text" class="form-control" id="company_short_name"
                                    placeholder="Enter Company Short Name">
                            </div>

                            <!-- Industry -->
                            {{-- <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="industry_id" class="form-label mb-1">Industry</label>
                                <select class="form-select" id="industry_id">
                                    <option value="">Select Industry</option>
                                    <option value="1">Industry 1</option>
                                    <option value="2">Industry 2</option>
                                </select>
                            </div> --}}
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="industry_id" class="form-label mb-1 req">Industry</label>
                                <select class="form-select" id="industry_id">
                                    <option value="">Select a Industry</option>
                                </select>
                            </div>

                            <!-- Business Registration Number -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="business_reg_no" class="form-label mb-1">Business / Employer
                                    Identification Number</label>
                                <input type="text" class="form-control" id="business_reg_no"
                                    placeholder="Enter Business ID No">
                            </div>

                            <!-- Contacts -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="contact_1" class="form-label mb-1">Contact 1</label>
                                <input type="text" class="form-control" id="contact_1" placeholder="Enter Contact 1">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="contact_2" class="form-label mb-1">Contact 2</label>
                                <input type="text" class="form-control" id="contact_2" placeholder="Enter Contact 2">
                            </div>

                            <!-- Email and Website -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="email" class="form-label mb-1">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter Email">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="website" class="form-label mb-1">Website Link</label>
                                <input type="text" class="form-control" id="website"
                                    placeholder="Enter Website Link">
                            </div>

                            <!-- More fields (EPF, TIN, Address) -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="epf_reg_no" class="form-label mb-1">EPF Reg No</label>
                                <input type="text" class="form-control" id="epf_reg_no"
                                    placeholder="Enter EPF Reg No">
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="tin_no" class="form-label mb-1">TIN No</label>
                                <input type="text" class="form-control" id="tin_no" placeholder="Enter TIN No">
                            </div>

                            <!-- Address -->
                            <div class="col-xxl-6 col-md-6 mb-3">
                                <label for="address_1" class="form-label mb-1">Address Line 1</label>
                                <input type="text" class="form-control" id="address_1"
                                    placeholder="Enter Address Line 1">
                            </div>
                            <div class="col-xxl-6 col-md-6 mb-3">
                                <label for="address_2" class="form-label mb-1">Address Line 2</label>
                                <input type="text" class="form-control" id="address_2"
                                    placeholder="Enter Address Line 2">
                            </div>

                            <!-- Postal Code, Country, Province, City -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="postal_code" class="form-label mb-1">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code"
                                    placeholder="Enter Postal Code">
                            </div>

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="country_id" class="form-label mb-1 req">Country</label>
                                <select class="form-select" id="country_id">
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="province_id" class="form-label mb-1 req">Province</label>
                                <select class="form-select" id="province_id">
                                    <option value="">Select a country first</option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="city_id" class="form-label mb-1 req">City</label>
                                <select class="form-select" id="city_id">
                                    <option value="">Select a country first</option>
                                </select>
                            </div>

                            <!-- Contacts -->

                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="admin_contact_id" class="form-label mb-1">Admin Contact</label>
                                <select class="form-select" id="admin_contact_id">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="billing_contact_id" class="form-label mb-1 me-2">Billing Contact</label>
                                <select class="form-select" id="billing_contact_id">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="primary_contact_id" class="form-label mb-1">Primary Contact</label>
                                <select class="form-select" id="primary_contact_id">
                                    <option value=""></option>
                                </select>
                            </div>

                            <!-- Logo Upload -->
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="logo_img" class="form-label mb-1">Logo Large</label>
                                <input type="file" class="form-control" id="company_logo" accept="image/*">
                                <img id="company_logo_i" src="" alt="Company Logo"
                                    style="max-width: 70px;" />
                            </div>
                            <div class="col-xxl-3 col-md-6 mb-3">
                                <label for="logo_small_img" class="form-label mb-1">Logo Small</label>
                                <input type="file" class="form-control" id="company_logo_small">
                                <img id="company_logo_small_i" src="" alt="Company Small Logo"
                                    style="max-width: 70px;" />
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn w-sm btn-primary"
                                    id="submit-confirm">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // ======================new code===================

        $(document).ready(async function() {
            // Fetch dropdown data first
            await getDropdownData();
            // Call the function to load the company data on page load
            await generateCompanyForm();
        });
        //=====================================================================================
        async function getDropdownData() {
            try {
                dropdownData = await commonFetchData('/company/dropdown');
                console.log(dropdownData);

                // Populate country dropdown
                let countryList = (dropdownData?.countries || [])
                    .map(country =>
                        `<option value="${country.id}">${country.country_name} (${country.country_code})</option>`)
                    .join('');
                $('#country_id').html('<option value="">Select a country</option>' + countryList);

                let industryList = (dropdownData?.industries || [])
                    .map(industry =>
                        `<option value="${industry.id}">${industry.industry_name}</option>`)
                    .join('');
                $('#industry_id').html('<option value="">Select a Industry</option>' + industryList);

                // Default values for province and city
                $('#province_id').html('<option value="">Select a country first</option>');

                $('#city_id').html('<option value="">Select a country first</option>');

                // Populate dropdown
                // Populate admin, billing, and primary contact dropdowns
                let employeeList = (dropdownData?.employees || [])
                    .map(employee =>
                        `<option value="${employee.id}">${employee.first_name} ${employee.last_name}</option>`)
                    .join('');
                $('#admin_contact_id').html('<option value="">Select an Admin</option>' + employeeList);
                $('#billing_contact_id').html('<option value="">Select a Billing Contact</option>' + employeeList);
                $('#primary_contact_id').html('<option value="">Select a Primary Contact</option>' + employeeList);

            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        }

        $(document).on('change', '#country_id', function() {
            let country_id = $(this).val();
            loadProvinces(country_id);
        });

        $(document).on('change', '#province_id', function() {
            let province_id = $(this).val();
            loadCities(province_id);
        });

        async function loadProvinces(countryId) {
            // Check if a country is selected
            if (!countryId) {
                $('#province_id').html('<option value="">Select a country first</option>');
                $('#city_id').html('<option value="">Select a province first</option>');
                return;
            }

            // Filter provinces by country_id
            const provinceList = (dropdownData?.provinces || [])
                .filter(province => province.country_id == countryId)
                .map(province => `<option value="${province.id}">${province.province_name}</option>`)
                .join('');

            // Populate the province dropdown
            $('#province_id').html('<option value="">Select a province</option>' + provinceList);
            $('#city_id').html('<option value="">Select a province first</option>');
        }

        async function loadCities(provinceId) {
            // Check if a province is selected
            if (!provinceId) {
                $('#city_id').html('<option value="">Select a province first</option>');
                return;
            }

            // Filter cities by province_id
            const cityList = (dropdownData?.cities || [])
                .filter(city => city.province_id == provinceId)
                .map(city => `<option value="${city.id}">${city.city_name}</option>`)
                .join('');

            // Populate the city dropdown
            $('#city_id').html('<option value="">Select a city</option>' + cityList);
        }


        // Function to populate form fields with company data
        async function generateCompanyForm() {
            const baseUrl = window.location.origin;
            try {
                // Fetch company data
                let companyData = await commonFetchData('/company/1'); // Example API call

                if (companyData && companyData.length > 0) {
                    // Access the first object in the array
                    let company = companyData[0];

                    // Populate form fields with the fetched company data
                    $('#company_name').val(company.company_name || '');
                    $('#company_short_name').val(company.company_short_name || '');
                    $('#industry_id').val(company.industry_id || '');
                    $('#business_reg_no').val(company.business_reg_no || '');
                    $('#contact_1').val(company.contact_1 || '');
                    $('#contact_2').val(company.contact_2 || '');
                    $('#email').val(company.email || '');
                    $('#website').val(company.website || '');
                    $('#epf_reg_no').val(company.epf_reg_no || '');
                    $('#tin_no').val(company.tin_no || '');
                    $('#address_1').val(company.address_1 || '');
                    $('#address_2').val(company.address_2 || '');
                    $('#postal_code').val(company.postal_code || '');
                    $('#country_id').val(company.country_id || '');
                    $('#province_id').val(company.province_id || '');
                    $('#city_id').val(company.city_id || '');
                    $('#admin_contact_id').val(company.admin_contact_id || '');
                    $('#billing_contact_id').val(company.billing_contact_id || '');
                    $('#primary_contact_id').val(company.primary_contact_id || '');

                    // Populate dependent dropdowns
                    populateDependentDropdowns(company);

                    if (company.logo) {
                        // $('#company_logo_display').attr('src', `${baseUrl}/storage/logos/${company.logo}`);
                        $('#company_logo_i').attr('src', `${baseUrl}/storage/${company.logo}`);
                    }

                    if (company.logo_small) {
                        // $('#company_logo_small_display').attr('src',
                        //     `${baseUrl}/storage/logos/small/${company.logo_small}`);
                        $('#company_logo_small_i').attr('src', `${baseUrl}/storage/${company.logo_small}`);
                    }
                } else {
                    console.error("No company data found");
                }
            } catch (error) {
                console.error("Error fetching company data", error);
            }
        }

        function populateDependentDropdowns(company) {
            // Populate province dropdown
            const provinceList = (dropdownData?.provinces || [])
                .filter(province => province.country_id === company.country_id) // Filter by country
                .map(province =>
                    `<option value="${province.id}">${province.province_name}</option>`)
                .join('');
            $('#province_id').html('<option value="">Select a Province</option>' + provinceList);
            $('#province_id').val(company.province_id || '');

            // Populate city dropdown
            const cityList = (dropdownData?.cities || [])
                .filter(city => city.province_id === company.province_id) // Filter by province
                .map(city =>
                    `<option value="${city.id}">${city.city_name}</option>`)
                .join('');
            $('#city_id').html('<option value="">Select a City</option>' + cityList);
            $('#city_id').val(company.city_id || '');


            // Populate admin contact dropdown
            const adminContactList = (dropdownData?.employees || [])
                .map(admin =>
                    `<option value="${admin.id}">${admin.first_name} ${admin.last_name}</option>`)
                .join('');
            $('#admin_contact_id').html('<option value="">Select an Admin</option>' + adminContactList);
            $('#admin_contact_id').val(company.admin_contact_id || '');

            // Populate billing contact dropdown
            const billingContactList = (dropdownData?.employees || [])
                .map(billing =>
                    `<option value="${billing.id}">${billing.first_name} ${billing.last_name}</option>`)
                .join('');
            $('#billing_contact_id').html('<option value="">Select a Billing Contact</option>' + billingContactList);
            $('#billing_contact_id').val(company.billing_contact_id || '');

            // Populate primary contact dropdown
            const primaryContactList = (dropdownData?.employees || [])
                .map(primary =>
                    `<option value="${primary.id}">${primary.first_name} ${primary.last_name}</option>`)
                .join('');
            $('#primary_contact_id').html('<option value="">Select a Primary Contact</option>' + primaryContactList);
            $('#primary_contact_id').val(company.primary_contact_id || '');
        }

        $(document).on('click', '#submit-confirm', async function() {
            const company_id = 1; // Replace with actual company ID if dynamic
            let updateUrl = `/company/update/${company_id}`;

            const formFields = {
                company_name: 'required',
                company_short_name: '', // Not required
                industry_id: 'required',
                business_reg_no: 'required',
                contact_1: 'required',
                contact_2: '', // Not required
                email: '', // Not required
                website: '', // Not required
                epf_reg_no: '', // Not required
                tin_no: '', // Not required
                address_1: 'required',
                address_2: '', // Not required
                postal_code: '', // Not required
                country_id: 'required',
                province_id: 'required',
                city_id: 'required',
                admin_contact_id: '', // Not required
                billing_contact_id: '', // Not required
                primary_contact_id: '', // Not required
                // Add any additional fields here
            };

            let formData = new FormData();
            let missingFields = [];

            // Validate only required fields
            for (const key in formFields) {
                const fieldId = key;
                const value = $('#' + fieldId).val(); // Fetch value using the ID

                // Check only required fields
                if (formFields[key] === 'required' && !value) {
                    missingFields.push(fieldId); // Collect missing required fields
                }

                // Append all fields to formData (even optional ones)
                formData.append(key, value || ''); // Append empty string if no value for optional fields
            }

            // Handle file uploads
            const logoFile = $('#company_logo')[0].files[0]; // Get the first file (if any)
            const logoSmallFile = $('#company_logo_small')[0].files[0];

            if (logoFile) {
                formData.append('company_logo', logoFile); // Append the logo file
            }

            if (logoSmallFile) {
                formData.append('company_logo_small', logoSmallFile); // Append the small logo file
            }

            // If there are missing required fields, display an error message
            if (missingFields.length > 0) {
                let errorMsg = '<p class="text-danger">The following fields are required: ';
                errorMsg += missingFields.map(field => field.replace('_', ' ')).join(', ') + '.</p>';
                $('#error-msg').html(errorMsg);
                return;
            } else {
                $('#error-msg').html(''); // Clear error message if no issues
            }

            // Append company_id if updating
            const isUpdating = Boolean(company_id);
            let url = isUpdating ? updateUrl : createUrl;
            let method = 'POST';

            if (isUpdating) {
                formData.append('company_id',
                    company_id); // Optional: you might not need to append company_id in the body for PUT
                method = 'PUT'; // Update method
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    generateCompanyForm();
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });
    </script>
</x-app-layout>
