// Get the base URL without any path after the domain (e.g., 'http://127.0.0.1:8000')
const baseUrl = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;

// Check for elements and load scripts dynamically
(document.querySelectorAll("[toast-list]") ||
 document.querySelectorAll("[data-choices]") ||
 document.querySelectorAll("[data-provider]")) &&
(
    document.writeln(
        `<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'></script>`
    ),
    document.writeln(
        `<script type='text/javascript' src='${baseUrl}/assets/libs/choices.js/public/assets/scripts/choices.min.js'></script>`
    ),
    document.writeln(
        `<script type='text/javascript' src='${baseUrl}/assets/libs/flatpickr/flatpickr.min.js'></script>`
    )
);
