<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch (true) {
        case isset($_POST['add_discount']):
            $name = $_POST['name'];
            $percentages = $_POST['percentages'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $promo_type = $_POST['promo_type'];  

            $valid_percentages = [];
            foreach ($percentages as $percentage) {
                $percentage = trim($percentage);
                if (!is_numeric($percentage) || (int)$percentage != $percentage || $percentage < 0 || $percentage > 100) {
                    $error_message = "Please enter valid percentages between 0 and 100.";
                    break;
                }
                $valid_percentages[] = (int)$percentage;
            }

            if (empty($error_message)) {
                $current_date = date('Y-m-d');
                if ($current_date < $start_date) {
                    $promo_status = 'Soon';
                } elseif ($current_date >= $start_date && $current_date <= $end_date) {
                    $promo_status = 'Available';
                } else {
                    $promo_status = 'Expired';
                }

                
                $check_discount_query = "SELECT id FROM Discounts WHERE name = ? AND start_date = ? AND end_date = ?";
                $stmt = $conn->prepare($check_discount_query);
                $stmt->bind_param("sss", $name, $start_date, $end_date);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $error_message = "A discount with the same name and date range already exists.";
                } else {
                    $insert_discount_query = "INSERT INTO Discounts (name, start_date, end_date, promo_status, promo_type) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insert_discount_query);
                    $stmt->bind_param("sssss", $name, $start_date, $end_date, $promo_status, $promo_type);

                    if ($stmt->execute()) {
                        $discount_id = $stmt->insert_id;

                        $insert_percentage_query = "INSERT INTO DiscountPercentages (discount_id, percentage) VALUES (?, ?)";
                        foreach ($valid_percentages as $percentage) {
                            $percentage_stmt = $conn->prepare($insert_percentage_query);
                            $percentage_stmt->bind_param("ii", $discount_id, $percentage);
                            if (!$percentage_stmt->execute()) {
                                $error_message = "Error adding percentage: " . $conn->error;
                                break;
                            }
                        }
                        if (!isset($error_message)) {
                            $success_message = "Discount and percentages added successfully!";
                            header("Location: " . $_SERVER['PHP_SELF']); 
                            exit;
                        }
                    } else {
                        $error_message = "Error adding discount: " . $conn->error;
                    }
                }
            }
            break;

        case isset($_POST['update_discount']):
            $id = $_POST['update_id'];
            $name = $_POST['update_name'];
            $percentages = $_POST['update_percentages'];
            $start_date = $_POST['update_start_date'];
            $end_date = $_POST['update_end_date'];
            $promo_type = $_POST['update_promo_type'];

            $valid_percentages = [];
            foreach ($percentages as $percentage) {
                $percentage = trim($percentage);
                if (!is_numeric($percentage) || (int)$percentage != $percentage || $percentage < 0 || $percentage > 100) {
                    $error_message = "Please enter valid percentages between 0 and 100.";
                    break;
                }
                $valid_percentages[] = (int)$percentage;
            }

            if (empty($error_message)) {
                $current_date = date('Y-m-d');
                if ($current_date < $start_date) {
                    $promo_status = 'Soon';
                } elseif ($current_date >= $start_date && $current_date <= $end_date) {
                    $promo_status = 'Available';
                } else {
                    $promo_status = 'Expired';
                }

                $update_query = "UPDATE Discounts SET name = ?, start_date = ?, end_date = ?, promo_status = ?, promo_type = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssssi", $name, $start_date, $end_date, $promo_status, $promo_type, $id);
                
                if ($stmt->execute()) {
                    $delete_percentages_query = "DELETE FROM DiscountPercentages WHERE discount_id = ?";
                    $delete_stmt = $conn->prepare($delete_percentages_query);
                    $delete_stmt->bind_param("i", $id);
                    $delete_stmt->execute();
                
                    $insert_percentage_query = "INSERT INTO DiscountPercentages (discount_id, percentage) VALUES (?, ?)";
                    foreach ($valid_percentages as $percentage) {
                        $percentage_stmt = $conn->prepare($insert_percentage_query);
                        $percentage_stmt->bind_param("ii", $id, $percentage);
                        if (!$percentage_stmt->execute()) {
                            $error_message = "Error adding percentage: " . $conn->error;
                            break;
                        }
                    }
                    if (!isset($error_message)) {
                        $success_message = "Discount updated successfully!";
                        header("Location: " . $_SERVER['PHP_SELF']); 
                        exit;
                    }
                } else {
                    $error_message = "Error updating discount: " . $conn->error;
                }
            }
            break;

        case isset($_POST['delete_discount']):
            $delete_id = $_POST['delete_id'];

            $delete_discount_query = "DELETE FROM Discounts WHERE id = ?";
            $stmt = $conn->prepare($delete_discount_query);
            $stmt->bind_param("i", $delete_id);

            if ($stmt->execute()) {
                $delete_percentages_query = "DELETE FROM DiscountPercentages WHERE discount_id = ?";
                $percentage_stmt = $conn->prepare($delete_percentages_query);
                $percentage_stmt->bind_param("i", $delete_id);
                $percentage_stmt->execute();

                $success_message = "Discount and associated percentages deleted successfully!";
                header("Location: " . $_SERVER['PHP_SELF']); 
                exit();
            } else {
                $error_message = "Error deleting discount: " . $conn->error;
            }
            break;
    }
}


?>



    <!-- Update Discount Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUpdateModal()">&times;</span>
        <h2>Update Discount</h2>
        <form id="updateForm" method="POST" action=" ">
            <input type="hidden" name="update_id" id="update_id">

            <div class="form-group">
                <label for="update_name">Discount Name:</label>
                <input type="text" name="update_name" id="update_name" required>
            </div>

            <div class="form-group">
                <label for="update_percentages">Discount Percentages:</label>
                <div id="update_percentages-container">
                    <!-- Existing percentages fields will be appended here -->
                </div>
                <button type="button" id="add-update-percentage" class="btn-add">Add another percentage</button>
            </div>

            <div class="form-group">
                <label for="update_promo_type">Promotion Type:</label>
                <select name="update_promo_type" id="update_promo_type" required>
                    <option value="BOGO">BOGO</option>
                    <option value="BOGO Free">BOGO Free</option>
                    <option value="Holiday Sales">Holiday Sales</option>
                </select>
            </div>

            <div class="form-group">
                <label for="update_start_date">Start Date:</label>
                <input type="date" name="update_start_date" id="update_start_date" required>
            </div>

            <div class="form-group">
                <label for="update_end_date">End Date:</label>
                <input type="date" name="update_end_date" id="update_end_date" required>
            </div>

            <button type="submit" name="update_discount" class="btn-submit">Update Discount</button>
        </form>
    </div>
</div>

<!-- Add Discount Modal -->
<div id="addDiscountModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>Add Discount</h2>
        <form method="POST" action=" ">
            <div class="form-group">
                <label for="name">Discount Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="percentages">Discount Percentages:</label>
                <div id="percentages-container">
                    <div class="percentage-field">
                        <input type="number" name="percentages[]" class="percentage-input" placeholder="Enter percentage" required>
                        <button type="button" class="remove-percentage">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-percentage" class="btn-add">Add another percentage</button>
            </div>

            <div class="form-group">
                <label for="promo_type">Promotion Type:</label>
                <select name="promo_type" id="promo_type" required>
                    <option value="None">None</option>
                    <option value="BOGO">BOGO</option>
                    <option value="BOGO Free">BOGO Free</option>
                    <option value="Holiday Sales">Holiday Sales</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" required>
            </div>

            <button type="submit" name="add_discount" class="btn-submit">Add Discount</button>
        </form>
    </div>
</div>


 <!-- Delete Discount Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h2>Are you sure you want to delete this discount?</h2>
        <form id="deleteForm" method="POST" action=" ">
            <input type="hidden" name="delete_id" id="delete_id">
            <button type="submit" name="delete_discount" class="btn-submit">Yes, Delete</button>
            <button type="button" onclick="closeDeleteModal()" class="btn-cancel">Cancel</button>
        </form>
    </div>
</div>





        
<script>
document.getElementById("addDiscountBtn").addEventListener("click", function() {
    document.getElementById("addDiscountModal").style.display = "block";
});

function closeAddModal() {
document.getElementById("addDiscountModal").style.display = "none";
}


document.getElementById("add-percentage").addEventListener("click", function() {
const container = document.getElementById("percentages-container");
const inputField = document.createElement("div");
inputField.classList.add("percentage-field");
inputField.innerHTML = `
    <input type="number" name="percentages[]" class="percentage-input" placeholder="Enter percentage" required>
    <button type="button" class="remove-percentage">Remove</button>
`;
container.appendChild(inputField);

inputField.querySelector('.remove-percentage').addEventListener('click', function() {
    container.removeChild(inputField);
});
});


function openUpdateModal(id, name, percentages, start_date, end_date) {
document.getElementById("updateModal").style.display = "block";
document.getElementById("update_id").value = id;
document.getElementById("update_name").value = name;
document.getElementById("update_start_date").value = start_date;
document.getElementById("update_end_date").value = end_date;

const percentagesContainer = document.getElementById("update_percentages-container");
percentagesContainer.innerHTML = "";


percentages.split(',').forEach((percent) => {
    const field = document.createElement("div");
    field.classList.add("percentage-field");
    field.innerHTML = `
<input type="number" name="update_percentages[]" class="percentage-input" value="${percent}" required>
<button type="button" class="remove-percentage">Remove</button>
`;
    percentagesContainer.appendChild(field);


    field.querySelector('.remove-percentage').addEventListener('click', function() {
        percentagesContainer.removeChild(field);
    });
});


document.getElementById("add-update-percentage").addEventListener("click", function() {
    const newField = document.createElement("div");
    newField.classList.add("percentage-field");
    newField.innerHTML = `
<input type="number" name="update_percentages[]" class="percentage-input" placeholder="Enter percentage" required>
<button type="button" class="remove-percentage">Remove</button>
`;
    percentagesContainer.appendChild(newField);


    newField.querySelector('.remove-percentage').addEventListener('click', function() {
        percentagesContainer.removeChild(newField);
    });
});
}

function closeUpdateModal() {
document.getElementById("updateModal").style.display = "none";
}

function openDeleteModal(id) {
console.log('Opening delete modal for ID:', id);
document.getElementById("deleteModal").style.display = "block";
document.getElementById("delete_id").value = id;
}

function closeDeleteModal() {
document.getElementById("deleteModal").style.display = "none";
}


</script>




