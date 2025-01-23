<script>

const user_id = "<?= isset($_GET['emp']) && is_numeric($_GET['emp']) ? $_GET['emp'] : '' ?>";

$(document).ready(function() {
    // Inject the PHP value into JavaScript, ensuring it's properly handled

    // Check if user_id is a valid number and greater than 0
    if (user_id && parseInt(user_id) > 0) {
        console.log('emp_profile_js loaded', user_id);
    }else{
        window.location.href = '/error';
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
        let data = await commonFetchData(`/employees/${user_id}`);

        if(data){
            let userData = data.user[0];
            let companyData = data.company[0];

            // Populate the fields with the API data
            $('#name').text(userData.first_name + " " + userData.last_name);
            $('#full-name').text(userData.full_name);
            $('#name-initials').text(userData.name_with_initials);
            $('#mobile').text(userData.contact_1);
            $('#email').text(userData.personal_email);
            $('#address').text(userData.address_1);
            $('#location').text(userData.city_id); // Modify this to display the proper location if necessary
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
            $('#user-number').text(userData.id);
            $('#punch-id').text(userData.punch_machine_user_id);
            $('#branch').text(userData.province_id);
            $('#department').text(userData.employee_group_id);
            $('#employment-group').text(userData.employee_group_id);
            $('#designation').text(userData.designation_id);
            $('#basis-employment').text(userData.employment_type_id);
            $('#currency').text(userData.currency_id);
            $('#pay-period').text(userData.pay_period_id || 'N/A');

            $('#appointment-date').text(userData.appointment_date);
            $('#appointment-note').text(userData.appointment_note || 'N/A');
            $('#termination-date').text(userData.terminated_date || 'N/A');
            $('#termination-note').text(userData.terminated_note || 'N/A');
            $('#confirmed-date').text(userData.confirmed_date || 'N/A');
            $('#retirement-date').text(userData.retirement_date || 'N/A');

            $('#title-name').text(userData.first_name + " " + userData.last_name);
            $('#title-role').text(userData.role || 'N/A');
            $('#title-user-no').text(userData.id || 'N/A');

            $('#title-company').text(companyData.company_name || 'N/A');
            $('#title-company-address').text(companyData.address_1 + ', ' + companyData.address_2);
        }
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}

async function loadEmployeeDocuments(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
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
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}

async function loadEmployeeWorkExperience(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}

async function loadEmployeePromotions(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}

async function loadEmployeeJobHistory(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}

async function loadEmployeeKPI(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}

async function loadEmployeeBank(){
    try {
        let data = await commonFetchData('');
    } catch (error) {
        console.error('error at emp_profile_js: ', error);
    }
}


</script>
