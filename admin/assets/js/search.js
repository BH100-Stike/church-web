document.addEventListener('DOMContentLoaded', () => {
    // Sample data - replace with your actual data
    const searchData = [
        { id: 1, name: "Sunday Service", type: "events", url: "events/events.php?id=1" },
        { id: 1, name: "Pastor Ngamsiha Donatus", type: "leaders", url: "leaders/leaders.php?id=1" },
        { id: 1, name: "Homepage Banner", type: "hero", url: "hero/hero.php?id=1" },
        { id: 1, name: "ministry", type: "ministries", url: "ministries/ministries.php?id=1" },
        // Add more items as needed
    ];

    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const searchResults = document.getElementById('search-results');

    function performSearch(query) {
        if (!query || query.length < 2) return [];
        query = query.toLowerCase();
        return searchData.filter(item => 
            item.name.toLowerCase().includes(query) || 
            item.type.toLowerCase().includes(query)
        );
    }

    function displayResults(results) {
        searchResults.innerHTML = '';
        
        if (!results || results.length === 0) {
            searchResults.innerHTML = '<div class="no-results">No results found</div>';
            searchResults.style.display = 'block';
            return;
        }
        
        results.forEach(item => {
            const link = document.createElement('a');
            link.href = item.url;
            link.innerHTML = `
                <span class="result-name">${item.name}</span>
                <span class="result-type">(${item.type})</span>
            `;
            searchResults.appendChild(link);
        });
        
        searchResults.style.display = 'block';
    }

    // Event listeners
    searchInput.addEventListener('input', () => {
        displayResults(performSearch(searchInput.value.trim()));
    });

    searchButton.addEventListener('click', () => {
        displayResults(performSearch(searchInput.value.trim()));
    });

    // Hide results when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-box')) {
            searchResults.style.display = 'none';
        }
    });
});