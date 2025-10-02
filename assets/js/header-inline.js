// Search functionality extracted from header.php
function toggleSearch() {
    const searchContainer = document.getElementById('searchContainer');
    const searchButton = document.querySelector('.search-toggle-button');
    const searchInput = document.getElementById('search');

    if (searchContainer.classList.contains('show')) {
        searchContainer.classList.remove('show');
        searchButton.classList.remove('active');
    } else {
        searchContainer.classList.add('show');
        searchButton.classList.add('active');
        setTimeout(() => {
            if (searchInput) searchInput.focus();
        }, 300);
    }
}

document.addEventListener('click', function(event) {
    const searchContainer = document.getElementById('searchContainer');
    const searchButton = document.querySelector('.search-toggle-button');
    const searchBox = document.querySelector('.search-box');

    if (searchContainer && searchContainer.classList.contains('show')) {
        if (!searchBox.contains(event.target) && !searchButton.contains(event.target)) {
            searchContainer.classList.remove('show');
            searchButton.classList.remove('active');
        }
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const searchContainer = document.getElementById('searchContainer');
        const searchButton = document.querySelector('.search-toggle-button');

        if (searchContainer && searchContainer.classList.contains('show')) {
            searchContainer.classList.remove('show');
            searchButton.classList.remove('active');
        }
    }
});
