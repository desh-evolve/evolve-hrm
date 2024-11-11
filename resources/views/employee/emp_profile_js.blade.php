<script>
    
const employee_id = "<?= isset($_GET['emp']) && is_numeric($_GET['emp']) ? $_GET['emp'] : '' ?>";

$(document).ready(function() {
    // Inject the PHP value into JavaScript, ensuring it's properly handled

    // Check if employee_id is a valid number and greater than 0
    if (employee_id && parseInt(employee_id) > 0) {
        console.log('emp_profile_js loaded', employee_id);
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
        let data = await commonFetchData(`/employee/${employee_id}`);
        
        if(data){
            let employeeData = data.employee[0];
            let companyData = data.company[0];

            // Populate the fields with the API data
            $('#name').text(employeeData.first_name + " " + employeeData.last_name);
            $('#full-name').text(employeeData.full_name);
            $('#name-initials').text(employeeData.name_with_initials);
            $('#mobile').text(employeeData.contact_1);
            $('#email').text(employeeData.personal_email);
            $('#address').text(employeeData.address_1);
            $('#location').text(employeeData.city_id); // Modify this to display the proper location if necessary
            $('#nic').text(employeeData.nic);
            $('#dob').text(employeeData.dob);
            $('#gender').text(employeeData.gender);
            $('#religion').text(employeeData.religion || 'N/A');
            $('#marital-status').text(employeeData.marital_status || 'N/A');

            $('#work-contact').text(employeeData.work_contact || 'N/A');
            $('#work-email').text(employeeData.work_email || 'N/A');
            $('#immediate-contact-person').text(employeeData.immediate_contact_person || 'N/A');
            $('#immediate-contact').text(employeeData.immediate_contact_no || 'N/A');
            $('#home-contact').text(employeeData.home_contact || 'N/A');
            $('#epf-reg-no').text(employeeData.epf_reg_no || 'N/A');

            $('#employee-status').text(employeeData.employee_status == 1 ? 'Active' : 'Inactive');
            $('#employee-number').text(employeeData.id);
            $('#punch-id').text(employeeData.punch_machine_user_id);
            $('#branch').text(employeeData.province_id);
            $('#department').text(employeeData.employee_group_id);
            $('#employment-group').text(employeeData.employee_group_id);
            $('#designation').text(employeeData.designation_id);
            $('#basis-employment').text(employeeData.employment_type_id);
            $('#currency').text(employeeData.currency_id);
            $('#pay-period').text(employeeData.pay_period_id || 'N/A');

            $('#appointment-date').text(employeeData.appointment_date);
            $('#appointment-note').text(employeeData.appointment_note || 'N/A');
            $('#termination-date').text(employeeData.terminated_date || 'N/A');
            $('#termination-note').text(employeeData.terminated_note || 'N/A');
            $('#confirmed-date').text(employeeData.confirmed_date || 'N/A');
            $('#retirement-date').text(employeeData.retirement_date || 'N/A');

            $('#title-name').text(employeeData.first_name + " " + employeeData.last_name);
            $('#title-role').text(employeeData.role || 'N/A');
            $('#title-employee-no').text(employeeData.id || 'N/A');

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