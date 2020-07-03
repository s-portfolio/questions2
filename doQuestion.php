<?php
require('function.php');
$category = getCategory();
?>

<?php
$sitetitle = '質問投稿画面';
require('head.php');
require('header.php');
if(!empty($_POST)){
    $category_id = $_POST['category_id'];
    $question = $_POST['question'];
    $user_id = $_SESSION['user_id'];
    validRequired($category_id,'category_id');
    validRequired($question,'question');
    if(empty($err_msg)){
        post($user_id,$category_id,$question,'category_id');
    }
}
?>

<main>
    <div class="container container__form">
        <div class="form">
            <form action="" method="post" style="overflow:hidden;">
                <h1 class="form__title">質問投稿画面</h1>
                <label for="" class="form__label">カテゴリー
                    <select class="form__select" name="category_id" id="">
                        <option value="">カテゴリーを選択してください</option>
                        <?php foreach($category as $key => $val){ ?>
                            <option value="<?php echo $val['id'] ?>"><?php echo $val['name']; ?></option>
                        <?php } ?>
                    </select>
                </label>
                <div class="<?php echo  (!empty($err_msg['category_id']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['category_id'])) echo $err_msg['category_id']; ?>
                </div>
                <label for="" class="form__label">内容
                    <textarea  name="question" id="" cols="30" rows="10" class="form__textarea"></textarea>
                </label>
                <div class="<?php echo  (!empty($err_msg['question']))?"form__err":"form__space";?>">
                    <?php if(!empty($err_msg['question'])) echo $err_msg['question']; ?>
                </div>
                <input type="submit" class="form__submit" value="質問する">
            </form>
        </div>
    </div>
</main>

<?php
require('footer.php');
?>