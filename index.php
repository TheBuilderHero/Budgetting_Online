<!DOCTYPE html>
<html>
<head>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Changed to min-height for better mobile scaling */
        margin: 0;
    }

    .form-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        width: 350px; /* Slightly wider for dates */
    }

    /* Target all input types consistently */
    input[type="text"], 
    input[type="date"], 
    input[type="number"] {
        width: 100%;
        padding: 12px;
        margin: 8px 0 16px 0;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-sizing: border-box;
        transition: border-color 0.3s;
        font-family: inherit; /* Ensures consistent font in date picker */
    }

    input:focus {
        border-color: #007bff;
        outline: none;
    }

    label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #555;
    }

    button {
        width: 100%;
        background-color: #007bff;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.1s;
        margin-top: 10px;
    }

    button:hover { background-color: #0056b3; }
    button:active { transform: scale(0.98); }

    .result-container {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .result-header { color: #007bff; font-weight: bold; margin-bottom: 5px; }
    .result-text { font-size: 0.95rem; color: #333; line-height: 1.4; }
</style>
</head>
<body>

<div class="form-card">
    <div class="result-container">
        <?php
		// SQL Database Credentials
		
		$host = "localhost";
		$user = "root";
		$pass = "";
		$db = "budgetting_online";
		
		//connect
		
		$connection = new mysqli($host, $user, $pass, $db);
		
		//Check if connection is sucsessful
		
		if($connection->connect_error){
			die("Connection to database has failed! ->" . $connection->connect_error);
		}
		
        // Only show "Previous Expense" if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Description'], $_POST['Date'], $_POST['Amount'])) {
            $desc   = htmlspecialchars($_POST['Description']);
            $date   = htmlspecialchars($_POST['Date']);
            $amount = htmlspecialchars($_POST['Amount']);
            $notes = htmlspecialchars($_POST['Notes']);
            
            echo "<div class='result-header'>Previous Expense:</div>";
            echo "<div class='result-text'><strong>($date):</strong> $desc — $$amount</div>";
			
			// Send data to datebase:
			$statement = $connection->prepare("INSERT INTO `running_expenses_2026` (`Description`, `Date`, `Amount`, `Notes`)
 VALUES (?,?,?,?)"); 

			//malicious code prevention:
			//This will help to prevent people from dropping data from my tables. SQL Injections.
			$statement->bind_param("ssds", $desc, $date, $amount, $notes);
			
			//Now Verify data sent and saved.
			
			if ($statement->execute()){
				//sucess
				$message = "Expense saved to database!";
			} else {
				//Failure
				$message = "Error saving: " . statement->error;
			}
			
			$statement->close();
			
		} else {
            echo "<div class='result-text'>Enter a new expense below.</div>";
        }
        ?>
    </div>

    <form method="post" action="">
        <label for="Description">Description</label>
        <input type="text" name="Description" id="Description" placeholder="Lunch, Rent, etc." required>
        
        <label for="Date">Date</label>
        <input type="date" name="Date" id="Date" value="<?php echo date('Y-m-d'); ?>" required>
        
        <label for="Amount">Amount</label>
        <!-- Use type="number" for better mobile keyboard and validation -->
        <input type="number" name="Amount" id="Amount" step="0.01" placeholder="0.00" required>
        
        <label for="Notes">Notes</label>
        <input type="text" name="Notes" id="Notes" placeholder="Optional notes...">
        
        <button type="submit">Save Expense</button>
    </form>
</div>

</body>
</html>
