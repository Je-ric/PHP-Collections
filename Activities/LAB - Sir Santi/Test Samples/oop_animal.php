<?php
class Animal {
    public $name = "Unnamed";
    protected $type = "Unknown";

    public function eat() {
        echo "Animal is eating.<br>";
    }
}

class Dog extends Animal {
    public $breed = "Mixed";

    public function bark() {
        echo "Dog is barking! Woof!<br>";
    }

    // Accessing parent (inherited) members inside child
    public function showInfo() {
        echo "Name: " . $this->name . "<br>";       // public → OK
        echo "Type: " . $this->type . "<br>";       // protected → OK
    }
}

// Object from parent class
$animal = new Animal();
$animal->name = "Generic Animal";
echo $animal->name . "<br>";    // ✔️ Can access public property
$animal->eat();                 // ✔️ Can access public method

// Object from child class
$dog = new Dog();
$dog->name = "Buddy";           // ✔️ inherited public property → can access
$dog->breed = "Labrador";       // ✔️ own public property → can access

// Access using child object
echo $dog->name . "<br>";       // Buddy
echo $dog->breed . "<br>";      // Labrador
$dog->eat();                    // ✔️ inherited method from Animal
$dog->bark();                   // ✔️ Dog's own method

// Access protected property? (from outside) ❌ NOT allowed
// echo $dog->type; → this will cause an error!

// But child can use a method to access it internally:
$dog->showInfo(); // OK — accesses name & type from within the class
?>
