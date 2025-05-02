    // Function to decrement the quantity
    function decrementQuantity() {
        var quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
            quantityInput.value--;
        }
    }

    // Function to increment the quantity
    function incrementQuantity() {
        var quantityInput = document.getElementById('quantity');
        quantityInput.value++;
    }