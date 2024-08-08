<?php
/* ----------------------------------------
 * 必要なファイルを読み込む
 * ---------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';


// 実装
//pageが0の時は入力画面、1の時は確認画面
$page = 0;
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
$content = "";
$reasons = [];
$select_gender = "";
$select_prefecture = "";
$select_reasons = [];


//性別をvalueの値から日本語へ変換するためのマッピング
$gender_map = array(
    'male' => '男性',
    'female' => '女性'
);

//都道府県をvalueの値から日本語へ変換するためのマッピング
$prefecture_map = array(
    'hokkaido' => '北海道',
    'aomori' => '青森県',
    'iwate' => '岩手県',
    'miyagi' => '宮城県',
    'akita' => '秋田県',
    'yamagata' => '山形県',
    'fukushima' => '福島県',
    'ibaraki' => '茨城県',
    'tochigi' => '栃木県',
    'gunma' => '群馬県',
    'saitama' => '埼玉県',
    'chiba' => '千葉県',
    'tokyo' => '東京都',
    'kanagawa' => '神奈川県',
    'niigata' => '新潟県',
    'toyama' => '富山県',
    'ishikawa' => '石川県',
    'fukui' => '福井県',
    'yamanashi' => '山梨県',
    'nagano' => '長野県',
    'gifu' => '岐阜県',
    'shizuoka' => '静岡県',
    'aichi' => '愛知県',
    'mie' => '三重県',
    'shiga' => '滋賀県',
    'kyoto' => '京都府',
    'osaka' => '大阪府',
    'hyogo' => '兵庫県',
    'nara' => '奈良県',
    'wakayama' => '和歌山県',
    'tottori' => '鳥取県',
    'shimane' => '島根県',
    'okayama' => '岡山県',
    'hiroshima' => '広島県',
    'yamaguchi' => '山口県',
    'tokushima' => '徳島県',
    'kagawa' => '香川県',
    'ehime' => '愛媛県',
    'kochi' => '高知県',
    'fukuoka' => '福岡県',
    'saga' => '佐賀県',
    'nagasaki' => '長崎県',
    'kumamoto' => '熊本県',
    'oita' => '大分県',
    'miyazaki' => '宮崎県',
    'kagoshima' => '鹿児島県',
    'okinawa' => '沖縄県'
);

//知った経由を日本語へ変換するためのマッピング
$reason_map = array(
    'family' => '家族から聞いた',
    'friend' => '友達から聞いた',
    'newspaper' => '新聞',
    'radio' => 'ラジオ',
    'web' => 'Web'
);

//POSTのリクエストが来た時の動作
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //戻るボタンが押された時の処理
    if(isset($_POST['edit'])){
        $page = 0;
    //確認ボタンが押された時の処理
    //変数に値を代入
    }else if(isset($_POST['confirm'])){
        $name = $_POST['name'];
        $namerb = $_POST['namerb'];
        $email = $_POST['email'];
        if($_POST['gender'] == ""){
            $gender = "";
        }else{
            $gender = $_POST['gender'];
            $select_gender = $gender_map[$gender];
        }
        $gender = $_POST['gender'];
        $top_postalcode= $_POST['top_postalcode'];
        $bottom_postalcode = $_POST['bottom_postalcode'];
        if($_POST['prefecture'] == ""){
            $prefecture = "";
        }else{
            $prefecture = $_POST['prefecture'];
            $select_prefecture = $prefecture_map[$prefecture];
        }
        $town = $_POST['town'];
        $housenumber = $_POST['housenumber'];
        $content = $_POST['content'];
        if (empty($_POST['reasons'])) {
            $reasons = [];
        } else {
            $reasons = $_POST['reasons'];
            $select_reasons = array_map(function($reason) use ($reason_map) {
                return $reason_map[$reason];
            }, $reasons);
        // $reasons = isset($_POST['reasons']) ? $_POST['reasons'] : [];
        }
    }

}

//記入必須欄に一つでも記入されていなかったらpageを0にする
if($name == "" || $namerb == "" || $gender== "" || $top_postalcode== "" || $bottom_postalcode == "" || $prefecture == "" || $town == "" || $housenumber == "" || $content == ""){
    $page = 0;
}else{
    //全てに記入されていた場合、pageを1にする
    $page = 1;
    //記入が必須でない欄が何も書かれていない時、なしと表示する。
    $emp = 'なし';
    $building = $_POST['building'];
    if($building == ''){
        $building = $emp;
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
    <?php if($page === 0){?>
    <!--入力画面-->
    <h1>お問い合わせフォーム</h1>
    <form method = "POST" action = index.php>
        <div>
            <p>※は必須項目です</p>
            <label for="name">氏名※</label>
            <input type="text" class="right" name = "name" id = "name" required>

            <label for="namerb">フリガナ※</label>
            <input type="text" class="right" name = "namerb" id = "namerb" required>

            <label for="email">メールアドレス※</label>
            <input type="email" class="right"  name = "email" id = "email" required>

            <label for="gender">性別※</label>
            <div class="right">
                <!-- <label for="male">男性</label> -->
                男性<input type="radio" id = "male" name = "gender" value = "male" required>
                <!-- <label for="female">女性</label> -->
                女性<input type="radio" id = "female" name = "gender" value = "female" required>
            </div>
            <label for="postalcode">住所(郵便番号)※</label>
            <div class="right">
                <input type="text" name = "top_postalcode" id = "top_postalcode" required>-<input type="text" name="bottom_postalcode" id = "bottom_postalcode" required>
            </div>
            <label for="prefecture">住所(都道府県)※</label>
            <div  class="right">
                <select name = "prefecture" id = "prefecture"required>
                    <option value="">選択してください</option>
                    <option value="hokkaido">北海道</option>
                    <option value="aomori">青森県</option>
                    <option value="iwate">岩手県</option>
                    <option value="miyagi">宮城県</option>
                    <option value="akita">秋田県</option>
                    <option value="yamagata">山形県</option>
                    <option value="fukushima">福島県</option>
                    <option value="ibaraki">茨城県</option>
                    <option value="tochigi">栃木県</option>
                    <option value="gunma">群馬県</option>
                    <option value="saitama">埼玉県</option>
                    <option value="chiba">千葉県</option>
                    <option value="tokyo">東京都</option>
                    <option value="kanagawa">神奈川県</option>
                    <option value="niigata">新潟県</option>
                    <option value="toyama">富山県</option>
                    <option value="ishikawa">石川県</option>
                    <option value="fukui">福井県</option>
                    <option value="yamanashi">山梨県</option>
                    <option value="nagano">長野県</option>
                    <option value="gifu">岐阜県</option>
                    <option value="shizuoka">静岡県</option>
                    <option value="aichi">愛知県</option>
                    <option value="mie">三重県</option>
                    <option value="shiga">滋賀県</option>
                    <option value="kyoto">京都府</option>
                    <option value="osaka">大阪府</option>
                    <option value="hyogo">兵庫県</option>
                    <option value="nara">奈良県</option>
                    <option value="wakayama">和歌山県</option>
                    <option value="tottori">鳥取県</option>
                    <option value="shimane">島根県</option>
                    <option value="okayama">岡山県</option>
                    <option value="hiroshima">広島県</option>
                    <option value="yamaguchi">山口県</option>
                    <option value="tokushima">徳島県</option>
                    <option value="kagawa">香川県</option>
                    <option value="ehime">愛媛県</option>
                    <option value="kochi">高知県</option>
                    <option value="fukuoka">福岡県</option>
                    <option value="saga">佐賀県</option>
                    <option value="nagasaki">長崎県</option>
                    <option value="kumamoto">熊本県</option>
                    <option value="oita">大分県</option>
                    <option value="miyazaki">宮崎県</option>
                    <option value="kagoshima">鹿児島県</option>
                    <option value="okinawa">沖縄県</option>
                
                </select>
            </div>
            <label for="town">住所(市区町村)※</label>
            <input type="text" class="right"  name = "town" id = "town" required>

            <label for="housenumber">住所(それ以外の住所)※</label>
            <input type="text" class="right"  name = "housenumber" id = "housenumber" required>

            <label for="building">住所(建物)</label>
            <input type="text" class="right"  name = "building" id = "building">

            <label for="content">お問い合わせ内容※</label> 
            <textarea name = "content" class="right" id = "content" required></textarea>

            <label>このフォームを知った経由</label>
            <div  class="right" >
                <input type="checkbox" id="checkbox" name="reasons[]" value="family">家族から聞いた<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="friend">友達から聞いた<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="newspaper">新聞<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="radio">ラジオ<br>
                <input type="checkbox" id="checkbox" name="reasons[]" value="web">Web<br>
            </div>
        </div>
        <input type="submit" name="confirm" id="confirm" value="確認">
        
    </form>
    <?php }?>
    <!--確認画面-->
    <?php if($page === 1){?>
        <h1>確認画面</h1>
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
            <!--確認内容-->
            <label for="name">氏名：</label><div class="right"><?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="namerb">フリガナ：</label><div class="right"><?php echo htmlspecialchars($namerb,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="email">メールアドレス：</label><div class="right"><?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="gender">性別：</label><div class="right"><?php echo htmlspecialchars($select_gender,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="postalcode">住所(郵便番号)：</label><div class="right"><?php echo htmlspecialchars($top_postalcode,ENT_QUOTES,'UTF-8'); ?>-<?php echo htmlspecialchars($bottom_postalcode,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="prefecture">住所(都道府県)：</label><div class="right"><?php echo htmlspecialchars($select_prefecture,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="town">住所(市区町村)：</label><div class="right"><?php echo htmlspecialchars($town,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="housenumber">住所(それ以外の住所)：</label><div class="right"><?php echo htmlspecialchars($housenumber,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="building">住所(建物)：</label><div class="right"><?php echo htmlspecialchars($building,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="content">お問い合わせ内容：</label><div class="right"><?php echo htmlspecialchars($content,ENT_QUOTES,'UTF-8'); ?></div>
            <label for="reasons">このフォームを知った経由：</label><div class="right"><?php echo htmlspecialchars(implode(',',$select_reasons),ENT_QUOTES,'UTF-8');?></div><br>
            <input type="submit" name="confirm" value="送信する">
        </form>

        <form method = "POST" action = index.php>
            <input type="submit" name="edit" value="戻る">
        </form>
    <?php }?>
 </body>
 </html>
