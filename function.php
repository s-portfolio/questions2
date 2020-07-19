<?php
//セッション
session_save_path("/var/tmp/");
session_start();
ini_set('session.gc_maxlifetime',60*60*24*30);
ini_set('session.cookie_lifetime',60*60*24*30);
session_regenerate_id();
//定義
define('MSG01','入力必須です');
define('MSG02','メールアドレスが正しくありません');
define('MSG03','6文字以上で入力してください');
define('MSG04','100文字以下で入力してください');
define('MSG05','英小文字・英大文字・数字の３種類を入れてください');
define('MSG06','パスワードとパスワード再確認が合致しません');
define('ERR01','エラーが発生しました');

//変数
$err_msg = array();

function validRequired($str,$key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}

function validEmail($email,$key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$email)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}

function validEmailDup($email,$key){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(array(':email' => $email));
        $result = 0;
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            $err_msg[$key] = ERR01;
        }
    }catch(Exception $e){
        $err_msg[$key] = ERR01;
    }
}

function validPassMatch($pass1,$pass2,$key){
    if($pass1 !== $pass2){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}

function validPass($pass,$key){
    global $err_msg;
    if(!mb_strlen($pass) >= 6){
        $err_msg[$key] = MSG03;
    }else if(!mb_strlen($pass) > 100){
        $err_msg[$key] = MSG04;
    }else if(!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{6,100}+\z/',$pass)){
        $err_msg[$key] = MSG05;
    }
}

function validCurrentPass($pass,$id,$key){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('SELECT password FROM users WHERE id = :id');
        $stmt->execute(array(':id' => $id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($pass,array_shift($result))){

        }
    }catch(Exception $e){
        $err_msg[$key] = ERR01;
    }
}

function passChange($pass,$id,$key){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('UPDATE users SET password = :password WHERE id = :id');
        $result = $stmt->execute(array(':password' => password_hash($pass,PASSWORD_DEFAULT),':id' => $id));
        if($result){

        }
    }catch(Exception $e){
        $err_msg[$key] = ERR01;
    }
}

function getUser($id){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt= $dbh->prepare('SELECT email,`name`,img FROM users WHERE id = :id');
        $stmt->execute(array(':id' => $id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(Exception $e){

    }
}

function dbConnect(){
    $dsn = 'mysql:host=us-cdbr-east-02.cleardb.com;dbname=heroku_da9bd3d4ad26a71;charset=utf8';
    $user = 'b06bdab8931359';
    $password = '9ba80aad';
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true   );
    $dbh = new PDO($dsn,$user,$password,$options);
    return $dbh;
}

function signUp($email,$pass,$key){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('INSERT INTO users(email,password,created_at) VALUES(:email,:password,:created_at)');
        $result = 0;
        $result = $stmt->execute(array( ':email' => $email,':password' => password_hash($pass,PASSWORD_DEFAULT),':created_at'=>date('Y-m-d H:i:s')));
        if($result){
            $limit = 60 * 60;
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $limit;
            $_SESSION['user_id'] = $dbh->lastInsertId();
            header("Location:mypage.php");
        }else{
            $err_msg[$key] = ERR01;
        }
    }catch(Exception $e){
        $err_msg[$key] = ERR01;
    }
}

function login($email,$pass,$key,$save){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('SELECT password,id FROM users WHERE email = :email AND delete_flg = 0');
        $stmt->execute(array(':email' => $email));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($pass,array_shift($result))){
            $limit = 60 * 60;
            if($save){
                $limit = $limit * 24 * 30;
            }
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $limit;
            $_SESSION['user_id'] = $result['id'];
            header("Location:mypage.php");
        }
    }catch(Exception $e){
        $err_msg[$key] = ERR01;
    }
}

function deleteUser($user_id){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('UPDATE users SET delete_flg = 1 WHERE id = :u_id');
        $result = $stmt->execute(array(':u_id'=>$user_id));
        if($result){
            $stmt = $dbh->prepare('UPDATE questions SET delete_flg = 1 WHERE user_id = :u_id');
            $result = $stmt->execute(array(':u_id'=>$user_id));
            if($result){
                $stmt = $dbh->prepare('UPDATE answers SET delete_flg = 1 WHERE user_id = :u_id');
                $result = $stmt->execute(array(':u_id'=>$user_id));
                if($result){
                    session_destroy();
                    header("Location:login.php");
                }
            }
        }
    }catch(Exception $e){

    }
}

function getCategory(){
    $dbh = dbConnect();
    $stmt = $dbh->prepare('SELECT id,name FROM category');
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function post($u_id,$category_id,$question,$key){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('INSERT INTO questions(user_id,category_id,question,create_date) VALUES (:u_id,:c_id,:question,:c_date)');
        $result = $stmt->execute(array(':u_id'=>$u_id,':c_id'=>$category_id,':question'=>$question,':c_date'=>date('Y-m-d H:i:s')));
        if($result){
            header("Location:mypage.php");
        }
    }catch(Exception $e){

    }
}

function getUserQuestion($user_id){
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('SELECT * FROM questions WHERE user_id = :u_id AND delete_flg = 0 ORDER BY create_date DESC');
        $stmt->execute(array('u_id'=>$user_id));
        $result = $stmt->fetchAll();
        return $result;
    }catch(Exception $e){

    }
}

function getQuestionOne($p_id){
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('SELECT * FROM questions WHERE id = :p_id AND delete_flg = 0');
        $stmt->execute(array(':p_id'=>$p_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }catch(Exception $e){

    }
}

function getQuestion($category_id = 0,$text = ""){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM questions WHERE delete_flg = 0';
        if(!empty($category_id)){
            $sql .=' AND category_id = :c_id';
        }
        if(!empty($text)){
            $text = htmlspecialchars($text,ENT_QUOTES);
            $sql .= ' AND question LIKE "%';
            $sql .= $text;
            $sql .='%"';
        }

        $sql .= ' ORDER BY create_date desc';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':c_id' => $category_id));
        $result = $stmt->fetchAll();
        return $result;

    }catch(Exception $e){

    }
}

function getAnswer($p_id){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('SELECT * FROM answers WHERE question_id = :q_id ORDER BY best_flg desc,updated_at desc');
        $result = $stmt->execute(array(':q_id' => $p_id));
        if($result){
            return $stmt->fetchAll();
        }else{
            return 0;
        }
    }catch(Exception $e){

    }
}

function doAnswer($user_id,$p_id,$answer){
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('INSERT INTO answers(user_id,question_id,answer,best_flg,create_date) VALUES (:u_id,:q_id,:answer,:best_flg,:c_date)');
        $result = $stmt->execute(array(':u_id'=>$user_id,':q_id'=>$p_id,':answer'=>$answer,':best_flg'=>0,':c_date' => date("Y:m:d H:i:s")));
        if($result){

        }
    }catch(Exception $e){

    }
}

function update($p_id,$category_id,$question,$key){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('UPDATE questions SET category_id = :c_id,question = :question WHERE id = :p_id');
        $stmt->execute(array(':c_id'=>$category_id,':question'=>$question,':p_id'=>$p_id));
    }catch(Exception $e){
        $err_msg[$key] = ERR01;
    }
}

function uploadImg($file, $key){
    if (isset($file['error']) && is_int($file['error'])) {
        try {
            switch ($file['error']) {
                case 0:
                    break;
                case 1:
                    throw new RuntimeException('ファイルが選択されていません');
                case 2:
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                case 3:
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default:
                    throw new RuntimeException('その他のエラーが発生しました');
            }
            $type = @exif_imagetype($file['tmp_name']);
            if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
                throw new RuntimeException('画像形式が未対応です');
            }
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            chmod($path, 0644);
            return $path;
        } catch (RuntimeException $e) {
            global $err_msg;
            $err_msg[$key] = $e->getMessage();
        }
    }
}

function editProf($user_id,$email,$name,$path){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('UPDATE users SET email = :email,`name` = :name,img = :img WHERE id = :u_id');
        $result = $stmt->execute(array(':u_id'=>$user_id,':email'=>$email,':name'=>$name,':img'=>$path));
        if($result){

        }
    }catch(Exception $e){
    }
}

function decisionBestAnswer($id,$q_id){
    try{
        $dbh = dbConnect();
        $stmt = $dbh->prepare('UPDATE answers SET best_flg = :b_flg WHERE id = :id');
        $result = $stmt->execute(array(':b_flg'=>1,'id'=>$id));
        if($result){
            $stmt = $dbh->prepare('UPDATE questions SET best_flg = :b_flg WHERE id = :q_id');
            $result = $stmt->execute(array(':b_flg'=>1,':q_id'=>$q_id));
            if($result){

            }
        }
    }catch(Exception $e){

    }
}