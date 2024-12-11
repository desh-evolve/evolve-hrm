<script>


function convertHoursAndMinutesToSeconds(time) {
    if (typeof time === 'string' && time.includes(':')) {
        // Handle the 'HH:mm' string format
        const [hours, minutes] = time.split(':').map(Number);
        return (hours * 3600) + (minutes * 60);
    } else if (typeof time === 'number' || /^\d+$/.test(time)) {
        // Handle integer or numeric string
        return Number(time) * 3600; // Convert hours directly to seconds
    } else {
        // Invalid input
        throw new Error('Invalid time format');
    }
}


function convertSecondsToHoursAndMinutes(seconds) {
    // Convert seconds to a number
    const totalSeconds = Number(seconds);
    
    // Validate input
    if (isNaN(totalSeconds) || totalSeconds < 0) {
        throw new Error('Invalid seconds value');
    }

    // Calculate hours and minutes
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);

    // Format to ensure two digits for hours and minutes
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
}

function dateTimeToDate(){
    
}

</script>