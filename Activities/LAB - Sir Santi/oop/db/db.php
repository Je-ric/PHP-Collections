<?php 

class myDB{
    private $servername="localhost";
    private $username="root";
    private $password="";
    private $db_name="oop";
    public $res;
    private $conn;

    public function __construct(){
        try{
            $this->conn = new mysqli($this->servername, 
                                    $this->username, 
                                    $this->password, 
                                    $this->db_name);
        }catch(exception $e){
            die("Database connection error. <br> " . $e);
        } // finally (depends on the scenario)
    }

    public function __destruct(){
        $this->conn->close();
    }

    public function insert($table, $data){
        try{
            $table_columns = implode(", ", array_keys($data));
            $prep=$types="";
            foreach($data as $key => $value){
                $prep .= "?,"; // make sure theres no additional spaces
                $types .= substr(gettype($value), 0, 1); //get the first letter
            }
            $prep = substr($prep, 0, -1); // remove last comma
            $stmt = $this->conn->prepare("INSERT INTO $table ($table_columns) VALUES ($prep)");
            $stmt->bind_param($types,...array_values($data)); 
            $stmt->execute();

            if($stmt->affected_rows > 0){
                $this->res = "Data inserted successfully!";
            } else {
                $this->res = "No data inserted.";
            }

            $stmt->close();
        }catch(exception $e){
            die("Error ehilr inserting data!. <br>" . $e);
        }
    }

    public function select($table, $row="*", $where=NULL) {
    }
}


?>