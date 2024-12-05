<?php

require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NameID = $_POST['NameID'];
    $FName = $_POST['FName'];  // Changed from $Name to $FName

    $Revenue = $_POST['Revenue'];
    $Expense = $_POST['Expense'];

    $query = "UPDATE Bookkeeping 
          SET FName = :FName, Revenue = :Revenue, Expense = :Expense 
          WHERE NameID = :NameID";

    $statement = $db->prepare($query);

    $statement->bindValue(':FName', $FName);

    $statement->bindValue(':Revenue', $Revenue);
    $statement->bindValue(':Expense', $Expense);
    $statement->bindValue(':NameID', $NameID, PDO::PARAM_INT);

    $statement->execute();

    header("Location: index.php");  
    exit;
}
?>
