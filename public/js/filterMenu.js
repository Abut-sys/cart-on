function toggleFilterMenu() {
    const menu = document.getElementById('filterMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(event) {
    const filterMenu = document.getElementById('filterMenu');
    const filterBtn = document.querySelector('.costumers-index-filter-btn');
    if (filterMenu.style.display === 'block' &&
        !filterBtn.contains(event.target) &&
        !filterMenu.contains(event.target)) {
        filterMenu.style.display = 'none';
    }
});
