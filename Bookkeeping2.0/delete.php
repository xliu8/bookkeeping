<?php

require('connect.php'); // 引入你的数据库连接文件

if (isset($_GET['NameID'])) {
    $nameId = $_GET['NameID'];

    // 删除语句
    $query = "DELETE FROM bookkeeping WHERE NameID = :nameId";
    
    $statement = $db->prepare($query);
    $statement->bindParam(':nameId', $nameId);
    $statement->execute();

    header("Location: index.php"); // 返回到主页或任何你想去的页面
    exit;
} else {
    echo "NameID not provided!";
}

?>
