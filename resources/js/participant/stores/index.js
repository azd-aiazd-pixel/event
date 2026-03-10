document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const storeCards = document.querySelectorAll('.store-card');
    const noResultsMessage = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            storeCards.forEach(card => {
                const storeName = card.getAttribute('data-name');

                if (storeName.includes(searchTerm)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0 && searchTerm !== '') {
                noResultsMessage.classList.remove('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
            }
        });
    }
});
