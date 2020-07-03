<?php
require('function.php');
require('auth.php');
$user_id = $_SESSION['user_id'];
$question = getUserQuestion($user_id);
$category = getCategory();
?>

<?php
$sitetitle = 'マイページ';
require('head.php');
require('header.php');
?>

<main>
    <div class="container">
        <h1 class="mypage__title">マイページ</h1>
        <div class="mypage">
            <div class="mypage__questions">
                <h2>質問したリスト</h2>
                <?php foreach($question as $key => $val){ ?>
                    <div class="questionList__list">
                        <p class="questionList__category questionList__category<?php echo $category[$val['category_id'] - 1]['id']; ?>">カテゴリー : <span><?php echo $category[$val['category_id'] - 1]['name']; ?></span></p>
                        <a class="questionList__question" href="questionDetail.php?p_id=<?php echo $val['id']; ?>"><?php echo $val['question']; ?></a>
                        <p class="questionList__condition">
                            <?php if($val['best_flg'] === "0"){ ?>
                            <span class="questionList__ok">回答受付中</span>
                            <?php }else if($val['best_flg'] === "1"){ ?>
                            <span class="questionList__out">回答は締め切りました</span>
                            <?php } ?>
                            <span>更新日 ： <?php echo $val['update_date']; ?></span>
                        </p>
                    </div>
                <?php } ?>
            </div>
            <div class="mypage__sidebar">
                <a href="doQuestion.php" class="mypage__edit">質問をする</a>
                <a href="profEdit.php" class="mypage__edit">登録内容編集</a>
                <a href="passEdit.php" class="mypage__edit">パスワード変更</a>
                <a href="deleteUser.php" class="mypage__edit">退会する</a>
            </div>
        </div>
    </div>
</main>

<?php
require('footer.php');
?>
