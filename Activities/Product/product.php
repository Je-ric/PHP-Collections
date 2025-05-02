<?php

class Product {
    private $name;
    private $description;
    private $price;
    private $quantity;
    
    public function __construct($name, $description, $price, $quantity) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
    }
    
    public function calculateTotal($quantity) {
        $totalPrice = $this->price * $quantity;
        return $totalPrice;
    }
    public function isInStock() {
        return $this->quantity > 0;
    }
    public function updateQuantity($newQuantity) {
        $this->quantity = $newQuantity;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getQuantity() {
        return $this->quantity;
    }
}


$product1 = new Product("Laptop", "Second-Hand laptop (local)", 1200, 1);
$product2 = new Product("Smartphone", "Oppo Nalang", 800, 5);
$product3 = new Product("Headphones", "Noise-canceling daw", 200, 6);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newQuantity = $_POST["new_quantity"];
    
    if (isset($_POST["update_product1"])) {
        $product1->updateQuantity($newQuantity);
    } elseif (isset($_POST["update_product2"])) {
        $product2->updateQuantity($newQuantity);
    } elseif (isset($_POST["update_product3"])) {
        $product3->updateQuantity($newQuantity);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
          
          body {
        background: linear-gradient(90deg, #2cd9ff, #7effb2);
        background-size: 400% 400%;
        animation: moving 2.5s infinite ease-in-out;
        margin: 0;
        padding: 0;
    }

    @keyframes moving {
        0% {
            background-position: 0 50%; 
        } 
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0 50%;
        }
    }

     .container{
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid black;
            width: 40%;
            margin: auto;
            padding: 10px;
            margin-top: 10%;
            border-radius: 8px;
        }

        .container-1{
            margin: 0;
        } 

        .container h1{
            border-bottom: 1px solid;
            padding-bottom: 10px;
        }

        button{
            background-color: blue;
            padding: 8px;
            border-radius: 10px;
            color: white;
        }

        button:hover{
            background-color: black;
            padding: 8px;
            color: white;
            margin: 0 auto;
        }
        input[type="number"]{
            background: transparent;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="container-1">
    <h1><?php echo $product1->getName(); ?></h1>
    <p><b>Description: </b><?php echo $product1->getDescription(); ?></p>
    <p><b>Price: Php </b><?php echo $product1->getPrice(); ?></p>
    <p><b>Quantity: </b><?php echo $product1->getQuantity(); ?></p>
    <p><b>Availability: </b><?php echo $product1->isInStock() ? 'In Stock' : 'Out of Stock'; ?></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_quantity_product1"><b>Enter New Quantity for Product 1: </b></label> 
        <input type="number" id="new_quantity_product1" name="new_quantity" min="0" value="0"> 
        <button type="submit" name="update_product1">Update Quantity</button> 
    </form>

    <!-- <h1><?php echo $product2->getName(); ?></h1>
    <p>Description: <?php echo $product2->getDescription(); ?></p>
    <p>Price: $<?php echo $product2->getPrice(); ?></p>
    <p>Quantity: <?php echo $product2->getQuantity(); ?></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_quantity_product2">Enter New Quantity for Product 2:</label>
        <input type="number" id="new_quantity_product2" name="new_quantity" min="0" value="0">
        <button type="submit" name="update_product2">Update Quantity</button>
    </form>

    <h1><?php echo $product3->getName(); ?></h1>
    <p>Description: <?php echo $product3->getDescription(); ?></p>
    <p>Price: $<?php echo $product3->getPrice(); ?></p>
    <p>Quantity: <?php echo $product3->getQuantity(); ?></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_quantity_product3">Enter New Quantity for Product 3:</label>
        <input type="number" id="new_quantity_product3" name="new_quantity" min="0" value="0">
        <button type="submit" name="update_product3">Update Quantity</button>
    </form> -->
    </div>
    </div>
</body>
</html>
