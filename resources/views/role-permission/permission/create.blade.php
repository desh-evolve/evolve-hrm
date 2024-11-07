<x-app-layout>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                @if ($errors->any())
                <ul class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Create Permissions
                            <a href="{{ url('permissions') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('permissions') }}" method="POST" id="permissionForm">
                            @csrf
                
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="">Permission Type</label>
                                    <input type="text" name="type" class="form-control" id="permissionTypeInput" required />
                                </div>
                                <div class="form-group col-md-11 mb-3">
                                    <label for="">Permission Name</label>
                                    <input type="text" id="permissionNameInput" class="form-control" />
                                </div>
                                <div class="form-group col-md-1 d-flex align-items-end justify-content-end mb-3">
                                    <button type="button" class="btn btn-secondary mt-2" id="addPermissionBtn">Add</button>
                                </div>
                            </div>
                                
                            <div class="mb-3">
                                <label>Permissions List</label>
                                <hr class="mt-0">
                                <ul id="permissionList" class="list-group"></ul>
                            </div>
                
                            <!-- Container for hidden inputs -->
                            <div id="hiddenInputsContainer"></div>
                
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#addPermissionBtn').on('click', function () {
                const permissionValue = $('#permissionNameInput').val().trim();

                if (permissionValue) {
                    // Add the permission to the list
                    $('#permissionList').append('<li class="list-group-item">' + permissionValue + '</li>');

                    // Add a hidden input with the permission value
                    $('#hiddenInputsContainer').append('<input type="hidden" name="names[]" value="' + permissionValue + '">');

                    // Clear the input field
                    $('#permissionNameInput').val('');
                }
            });

            // Ensure form cannot be submitted without permissions
            $('#permissionForm').on('submit', function (e) {
                if ($('#hiddenInputsContainer').children().length === 0) {
                    alert('Please add at least one permission before submitting.');
                    e.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>

</x-app-layout>