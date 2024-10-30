<script>
    
let dropdownData = [];

$(document).ready(function(){
    getDropdownData();
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

async function getDropdownData() {
    try {
        dropdownData = await commonFetchData('/employee/dropdown');
        console.log('dropdown', dropdownData)
        /*
        // Populate country dropdown
        let countryList = (dropdownData?.countries || [])
            .map(country => `<option value="${country.id}">${country.country_name} (${country.country_code})</option>`)
            .join('');
        $('#country_id').html('<option value="">Select a country</option>' + countryList);

        // Default values for province and city
        $('#province_id').html('<option value="">Select a country first</option>');
        $('#city_id').html('<option value="">Select a country first</option>');

        // Populate currency dropdown
        let currencyList = (dropdownData?.currencies || [])
            .map(currency => `<option value="${currency.id}">${currency.currency_name} (${currency.iso_code})</option>`)
            .join('');
        $('#currency_id').html('<option value="">Select a currency</option>' + currencyList);
        */
    } catch (error) {
        console.error('Error fetching dropdown data:', error);
    }
}

</script>