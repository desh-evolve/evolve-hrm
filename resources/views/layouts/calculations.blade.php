<script>

function convertSecondsToHoursAndMinutes(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);

    // Format to ensure two digits for hours and minutes
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
}

function convertHoursAndMinutesToSeconds(time) {
    const [hours, minutes] = time.split(':').map(Number);
    return (hours * 3600) + (minutes * 60);
}

</script>