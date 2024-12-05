<?php
require('connect.php');


$query2 = "SELECT k.NameID, k.FName, c.Category, d.Date, k.Revenue, k.Expense, d.DateID, k.AccountID
FROM Bookkeeping k
JOIN Category c ON c.CategoryID = k.CategoryID
JOIN Date d ON d.DateID = k.DateID
JOIN account a on a.AccountID = k.AccountID
ORDER BY d.date ASC, k.Expense DESC
LIMIT 20;";
$statement2 = $db->prepare($query2);
$statement2->execute();

$results2 = $statement2->fetchAll(PDO::FETCH_ASSOC);


$query3 = "SELECT SUM(k.Expense) AS 'Monthly Total Amount', 
d.Date,d.DateID
FROM Bookkeeping k
JOIN Date d ON d.DateID = k.DateID
GROUP BY d.Date;";
$statement3 = $db->prepare($query3);
$statement3->execute();

$results3 = $statement3->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Expense Report</title>
    <script src="scripts.js"></script>
    
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
<!-- The section of Inserting a new book keeping data -->
    <div class="container ">
        <h1>INSERT YOUR DATA</h1>
        <form class="row gx-5 align-items-end" action="insert.php" method="post">

            <div class="row gx-5">
                <label class="form-label text-sm">Content item
                <input class="form-control text-sm" type="text" name="FName"></label><br>
            </div>

            <div class="col-sm-2 mb-1 ">
                <label class="form-label text-sm">Revenue
                <input class="form-control text-sm" type="number" name="Revenue" step="0.01"></label><br>
            </div>

            <div class="col-sm-2 mb-1 pl-1">
                <label class="form-label text-sm">Expense
                <input class="form-control text-sm" type="number" name="Expense" step="0.01"></label>
            </div>
    
            <div class="col-sm-2 mb-1 pl-1">
                <label class="form-label text-sm">Category
                <select class="form-select form-select-md" name="CategoryID" id="CategoryID">
                    <option value="RENT">Rent</option>
                    <option value="ED">Education</option>
                    <option value="ENT">Entertainment</option>
                    <option value="GAS">Gas</option>
                    <option value="GH">Grocery & Health</option>
                    <option value="INS">Insurance</option>
                    <option value="IP">Internet & Phone</option>
                    <option value="PK">Parking</option>
                    <option value="RST">Restaurant</option>
                    <option value="SP">Shopping</option>
                    <option value="TRS">Transport</option>
                    <option value="TVL">Traveling</option>
                    <option value="UT">Utilities</option>
                    <option value="OT">Others</option>
                </select>
            </div>

            <div class="col-sm-2 mb-1 pl-1">
                <label class="form-label text-sm">Date
                <select class="form-select form-select-md" name="DateID" id="DateID">
                    <option value="202311">2023-11</option>
                    <option value="202310">2023-10</option>
                    <option value="202309">2023-09</option>
                    <option value="202308">2023-08</option>
                    <option value="202307">2023-07</option>
                    <option value="createNew">Create New</option>
                </select>
            </div>
    
            <div class="col-sm-2 mb-1 w-30">
                <label class="form-label text-sm">Method
                <select class="form-select form-select-md" name="AccountID" id="AccountID">
                    <option value="JDS">Jack Scotia Debit</option>
                    <option value="JCS">Jack Scotia Credit</option>
                    <option value="JCIBC">Jack CIBC Credit</option>
                    <option value="CDS">Coco Scotia Debit</option>
                    <option value="COSTCOGIFT">Costco Gift</option>
                    <option value="createNew">Create New</option>
                </select>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input class="btn btn-primary me-md-2" type="submit" value="Add Entry">
            </div>
        </form>
    </div>

<!--The section of each data entry -->
<div class="container">
    <div class="d-flex align-items-center pl-5">
        <h1 style="margin-right: 40px;">Data Entry</h1>
        <button class="btn btn-outline-info px-2 btn-sm" type="button" data-toggle="collapse" data-target="#collapsible-content">Expand/Collapse</button>   
    </div>

    <div id="collapsible-content" class="collapse">
    <div class="container mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="col-3">Name</th>
                    <th scope="col" class="col-3">Category</th>
                    <th scope="col" class="col-3">Date</th>
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
                            <td scope="row">$<?= htmlspecialchars($row2['Revenue']) ?></td>
                            <td scope="row">$<?= htmlspecialchars($row2['Expense']) ?></td>
                            <td scope="row">
                                <div>
                                <a href="edit.php?NameID=<?= $row2['NameID']?>&Date=<?= $row2['Date']?>&CategoryID=<?=$row2['Category']?>&AccountID=<?=$row2['AccountID']?>">Edit</a>
                                    <a href="delete.php?NameID=<?= $row2['NameID'] ?>" onclick="return confirmDelete(<?= $row2['NameID'] ?>)">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
        </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<!--The section of Total Cost of each Month -->
<div class="container mt-3">
        <h1>Monthly Expense Report</h1>
        <table class="table table-primary table-striped">
            <thead>
                <tr class="table-primary table-striped">
                    <th scope="col" class="col-4">Date</th>
                    <th scope="col" class="col-4">Monthly Cost</th>
                    <th scope="col" class="col-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results3 as $row3): ?>
                    <tr class="table-primary">
                        <td scope="row"><?= htmlspecialchars($row3['Date']) ?></td>
                        <td scope="row">$<?= htmlspecialchars($row3['Monthly Total Amount']) ?></td>
                        <td scope="row">
                            <div>
                            <a href="details.php?DateID=<?= $row3['DateID'] ?>"> Show details </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
