<?php

//for fetching data from the SQL database
class SQLConnect {

    // SQL Database Credentials
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = ""; // usually "budgetting_online"

    //data for database
    private $desc;
    private $date;
    private $amount;
    private $notes;

    //connection esablished:
    private $connection;

    public function __construct($database){
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
        $statement = $this->connection->prepare("INSERT INTO              `running_expenses_2026` (`Description`, `Date`, `Amount`, `Notes`)
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
}

?>