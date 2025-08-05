<?php
// Parent class (base class)
class Vehicle {

    public $brand = "Generic";     // Public property: accessible anywhere
    protected $speed = 0;          // Protected property: accessible in this class AND child classes
    private $engineNumber = "XYZ123"; // Private property: accessible ONLY inside this class

    public function start() {
        echo "Vehicle started.<br>";
        echo "Engine: " . $this->engineNumber . "<br>";
    }
}

// Child class (inherits from Vehicle)
class Car extends Vehicle {

    public $color = "Black";

    // Method in child class
    public function showDetails() {
        // Accessing public property – OK
        echo "Brand: " . $this->brand . "<br>";

        // Accessing protected property – OK (because it's inherited and protected allows access in child)
        echo "Speed: " . $this->speed . "<br>";

        // Accessing private property – ERROR (uncommenting will cause fatal error)
        // echo "Engine #: " . $this->engineNumber . "<br>";
    }
}

// Create an object of the child class
$myCar = new Car();

$myCar->start();           // Inherited method from Vehicle
$myCar->showDetails();     // Child method

?>