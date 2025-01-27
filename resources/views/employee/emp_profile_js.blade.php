<script>

const user_id = "<?= isset($_GET['emp']) && is_numeric($_GET['emp']) ? $_GET['emp'] : '' ?>";

$(document).ready(function() {
    // Inject the PHP value into JavaScript, ensuring it's properly handled

    // Check if user_id is a valid number and greater than 0
    if (user_id && parseInt(user_id) > 0) {
        console.log('emp_profile_js loaded', user_id);
    }else{
        window.location.href = `/employee/my_profile`;
    }

    loadEmployeeOverview();

    // Add click event listener to each nav-link
    $('.nav-link').on('click', function(event) {
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
});

async function loadEmployeeOverview(){
    try {
        let data = await commonFetchData(`/employee/${user_id}`);

        if(data && data.data){
            console.log('Profile Data:', data);

            let userData = data.data.user[0];
            let companyData = data.data.company[0];

            if (!userData || !companyData) {
                throw new Error('Invalid profile data received.');
            }

            // Populate the fields with the API data
            $('#name').text(userData.first_name + " " + userData.last_name);
            $('#full-name').text(userData.full_name);
            $('#name-initials').text(userData.name_with_initials);
            $('#mobile').text(userData.contact_1);
            $('#email').text(userData.personal_email);
            $('#address').text(userData.address_1);
            $('#location').text(userData.city_name); // Modify this to display the proper location if necessary
            $('#nic').text(userData.nic);
            $('#dob').text(userData.dob);
            $('#gender').text(userData.gender);
            $('#religion').text(userData.religion || 'N/A');
            $('#marital-status').text(userData.marital_status || 'N/A');

            $('#work-contact').text(userData.work_contact || 'N/A');
            $('#work-email').text(userData.work_email || 'N/A');
            $('#immediate-contact-person').text(userData.immediate_contact_person || 'N/A');
            $('#immediate-contact').text(userData.immediate_contact_no || 'N/A');
            $('#home-contact').text(userData.home_contact || 'N/A');
            $('#epf-reg-no').text(userData.epf_reg_no || 'N/A');

            $('#user-status').text(userData.user_status == 1 ? 'Active' : 'Inactive');
            $('#user-number').text(userData.user_id);
            $('#punch-id').text(userData.punch_machine_user_id);
            $('#branch').text(userData.province_name);
            $('#department').text(userData.user_group_id);
            $('#employment-group').text(userData.user_group_id);
            $('#designation').text(userData.emp_designation_name);
            $('#basis-employment').text(userData.employment_type_id);
            $('#currency').text(userData.currency_name);
            $('#pay-period').text(userData.pay_period_id || 'N/A');

            $('#appointment-date').text(userData.appointment_date);
            $('#appointment-note').text(userData.appointment_note || 'N/A');
            $('#termination-date').text(userData.terminated_date || 'N/A');
            $('#termination-note').text(userData.terminated_note || 'N/A');
            $('#confirmed-date').text(userData.confirmed_date || 'N/A');
            $('#retirement-date').text(userData.retirement_date || 'N/A');

            $('#title-name').text(userData.first_name + " " + userData.last_name);
            $('#title-role').text(userData.role || 'Default Role');
            $('#title-user-no').text(userData.id || 'N/A');

            $('#title-company').text(' ' + '-' + ' ' + companyData.company_name || 'N/A');
            $('#title-company-address').text(' ' + '-' + ' ' + companyData.address_1 + ', ' + companyData.address_2);
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
                            <td>${user.doc_type_id}</td>
                            <td>${formatDate1(user.created_at)}</td>
                            <td>
                                <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_download_document" title="Download Document" data-tooltip="tooltip" data-bs-placement="top">
                                    <i class="ri-download-2-line"></i>
                                     DOWNLOAD
                                </button>
                            </td>
                        </tr> `;
                });
            } else {
                list = `<tr><td colspan="5" class="text-danger text-center">No Documents !</td></tr>`;
            }

            $('#documents-table-body').html(list);

    } catch (error) {
        console.error('error at emp_profile_js: ', error);
        console.error('Error fetching documents data:', error);
            $('#documents-table-body').html('<tr><td colspan="5" class="text-danger text-center">Error loading data...</td></tr>');
    }
    // <tr>
    //     <td>
    //         <div class="d-flex align-items-center">
    //             <div class="avatar-sm">
    //                 <div class="avatar-title bg-info-subtle text-info rounded fs-20 material-shadow">
    //                     <i class="ri-folder-line"></i>
    //                 </div>
    //             </div>
    //             <div class="ms-3 flex-grow-1">
    //                 <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Project Screenshots Collection</a></h6>
    //             </div>
    //         </div>
    //     </td>
    //     <td>Floder File</td>
    //     <td>08 Nov 2021</td>
    //     <td>
    //         <button type="button" class="btn btn-info waves-effect waves-light btn-sm click_download_document" title="Download Document" data-tooltip="tooltip" data-bs-placement="top">
    //             <i class="ri-download-2-line"></i>
    //         </button>
    //     </td>
    // </tr>
}


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
