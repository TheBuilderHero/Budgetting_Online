<!DOCTYPE html>
<html>
    <?php include 'nav.php'; ?>
    <link rel="stylesheet" href="budget.css">
<body>

<?php
include 'mySQLConnect.php';
$budgettingOnline = new SQLConnect();

// 1. Determine the Type (Expense vs Gas)
$viewType = $_GET['view_type'] ?? 'expense'; 
$ignored = $budgettingOnline->getIngnoreArray();

// 2. Get the correct table list based on type
if ($viewType === 'gas') {
    $available_tables = array_values(array_diff($budgettingOnline->getMileageTables(), $ignored));
    $defaultTable = 'vehicle_logs';
    $tableLabel = "View Mileage From:";
} else {
    $available_tables = array_values(array_diff($budgettingOnline->getExpenseTables(), $ignored));
    $defaultTable = 'transactions';
    $tableLabel = "View Expenses From:";
}

// 3. Determine specific table selection
$selectedViewTable = $_GET['view_table'] ?? '';
if (!in_array($selectedViewTable, $available_tables)) {
    $selectedViewTable = in_array($defaultTable, $available_tables) ? $defaultTable : ($available_tables[0] ?? null);
}
?>

<div class="table-container">
    <!-- View Type and Table Selector -->
    <form method="GET" action="" id="viewForm" style="margin-bottom: 20px;">
        <div class="view-controls">
            <label for="view_type">Entry Type:</label>
            <select name="view_type" id="view_type" onchange="this.form.submit()">
                <option value="expense" <?= ($viewType === 'expense') ? 'selected' : '' ?>>Expenses</option>
                <option value="gas" <?= ($viewType === 'gas') ? 'selected' : '' ?>>Gas/Mileage</option>
            </select>

            <label for="view_table" style="margin-left:20px;"><?= $tableLabel ?></label>
            <select name="view_table" id="view_table" onchange="this.form.submit()">
                <?php foreach ($available_tables as $tableName): ?>
                    <option value="<?= htmlspecialchars($tableName) ?>" <?= ($tableName === $selectedViewTable) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tableName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <table class="styled-table">
        <thead>
            <tr>
                <?php if ($viewType === 'gas'): ?>
                    <th>Date</th>
                    <th class="numeric">MPG</th>
                    <th class="numeric">Trip Miles</th>
                    <th class="numeric">Total ($)</th>
                    <th class="numeric">Price/Gal</th>
                    <th>Notes</th>
                <?php else: ?>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="numeric">Amount</th>
                    <th>Notes</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($selectedViewTable):
                // Use the correct stream function based on type
                $rows = ($viewType === 'gas') 
                    ? $budgettingOnline->streamMileageRows($selectedViewTable) 
                    : $budgettingOnline->streamExpenseRows($selectedViewTable);

                foreach ($rows as $row): 
            ?>
                <tr>
                    <?php if ($viewType === 'gas'): ?>
                        <td><?= htmlspecialchars($row['Date']) ?></td>
                        <td class="numeric"><?= number_format($row['MPG'], 1) ?></td>
                        <td class="numeric"><?= number_format($row['Trip'], 1) ?></td>
                        <td class="numeric">$<?= number_format($row['Total'], 2) ?></td>
                        <td class="numeric">$<?= number_format($row['PricePerGallon'], 3) ?></td>
                        <td><?= htmlspecialchars($row['Notes']) ?></td>
                    <?php else: ?>
                        <td><?= htmlspecialchars($row['Date']) ?></td>
                        <td><?= htmlspecialchars($row['Description']) ?></td>
                        <td class="numeric">$<?= number_format($row['Amount'], 2) ?></td> 
                        <td><?= htmlspecialchars($row['Notes']) ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No tables or data found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


    <script>
        // Get the button element
        let mybutton = document.getElementById("backToTopBtn");

        // When the user scrolls down, call scrollFunction
        window.onscroll = function() { scrollFunction() };

        function scrollFunction() {
            // Show button after 800px (roughly 20 rows of data)
            if (document.body.scrollTop > 800 || document.documentElement.scrollTop > 800) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top
        function topFunction() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, and Opera
        }
    </script>


    <button onclick="topFunction()" id="backToTopBtn" title="Go to top">
        <svg xmlns="http://www.w3.org" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>


</body>
</html>
