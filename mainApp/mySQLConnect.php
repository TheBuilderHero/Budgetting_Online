<?php

//for fetching data from the SQL database
class SQLConnect {


    // SQL Database Credentials
    private $host = "other_db";
    private $user = "user";
    private $pass = "password"; 
    private $expenses_db = "expenses_db";
    private $mileage_db = "mileage_db";
    private $other_db = "other_db";


    //data for database
    private $desc;
    private $date;
    private $amount;
    private $notes;

    //connection esablished:
    private $connection_expenses;
    private $connection_mileage;
    private $connection_other;

    public function getIngnoreArray(){
        return ['contacts', 'users'];
    }

    public function __construct(){
        
        //connect expenses
		
		$this->connection_expenses = new mysqli($this->host, $this->user, $this->pass, $this->expenses_db);

        //Check if connection is sucsessful
		
		if($this->connection_expenses->connect_error){
			die("Connection to expenses database has failed! ->" . $this->connection_expenses->connect_error);
		}
        
        //connect mileage
		
		$this->connection_mileage = new mysqli($this->host, $this->user, $this->pass, $this->mileage_db);

        //Check if connection is sucsessful
		
		if($this->connection_mileage->connect_error){
			die("Connection to expenses database has failed! ->" . $this->connection_mileage->connect_error);
		}
        
        //connect Other
		
		$this->connection_other = new mysqli($this->host, $this->user, $this->pass, $this->other_db);

        //Check if connection is sucsessful
		
		if($this->connection_other->connect_error){
			die("Connection to expenses database has failed! ->" . $this->connection_other->connect_error);
		}

    }

    public function getPreviousExpense(){

    }

    //a table generator for outputting all the rows in a table:
    public function streamExpenseRows($table){
        // 1. Security Check
        if (!in_array($table, $this->getExpenseTables())) { 
            throw new Exception("Invalid table name: " . htmlspecialchars($table)); 
        }

        // 2. Query with Sorting
        $result = $this->connection_expenses->query("SELECT * FROM `$table` ORDER BY Date DESC");
        if (!$result) {
            throw new Exception("Query failed: " . $this->connection_expenses->error);
        }
        while ($row = $result->fetch_assoc()) {
            yield $row;
        }
    }

    public function streamMileageRows($table) {
        // Whitelist check (Security) 
        if (!in_array($table, $this->getMileageTables())) { 
            throw new Exception("Invalid table name: " . htmlspecialchars($table)); 
        }

        $result = $this->connection_mileage->query("SELECT * FROM `$table` ORDER BY Date DESC");
        if (!$result) {
            throw new Exception("Query failed: " . $this->connection_mileage->error);
        }
        while ($row = $result->fetch_assoc()) {
            yield $row;
        }
    }


    public function sendNewExpense($table, $desc, $date, $amount, $notes) {
        // Table Verification (The Whitelist)
        $allowed_tables = $this->getExpenseTables(); // Uses your new array-returning function

        if (!in_array($table, $allowed_tables)) {
            throw new Exception("Security Alert: Invalid table name provided.");
        }

        // Prepare the Statement (Table name is now safe to use in backticks)
        $statement = $this->connection_expenses->prepare(
            "INSERT INTO `$table` (`Description`, `Date`, `Amount`, `Notes`) VALUES (?, ?, ?, ?)"
        );

        if (!$statement) {
            throw new Exception("Prepare failed: " . $this->connection_expenses->error);
        }

        // Bind Parameters (Malicious code prevention)
        // s = string, d = double (for Amount), s = string, s = string
        $statement->bind_param("ssds", $desc, $date, $amount, $notes);

        // Execute and Verify
        if ($statement->execute()) {
            $success = true;
        } else {
            // Log the error for debugging, but don't show raw SQL errors to users
            error_log("Error saving to $table: " . $statement->error);
            $success = false;
        }

        $statement->close();
        return $success;
    }


    public function insertContact($name, $email, $phone){
        $sql = "INSERT INTO contacts (Name, Email, Phone) VALUES (?,?,?)";

        $statement = $this->connection_other->prepare($sql);

        $statement->bind_param("sss", $name, $email, $phone);

        return $statement->execute();
    }

    public function verifyLogin($username, $password) {
        $sql = "SELECT ID, Username, Passhash FROM users WHERE Username = ?";
        
        if ($stmt = $this->connection_other->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $hashed_password);
                $stmt->fetch();
                
                if (password_verify($password, $hashed_password)) {
                    return [
                        'id' => $id,
                        'username' => $db_username
                    ];
                }
            }
            $stmt->close();
        }
        return false; // Login failed
    }

    public function getExpenseTables(){
        $sql = "SHOW TABLES";

        //Query to get table names
        $result = mysqli_query($this->connection_expenses, $sql);

        $tables = [];
        if ($result) {
            // Fetch each row and grab the first element (the table name)
            while ($row = mysqli_fetch_array($result)) {
                $tables[] = $row[0];
            }
        }
        
        return $tables; // Now returns a clean array: ['table1', 'table2', ...]
    }

    public function getMileageTables(){
        $sql = "SHOW TABLES";

        //Query to get table names
        $result = mysqli_query($this->connection_mileage, $sql);

        $tables = [];
        if ($result) {
            // Fetch each row and grab the first element (the table name)
            while ($row = mysqli_fetch_array($result)) {
                $tables[] = $row[0];
            }
        }
        
        return $tables; // Now returns a clean array: ['table1', 'table2', ...]

    }

    public function sendNewGasFillup($table, $date, $mpg, $trip, $total, $price, $notes){
        // 1. Fetch tables into a proper array for validation
        $allowed_tables = $this->getMileageTables();

        // 2. Strict validation
        if (!in_array($table, $allowed_tables)) {
            throw new Exception("Invalid table name.");
        }
        $sql = "INSERT INTO `$table` (Date, Mpg, Trip, Total, PricePerGallon, Notes) VALUES (?,?,?,?,?,?)";

        $statement = $this->connection_mileage->prepare($sql);

        $statement->bind_param("sdddds", $date, $mpg, $trip, $total, $price, $notes);

        return $statement->execute();
    }

}

?>