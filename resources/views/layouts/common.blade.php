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

//desh(2024-10-18)
async function commonDeleteFunction(itemId, deleteUrl, itemName, $row = null) {
    // Show confirmation modal
    $('#delete_item_name').text(itemName);
    $('#delete_modal').modal('show');

    // Attach an event listener to the "Yes, Delete It!" button
    $('#delete-confirm').off('click').on('click', async function() {
        try {

            // Send DELETE request using Fetch API
            const response = await fetch(`${deleteUrl}/${itemId}`, {
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
            return response.ok;
        } catch (error) {
            let icon = 'error';
            let msg = `Error deleting ${itemName}`;
            commonAlert(icon, msg);
            console.error('Error deleting the item:', error.message);
            return false;
        }
    });
}

//desh(2024-10-18)
async function commonFetchData(url) {
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data?.data || [];
    } catch (error) {
        console.error(`Error fetching data from ${url}:`, error);
        return [];
    }
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