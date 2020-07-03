<?php
require('function.php');
require('auth.php');
if(!empty($_POST)){
    $pass = $_POST['pass'];
    $new_pass = $_POST['new_pass'];
    $new_pass_re = $_POST['new_pass_re'];
    $id = $_SESSION['user_id'];
    validRequired($pass,'pass');
    validRequired($new_pass,'new_pass');
    validRequired($new_pass_re,'new_pass_re');
    validCurrentPass($pass,$id,'pass');
    if(empty($err_msg)){
        validPassMatch($new_pass,$new_pass_re,'new_pass');
        if(empty($err_msg)){
            passChange($new_pass,$id,'pass');
            header("Location:mypage.php");
        }
    }
}
?>

<?php
$sitetitle = 'パスワード変更画面';
require('head.php');
require('header.php');
?>

<main>
    <div class="container container__form">
        <div class="form">
            <form action="" method="post" style="overflow:hidden;" enctype="multipart/form-data">
                <h1 class="form__title">パスワード変更</h1>
                <label for="" class="form__label">現在のパスワード
                    <input name="pass" type="password" class="form__input <?php echo (!empty($err_msg['pass']))?"form__inputErr":""?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['pass']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
                </div>
                <label for="" class="form__label">新しいパスワード
                    <input name="new_pass" type="password" class="form__input <?php echo (!empty($err_msg['new_pass']))?"form__inputErr":""?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['new_pass']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['new_pass'])) echo $err_msg['new_pass']; ?>
                </div>
                <label for="" class="form__label">新しいパスワード再確認
                    <input name="new_pass_re" type="password" class="form__input <?php echo (!empty($err_msg['new_pass_re']))?"form__inputErr":""?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['new_pass_re']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['new_pass_re'])) echo $err_msg['new_pass_re']; ?>
                </div>
                <input type="submit" class="form__submit" value="変更する">
            </form>
        </div>
    </div>
</main>

<?php
require('footer.php');
?>
