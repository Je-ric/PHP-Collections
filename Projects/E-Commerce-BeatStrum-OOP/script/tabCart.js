
document.addEventListener("DOMContentLoaded", function() {
    // Retrieve stored quantities from session storage if available
    var storedQuantities = sessionStorage.getItem('cartQuantities');
    var cartQuantities = storedQuantities ? JSON.parse(storedQuantities) : {};

    // Update quantity fields with stored quantities
    var quantityInputs = document.querySelectorAll('.item-quantity');
    quantityInputs.forEach(function(input) {
        var itemId = input.getAttribute('data-item-id');
        if (itemId in cartQuantities) {
            input.value = cartQuantities[itemId];
        }
    });

    var form = document.getElementById('cartForm');
    form.addEventListener('submit', function(event) {
        var activeElement = document.activeElement;
        if (activeElement && activeElement.tagName === 'INPUT' && activeElement.type === 'number' && activeElement.classList.contains('item-quantity')) {
            event.preventDefault(); // Prevent form submission when quantity input is changed
        }
    });

    var checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateOrderSummary();
        });
    });

    quantityInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            updateOrderSummary();
            // Store the updated quantity in session storage
            var itemId = input.getAttribute('data-item-id');
            cartQuantities[itemId] = input.value;
            sessionStorage.setItem('cartQuantities', JSON.stringify(cartQuantities));
        });
    });

    function updateOrderSummary() {
        var selectedItems = document.querySelectorAll('.item-checkbox:checked');
        var totalItems = selectedItems.length;
        var totalPrice = 0;
        var totalShippingFee = 0;

        selectedItems.forEach(function(item) {
            var itemTotal = parseFloat(item.parentNode.querySelector('.item-price').textContent.replace('Price: ₱', '')) * parseInt(item.parentNode.querySelector('.item-quantity').value);
            totalPrice += itemTotal;
            var itemShippingFee = parseFloat(item.parentNode.querySelector('.item-shipping').textContent.replace('Shipping: ₱', '')) * parseInt(item.parentNode.querySelector('.item-quantity').value);
            totalShippingFee += itemShippingFee;
        });

        document.getElementById('totalItems').textContent = "Total Items: " + totalItems;
        document.getElementById('orderPrice').textContent = "Price: ₱" + totalPrice.toFixed(2); // Change $ to ₱
        document.getElementById('orderShipping').textContent = "Total Shipping Fee: ₱" + totalShippingFee.toFixed(2); // Change $ to ₱
        document.getElementById('orderTotal').textContent = "Total Price: ₱" + (totalPrice + totalShippingFee).toFixed(2); // Change $ to ₱
    }
});
