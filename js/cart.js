document.addEventListener('DOMContentLoaded', () => {
    // get buttons from page
    const buttons = document.querySelectorAll('.qty-btn');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.dataset.id;
            const action = button.dataset.action;
            // get the parent order item card and its price
            const card = button.closest('.order-item');
            const price = parseFloat(card.dataset.price);

            // send the item id and action to update_cart
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `item_id=${itemId}&action=${action}`
            })
            .then(response => response.json())
            .then(cart => {
                if (!cart[itemId]) {
                    card.remove();
                } else {
                    // update the quantity and subtotal displayed on the card
                    const newQty = cart[itemId];
                    card.querySelector('.qty-display').textContent = newQty;
                    card.querySelector('.subtotal').textContent = '$' + (price * newQty).toFixed(2);
                }
                updateTotal();
            });
        });
    });

    // recalculates the total price based on all remaining order items
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(card => {
            const price = parseFloat(card.dataset.price);
            const qty = parseInt(card.querySelector('.qty-display').textContent);
            total += price * qty;
        });
        document.querySelector('.total-display').textContent = '$' + total.toFixed(2);
    }
});