<?php

require('function.php');

if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');
    if(empty($err_msg)){
        validEmail($email,'email');
        validPass($pass,'pass');
        validPassMatch($pass,$pass_re,'pass');
        validEmailDup($email,'email');
    }
    if(empty($err_msg)){
        signUp($email,$pass,'email');
    };
}
?>
<?php
$sitetitle = "新規登録";
require('head.php'); ?>
<?php require('header.php'); ?>
<main>
    <div class="container container__form">
        <div class="form">
            <form action="" method="post" style="overflow:hidden;">
                <h1 class="form__title">新規登録</h1>
                <label for="" class="form__label">
                    <input placeholder="メールアドレス" name="email" class="form__input <?php echo (!empty($err_msg['email']))?"form__inputErr":""?>" type="text">
                </label>
                <div class="<?php echo  (!empty($err_msg['email']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                </div>
                <label for="" class="form__label">
                    <input placeholder="パスワード" name="pass" type="password" class="form__input <?php echo (!empty($err_msg['pass']))?"form__inputErr":""?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['pass']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
                </div>
                <label for="" class="form__label">
                    <input placeholder="パスワード再確認" name="pass_re" type="password" class="form__input <?php echo (!empty($err_msg['pass_re']))?"form__inputErr":""?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['pass_re']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?>
                </div>
                <input type="submit" class="form__submit" value="登録">
            </form>
        </div>
    </div>
</main>
<?php require('footer.php'); ?>
