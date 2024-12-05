<?php

require('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NameID = $_POST['NameID'];
    $Name = $_POST['FName'];
    $CategoryID = $_POST['CategoryID'];
    $DateID = $_POST['DateID'];
    $AccountID = $_POST['AccountID'];
    $Revenue = $_POST['Revenue'];
    $Expense = $_POST['Expense'];

    $query = "INSERT INTO Bookkeeping(FName, CategoryID, DateID, AccountID, Revenue, Expense)
              VALUES(:FName, :CategoryID, :DateID, :AccountID, :Revenue, :Expense)";

    $statement = $db->prepare($query);
    $statement->bindParam(':FName', $Name);
    $statement->bindParam(':CategoryID', $CategoryID);
    $statement->bindParam(':DateID', $DateID);
    $statement->bindParam(':AccountID', $AccountID);
    $statement->bindParam(':Revenue', $Revenue);
    $statement->bindParam(':Expense', $Expense);
    
    $statement->execute();

    header("Location: index.php");  // 重定向回主页
    exit;
}
?>