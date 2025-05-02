<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">

    <title>View Item</title>
    <style>
 .category-links{
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #000000;
    /* padding: 5px; */
    padding: 0;
    color: white;
}

.category-links p{
    background-color: #d43132;
}

.category-links a{
    text-decoration: none;
    padding: 6px;
    color: white;
}

.category-links a:hover{
    background-color: #5280e2;
}

.title{
    background-color: #d43132;
}

/* --------------------------------  */
body{
    background-color: #f5f5f5;
    padding: 0;
        margin: 0;
        /* overflow-x: hidden; */
    }
    @font-face {
        font-family: 'oswald';
        src: url(/PHP-Projects/E-Commerce-BeatStrum/src/1-Oswald-Font/Oswald-VariableFont_wght.ttf);
      }
      * {
        font-family: 'oswald';
      }
.head {
display: flex;
background-color: #222222;
justify-content: space-between;
align-items: center;
margin-right: 1%;
width: calc(100% - 40px); 
/* margin-top: 20px;    */
padding: 20px
}
.head img{
    max-width: 100%;
    height: 100px;
    margin: 0 auto;
    /* border-radius: 50%; */
    padding-right: 163px;
}
.head-group a{
    color: white;
    text-decoration: none;
}


.head-group i{
    font-size: 30px;
}
.head-group{
    display: flex;
    justify-content: space-around;
}
.head-group1 a{
    padding-bottom: 10px;
    color: white;
    text-decoration: none;
}

.head-group1{
    /* text-align: center; */
    display: flex;
    flex-direction: column;
}


.sorting-options {
    display: flex;
    color: white;
    align-items: center;
}

.sorting-options label {
    margin-right: 10px;
}

.sorting-options select {
    padding: 8px;
    background-color: #282828;
    border: 2px solid black;
    cursor: pointer;
    color: white;
    outline: none;
}

.sorting-options select:hover {
    border: 2px solid white;
    background-color: #282828;
}

.sorting-options select:focus {
    border: 2px solid #D2292D;
}

.sorting-options select option {
    padding: 10px;
    border-radius: 5px;
}

.search-box {
    display: flex;
    align-items: center;
    margin-right: 10px;
}


.bx-search {
    font-size: 25px;
    /* color: #D2292D; */
    color: white;
    margin-right: 5px;
}


input[type="text"].search {
    padding: 8px 10px;
    border: 1px solid #000000;
    /* border-radius: 5px; */
    outline: none;
    font-size: 14px;
    width: 280px;
    height: 30px;
}


</style>
</head>
<body>   
    
<div class="head">


<div class="head-group1">
    <a href="item_category.php">Browse by Category</a>
<?php
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = '';
if (!empty($search_query)) {
    $search_condition = "WHERE items.name LIKE '%$search_query%'";
}
?>

    <li class="search-box">
        <form action="index.php" method="GET">
            <input class="search" type="text" name="search" placeholder="Search...">
            <i class='bx bx-search icon'></i>
        </form>
    </li>
</div>

<div>
    <a href="index.php"><img src="/PHP-Projects/E-Commerce-BeatStrum/images/svg/logo-no-background.svg" alt=""></a></li>
</div>

<div>
<ul class="head-group">

       <a href="#"><i class='bx bx-heart'></i></a>
       <a href="shopping_cart.php"><i class='bx bx-cart'></i></a>
       <a href="user_details.php"><i class='bx bx-user-circle'></i></a>
    </ul>

    <div class="sorting-options">
        <label for="sort">Sort by:</label>
        <select>
            <option>Name (A-Z)</option>
            <option>Name (Z-A)</option>
            <option>Most Sold</option>
            <option>Price (Low to High)</option>
            <option>Price (High to Low)</option>
        
        </select>
    </div>
    
    </div>
    </div>
<div class="categories">
        <div class="category-links">
            <?php
            echo "<a class='title'>Category</a>";
            $sql_categories = "SELECT * FROM categories";
            $result_categories = $conn->query($sql_categories);
            if ($result_categories->num_rows > 0) {
                while ($row_category = $result_categories->fetch_assoc()) {
                    $category_id = $row_category['id'];
                    $category_name = $row_category['name'];
                    // Modify the following line to include the anchor tags
                    echo "<a href='item_category.php?category=$category_id'>$category_name</a>";
                }
            }
            ?>
        </div>
    </div>

	</body>
</html>
