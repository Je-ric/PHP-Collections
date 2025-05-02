<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<!-- Product -->

<!--  Add New Product -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddProductModal()">&times;</span>
        <h2>Add New Product</h2>

        <?php
        $result = $conn->query("SELECT MAX(item_id) AS max_id FROM Item");
        $row = $result->fetch_assoc();
        $nextNumber = $row['max_id'] + 1; 
        ?>
        <form id="addProductForm" method="POST">
            <input type="hidden" name="action" value="addItem"> 
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />

            <input type="hidden" name="product_no" value="<?php echo $nextNumber; ?>" readonly><br>
            <label>Item Name:</label>
            <input type="text" name="name" required><br>
            <label>Brand:</label>
            <input type="text" name="brand" required><br>
            <div class="form-row">
                <div>
                    <label>Size:</label>
                    <input type="text" name="size"  required>
                </div>
                <div>
                    <label>Colors:</label>
                    <input type="text" name="color" id="colorInput" list="colorOptions" placeholder="Type the Color(s), separated by commas" required>
                    <datalist id="colorOptions">
                        <?php
                        $colors = $conn->query("SELECT color_name FROM Colors");
                        while ($color = $colors->fetch_assoc()) {
                            echo "<option value='{$color['color_name']}'>";
                        }
                        ?>
                    </datalist>
                </div>
            </div>
                <br>
                <div class="form-row">
                    <label>Quantity:</label>
                    <span class="input-number-decrement">–</span>
                    <input name="quantity" class="input-number" type="text" value="1" min="0" max="10" pattern="\d*" inputmode="numeric" required>
                    <span class="input-number-increment">+</span>
                </div>
                <br>
                <div class="form-row">
                    <label>Investment Price:</label>
                    <input type="number" step="0.01" name="investment_price" required><br> 
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" required><br> 
                </div>

                <input type="hidden" name="category_id" id="addCategoryId" value="<?php echo $category_id; ?>">
                <input type="hidden" name="category_id" value="<?php echo isset($_GET['category_id']) ? $_GET['category_id'] : ''; ?>"> <!-- Pass selected category_id -->
                <div class="addModalBtn">
                    <button type="submit" class="button-confirm" id=addProduct>Confirm Add Product</button>
                    <button type="button" class="button-cancel" onclick="closeAddProductModal()">Cancel</button>
                </div>
        </form>
    </div>
</div>

<!-- Modal for Restocking Product -->
<div id="restockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRestockModal()">&times;</span>
        <h2>Restock Product</h2>
        <form id="restockForm" method="POST">
    <input type="hidden" name="action" value="restockItem">
    <input type="hidden" name="item_id" id="restockItemId">
    <label>Restock Quantity:</label>
    <input type="number" name="quantity" min="1" required><br>
    <button type="submit" class="button-confirm" id= restockProduct >Confirm Restock</button>
    <button type="button" class="button-cancel" onclick="closeRestockModal()">Cancel</button>
</form>
    </div>
</div>

<!-- Modal for Deleting Product -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Confirmation</h2><br>
        <p>Are you sure you want to delete this item?</p><br>
        <form id="deleteForm" method="POST" action="">
            <input type="hidden" name="action" value="deleteItem">
            <input type="hidden" name="item_id" id="itemIdToDelete">
            <button type="submit" class="button-confirm" id="deleteProduct"name="delete">Yes, Delete</button>
            <button type="button" class="button-cancel" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>


<!-- Category -->
   <!--  Delete Modal -->
   <div id="deleteCategoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteCategoryModal()">&times;</span>
            <h2>Confirmation</h2><br>
            <p>Are you sure you want to delete this category?</p><br>
            <form method="POST">
                <input type="hidden" name="category_id" id="categoryIdToDelete">
                <input type="hidden" name="action" value="delete"> 
                <input type="submit" class="button-confirm" name="delete">
                <button type="button" class="button-cancel" onclick="closeDeleteCategoryModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2>Update Category</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="category_id" id="updateCategoryId">
                <input type="hidden" name="action" value="update"> 
                <label for="name">Category Name:</label>
                <input type="text" name="name" id="updateCategoryName" required>
                <br>
                <label>Existing Image:</label>
                <img id="existingImage" alt="Existing Image"><br>
                <label for="image">Update Image:</label>
                <input type="file" name="image">
                <br>
                <input type="submit" class="button-confirm" name="update" value="Update">
                <button type="button" class="button-cancel" onclick="closeUpdateModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddCategoryModal()">&times;</span>
            <h2>Add Category</h2>
            <form method="POST" enctype="multipart/form-data">
                <label for="name">Category Name:</label>
                <input type="text" name="name" " required>
                <br>
                <label for="image">Category Image:</label>
                <input type="file" name="image" required>
                <br>
                <input type="hidden" name="action" value="add"> 
                <input type="submit" class="button-confirm" name="add" id=add_Category value="Add Category">
                <button type="button" class="button-cancel" onclick="closeAddCategoryModal()">Cancel</button>
            </form>
        </div>
    </div>

</body>
<script>
    // Product
function openAddProductModal() {
    document.getElementById('addProductModal').style.display = 'block';
}


function closeAddProductModal() {
    document.getElementById('addProductModal').style.display = 'none';
}

function openRestockModal(item_id) {
    document.getElementById('restockModal').style.display = 'block';
    document.getElementById('restockItemId').value = item_id; 
}

function closeRestockModal() {
    document.getElementById('restockModal').style.display = 'none';
}

function confirmDelete(item_id) {
    document.getElementById('confirmModal').style.display = 'block';
    document.getElementById('itemIdToDelete').value = item_id; 
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
}


// Category
    function openUpdateModal(categoryId, categoryName, categoryImage) {
        const updateModal = document.getElementById('updateModal');
        updateModal.style.display = 'block';
        document.getElementById('updateCategoryId').value = categoryId;
        document.getElementById('updateCategoryName').value = categoryName;
        document.getElementById('existingImage').src = categoryImage;
    }

    function closeUpdateModal() {
        const updateModal = document.getElementById('updateModal');
        updateModal.style.display = 'none';
    }

    function openAddCategoryModal() {
        const addModal = document.getElementById('addModal');
        addModal.style.display = 'block';
    }

    function closeAddCategoryModal() {
        const addModal = document.getElementById('addModal');
        addModal.style.display = 'none';
    }

    function deleteCategoryModal(categoryId) {
    const confirmModal = document.getElementById('deleteCategoryModal');
    confirmModal.style.display = 'block';
    document.getElementById('categoryIdToDelete').value = categoryId;
}

function closeDeleteCategoryModal() {
    const confirmModal = document.getElementById('deleteCategoryModal');
    confirmModal.style.display = 'none';
}


</script>
<script src="../../assets/js/confirmations.js"></script>
</html>