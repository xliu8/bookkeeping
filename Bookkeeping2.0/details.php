<?php
require('connect.php');

$DateID = $_GET['DateID'];
$query1 = "WITH MonthlyTotals AS (
    SELECT 
        d.DateID,
        SUM(k.Expense) AS MonthlyTotalAmount
    FROM Bookkeeping k
    JOIN Date d ON d.DateID = k.DateID
    GROUP BY d.DateID
)
SELECT 
    c.Category,
    d.Date,
    SUM(k.Expense) AS TotalAmount,
    ROUND((SUM(k.Expense) * 1.0 / mt.MonthlyTotalAmount * 100), 2) AS RatioPercentage
FROM Bookkeeping k
JOIN Category c ON c.CategoryID = k.CategoryID
JOIN Date d ON d.DateID = k.DateID
JOIN MonthlyTotals mt ON mt.DateID = k.DateID
WHERE d.DateID = $DateID
GROUP BY c.Category, d.Date, mt.MonthlyTotalAmount
ORDER BY RatioPercentage DESC";
$statement1 = $db->prepare($query1);
$statement1->execute();

$results1 = $statement1->fetchAll(PDO::FETCH_ASSOC);

$query2 = "SELECT k.NameID, k.FName, c.Category, d.Date, k.Revenue, k.Expense, d.DateID, a.Account
FROM Bookkeeping k
JOIN Category c ON c.CategoryID = k.CategoryID
JOIN Date d ON d.DateID = k.DateID
JOIN account a on a.AccountID = k.AccountID
WHERE d.DateID = $DateID
ORDER BY d.date ASC, k.Expense DESC
LIMIT 50;";
$statement2 = $db->prepare($query2);
$statement2->execute();

$results2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    
<!--The section of Total Cost of Category for each Month -->
    <div class="container mt-3">
        <h1 class="mb-3">Category Expense Report</h1>
        <div>
            <a href="index.php">Back to Home</a>

        </div>
        <table class="table table-secondary table-striped">
            <thead>
                <tr class="table-secondary">
                    
                    <th scope="col" class="col-4">Category</th>
                    <th scope="col" class="col-4">Total Amount</th>
                    <th scope="col" class="col-2">Ratio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results1 as $row1): ?>
                    <tr class="table-secondary">
                        
                        <td scope="row"><?= htmlspecialchars($row1['Category']) ?></td>
                        <td scope="row">$<?= htmlspecialchars($row1['TotalAmount']) ?></td>
                        <td scope="row"><?= htmlspecialchars($row1['RatioPercentage']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!--The section of each data entry -->
<div class="container mt-3">
    <h1>Data Entry</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="col-3">Name</th>
                <th scope="col" class="col-3">Category</th>
                <th scope="col" class="col-3">Date</th>
                <th scope="col" class="col-3">Account</th>
                <th scope="col" class="col-1">Revenue</th>
                <th scope="col" class="col-1">Expense</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($results2 as $row2): ?>
                    <tr data-id="<?= $row2['NameID'] ?>">
                        <td scope="row"><?= htmlspecialchars($row2['FName']) ?></td>
                        <td scope="row"><?= htmlspecialchars($row2['Category']) ?></td>
                        <td scope="row"><?= htmlspecialchars($row2['Date']) ?></td>
                        <td scope="row"><?= htmlspecialchars($row2['Account']) ?></td>
                        <td scope="row">$<?= htmlspecialchars($row2['Revenue']) ?></td>
                        <td scope="row">$<?= htmlspecialchars($row2['Expense']) ?></td>
                        
                        <td scope="row">
                            <div>
                                <a href="edit.php?NameID=<?= $row2['NameID']?>&Date=<?= $row2['Date']?>&CategoryID=<?=$row2['Category']?>&Account=<?=$row2['Account']?>">Edit</a>
                                <a href="delete.php?NameID=<?= $row2['NameID'] ?>" onclick="return confirmDelete(<?= $row2['NameID'] ?>)">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
    </table>
</div>
</body>
</html>