<?php 

use PhpParser\Node\Stmt\Echo_;

class Book{
    public $title, $author, $type;

    public function __construct($title, $author,$type){ // natatawag kaagad sa pag instantiate ng class
        $this->setTitle($title);
        $this->author = $author;
        $this->type = $type;
    }
    public function __destruct(){ // end lang natatawag 
        echo "Title: " . $this->title . "<br>";
        echo "Author: " . $this->author . "<br>";
        echo "Type: " . $this->type . "<br><br>";
    }

    public function setTitle($param) { 
        $this->title = $param; 
    }
}

class Manga extends Book {
    const _MESSAGE = "<h1 style='color: red;
                    padding:10px;
                    border: 1px solid red;
                    '>Greeting!</h1><br><br>"; // same with static
    public function links(){
        Echo 'Read the Manga <a href="sample.php">Here</a><br>'; 
    }
}

$manga1 = new Manga("FULL METAL ALCHEMIST", 
                    "HIROMU", 
                    "MANGA");
echo $manga1:: _MESSAGE; 
$manga1->links();

// --------------------------------------
$bk1 = new Book("The Book of Life", 
                "Deborag Harkness", 
                "Novel");
$bk1->setTitle("The Book of Life 2");
$bk1->author = "Deborah Harkness";

// --------------------------------------
$manga = new Manga("Las Ramblas", 
                    "Paciano", 
                    "J-Riz");
$manga->links();

?>