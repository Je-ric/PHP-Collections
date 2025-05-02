//Confirmation Product

const add_Category = document.getElementById("add_Category");
add_Category.addEventListener("click", function () {
  alert("Category has been added successfully!");
});

const Add_Product = document.getElementById("addProduct");

addProduct.addEventListener("click", function () {
  alert("Product added successfully!");
});

const Delete_Product = document.getElementById("deleteProduct");

deleteProduct.addEventListener("click", function () {
  alert("Product deleted successfully.");
});

const Restock_Product = document.getElementById("restockProduct");

restockProduct.addEventListener("click", function () {
  alert("Product restock successfully!");
});



// toastr.success(message, title): Shows a green toast for successful actions.
// toastr.error(message, title): Shows a red toast for errors.
// toastr.warning(message, title): Shows a yellow toast for warnings.
// toastr.info(message, title): Shows a blue toast for informational messages.

// Accounts.php
function confirmDownloadAll() {
  const userConfirmed = confirm("Are you sure you want to download the details of all users? This will include all user information.");
  if (userConfirmed) {
      toastr.success("All user details downloaded successfully!", "Success");
      return true; 
  }
  else{
    toastr.error("Download canceled.", "Canceled");
    return false;
  }
}

const add_User = document.getElementById("addUser");

addUser.addEventListener("click", function () {
  alert("User added successfully!");
});

  function confirmDownloadUser() {
  const userConfirmed = confirm("Are you sure you want to download the details of the selected user including attendance history?");
  if (userConfirmed) {
      toastr.success("Details downloaded successfully!", "Success");
      return true; 
  }
  else{
    toastr.error("Download canceled.", "Canceled");
    return false;
  }
}

//Payment.php
function confirmPayment() {
  const userConfirmed = confirm("Are you sure you want to proceed with the payment?");
  if (userConfirmed) {
      toastr.success("Payment confirmed successfully!", "Success"); //hindi nga lang makikita kase asa ibang page
      return true;
  } else {
      toastr.error("Payment was canceled.", "Canceled");
      return false;
  }
}

//Receipt.php
function confirmVoidSale() {
  const userConfirmed = confirm("Are you sure you want to void this sale?");
  if (userConfirmed) {
      toastr.success("Sale voided successfully!", "Success");
      return true;
  } else {
      toastr.error("Sale voiding canceled.", "Cancelled");
      return false;
  }
}