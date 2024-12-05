<?php
require('connect.php');

$id = filter_input(INPUT_GET, 'NameID', FILTER_SANITIZE_NUMBER_INT);
$Date = filter_input(INPUT_GET, 'Date', FILTER_SANITIZE_STRING);
$CategoryID = filter_input(INPUT_GET, 'CategoryID', FILTER_SANITIZE_STRING);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($id)) {
    $fname = filter_input(INPUT_POST, 'FName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $revenue = filter_input(INPUT_POST, 'Revenue', FILTER_SANITIZE_NUMBER_INT);
    $expense = filter_input(INPUT_POST, 'Expense', FILTER_SANITIZE_NUMBER_INT);
    $date = filter_input(INPUT_POST, 'Date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'CategoryID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $method = filter_input(INPUT_POST, 'AccountID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $query = "UPDATE bookkeeping SET FName = :FName, Revenue = :Revenue, Expense = :Expense, Date = :Date, CategoryID = :Category, AccountID = :AccountID WHERE NameID = :NameID";
    $statement = $db->prepare($query);

    $statement->bindValue(':NameID', $id, PDO::PARAM_INT);
    $statement->bindValue(':FName', $fname);
    $statement->bindValue(':Revenue', $revenue);
    $statement->bindValue(':Expense', $expense);
    $statement->bindValue(':Category', $category);
    $statement->bindValue(':Date', $date);
    $statement->bindValue(':AccountID', $method);

    $statement->execute();
    header("Location: index.php");
    exit;
}

// Fetch the record for displaying
if (isset($id)) {
    $query = "SELECT * FROM bookkeeping WHERE NameID = :NameID";
    $statement = $db->prepare($query);
    $statement->bindValue(':NameID', $id, PDO::PARAM_INT);
    $statement->execute();
    $bookkeeping = $statement->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Edit Data</title>
</head>
<body>
<div class="container">
    <h2>Edit your data here</h2>
    <form class="row gx-5 align-items-end" action="update.php" method="post">
        <input type="hidden" name="NameID" value="<?= $id ?>">
        
        <!-- Content Item -->
        <div class="row gx-5">
            <label class="form-label text-sm">Content item
                <input class="form-control text-sm" type="text" name="FName" value="<?= $bookkeeping['FName']?>">
            </label>
        </div>
        
        <!-- Revenue -->
        <div class="col-sm-2 mb-1">
            <label class="form-label text-sm">Revenue
                <input class="form-control text-sm" type="number" name="Revenue" value="<?= $bookkeeping['Revenue']?>" step="0.01">
            </label>
        </div>

        <!-- Expense -->
        <div class="col-sm-2 mb-1 pl-1">
            <label class="form-label text-sm">Expense
                <input class="form-control text-sm" type="number" name="Expense" value="<?= $bookkeeping['Expense']?>" step="0.01">
            </label>
        </div>

        <!-- Other fields ... similar format as above -->

        <div class="mt-2">
            <input class="btn btn-primary" type="submit" value="Update">
            <button class="btn btn-secondary" type="button" onclick="location.href='index.php';">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>
