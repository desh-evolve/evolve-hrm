<script>

const user_id = "<?= isset($emp) && is_numeric($emp) ? $emp : '' ?>";
console.log('user_id: ', user_id)

$(document).ready(function() {
    // Inject the PHP value into JavaScript, ensuring it's properly handled

    // Check if user_id is a valid number and greater than 0
    if (user_id && parseInt(user_id) > 0) {
        console.log('emp_profile_js loaded', user_id);
    }else{
      //  window.location.href = `/error`;
    }

    loadEmployeeOverview();

    // Add click event listener to each link-click
    $('.link-click').on('click', function(event) {
        event.preventDefault(); // Prevent default link behavior if needed

        // Get the ID or any data you need (for example, href or tab id)
        const targetTab = $(this).attr('href');

        // Call a function to load data based on the tab clicked
        loadTabData(targetTab);
    });

    // Example function to load data based on the tab clicked
    function loadTabData(tab) {
        showPreloader(0.5);
        switch (tab) {
            case '#overview-tab':
                loadEmployeeOverview();
                break;
            case '#documents':
                loadEmployeeDocuments();
                break;
            case '#activities':
                loadEmployeeActivities();
                break;
            case '#qualifications':
                loadEmployeeQualifications();
                break;
            case '#work_experience':
                loadEmployeeWorkExperience();
                break;
            case '#promotions':
                loadEmployeePromotions();
                break;
            case '#job_history':
                loadEmployeeJobHistory();
                break;
            case '#kpi':
                loadEmployeeKPI();
                break;
            case '#bank':
                loadEmployeeBank();
                break;
            default:
                console.log('Unknown tab');
        }
    }


    //edit user
    $(document).on('click', '.edit-profile-btn', function () {
        if (user_id) {
            window.location.href = '/employee/form?emp_id=' + user_id;
        }else{
            console.error('User id not found!');
        }
    });

});



async function loadEmployeeOverview(){
    try {
        let data = await commonFetchData(`/employee/details/${user_id}`);

        if(data && data){
            console.log('Profile Data:', data);

            let userData = data.user[0];
            let companyData = data.company[0];

            if (!userData || !companyData) {
                throw new Error('Invalid profile data received.');
            }

            // Populate the fields with the API data
            $('#name').text(userData.first_name + " " + userData.last_name || 'N/A');
            $('#full-name').text(userData.full_name || 'N/A');
            $('#name-initials').text(userData.name_with_initials || 'N/A');
            $('#mobile').text(userData.contact_1 || 'N/A');
            $('#email').text(userData.personal_email || 'N/A');
            $('#address').text(userData.address_1 || 'N/A');
            $('#location').text(userData.city_name || 'N/A'); // Modify this to display the proper location if necessary
            $('#nic').text(userData.nic || 'N/A');
            $('#dob').text(userData.dob || 'N/A');
            $('#gender').text(userData.gender || 'N/A');
            $('#religion').text(userData.religion_name || 'N/A');
            $('#marital-status').text(userData.marital_status || 'N/A');

            $('#work-contact').text(userData.work_contact || 'N/A');
            $('#work-email').text(userData.work_email || 'N/A');
            $('#immediate-contact-person').text(userData.immediate_contact_person || 'N/A');
            $('#immediate-contact').text(userData.immediate_contact_no || 'N/A');
            $('#home-contact').text(userData.home_contact || 'N/A');
            $('#epf-reg-no').text(userData.epf_reg_no || 'N/A');

            $('#user-status').text(userData.user_status == 1 ? 'Active' : 'Inactive' || 'N/A');
            $('#user-number').text(userData.id || 'N/A');
            $('#punch-id').text(userData.punch_machine_user_id || 'N/A');




            // branch and department details
            if (userData.branch_department_details && userData.branch_department_details.length > 0) {
                const branchDetails = userData.branch_department_details[0];
                $('#branch').text(branchDetails.branch_name || 'N/A');
                $('#department').text(branchDetails.department_name || 'N/A');
            } else {
                $('#branch').text('N/A');
                $('#department').text('N/A');
            }

            $('#employment-group').text(userData.user_group_name || 'N/A');
            $('#designation').text(userData.designation_name || 'N/A');
            $('#basis-employment').text(userData.employment_types_name || 'N/A');
            $('#currency').text(userData.currency_name || 'N/A');

            // pay period details
            if (userData.pay_period_schedule_details && userData.pay_period_schedule_details.length > 0) {
                const payPeriodDetails = userData.pay_period_schedule_details[0];
                $('#pay-period').text(payPeriodDetails.pay_period_schedule_name);
            } else {
                $('#pay-period').text('N/A');
            }

            $('#appointment-date').text(userData.appointment_date || 'N/A');
            $('#appointment-note').text(userData.appointment_note || 'N/A');
            $('#termination-date').text(userData.terminated_date || 'N/A');
            $('#termination-note').text(userData.terminated_note || 'N/A');
            $('#confirmed-date').text(userData.confirmed_date || 'N/A');
            $('#retirement-date').text(userData.retirement_date || 'N/A');
            $('#resign-date').text(userData.resigned_date || 'N/A');

            $('#title-name').text(userData.first_name + " " + userData.last_name || 'N/A');

            // Role details
            if (userData.role_details && userData.role_details.length > 0) {
                const roleDetails = userData.role_details[0];
                const roleName = roleDetails.role_name || 'Default Role';

                // Capitalize the first letter
                const formattedRoleName = roleName.charAt(0).toUpperCase() + roleName.slice(1).toLowerCase();

                $('#title-role').text(formattedRoleName);
            } else {
                $('#title-role').text('Default Role');
            }


            if (userData.user_image) {
                const imageUrl = '/storage/' + userData.user_image;  // Make sure the path matches the one stored in the DB
                // Update the image source dynamically
                $('.avatar-lg img').attr('src', imageUrl);
            } else {
                // Set a default image if no user image exists
                $('.avatar-lg img').attr('src', '{{ asset('assets/images/users/default-avatar.jpg') }}');
            }

            
            $('#title-user-no').text(userData.id || 'N/A');

            $('#title-company').text(' ' + '-' + ' ' + companyData.company_name || 'N/A');
            $('#title-company-address').text(' ' + '-' + ' ' + companyData.address_1 + ', ' + companyData.address_2 || 'N/A');
        }else{
            throw new Error('No data received from the server.');
        }
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        alert('Something went wrong while loading the profile. Please try again later.');
    }
}


// Utility Function: Format Date
function formatDate1(datetime) {
    return dayjs(datetime).format('MMM DD, YYYY');
}


async function loadEmployeeDocuments(){
    try {
        let documents = await commonFetchData(`/employee/profile/documents/${user_id}`);
            let list = '';

            if (documents && documents.length > 0) {
                documents.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.title}</td>
                            <td>${user.name}</td>
                            <td>${formatDate1(user.created_at)}</td>
                            <td>
                                ${user.file ? `<a href="/employee/document/download/${user.file}" class="text-primary" target="_blank">${user.file}</a>` : 'No File'}
                            </td>
                            <td>
                                ${user.file ? `
                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_download_document" data-file="${user.file}" title="Download Document" data-tooltip="tooltip" data-bs-placement="top">
                                        <i class="ri-download-2-line"></i>
                                        DOWNLOAD
                                    </button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });
            } else {
                list = `<tr><td colspan="6" class="text-danger text-center">No Documents !</td></tr>`;
            }

            $('#documents-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching documents data:', error);
            $('#documents-table-body').html('<tr><td colspan="6" class="text-danger text-center">Error loading data...</td></tr>');
    }

}


    //download document
    $(document).on('click', '.click_download_document', function (e) {
        e.preventDefault();

        let fileName = $(this).data('file');
        if (!fileName) {
            alert('No file found.');
            return;
        }

        let downloadUrl = `/employee/document/download/${fileName}`;
        window.location.href = downloadUrl;
    });



async function loadEmployeeActivities(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}


async function loadEmployeeQualifications(){
    try {
        let users_qualifications = await commonFetchData(`/employee/profile/qualification/${user_id}`);
            let list = '';

            if (users_qualifications && users_qualifications.length > 0) {

                users_qualifications.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.qualification}</td>
                            <td>${user.institute}</td>
                            <td>${user.year}</td>
                            <td>${user.remarks}</td>
                        </tr> `;
                });
            } else {
                list = `<tr><td colspan="5" class="text-danger text-center">No Qualification !</td></tr>`;
            }

            $('#qualification-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching qualification data:', error);
            $('#qualification-table-body').html('<tr><td colspan="5" class="text-danger text-center">Error loading data...</td></tr>');
    }
}



async function loadEmployeeWorkExperience(){
    try {
        let workExperience = await commonFetchData(`/employee/profile/work_experience/${user_id}`);
            let list = '';

            if (workExperience && workExperience.length > 0) {

                workExperience.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.company}</td>
                            <td>${user.from_date} - ${user.to_date}</td>
                            <td>${user.department}</td>
                            <td>${user.designation}</td>
                            <td>${user.remarks}</td>
                        </tr> `;
                });
            } else {
                list = `<tr><td colspan="6" class="text-danger text-center">No Work-Experience !</td></tr>`;
            }

            $('#work-experience-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching work experience data:', error);
            $('#work-experience-table-body').html('<tr><td colspan="6" class="text-danger text-center">Error loading data...</td></tr>');
    }
}


async function loadEmployeePromotions(){
    try {
        let promotions = await commonFetchData(`/employee/profile/promotion/${user_id}`);
            let list = '';

            if (promotions && promotions.length > 0) {

                promotions.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.current_designation}</td>
                            <td>${user.new_designation}</td>
                            <td>${user.current_salary}</td>
                            <td>${user.new_salary}</td>
                            <td>${user.effective_date}</td>
                            <td>${user.remarks}</td>
                        </tr> `;
                });
            } else {
                list = `<tr><td colspan="7" class="text-danger text-center">No Promotions !</td></tr>`;
            }

            $('#promotion-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching promotion data:', error);
            $('#promotion-table-body').html('<tr><td colspan="7" class="text-danger text-center">Error loading data...</td></tr>');
    }
}

async function loadEmployeeJobHistory(){
    try {
        let jobHistory = await commonFetchData(`/employee/profile/jobhistory/${user_id}`);
            let list = '';

            if (jobHistory && jobHistory.length > 0) {

                jobHistory.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.branch_name}</td>
                            <td>${user.department_name}</td>
                            <td>${user.emp_designation_name}</td>
                            <td>${user.first_worked_date}</td>
                            <td>${user.last_worked_date}</td>
                            <td>${user.note}</td>
                        </tr> `;
                });
            } else {
                list = `<tr><td colspan="7" class="text-danger text-center">No jobHistory !</td></tr>`;
            }

            $('#jobhistory-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching jobhistory data:', error);
            $('#jobhistory-table-body').html('<tr><td colspan="7" class="text-danger text-center">Error loading data...</td></tr>');
    }
}

async function loadEmployeeKPI(){
    try {
        let kpi = await commonFetchData(`/employee/profile/kpi/${user_id}`);
            let list = '';

            if (kpi && kpi.length > 0) {

                kpi.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                            <td>${i + 1}</td>
                            <td>${user.qualification}</td>
                            <td>${user.institute}</td>
                            <td>${user.year}</td>
                            <td>${user.remarks}</td>
                        </tr> `;
                });
            } else {
                list = `<tr><td colspan="5" class="text-danger text-center">No KPI !</td></tr>`;
            }

            $('#kpi-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching KPI data:', error);
            $('#kpi-table-body').html('<tr><td colspan="5" class="text-danger text-center">Error loading data...</td></tr>');
    }
}

async function loadEmployeeBank(){
    try {
        let bank = await commonFetchData(`/employee/profile/bank/${user_id}`);
            let list = '';

            if (bank && bank.length > 0) {

                bank.forEach((user, i) => {
                    list += `
                        <tr id="${user.id}">
                        </tr>
                        <tr><th>Bank Code</th>
                            <td>${user.bank_code}</td>
                        </tr>
                        <tr><th>Bank Name</th>
                            <td>${user.bank_name}</td>
                        </tr>
                        <tr><th>Bank Branch</th>
                            <td>${user.bank_branch}</td>
                        </tr>
                        <tr><th>Account No</th>
                            <td>${user.account_number}</td>
                        </tr>
                        `;
                });
            } else {
                list = `<tr><td colspan="5" class="text-danger text-center">No Bank Details!</td></tr>`;
            }

            $('#bank-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching bank data:', error);
            $('#bank-table-body').html('<tr><td colspan="5" class="text-danger text-center">Error loading data...</td></tr>');
    }
}


</script>
