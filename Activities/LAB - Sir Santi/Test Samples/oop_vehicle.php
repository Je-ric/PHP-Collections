<?php
// Parent class
class Vehicle {

    public $brand;
    protected $speed;
    private $engineNumber;

    // Constructor runs automatically when the object is created
    public function __construct($brandValue, $engineValue) {
        $this->brand = $brandValue;
        $this->engineNumber = $engineValue;
        $this->speed = 0;

        echo "Vehicle constructor called.<br>";
    }

    public function start() {
        echo "Vehicle started.<br>";
    }

    // Destructor runs automatically at the end of the script or when object is destroyed
    public function __destruct() {
        echo "Vehicle is being destroyed.<br>";
    }
}

// Child class inherits from Vehicle
class Car extends Vehicle {

    public $color;

    // Child constructor
    public function __construct($brandValue, $engineValue, $colorValue) {
        // Use parent constructor
        parent::__construct($brandValue, $engineValue);

        $this->color = $colorValue;
        echo "Car constructor called.<br>";
    }

    public function showDetails() {
        // public → accessible
        echo "Brand: " . $this->brand . "<br>";

        // protected → accessible inside child
        echo "Speed: " . $this->speed . "<br>";

        // private → NOT accessible here (would cause error)
        // echo $this->engineNumber;

        echo "Color: " . $this->color . "<br>";
    }

    // Example of overriding a parent method
    public function start() {
        echo "Car started with a button press.<br>";
    }
}

// Create an object
$myCar = new Car("Toyota", "ENG-999", "Red");

$myCar->start();        // calls the overridden start() in Car
$myCar->showDetails();  // show info

// When the script ends, destructor will automatically run
?>
