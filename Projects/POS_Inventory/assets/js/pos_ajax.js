$(document).ready(function() {
        
    $('.add-to-cart').click(function() {
        var itemId = $(this).data('item-id');
        var quantity = $(this).data('quantity');
        $.post('pos.php', { action: '', item_id: itemId, quantity: quantity }, function(response) {
            location.reload(); 
        });
    });

    
    $('.cart-item-quantity').on('change', function() {
        var itemId = $(this).data('item-id');
        var newQuantity = $(this).val();

        $.post('pos.php', { action: 'update_quantity', item_id: itemId, quantity: newQuantity }, function(response) {
            location.reload(); 
        });
    });

    
    $('.remove-item').on('click', function(e) {
        e.preventDefault();
        var itemId = $(this).data('item-id');

        $.post('pos.php', { 
            action: 'remove', item_id: itemId }, function(response) {
            location.reload(); 
        });
    });
});