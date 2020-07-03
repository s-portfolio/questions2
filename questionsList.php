<?php
require('function.php');
$c_id = $_GET['c_id'];
$text = "";
if(!empty($_POST['search'])){
    $text = $_POST['search'];
}
$question = getQuestion($c_id,$text);
$category = getCategory();
?>

<?php
require('head.php');
require('header.php');
?>

<main>
    <div class="container container__questionList">
        <?php if(!empty($c_id)){ ?>
        <h1>「<?php echo $category[$c_id - 1]['name']; ?>」カテゴリー一覧</h1>
        <?php }else{ ?>
            <h1>「<?php echo $text ?>」を含む検索結果</h1>
        <?php } ?>
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
</main>

<?php
require('footer.php');
?>
