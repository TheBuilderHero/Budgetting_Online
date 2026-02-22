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
        
        $budgettingOnline = new SQLConnect("budgetting_online");        
        
        /*foreach ($budgettingOnline->streamExpenseRows("running_expenses_2026") as $row){
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }*/
        
        ?>
        <div class="table-container">
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
                    <?php foreach ($budgettingOnline->streamExpenseRows("running_expenses_2026") as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Date']) ?></td>
                        <td><?= htmlspecialchars($row['Description']) ?></td>
                        <td>$<?= number_format($row['Amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['Notes']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>




    </body>
</html>
