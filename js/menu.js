/*menu.js*/
document.addEventListener('DOMContentLoaded', () => {
const filterBtns = document.querySelectorAll('.filter-btn');
const menuItems = document.querySelectorAll('.menu-item');
const filterbar = document.getElementById('searchInput');
const orderBtn = document.querySelectorAll('.order-btn');

let activeCategory = 'all';


function filterMenu() {
        const searchTerm = filterbar.value.toLowerCase();
        const currentCategory = activeCategory || 'all';  // fallback to 'all' if undefined
    
        // display menu items if the search term is in the item name
        menuItems.forEach(item => {
            const itemName = item.dataset.name.toLowerCase();
            const itemCategory = item.dataset.category;

            if (itemName.includes(searchTerm) && (currentCategory === 'all' || itemCategory === currentCategory)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
}

// categories listener
filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
        
            // make the selected category active for styling
            filterBtns.forEach(button => button.classList.remove('active'));
            btn.classList.add('active');
            //stores the active category for filtering
            activeCategory = btn.dataset.category;
            filterMenu();
        });
});

filterbar.addEventListener('input', () => {
        filterMenu();
    });

            
filterMenu();

});
