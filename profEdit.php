<?php
require('function.php');
require('auth.php');
$id = $_SESSION['user_id'];
$formData = getUser($id);
if(!empty($_POST)){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $path = (!empty($_FILES['img']['name']))?uploadImg($_FILES['img'],'img'):$formData['img'];
    editProf($id,$email,$name,$path);
    header("Location:mypage.php");
}
?>

<?php
if(!empty($text)){
    $sitetitle = '「'.$text.'」を含む検索結果';
}else{
    $sitetitle = '「'.$category[$c_id - 1]['name'].'」カテゴリー一覧';
}
require('head.php');
require('header.php');
?>

<main>
    <div class="container container__form">
        <div class="form">
            <form action="" method="post" style="overflow:hidden;" enctype="multipart/form-data">
                <h1 class="form__title">マイページ</h1>
                <label for="" class="form__label">メールアドレス
                    <input name="email" class="form__input <?php echo (!empty($err_msg['email']))?"form__inputErr":""?>" type="text" value="<?php echo $formData['email']; ?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['email']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                </div>
                <label for="" class="form__label">名前
                    <input name="name" type="text" class="form__input <?php echo (!empty($err_msg['name']))?"form__inputErr":""?>" value="<?php echo $formData['name'];?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['name']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?>
                </div>
                トップ画像<br/>※画像をかざすとアップロードできます
                <label for="" class="form__label form__img" id="imgArea">
                    <img src="<?php echo $formData['img']; ?>" alt="" class="prev__img <?php echo (empty($formData['img']))? 'prev__img--before' : 'form__file--after' ; ?>">
                    <input name="img" id="imgFile" type="file" class="form__file <?php echo (!empty($err_msg['img']))?"form__inputErr":""?>">
                </label>
                <div class="<?php echo  (!empty($err_msg['img']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['img'])) echo $err_msg['pass_re']; ?>
                </div>
                <input type="submit" class="form__submit" value="登録">
            </form>
        </div>
    </div>
</main>

<?php
require('footer.php');
?>

