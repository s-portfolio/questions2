<?php
require('function.php');
if(empty($_GET['p_id'])){
    header("Location:index.php");
}
$p_id = $_GET['p_id'];
$question = getQuestionOne($p_id);
$user = getUser($question['user_id']);
$category = getCategory();
$answer = getAnswer($p_id);
$answerUser = getUser($answer[0]['user_id']);
if(!empty($_POST['answer'])){
    $a = $_POST['answer'];
    doAnswer($_SESSION['user_id'],$p_id,$a);
    header("Location:questionDetail.php?p_id=".$p_id);
}
if(!empty($_POST['best'])){
    decisionBestAnswer($_POST['id'],$p_id);
    header("Location:questionDetail.php?p_id=".$p_id);
}
?>
<?php
$sitetitle = '質問詳細';
require('head.php');
require('header.php');
?>

<main>
    <div class="container">
        <div class="questionDetail">
            <div class="questionDetail__user">
                <?php if($user['img']){ ?>
                    <img src="<?php echo $user['img']; ?>" alt="" class="user__img">
                <?php }else{ ?>
                    <div class="user__img"></div>
                <?php } ?>
                <h3><?php echo (!empty($user['name']))? $user['name'].'さん' : '名無しさん'; ?></h3>
            </div>
            <div class="questionDetail__question">
                <p class="questionList__category questionList__category<?php echo $category[$question['category_id'] - 1]['id'];  ?>">カテゴリー : <span><?php echo $category[$question['category_id'] - 1]['name']; ?></span></p>
                <h3 class="questionList__question--detail"><?php echo $question['question']; ?></h3>
                <?php if($_SESSION['user_id'] === $question['user_id']){ ?>
                <div class="questionDetail__option">
                    <a class="questionDetail__edit" href="editQuestion.php?p_id=<?php echo $question['id']; ?>">編集する</a>
                    <a class="questionDetail__delete" href="deleteQuestion.php?p_id=<?php echo $question['id']; ?>" onclick="alert('削除しますか')">削除する</a>
                </div>
                <?php } ?>
                <span class="questionDetail__date">更新日時 : <?php echo $question['update_date']; ?></span>
            </div>
        </div>
        <?php foreach($answer as $key => $val){ ?>
        <div class="questionDetail questionDetail__toAnswer" >
            <div class="questionDetail__triangle <?php if($val['best_flg'] === "1") echo "questionDetail__triangle--red";  ?>"></div>
            <div class="questionDetail__user">
                <?php if(getUser($val['user_id'])['img']){ ?>
                    <img src="<?php echo getUser($val['user_id'])['img']; ?>" alt="" class="user__img">
                <?php }else{ ?>
                    <div class="user__img"></div>
                <?php } ?>
                <h3><?php echo (!empty(getUser($val['user_id'])['name']))? getUser($val['user_id'])['name'].'さん' : '名無しさん'; ?></h3>
            </div>
            <div class="questionDetail__question">
                <h3><?php echo $val['answer']; ?></h3>
                <?php if($question['best_flg'] === "0" && $question['user_id'] === $_SESSION['user_id']){ ?>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?php echo $val['id']; ?>">
                        <input name="best" class="questionDetail__doBestAnswer" type="submit" value="ベストアンサーにする！">
                    </form>
                <?php }else if($question['best_flg'] === "1"){ ?>
                    <?php if($val['best_flg'] === "1"){ ?>
                        <span class="questionDetail__bestAnswer">ベストアンサー</span>
                    <?php } ?>
                <?php } ?>
                <span class="questionDetail__date">回答日時 : <?php echo $val['create_date']; ?></span>
            </div>
        </div>
        <?php } ?>
        <?php if($question['best_flg'] === "0"){ ?>
            <?php if(empty($_SESSION['user_id'])){ ?>
                <div class="questionDetail__noLogin">
                    <p>ログインすると回答することができます</p>
                </div>
            <?php }else if($_SESSION['user_id'] !== $question['user_id']){ ?>
                <div class="questionDetail__answer">
                    <div>
                        <h2 class="questionDetail__answerTitle">回答する！</h2>
                        <form action="" method="post">
                            <textarea class="questionDetail__answerArea" name="answer" id="" cols="30" rows="10"></textarea>
                            <div class="questionDetail__submitArea">
                                <input class="questionDetail__submit" type="submit">
                            </div>
                        </form>
                        </label>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</main>

<?php
require('footer.php');
?>
