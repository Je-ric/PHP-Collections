class ParentClass {
    public $a = 1;        // inherited & accessible
    protected $b = 2;     // inherited & accessible inside child
    private $c = 3;       // NOT inherited
    public function sayHi(){ }        // inherited
}
class ChildClass extends ParentClass {
    public function test(){
        echo $this->a;    // OK
        echo $this->b;    // OK
        // echo $this->c; // ERROR, not inherited
        $this->sayHi();   // OK
    }
}
