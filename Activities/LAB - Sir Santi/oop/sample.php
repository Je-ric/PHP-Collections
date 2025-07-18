<?php 

class Book1{
    public $title; // properties

    public function setTitle($param) { // method
        $this->title = $param; // set the title property
    }
}

$bk_math = new Book1(); // create instance
$bk_math->setTitle("ALGEBRA"); 

$bk_english = new Book1();
$bk_english->setTitle("ENGLISH ");

echo $bk_math->title; 
echo "<br>";
echo $bk_english->title;

?>