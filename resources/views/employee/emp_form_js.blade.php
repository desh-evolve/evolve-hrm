<script>
    
$(document).ready(function(){
    console.log('emp_form_js loaded')
})

$(document).on('change', 'input[name="employment_type"]', function() {
    // Check the selected value
    const selectedValue = $(this).val();

    // Show month input only for Contract, Training, and Permanent (With Probation)
    if (selectedValue === "Contract" || selectedValue === "Training" || selectedValue === "PermanentProbation") {
        $('#month-selection').show();
        $('#months').prop('required', true); // Make month input required
    } else {
        $('#month-selection').hide();
        $('#months').prop('required', false); // Remove required attribute
        $('#months').val(''); // Clear month input
    }
});

</script>