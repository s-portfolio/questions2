<?php
require('function.php');
require('auth.php');
$user_id = $_SESSION['user_id'];

if(!empty($_POST['delete'])){
    deleteUser($user_id);
}
?>

<?php
$sitetitle = '退会ページ';
require('head.php');
require('header.php');
?>

<main>
    <div class="container container__form">
        <div class="form">
            <form action="" method="post" style="overflow:hidden;">
                <h1 class="form__title">本当に退会しますか</h1>
                <input name="delete" class="form__deleteUser" type="submit" value="退会する">
            </form>
        </div>
    </div>
</main>


<?php
require('footer.php');
?>
