<header class="header">
    <div class="container container__header">
        <div class="header__title">
            <a href="index.php" class="header__titleLink">Questions</a>
        </div>
        <div class="header__item">
            <?php if($_SESSION['login_date']){ ?>
                <div class="header__itemList">
                    <a href="mypage.php" class="header__itemLink">マイページ</a>
                </div>
                <div class="header__itemList">
                    <a href="logout.php" onclick="alert('ログアウトしますか')" class="header__itemLink">ログアウト</a>
                </div>
            <?php }else{ ?>
                <div class="header__itemList">
                    <a href="login.php" class="header__itemLink">ログイン</a>
                </div>
                <div class="header__itemList">
                    <a href="signup.php" class="header__itemLink">新規登録</a>
                </div>
            <?php } ?>
        </div>
    </div>
</header>
