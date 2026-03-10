document.addEventListener('DOMContentLoaded', function () {
    const closeAllDropdowns = () => {
        document.querySelectorAll('details.dropdown-action').forEach(d => d.removeAttribute('open'));
    };

    window.addEventListener('pageshow', (event) => {
        closeAllDropdowns();
    });

    document.addEventListener('click', function (event) {
        const allDropdowns = document.querySelectorAll('details.dropdown-action');

        allDropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target)) {
                dropdown.removeAttribute('open');
            }
        });

        if (event.target.closest('details.dropdown-action summary')) {
            const currentDropdown = event.target.closest('details.dropdown-action');
            allDropdowns.forEach(dropdown => {
                if (dropdown !== currentDropdown) {
                    dropdown.removeAttribute('open');
                }
            });
        }
    });
});
