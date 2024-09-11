<?php
/* ----------------------------------------
 * 必要なファイルを読み込む
 * ---------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';
require_once 'mapping.php';


// 実装
//pageがinputの時は入力画面、confirmの時は確認画面

$page = "input";
//記入欄に内容が入っているかチェックするための変数
$name = "";
$namerb = "";
$email = "";
$gender = "";
$top_postalcode = "";
$bottom_postalcode = "";
$prefecture = "";
$town = "";
$housenumber = "";
$building = "";
$content = "";
$reasons = [];
$reasons_array = ['family','friend','newspaper','radio','web'];
$select_gender = "";
$select_prefecture = "";
$select_reasons = [];


//POSTのリクエストが来た時の動作
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //確認ボタンが押された時の処理
    //変数に値を代入
    if(isset($_POST['confirm'])){
        $name = $_POST['name'];
        $namerb = $_POST['namerb'];
        $email = $_POST['email'];
        if($_POST['gender'] == ""){
            $gender = "";
        }else{
            $gender = $_POST['gender'];
            $genderEnum = Gender::fromPostValue($gender);
            $mapper = new gender_map();
            $select_gender = $mapper->toJapanese($genderEnum);
        }
        $gender = $_POST['gender'];
        $top_postalcode= $_POST['top_postalcode'];
        $bottom_postalcode = $_POST['bottom_postalcode'];
        if($_POST['prefecture'] == ""){
            $prefecture = "";
        }
        $prefecture = $_POST['prefecture'];

        $town = $_POST['town'];
        $housenumber = $_POST['housenumber'];
        $content = $_POST['content'];
        if (empty($_POST['reasons'])) {
            $reasons = [];
        } else {
            $reasons = $_POST['reasons'];
        }
    }
}


//記入必須欄に一つでも記入されていなかったらpageを0にする
if($name == "" || $namerb == "" || $gender== "" || $top_postalcode== "" || $bottom_postalcode == "" || $prefecture == "" || $town == "" || $housenumber == "" || $content == ""){
    $page = "input";
}else{
    //全てに記入されていた場合、pageを1にする
    $page = "confirm";
    //記入が必須でない欄が何も書かれていない時、なしと表示する。
    $building = $_POST['building'];
    if($building == ''){
        $building = '';
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //戻るボタンが押された時の処理
    if(isset($_POST['edit'])){
        $page = "input";
        $name = $_POST['name'];
        $namerb = $_POST['namerb'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $top_postalcode = $_POST['top_postalcode'];
        $bottom_postalcode = $_POST['bottom_postalcode'];
        $prefecture = $_POST['prefecture'];
        $town = $_POST['town'];
        $housenumber = $_POST['housenumber'];
        $building = $_POST['building'];
        $content = $_POST['content'];
        $reasons = explode(',',$_POST['reasons']);
        $select_gender = "";
        $select_prefecture = "";
        $select_reasons = [];
    }
}


?>

<!-- 描画するHTML -->
 <!DOCTYPE html>
 <html lang="ja">
 <head>
    <meta charset="UTF-8">
    <title>お問い合わせフォーム</title>

    <style>
        #confirm{
            background-color:rgb(173,216,230);
            width:100px;
            height:40px;
            position: absolute;
            left: 20%;
            border-radius:12px;
            margin-right: 10px;
            padding: 10px 20px;
            display: block;
            margin-top: 150px;
        }
        .right{
            display: block;
            position:absolute;
            left:40%;
        }
        label {
            display: block;
            margin-top: 30px;
            margin-left: 20px;
            font-weight: bold;
        }
        p{
            margin-left: 20px;
        }
    </style>

 </head>
 <body>
    <?php if($page === "input"){?>
    <!--入力画面-->
    <h1>お問い合わせフォーム</h1>
    <form method = "POST" action = index.php>
        <div>
            <p>※は必須項目です</p>
            <label for="name">氏名※</label>
            <input type="text" class="right" name = "name" id = "name" value="<?= $name; ?>" required>

            <label for="namerb">フリガナ※</label>
            <input type="text" class="right" name = "namerb" id = "namerb" value="<?= $namerb; ?>" required>

            <label for="email">メールアドレス※</label>
            <input type="email" class="right"  name = "email" id = "email" value="<?= $email; ?>" required>

            <label for="gender" >性別※</label>
            <div class="right">
                男性<input type="radio" id = "male" name = "gender" value = "male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'checked' : ''; ?> required>
                女性<input type="radio" id = "female" name = "gender" value = "female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'checked' : ''; ?> required>
            </div>
            <label for="postalcode">住所(郵便番号)※</label>
            <div class="right">
                <input type="number" name = "top_postalcode" id = "top_postalcode" value="<?= $top_postalcode; ?>" required>-<input type="number" name="bottom_postalcode" id = "bottom_postalcode" value="<?= $bottom_postalcode; ?>" required>
            </div>
            <label for="prefecture">住所(都道府県)※</label>
            <div  class="right">
                <select name = "prefecture" id = "prefecture" required>
                    <option value="">選択してください</option>
                    <option value="北海道" <?php echo (isset($prefecture) && $prefecture === '北海道') ? 'selected' : ''; ?>>北海道</option>
                    <option value="青森県" <?php echo (isset($prefecture) && $prefecture === '青森') ? 'selected' : ''; ?>>青森県</option>
                    <option value="岩手県" <?php echo (isset($prefecture) && $prefecture === '岩手県') ? 'selected' : ''; ?>>岩手県</option>
                    <option value="宮城県" <?php echo (isset($prefecture) && $prefecture === '宮城県') ? 'selected' : ''; ?>>宮城県</option>
                    <option value="秋田県" <?php echo (isset($prefecture) && $prefecture === '秋田県') ? 'selected' : ''; ?>>秋田県</option>
                    <option value="山形県" <?php echo (isset($prefecture) && $prefecture === '山形県') ? 'selected' : ''; ?>>山形県</option>
                    <option value="福島県" <?php echo (isset($prefecture) && $prefecture === '福島県') ? 'selected' : ''; ?>>福島県</option>
                    <option value="茨城県" <?php echo (isset($prefecture) && $prefecture === '茨城県') ? 'selected' : ''; ?>>茨城県</option>
                    <option value="栃木県" <?php echo (isset($prefecture) && $prefecture === '栃木県') ? 'selected' : ''; ?>>栃木県</option>
                    <option value="群馬県" <?php echo (isset($prefecture) && $prefecture === '群馬県') ? 'selected' : ''; ?>>群馬県</option>
                    <option value="埼玉県" <?php echo (isset($prefecture) && $prefecture === '埼玉県') ? 'selected' : ''; ?>>埼玉県</option>
                    <option value="千葉県" <?php echo (isset($prefecture) && $prefecture === '千葉県') ? 'selected' : ''; ?>>千葉県</option>
                    <option value="東京都" <?php echo (isset($prefecture) && $prefecture === '東京都') ? 'selected' : ''; ?>>東京都</option>
                    <option value="神奈川県" <?php echo (isset($prefecture) && $prefecture === '神奈川県') ? 'selected' : ''; ?>>神奈川県</option>
                    <option value="新潟県" <?php echo (isset($prefecture) && $prefecture === '新潟県') ? 'selected' : ''; ?>>新潟県</option>
                    <option value="富山県" <?php echo (isset($prefecture) && $prefecture === '富山県') ? 'selected' : ''; ?>>富山県</option>
                    <option value="石川県" <?php echo (isset($prefecture) && $prefecture === '石川県') ? 'selected' : ''; ?>>石川県</option>
                    <option value="福井県" <?php echo (isset($prefecture) && $prefecture === '福井県') ? 'selected' : ''; ?>>福井県</option>
                    <option value="山梨県" <?php echo (isset($prefecture) && $prefecture === '山梨県') ? 'selected' : ''; ?>>山梨県</option>
                    <option value="長野県" <?php echo (isset($prefecture) && $prefecture === '長野県') ? 'selected' : ''; ?>>長野県</option>
                    <option value="岐阜県" <?php echo (isset($prefecture) && $prefecture === '岐阜県') ? 'selected' : ''; ?>>岐阜県</option>
                    <option value="静岡県" <?php echo (isset($prefecture) && $prefecture === '静岡県') ? 'selected' : ''; ?>>静岡県</option>
                    <option value="愛知県" <?php echo (isset($prefecture) && $prefecture === '愛知県') ? 'selected' : ''; ?>>愛知県</option>
                    <option value="三重県" <?php echo (isset($prefecture) && $prefecture === '三重県') ? 'selected' : ''; ?>>三重県</option>
                    <option value="滋賀県" <?php echo (isset($prefecture) && $prefecture === '滋賀県') ? 'selected' : ''; ?>>滋賀県</option>
                    <option value="京都府" <?php echo (isset($prefecture) && $prefecture === '京都府') ? 'selected' : ''; ?>>京都府</option>
                    <option value="大阪府" <?php echo (isset($prefecture) && $prefecture === '大阪府') ? 'selected' : ''; ?>>大阪府</option>
                    <option value="兵庫県" <?php echo (isset($prefecture) && $prefecture === '兵庫県') ? 'selected' : ''; ?>>兵庫県</option>
                    <option value="奈良県" <?php echo (isset($prefecture) && $prefecture === '奈良県') ? 'selected' : ''; ?>>奈良県</option>
                    <option value="和歌山県" <?php echo (isset($prefecture) && $prefecture === '和歌山県') ? 'selected' : ''; ?>>和歌山県</option>
                    <option value="鳥取県" <?php echo (isset($prefecture) && $prefecture === '鳥取県') ? 'selected' : ''; ?>>鳥取県</option>
                    <option value="島根県" <?php echo (isset($prefecture) && $prefecture === '島根県') ? 'selected' : ''; ?>>島根県</option>
                    <option value="岡山県" <?php echo (isset($prefecture) && $prefecture === '岡山県') ? 'selected' : ''; ?>>岡山県</option>
                    <option value="広島県" <?php echo (isset($prefecture) && $prefecture === '広島県') ? 'selected' : ''; ?>>広島県</option>
                    <option value="山口県" <?php echo (isset($prefecture) && $prefecture === '山口県') ? 'selected' : ''; ?>>山口県</option>
                    <option value="徳島県" <?php echo (isset($prefecture) && $prefecture === '徳島県') ? 'selected' : ''; ?>>徳島県</option>
                    <option value="香川県" <?php echo (isset($prefecture) && $prefecture === '香川県') ? 'selected' : ''; ?>>香川県</option>
                    <option value="愛媛県" <?php echo (isset($prefecture) && $prefecture === '愛媛県') ? 'selected' : ''; ?>>愛媛県</option>
                    <option value="高知県" <?php echo (isset($prefecture) && $prefecture === '高知県') ? 'selected' : ''; ?>>高知県</option>
                    <option value="福岡県" <?php echo (isset($prefecture) && $prefecture === '福岡県') ? 'selected' : ''; ?>>福岡県</option>
                    <option value="佐賀県" <?php echo (isset($prefecture) && $prefecture === '佐賀県') ? 'selected' : ''; ?>>佐賀県</option>
                    <option value="長崎県" <?php echo (isset($prefecture) && $prefecture === '長崎県') ? 'selected' : ''; ?>>長崎県</option>
                    <option value="熊本県" <?php echo (isset($prefecture) && $prefecture === '熊本県') ? 'selected' : ''; ?>>熊本県</option>
                    <option value="大分県" <?php echo (isset($prefecture) && $prefecture === '大分県') ? 'selected' : ''; ?>>大分県</option>
                    <option value="宮崎県" <?php echo (isset($prefecture) && $prefecture === '宮城県') ? 'selected' : ''; ?>>宮崎県</option>
                    <option value="鹿児島県" <?php echo (isset($prefecture) && $prefecture === '鹿児島県') ? 'selected' : ''; ?>>鹿児島県</option>
                    <option value="沖縄県" <?php echo (isset($prefecture) && $prefecture === '沖縄県') ? 'selected' : ''; ?>>沖縄県</option>
                
                </select>
            </div>
            <label for="town">住所(市区町村)※</label>
            <input type="text" class="right"  name = "town" id = "town" value="<?= $town; ?>" required>

            <label for="housenumber">住所(それ以外の住所)※</label>
            <input type="text" class="right"  name = "housenumber" id = "housenumber" value="<?= $housenumber; ?>" required>

            <label for="building">住所(建物)</label>
            <input type="text" class="right"  name = "building" id = "building" value="<?= $building; ?>">

            <label for="content">お問い合わせ内容※</label> 
            <textarea name = "content" class="right" id = "content" required><?php echo htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

            <label>このフォームを知った経由</label>
            <div  class="right">
                <input type="checkbox" id="checkbox" name="reasons[]" value="家族から聞いた" <?php echo (isset($_POST['reasons']) && in_array('家族から聞いた', $reasons)) ? 'checked' : ''; ?>>家族から聞いた<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="友達から聞いた" <?php echo (isset($_POST['reasons']) && in_array('友達から聞いた', $reasons)) ? 'checked' : ''; ?>>友達から聞いた<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="新聞" <?php echo (isset($_POST['reasons']) && in_array('新聞', $reasons)) ? 'checked' : ''; ?>>新聞<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="ラジオ" <?php echo (isset($_POST['reasons']) && in_array('ラジオ', $reasons)) ? 'checked' : ''; ?>>ラジオ<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="Web" <?php echo (isset($_POST['reasons']) && in_array('Web', $reasons)) ? 'checked' : ''; ?>>Web<br>
            </div>
        </div>
        <input type="submit" name="confirm" id="confirm" value="確認">
        
    </form>
    <?php }?>
    <!--確認画面-->
    <?php if($page === "confirm"){?>
        <h1>確認画面</h1>
            <!--確認内容-->
            <label for="name">氏名：</label><div class="right"><?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="namerb">フリガナ：</label><div class="right"><?php echo htmlspecialchars($namerb,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="email">メールアドレス：</label><div class="right"><?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="gender">性別：</label><div class="right"><?php echo htmlspecialchars($select_gender,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="postalcode">住所(郵便番号)：</label><div class="right"><?php echo htmlspecialchars($top_postalcode,ENT_QUOTES,'UTF-8'); ?>-<?php echo htmlspecialchars($bottom_postalcode,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="prefecture">住所(都道府県)：</label><div class="right"><?php echo htmlspecialchars($prefecture,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="town">住所(市区町村)：</label><div class="right"><?php echo htmlspecialchars($town,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="housenumber">住所(それ以外の住所)：</label><div class="right"><?php echo htmlspecialchars($housenumber,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="building">住所(建物)：</label><div class="right"><?php echo htmlspecialchars($building,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="content">お問い合わせ内容：</label><div class="right"><?php echo htmlspecialchars($content,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="reasons">このフォームを知った経由：</label><div class="right"><?php echo htmlspecialchars(implode(',',$reasons),ENT_QUOTES,'UTF-8');?></div><br>
            <form method = "POST" action = thanks.php>
                <!--postで送るためのデータ-->
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="namerb" value="<?php echo htmlspecialchars($namerb,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="gender" value="<?php echo htmlspecialchars($gender,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="top_postalcode" value="<?php echo htmlspecialchars($top_postalcode,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="bottom_postalcode" value="<?php echo htmlspecialchars($bottom_postalcode,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="prefecture" value="<?php echo htmlspecialchars($prefecture,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="town" value="<?php echo htmlspecialchars($town,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="housenumber" value="<?php echo htmlspecialchars($housenumber,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="building" value="<?php echo htmlspecialchars($building,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="content" value="<?php echo htmlspecialchars($content,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="reasons" value="<?php echo htmlspecialchars(implode(',',$reasons),ENT_QUOTES,'UTF-8'); ?>">
                <input type="submit" name="confirm" value="送信する">
            </form>

            <form method = "POST" action = index.php>
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="namerb" value="<?php echo htmlspecialchars($namerb,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="gender" value="<?php echo htmlspecialchars($gender,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="top_postalcode" value="<?php echo htmlspecialchars($top_postalcode,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="bottom_postalcode" value="<?php echo htmlspecialchars($bottom_postalcode,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="prefecture" value="<?php echo htmlspecialchars($prefecture,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="town" value="<?php echo htmlspecialchars($town,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="housenumber" value="<?php echo htmlspecialchars($housenumber,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="building" value="<?php echo htmlspecialchars($building,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="content" value="<?php echo htmlspecialchars($content,ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="reasons" value="<?php echo htmlspecialchars(implode(',',$reasons),ENT_QUOTES,'UTF-8'); ?>">
                <input type="submit" name="edit" id="edit" value="戻る">
            </form>
    <?php }?>
 </body>
 </html>
