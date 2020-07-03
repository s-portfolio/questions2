<?php
require('function.php');
$sitetitle = "メインページ";
$category = getCategory();
$question = getQuestion();
?>

<?php require('head.php'); ?>
<?php require('header.php'); ?>
<main>
    <div class="container container__main">
        <div class="questions">
            <div class="questions__search">
                <form class="questions__searchB" action="questionsList.php" method="post">
                    <input name="search" type="text" class="questions__searchForm" placeholder="投稿された質問を検索する">
                    <button type="submit"  class="questions__searchGo">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="questions__main">
                <div class="questions__all">
                    <h3 class="questions__heading">投稿された質問</h3>
                    <?php foreach($question as $key => $val){ ?>
                        <div class="questionList__list">
                            <p class="questionList__category questionList__category<?php echo $category[$val['category_id'] - 1]['id']; ?>">カテゴリー : <span><?php echo $category[$val['category_id'] - 1]['name']; ?></span></p>
                            <div>
                                <a class="questionList__question" href="questionDetail.php?p_id=<?php echo $val['id']; ?>"><?php echo $val['question']; ?></a>
                            </div>
                            <p class="questionList__condition">
                                <?php if($val['best_flg'] === "0"){ ?>
                                    <span class="questionList__ok">回答受付中</span>
                                <?php }else if($val['best_flg'] === "1"){ ?>
                                    <span class="questionList__out">回答は締め切りました</span>
                                <?php } ?>
                                <span class="questionList__date">更新日 ： <?php echo $val['update_date']; ?></span>
                            </p>
                        </div>
                    <?php } ?>
                </div>
                <div class="questions__sidebar">
                    <div class="questions__do">
                        <h3 class="questions__heading">質問してみる!</h3>
                        <?php if($_SESSION['login_date']){ ?>
                        <div class="questions__doClick">
                            <a href="doQuestion.php">質問投稿画面へ</a>
                        </div>
                        <?php }else{ ?>
                        <div class="questions__doClick">
                            ログインすると質問することができます
                            <a href="login.php">ログイン</a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="questions__category">
                        <h3 class="questions__heading">カテゴリー一覧</h3>
                        <?php foreach($category as $key =>$val){?>
                            <a class="questions__categoryList" href="questionsList.php?c_id=<?php echo $val['id']; ?>"><?php echo $val['name']; ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require('footer.php'); ?>
</body>
</html>
