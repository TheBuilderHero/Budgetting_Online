<!DOCTYPE html>
<html>
    <?php include 'nav.php'; ?>

    <!-- get budget table style sheet-->
    <link rel="stylesheet" href="budget.css">


    <body>

        <?php
        include 'mySQLConnect.php';

        /*for($i = 10; $i < 30; $i++){
            echo "<br>";
        }*/
        
        $budgettingOnline = new SQLConnect();        
        
        /*foreach ($budgettingOnline->streamExpenseRows("running_expenses_2026") as $row){
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }*/
        
        ?>
        <div class="table-container">
            <?php
            
            $ignored_tables = $budgettingOnline->getIngnoreArray();
            $available_tables = [];

            // 1. Get all valid tables first
            $tableResult = $budgettingOnline->getTables();
            while ($tableRow = mysqli_fetch_array($tableResult)) {
                if (!in_array($tableRow[0], $ignored_tables)) {
                    $available_tables[] = $tableRow[0];
                }
            }

            // 2. Determine which table to show:
            // Priority 1: What the user just selected via GET
            // Priority 2: 'transactions' (if it exists)
            // Priority 3: The first table in the database
            if (isset($_GET['view_table']) && in_array($_GET['view_table'], $available_tables)) {
                $selectedViewTable = $_GET['view_table'];
            } elseif (in_array('transactions', $available_tables)) {
                $selectedViewTable = 'transactions';
            } else {
                $selectedViewTable = $available_tables[0] ?? null; // Fallback to first available or null
            }
            ?>

            <!-- Table Selector Form -->
            <form method="GET" action="" style="margin-bottom: 20px;">
                <label for="view_table">View Expenses From:</label>
                <select name="view_table" id="view_table" onchange="this.form.submit()">
                    <?php if (empty($available_tables)): ?>
                        <option value="">No tables found</option>
                    <?php else: ?>
                        <?php foreach ($available_tables as $tableName): ?>
                            <option value="<?= htmlspecialchars($tableName) ?>" <?= ($tableName === $selectedViewTable) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tableName) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </form>

    <!-- The Data Table -->
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($selectedViewTable):
                        $expenseRows = $budgettingOnline->streamExpenseRows($selectedViewTable);
                        foreach ($expenseRows as $row): 
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Date']) ?></td>
                        <td><?= htmlspecialchars($row['Description']) ?></td>
                        <td>$<?= number_format($row['Amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['Notes']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center;">No data available.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>




    </body>
</html>
