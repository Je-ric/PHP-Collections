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
        }
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

    // public function select($table,$row='*',$where=NULL){
    //     try{   
    //         if(!is_null($where)){
    //         $cond=$types="";
    //         foreach($where as $key => $value){
    //             $cond .= $key . " = ? AND ";
    //             $types .= substr(gettype($value),0,1);
    //         }

    //         $cond = substr($cond,0,-4);
    //         $stmt = $this->conn->prepare("SELECT $row FROM $table WHERE $cond");
    //         $stmt->bind_param($types, ...array_values($where));
    //         }else{
    //             $stmt = $this->conn->prepare("SELECT $row FROM $table");
    //             $stmt->execute();
    //             $this->res = $stmt->get_result();
    //         }
    //     }catch(Exception $e){
    //         die("Error requesting data !. <br>".$e);
    //     }
    // }


    public function select($table, $columns = '*', $where = NULL) {
        $query = "SELECT $columns FROM $table";
        if ($where) {
            $query .= " WHERE $where";
        }
        $this->res = $this->conn->query($query);
        if (!$this->res) {
            die("Select Error! <br>".$this->conn->error);
        }
        return $this->res;
    }

    public function update($table, $data, $where = NULL) {
        $prep = '';
        $types = '';
        foreach ($data as $key => $value) {
            $prep .= "$key = ?,";
            $types .= substr(gettype($value), 0, 1);
        }
        // $prep = rtrim($prep, ', '); 
        $prep = substr($prep, 0, -1); 
        $stmt = $this->conn->prepare("UPDATE $table SET $prep WHERE $where");
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->res = "Data updated successfully!";
        } else {
            $this->res = "No data inserted.";
        }
    }


    public function delete($table, $data, $where = NULL) {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE $where");
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $this->res = "Data deleted successfully!";
        } else {
            $this->res = "No data deleted.";
        }
        $stmt->close();
    }

    // public function fetchAssoc() {
    //     if ($this->res) {
    //         return $this->res->fetch_assoc();
    //     } else {
    //         return null;
    //     }
    // }

    //  $full_name = $student['full_name'] ?? '';
    // $email = $student['email'] ?? '';
    // $course_year_section = $student['course_year_section'] ?? '';
    
}


?>