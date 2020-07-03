<?php
if(!empty($_SESSION['login_date'])){
    if($_SESSION['login_date'] + $_SESSION['login_limit'] > time()){
        $_SESSION['login_date'] = time();
        if(basename($_SERVER['PHP_SELF'])=== "login.php" || basename($_SERVER['PHP_SELF'])==="signup.php"){
            header("Location:mypage.php");
        }
    }else{
        session_destroy();
        header("Location:login.php");
    }
}else{
    if(basename($_SERVER['PHP_SELF']) !== "login.php" ){
        header("Location:login.php");
    }
}
