<?php
    require('function.php');
    require('auth.php');
if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = $_POST['pass_save'];
    validRequired($email,'email');
    validRequired($pass,'pass');
    if(empty($err_msg)){
        validEmail($email,'email');
        validPass($pass,'pass');
        if(empty($err_msg)){
            login($email,$pass,'email',$pass_save);
        }
    }
}
?>

<?php
$sitetitle = "ログイン";
require('head.php'); ?>
<?php require('header.php'); ?>
<main>
    <div class="container container__form">
        <div class="form">
            <form action="" method="post" style="overflow:hidden;">
                <h1 class="form__title">ログイン</h1>
                <label for="" class="form__label">
                    <input name="email" class="form__input <?php echo (!empty($err_msg['email']))?"form__inputErr":""?>" type="text" placeholder="メールアドレス">
                </label>
                <div class="<?php echo  (!empty($err_msg['email']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                </div>
                <label for="" class="form__label">
                    <input name="pass" type="password" class="form__input <?php echo (!empty($err_msg['pass']))?"form__inputErr":""?>" placeholder="パスワード">
                </label>
                <div class="<?php echo  (!empty($err_msg['pass']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
                </div>
                <input name="pass_save" type="checkbox" class="form__checkbox">ログイン情報を保存する
                <input type="submit" class="form__submit" value="ログイン">
            </form>
        </div>
    </div>
</main>
<?php require('footer.php'); ?>
