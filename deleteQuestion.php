<?php
require('function.php');
$p_id = $_GET['p_id'];
$user_id = $_SESSION['user_id'];
try{
    $dbh = dbConnect();
    $stmt = $dbh->prepare('UPDATE questions SET delete_flg = 1 WHERE id = :id AND user_id = :u_id');
    $result = $stmt->execute(array(':id' => $p_id,':u_id' => $user_id));
    if($result){
        header("Location:index.php");
    }
}catch(Exception $e){

}
