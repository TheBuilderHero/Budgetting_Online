<?php

//for fetching data from the SQL database
class SQLConnect {

/*

CREATE TABLE transactions (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Description VARCHAR(255) NOT NULL,
    Date DATE NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL,
    Notes TEXT
);

CREATE TABLE contacts (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(150),
    Phone VARCHAR(20)
);

 */

    // SQL Database Credentials
    private $host = "mysql";
    private $user = "user";
    private $pass = "password"; 
    private $db = ""; // usually "budgetting_online"

    //data for database
    private $desc;
    private $date;
    private $amount;
    private $notes;

    //connection esablished:
    private $connection;

    public function __construct($database = "budgetonline"){
        $this->db = $database;		
        
        //connect
		
		$this->connection = new mysqli($this->host, $this->user, $this->pass, $this->db);

        //Check if connection is sucsessful
		
		if($this->connection->connect_error){
			die("Connection to database has failed! ->" . $this->connection->connect_error);
		}

    }

    public function getPreviousExpense(){

    }

    //a table generator for outputting all the rows in a table:
    public function streamExpenseRows($table){
        $statement = $this->connection->prepare("SELECT * FROM `$table`");

        if (!$statement) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        $statement->execute();
        $result = $statement->get_result(); // This gets the result object

        // This loop runs as long as there is another row to fetch
        while ($tableRow = $result->fetch_assoc()) {
            yield $tableRow;
        }
    }

    public function sendNewExpense($desc, $date, $amount, $notes){
        $this->desc = $desc;
        $this->date = $date;
        $this->amount = $amount;
        $this->notes = $notes;

        // Send data to datebase:
        $statement = $this->connection->prepare("INSERT INTO `transactions` (`Description`, `Date`, `Amount`, `Notes`)
            VALUES (?,?,?,?)"); 

        //malicious code prevention:
        //This will help to prevent people from dropping data from my tables. SQL Injections.
        $statement->bind_param("ssds", $this->desc, $this->date, $this->amount, $this->notes);
        
        //Now Verify data sent and saved.
        
        if ($statement->execute()){
            //sucess
            $message = "Expense saved to database!";
        } else {
            //Failure
            $message = "Error saving: " . statement->error;
        }
        
        $statement->close();
    }

    public function insertContact($name, $email, $phone){
        $sql = "INSERT INTO contacts (Name, Email, Phone) VALUES (?,?,?)";

        $statement = $this->connection->prepare($sql);

        $statement->bind_param("sss", $name, $email, $phone);

        return $statement->execute();
    }

    public function verifyLogin($username, $password) {
        $sql = "SELECT ID, Username, Passhash FROM users WHERE Username = ?";
        
        if ($stmt = $this->connection->prepare($sql)) {
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

}

?>