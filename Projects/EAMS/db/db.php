<?php
class myDB {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";   
    private $dbname = "eams";
    public $res;
    public $conn;   
    public $srs = [];

    public function __construct() {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        } catch (Exception $e) {
            die("Database Connection Error!<br>" . $e);
        }
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function insert($table, $data) {
        try {
        $table_columns = implode(',', array_keys($data));
        $prep = $types = "";
        foreach ($data as $key => $value) {
            $prep .= '?,';
            $types .= (gettype($value) === 'integer') ? 'i' : 's';
        }
        $prep = rtrim($prep, ',');
        
        $stmt = $this->conn->prepare("INSERT INTO $table ($table_columns) VALUES ($prep)");
        if (!$stmt) {
            error_log("Insert Prepare Failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected > 0; 
        } catch(Exception $e) {
            error_log("Insert Exception: " . $e->getMessage());
            return false;
        }
    }

    public function select($table) {
        $result = $this->conn->query("SELECT * FROM $table");
        $this->srs = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->srs[] = $row;
            }
        }
        return $result;
    }

    public function delete($table, $condition, $params = []) {
        try {
            $sql = "DELETE FROM $table WHERE $condition";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $this->conn->error);
            }

            if (!empty($params)) {
                $types = "";
                foreach ($params as $p) {
                    $types .= (gettype($p) === 'integer') ? 'i' : 's';
                }
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
                echo "Deletion Error: " . $e->getMessage();
                return false;
            }
    }

    public function update($table, $data, $where) {
        try {
            $set = [];
            $types = "";
            $params = [];

            foreach ($data as $key => $value) {
                $set[] = "$key = ?";
                $types .= (gettype($value) === 'integer') ? 'i' : 's';
                $params[] = $value;
            }
            $set_str = implode(', ', $set);

            $cond = [];
            foreach ($where as $key => $value) {
                if (is_null($value)) {
                    $cond[] = "$key IS NULL";
                } else {
                    $cond[] = "$key = ?";
                    $types .= (gettype($value) === 'integer') ? 'i' : 's';
                    $params[] = $value;
                }
            }
            $cond_str = implode(' AND ', $cond);

            $sql = "UPDATE $table SET $set_str WHERE $cond_str";
            $stmt = $this->conn->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $ok = $stmt->execute();
            $stmt->close();

            return $ok; 
        } catch (Exception $e) {
            error_log("Update Error: " . $e->getMessage());
            return false;
        }
    }
}  
?>
