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
                            <div class="col-lg-6 border-end">
                                <div class="row">
                                    <!-- Company Name -->
                                    <div class="col-lg-12">
                                        <div class="mt-1 mb-3">
                                            <label for="company_name" class="form-label req">Company Name</label>
                                            <input type="text" class="form-control" id="company_name"
                                                placeholder="Enter Company Name" required>
                                        </div>
                                    </div>

                                    <!-- Company Short Name -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="company_short_name" class="form-label">Company Short Name</label>
                                            <input type="text" class="form-control" id="company_short_name"
                                                placeholder="Enter Company Short Name">
                                        </div>
                                    </div>

                                    <!-- Industry -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="industry_id" class="form-label req">Industry</label>
                                            <select class="form-select" id="industry_id">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Business Registration Number -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="business_reg_no" class="form-label req">Business / Employer
                                                Identification Number</label>
                                            <input type="text" class="form-control mt-1" id="business_reg_no" placeholder="Enter Business ID No">
                                        </div>
                                    </div>



                                    <!-- Address -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="address_1" class="form-label req">Address Line 1</label>
                                            <input type="text" class="form-control mt-4" id="address_1" placeholder="Enter Address Line 1">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="address_2" class="form-label">Address Line 2</label>
                                            <input type="text" class="form-control" id="address_2" placeholder="Enter Address Line 2">
                                        </div>
                                    </div>

                                    <!-- Postal Code, Country, Province, City -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="country_id" class="form-label req">Country</label>
                                            <select class="form-select" id="country_id">
                                                <option value="">Select Country</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="province_id" class="form-label req">Province/State</label>
                                            <div class="input-group">
                                                <select class="form-select" id="province_id">
                                                    <option value="">Select Province</option>
                                                </select>
                                                {{-- <button class="btn btn-outline-secondary" type="button">Add New</button> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="city_id" class="form-label req">City</label>
                                            <div class="input-group">
                                                <select class="form-select" id="city_id">
                                                    <option value="">Select City</option>
                                                </select>
                                                {{-- <button class="btn btn-outline-secondary" type="button">Add New</button> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="postal_code" class="form-label">Postal Code</label>
                                            <input type="text" class="form-control" id="postal_code"
                                                placeholder="Enter Postal Code">
                                        </div>
                                    </div>

                                     <!-- Contacts 1-->
                                     <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="contact_1" class="form-label req">contact 1</label>
                                            <input type="text" class="form-control" id="contact_1" placeholder="Enter Phone Number">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-6 border-end">
                                <div class="row">

                                    <!-- Contacts 2-->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="contact_2" class="form-label">contact 2</label>
                                            <input type="text" class="form-control" id="contact_2" placeholder="Enter Fax">
                                        </div>
                                    </div>

                                    <!-- Email and Website -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="email" class="form-label req">Email</label>
                                            <input type="email" class="form-control" id="email" placeholder="Enter Email">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="website" class="form-label">Website Link</label>
                                            <input type="text" class="form-control" id="website"
                                                placeholder="Enter Website Link">
                                        </div>
                                    </div>

                                    <!-- More fields (EPF, TIN, Address) -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="epf_reg_no" class="form-label req">EPF Reg No</label>
                                            <input type="text" class="form-control" id="epf_reg_no"
                                                placeholder="Enter EPF Reg No">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="tin_no" class="form-label req">TIN No</label>
                                            <input type="text" class="form-control mt-4" id="tin_no" placeholder="Enter TIN No">
                                        </div>
                                    </div>



                                    <!-- Contacts -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="admin_contact_id" class="form-label">Admin Contact</label>
                                            <select class="form-select mt-4" id="admin_contact_id">
                                                <option value="">Select Admin Contact</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="billing_contact_id" class="form-label">Billing Contact</label>
                                            <select class="form-select" id="billing_contact_id">
                                                <option value="">Select Billing Contact</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="primary_contact_id" class="form-label">Primary Contact</label>
                                            <select class="form-select" id="primary_contact_id">
                                                <option value="">Select Primary Contact</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Logo Upload -->
                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="logo_img" class="form-label">Logo Large</label>
                                            <input type="file" class="form-control" id="company_logo" accept="image/*">
                                            <img id="company_logo_i" src="" alt="Company Logo" style="max-width: 70px;" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-1 mb-3">
                                            <label for="logo_small_img" class="form-label">Logo Small</label>
                                            <input type="file" class="form-control" id="company_logo_small">
                                            <img id="company_logo_small_i" src="" alt="Company Small Logo" style="max-width: 70px;" />
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div id="error-msg" class="text-danger mb-3"></div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <input type="hidden" id="company_id" value="1">
                                <button type="button" class="btn w-sm btn-primary" id="submit-confirm">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // ======================new code===================

        let dropdownData = [];

        $(document).ready(async function() {
            // Call the function to load the company data on page load
            await getDropdownData();
            await generateCompanyForm();
        });


        async function getDropdownData() {
            showPreloader();
            try {
                dropdownData = await commonFetchData('/company/info/dropdown');

                // Populate Admin Contact dropdown
                let adminContactList = (dropdownData?.adminUsers || [])
                    .map(adminContact => `<option value="${adminContact.id}">${adminContact.first_name} ${adminContact.last_name}</option>`)
                    .join('');
                $('#admin_contact_id').html('<option value="">Select Admin Contact</option>' + adminContactList);

                // Populate Admin Contact dropdown
                let billingContactList = (dropdownData?.billingUsers || [])
                    .map(billingContact => `<option value="${billingContact.id}">${billingContact.first_name} ${billingContact.last_name}</option>`)
                    .join('');
                $('#billing_contact_id').html('<option value="">Select Billing Contact</option>' + billingContactList);

                // Populate Admin Contact dropdown
                let primaryContactList = (dropdownData?.primaryUsers || [])
                    .map(primaryContact => `<option value="${primaryContact.id}">${primaryContact.first_name} ${primaryContact.last_name}</option>`)
                    .join('');
                $('#primary_contact_id').html('<option value="">Select Primary Contact</option>' + primaryContactList);

                // Populate industry name dropdown
                let industryList = (dropdownData?.industries || [])
                    .map(industry => `<option value="${industry.id}">${industry.industry_name}</option>`)
                    .join('');
                $('#industry_id').html('<option value="">Select Industry</option>' + industryList);

                // Populate country dropdown
                let countryList = (dropdownData?.countries || [])
                    .map(country => `<option value="${country.id}">${country.country_name} (${country.country_code})</option>`)
                    .join('');
                $('#country_id').html('<option value="">Select a country</option>' + countryList);

                // Default values for province and city
                $('#province_id').html('<option value="">Select a country first</option>');
                $('#city_id').html('<option value="">Select a country first</option>');


            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            } finally {
                hidePreloader();
            }
        }



//=========================================================================================================

        $(document).on('change', '#country_id', function () {
            let country_id = $(this).val();
            loadProvinces(country_id);
        });

        $(document).on('change', '#province_id', function () {
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

//=========================================================================================================

        // Function to populate form fields with company data
        async function generateCompanyForm()
        {
            const baseUrl = window.location.origin;
            const company_id = $('#company_id').val(); // Fetch from hidden input or URL

            try {
                // Fetch company data
                let companyData = await commonFetchData(`/company/${company_id}`); // Example API call
                console.log('conpany data', companyData);

                if (companyData && companyData.length > 0) {
                    let company = companyData[0]; // Access the first object in the array

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

                    // Populate Province and City based on Country
                    await loadProvinces(company.country_id);
                    $('#province_id').val(company.province_id || '');

                    await loadCities(company.province_id);
                    $('#city_id').val(company.city_id || '');

                    $('#admin_contact_id').val(company.admin_contact_id || '');
                    $('#billing_contact_id').val(company.billing_contact_id || '');
                    $('#primary_contact_id').val(company.primary_contact_id || '');

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
                    resetForm();
                }
            } catch (error) {
                console.error("Error fetching company data", error);
                $('#error-msg').text('Error fetching bank details. Please try again later.');
            }
        }



        // Submit company details
        $(document).on('click', '#submit-confirm', async function() {
            const company_id = $('#company_id').val();

            const isUpdating = Boolean(company_id);
            const url = isUpdating ? `/company/update/${company_id}` : `/company/create`;
            const method = isUpdating ? 'PUT' : 'POST';

            const formFields = {
                company_name: 'required',
                company_short_name: '', // Not required
                industry_id: 'required',
                business_reg_no: 'required',
                contact_1: 'required',
                contact_2: '', // Not required
                email: 'required',
                website: '', // Not required
                epf_reg_no: 'required',
                tin_no: 'required',
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

            if (missingFields.length > 0) {
                $('#error-msg').html(
                    '<p class="text-danger"><strong>The following fields are required:</strong> ' +
                        missingFields.map((field) => field.replace('_', ' ')).join(', ') +
                        '.</p>'
                );
                return;
            } else {
                $('#error-msg').html('');
            }

            formData.append('company_id',company_id);

            // Debugging FormData
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            try {
                // Send data and handle response
                let res = await commonSaveData(url, formData, method);
                console.log('Response:', res);
                await commonAlert(res.status, res.message);

                if (res.status === 'success') {
                    resetForm();
                    await generateCompanyForm();
                } else {
                    $('#error-msg').html('<p class="text-danger">' + res.message + '</p>');
                }
            } catch (error) {
                console.error('Error:', error);
                $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });


        function resetForm() {
            // Clear text inputs
            $('input[type="text"], input[type="email"], input[type="tel"], input[type="number"]').val('');

            // Clear textarea fields
            $('textarea').val('');

            // Reset dropdowns to default option
            $('select').val('').trigger('change');

            // Reset file inputs
            $('input[type="file"]').val('');

            // Clear displayed images (logos)
            $('#company_logo_i').attr('src', ''); // Replace with default placeholder if needed
            $('#company_logo_small_i').attr('src', ''); // Replace with default placeholder if needed

            // Clear error messages
            $('#error-msg').html('');

            console.log("Form reset successfully!");
        }


    </script>

</x-app-layout>
