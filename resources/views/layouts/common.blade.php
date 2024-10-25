<!-- desh(2024-10-16) -->
<!-- delete modal -->
<div id="delete_modal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this <span id="delete_item_name"></span>?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-confirm">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

async function commonDeleteFunction(itemId = null, deleteUrl, itemName, $row = null) {
    return new Promise((resolve) => {
        // Show confirmation modal
        $('#delete_item_name').text(itemName);
        $('#delete_modal').modal('show');

        // Attach an event listener to the "Yes, Delete It!" button
        $('#delete-confirm').off('click').on('click', async function () {
            try {
                // Send DELETE request using Fetch API
                const response = await fetch(`${deleteUrl}${itemId ? '/' + itemId : ''}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                    }
                });

                const res = await response.json();  // Parse response JSON

                // Common operation: hide the modal
                $('#delete_modal').modal('hide');

                let icon = response.ok ? 'success' : 'warning';
                let msg = response.ok ? res.message || `${itemName} deleted successfully!` : res.message || `Failed to delete ${itemName}`;
                commonAlert(icon, msg);
                if (response.ok && $row) {
                    $row.remove();
                }

                resolve(response.ok); // Resolve the promise with the response status
            } catch (error) {
                let icon = 'error';
                let msg = `Error deleting ${itemName}`;
                commonAlert(icon, msg);
                console.error('Error deleting the item:', error.message);
                resolve(false); // Resolve with false on error
            }
        });

        // Handle modal close event to resolve promise with false if canceled
        $('#delete_modal').on('hidden.bs.modal', function () {
            resolve(false); // User canceled the deletion
        });
    });
}


//desh(2024-10-18)
async function commonFetchData(url) {
    return new Promise(async (resolve) => {
        try {
            // Send GET request using Fetch API
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Optional CSRF token for Laravel
                }
            });

            // Check if the response is OK (status 200-299)
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Parse the response as JSON
            const data = await response.json();

            // Resolve with the data or an empty array if no data
            resolve(data?.data || []);
        } catch (error) {
            console.error(`Error fetching data from ${url}:`, error.message);

            // Resolve with an empty array on error
            resolve([]);
        }
    });
}


//desh(2024-10-18)
async function commonSaveData(url, formData, method = "POST") {
    formData.append('_method', method);

    let errorResponse = {
        status: 'error',
        message: 'Something went wrong!',
        data: []
    };

    try {
        const response = await fetch(url, {
            method: 'POST',              // Set the method to POST when save and PUT when update
            body: formData,              // Send the FormData
            headers: {
                'Accept': 'application/json',  // Ensure the server responds with JSON
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
            }
        });

        // Check if the response status is OK (HTTP 2xx)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();   // Parse the response to JSON
        // Return the actual data or fallback to error response if the structure is unexpected
        return data || errorResponse;

    } catch (error) {
        console.error(`Error fetching data from ${url}:`, error);
        return errorResponse;
    }
}



//desh(2024-10-18)
// Function to handle showing success or error messages
function commonAlert(icon, msg) {
    Swal.fire({
        position: "top-end",
        icon: icon, // success/warning/info/error
        title: msg,
        showConfirmButton: false,
        timer: 1500,
        showCloseButton: true
    });
}

</script>