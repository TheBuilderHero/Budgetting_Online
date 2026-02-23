<!DOCTYPE html>
<html>
    <?php include 'nav.php'; ?>
<head>
    <!--get my style sheet-->
    <link rel="stylesheet" href="sharedstyle.css">
</head>
<body>

<div class="form-card">
    <div class="result-container">
        <?php include 'mySQLConnect.php'; ?>
        <?php
        //create connection to database:

        $mySQLOnlineDB = new SQLConnect("budgetting_online");


        // Only show "Previous Expense" if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Description'], $_POST['Date'], $_POST['Amount'])) {
            $desc   = htmlspecialchars($_POST['Description']);
            $date   = htmlspecialchars($_POST['Date']);
            $amount = htmlspecialchars($_POST['Amount']);
            $notes = htmlspecialchars($_POST['Notes']);
            
            echo "<div class='result-header'>Previous Expense:</div>";
            echo "<div class='result-text'><strong>($date):</strong> $desc — $$amount</div>";
			
			//send data to database
            $mySQLOnlineDB->sendNewExpense($desc,$date, $amount, $notes);
			
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
