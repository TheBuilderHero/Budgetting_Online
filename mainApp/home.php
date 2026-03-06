<!DOCTYPE html>
<html>
    <?php include 'nav.php'; ?>
    <?php include 'mySQLConnect.php'; 
    $mySQLOnlineDB = new SQLConnect();
    ?>
<head>
    <!--get my style sheet-->
    <link rel="stylesheet" href="sharedstyle.css">

    <script>
        function switchForm() {
            var type = document.getElementById("entry_type").value;
            var tableSelect = document.getElementById("table_select");
            var tableContainer = document.getElementById("tableContainer");

            // Reset dropdown
            tableSelect.innerHTML = '<option value="">-- Select a Table --</option>';

            // Hide everything by default
            tableContainer.style.display = "none";
            document.getElementById("expenseFields").style.display = "none";
            document.getElementById("gasFields").style.display = "none";

            // PHP arrays
            <?php $ignored = $mySQLOnlineDB->getIngnoreArray(); ?>
            var expenseTables = <?php echo json_encode(array_values(array_diff($mySQLOnlineDB->getExpenseTables(), $ignored))); ?>;
            var gasTables     = <?php echo json_encode(array_values(array_diff($mySQLOnlineDB->getMileageTables(), $ignored))); ?>;

            var currentList = [];

            if (type === "expense") {
                currentList = expenseTables;
                document.getElementById("expenseFields").style.display = "block";
                tableContainer.style.display = "block";
            } 
            else if (type === "gas") {
                currentList = gasTables;
                document.getElementById("gasFields").style.display = "block";
                tableContainer.style.display = "block";
            }

            currentList.forEach(function(table) {
                var opt = document.createElement('option');
                opt.value = table;
                opt.textContent = table;
                tableSelect.appendChild(opt);
            });
        }
    </script>

</head>
<body>

<div class="form-card">
    <div class="result-container">
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['entry_type'])) {

            $type  = $_POST['entry_type'];
            $table = htmlspecialchars($_POST['table_name']); //for table selection

            // =========================
            // EXPENSE SUBMISSION
            // =========================
            if ($type === "expense" && isset($_POST['Description'], $_POST['Date'], $_POST['Amount'])) {

                $desc   = htmlspecialchars($_POST['Description']);
                $date   = htmlspecialchars($_POST['Date']);
                $amount = htmlspecialchars($_POST['Amount']);
                $notes  = htmlspecialchars($_POST['ExpenseNotes']);

                echo "<div class='result-header'>Previous Expense:</div>";
                echo "<div class='result-text'><strong>($date):</strong> $desc — $$amount</div>";

                $mySQLOnlineDB->sendNewExpense($table, $desc, $date, $amount, $notes);
            }

            // =========================
            // GAS SUBMISSION
            // =========================
            if ($type === "gas" && isset($_POST['GasDate'], $_POST['Mpg'], $_POST['Trip'], $_POST['TotalAmount'], $_POST['PricePerGallon'])) {

                $date  = htmlspecialchars($_POST['GasDate']);
                $mpg   = htmlspecialchars($_POST['Mpg']);
                $trip  = htmlspecialchars($_POST['Trip']);
                $total = htmlspecialchars($_POST['TotalAmount']);
                $price = htmlspecialchars($_POST['PricePerGallon']);
                $notes = htmlspecialchars($_POST['GasNotes']);

                echo "<div class='result-header'>Previous Gas Fill-Up:</div>";
                echo "<div class='result-text'>
                        <strong>($date)</strong><br>
                        MPG: $mpg<br>
                        Trip: $trip miles<br>
                        PricePerGallon: $$price<br>
                        Total: $$total
                    </div>";

                $mySQLOnlineDB->sendNewGasFillup($table, $date, $mpg, $trip, $total, $price, $notes);
            }
        } else {
            echo "<div class='result-text'>Enter a new expense or gas fill-up below.</div>";
        }
        ?>
    </div>


    <form method="post" action="">

        <!-- Entry Type Selector -->
        <label for="entry_type">Select Entry Type</label>
        <select name="entry_type" id="entry_type" onchange="switchForm()" required>
            <option value="">-- Select --</option>
            <option value="expense">Expense</option>
            <option value="gas">Gas Fill-Up</option>
        </select>

        <br><br>

        <!-- TABLE SELECT (AVAILABLE TO BOTH) -->
        <div id="tableContainer" style="display:none;">
            <label for="table_select">Select a Database Table:</label>
            <select name="table_name" id="table_select" required>
                <option value="">-- Choose Type First --</option>
                <?php
                $ignored = $mySQLOnlineDB->getIngnoreArray();
                
                // Get the array from your new function
                $allTables = $mySQLOnlineDB->getExpenseTables(); 

                // Filter and loop
                foreach ($allTables as $tableName) {
                    if (!in_array($tableName, $ignored)) {
                        echo '<option value="' . htmlspecialchars($tableName) . '">' . htmlspecialchars($tableName) . '</option>';
                    }
                }
                ?>
            </select>
        </div>


        <br><br>

        <!-- ===================== -->
        <!-- EXPENSE FIELDS -->
        <!-- ===================== -->
        <div id="expenseFields" style="display:none;">

            <label>Description</label>
            <input type="text" name="Description" placeholder="Lunch, Rent, etc.">

            <label>Date</label>
            <input type="date" name="Date" value="<?php echo date('Y-m-d'); ?>">

            <label>Amount</label>
            <input type="number" name="Amount" step="0.01" placeholder="0.00">

            <label>Notes</label>
            <input type="text" name="ExpenseNotes" placeholder="Optional notes...">
        </div>

        <!-- ===================== -->
        <!-- GAS FIELDS -->
        <!-- ===================== -->
        <div id="gasFields" style="display:none;">

            <label>Date</label>
            <input type="date" name="GasDate" value="<?php echo date('Y-m-d'); ?>">

            <label>MPG</label>
            <input type="number" name="Mpg" step="0.1">

            <label>Trip Miles</label>
            <input type="number" name="Trip" step="0.1">

            <label>Price per Gallon</label>
            <input type="number" name="PricePerGallon" step="0.001">

            <label>Total Amount</label>
            <input type="number" name="TotalAmount" step="0.01">

            <label>Notes</label>
            <input type="text" name="GasNotes" placeholder="Optional notes...">
        </div>

        <br>
        <button type="submit">Save Entry</button>

    </form>
</div>

</body>
</html>
