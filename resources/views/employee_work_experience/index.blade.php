<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-sm-0">{{ __('Employee Work Experience') }}</h4>
    </x-slot>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 flex-grow-1">Employee Work Experience</h5>
                    </div>

                    <div class="justify-content-md-end">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary waves-effect waves-light material-shadow-none me-1" id="add_new_work_experience_btn">New Work Experience <i class="ri-add-line"></i></button>
                            <a href="/employee/list" class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </div>


                <div class="card-body">

                    <div class="row mb-3 mb-4">
                        <div class="col-lg-2 d-flex align-items-center">
                            <label for="user_idname" class="form-label mb-1 req">Employee Name</label>
                        </div>

                        <div class="col-lg-10">
                            <select class="form-select" id="userDropdown">
                                <option value="">Select Employee</option>
                            </select>
                        </div>


                    </div>
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col">#</th>
                                <th class="col">Company</th>
                                <th class="col">From Date</th>
                                <th class="col">To Date</th>
                                <th class="col">Department</th>
                                <th class="col">Designation</th>
                                <th class="col">Remark</th>
                                <th class="col">Status</th>
                                <th class="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="work-experience-table-body">
                            <tr>
                                <td colspan="10" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="work-experience-form-modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="work-experience-form-title">New Work Experience</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="work-experience-form-body" class="row">

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="user_name" class="form-label mb-1">Employee Name</label>
                            <input type="text" class="form-control" id="user_name" value="" disabled>
                            <input type="hidden" class="form-control" id="user_id" value="" disabled>
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="company" rows="3">
                        </div>

                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" rows="3">
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label for="remarks" class="form-label">Remark</label>
                            <input type="text" class="form-control" id="remarks" rows="3">
                        </div>

                        <div class="col-xxl-4 col-md-6 mb-3">
                            <label for="work_experience_status" class="form-label mb-1 req">Status</label>
                            <select class="form-select" id="work_experience_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div id="error-msg"></div>

                        <div class="d-flex gap-2 justify-content-end mt-4 mb-2">
                            <input type="hidden" id="work_experience_id" value="">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-primary"
                                id="work-experience-submit-confirm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    let userId = "{{ $user->user_id }}";

    // Fetch and render users_work_experience for the selected user
    async function renderWorkExperienceTable() {
        if (!userId) {
            $('#work-experience-table-body').html(
                '<tr><td colspan="10" class="text-center">Please Selecte Employee...</td></tr>');
            return;
        }

        let users_work_experience = await commonFetchData(`/employee/work_experience/${userId}`);
        let list = '';

        if (users_work_experience && users_work_experience.length > 0) {
            users_work_experience.forEach((item, i) => {
                list += `
                    <tr work_experience_id="${item.id}">
                        <td>${i + 1}</td>
                        <td>${item.company}</td>
                        <td>${item.from_date}</td>
                        <td>${item.to_date}</td>
                        <td>${item.department}</td>
                        <td>${item.designation}</td>
                        <td>${item.remarks}</td>
                        <td class="text-capitalize">${item.status === 'active'
                            ? `<span class="badge border border-success text-success">${item.status}</span>`
                            : `<span class="badge border border-warning text-warning">${item.status}</span>`}</td>
                        <td>
                            <button type="button" class="btn btn-info waves-effect waves-light btn-sm click-edit-work-experience" title="Edit" data-tooltip="tooltip" data-bs-placement="top">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-danger waves-effect waves-light btn-sm click_delete_work_experience" title="Delete" data-tooltip="tooltip" data-bs-placement="top">
                                <i class="ri-delete-bin-fill"></i>
                            </button>
                        </td>
                    </tr> `;
            });
        } else {
            list = `<tr><td colspan="10" class="text-danger text-center">No Work Experience Yet!</td></tr>`;
        }

        $('#work-experience-table-body').html(list);
    }


    async function getEmployeeList() {
        let users = await commonFetchData('/employee/work_experience/dropdown');

        // Check if users data is valid
        if (users && users.length > 0) {
            // Target the dropdown element
            let dropdown = $('#userDropdown');

            // Clear existing options (optional)
            dropdown.empty();
            dropdown.append('<option value="">Select Employee</option>'); // Add a default option

            // Loop through the users and add options
            users.forEach(user => {
                let isSelected = user.user_id == userId ? "selected" : ""; // Pre-select if IDs match
                let option = `<option value="${user.user_id}" ${isSelected}>${user.first_name} ${user.last_name}</option>`;
                dropdown.append(option);

                if (isSelected) {
                    userName = `${user.first_name} ${user.last_name}`;
                }
            });

            if (userId) {
                $('#userDropdown').val(userId); // Set the dropdown value
                $('#user_name').val(userName); // Display the name in the input field
                await renderWorkExperienceTable(userId); // Fetch and display wage details for the selected employee
            }
        } else {
            console.log('No users found');
        }

    }

    // Populate user dropdown and set up change event
    $(document).ready(async function() {
        await getEmployeeList();

        $('#userDropdown').on('change', async function() {
            userId = $(this).val(); // Get selected user ID
            let userName = $('#userDropdown option:selected').text();
            $('#user_name').val(userName);

            // Enable button if user is selected
            if (userId) {
                $('#add_new_work_experience_btn').prop('disabled', false);
            } else {
                $('#add_new_work_experience_btn').prop('disabled', true);
            }

            // Render work_experience table for the selected user
            await renderWorkExperienceTable();
        });


    });


    $(document).on('click', '#add_new_work_experience_btn', function() {
        resetForm();
        title = `Add New Work Experience`;
        $('#work-experience-form-title').html(title);
        $('#work-experience-form-modal').modal('show');
    });


    $(document).on('click', '#work-experience-submit-confirm', async function() {
        const work_experience_id = $('#work_experience_id').val();
        const company = $('#company').val();
        const from_date = $('#from_date').val();
        const to_date = $('#to_date').val();
        const department = $('#department').val();
        const designation = $('#designation').val();
        const remarks = $('#remarks').val(); // Correctly fetch the remarks value
        const work_experience_status = $('#work_experience_status').val();

        let createUrl = `/employee/work_experience/create`;
        let updateUrl = `/employee/work_experience/update/${work_experience_id}`;

        let formData = new FormData();

        if (!company || !from_date ||!to_date || !designation || !work_experience_status) {
            $('#error-msg').html('<p class="text-danger">All fields are required</p>');
            return;
        } else {
            $('#error-msg').html(''); // Clear error message if no issues
        }

        formData.append('user_id', userId);
        formData.append('company', company);
        formData.append('from_date', from_date);
        formData.append('to_date', to_date);
        formData.append('department', department);
        formData.append('designation', designation);
        formData.append('remarks', remarks); // Use the fetched remarks value here
        formData.append('work_experience_status', work_experience_status);

        const isUpdating = Boolean(work_experience_id);
        let url = isUpdating ? updateUrl : createUrl;
        let method = isUpdating ? 'PUT' : 'POST';

        try {
            let res = await commonSaveData(url, formData, method);
            await commonAlert(res.status, res.message);

            if (res.status === 'success') {
                $('#work-experience-form-modal').modal('hide');
                await renderWorkExperienceTable(); // Re-render table on success
            }
        } catch (error) {
            console.error('Error:', error);
            $('#error-msg').html('<p class="text-danger">An error occurred. Please try again.</p>');
        }
    });


    // edit click event
    $(document).on('click', '.click-edit-work-experience', async function() {
        let work_experience_id = $(this).closest('tr').attr('work_experience_id');

        // Get branch data by id
        try {
            let work_experience_data = await commonFetchData(`/employee/single_work_experience/${work_experience_id}`);
                work_experience_data = work_experience_data[0];
            console.log('work_experience_data', work_experience_data);

            // Set initial form values
            $('#work_experience_id').val(work_experience_id);
            $('#company').val(work_experience_data?.company || '');
            $('#from_date').val(work_experience_data?.from_date || '');
            $('#to_date').val(work_experience_data?.to_date || '');
            $('#department').val(work_experience_data?.department || '');
            $('#designation').val(work_experience_data?.designation || '');
            $('#remarks').val(work_experience_data?.remarks || '');
            $('#work_experience_status').val(work_experience_data?.status || '');


        } catch (error) {
            console.error('error at getWorkExperienceById: ', error);
        } finally {
            title = `Edit Work Experience`;
            $('#work-experience-form-title').html(title);
            $('#work-experience-form-modal').modal('show');
        }
    });


    $(document).on('click', '.click_delete_work_experience', async function() {
        let work_experience_id = $(this).closest('tr').attr('work_experience_id');

        try {
            let url = `/employee/work_experience/delete`;
            const res = await commonDeleteFunction(work_experience_id, url, 'Work Experience'); // Await the promise here

            if (res) {
                await renderWorkExperienceTable();
            }
        } catch (error) {
            console.error(`Error during Work Experience deletion:`, error);
        }
    })

    function resetForm() {
        $('#work_experience_id').val('');
        $('#company').val('');
        $('#from_date').val('');
        $('#to_date').val('');
        $('#department').val('');
        $('#designation').val('');
        $('#remarks').val('');
        $('#work_experience_status').val('active'); // Reset status to default
        $('#error-msg').html(''); // Clear error messages
    }

</script>

</x-app-layout>
