<?php
/* ----------------------------------------
 * 必要なファイルを読み込む
 * ---------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';

// 実装
//pageが0の時は入力画面、1の時は確認画面
$page = 0;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $page = 1;
    $emp = 'なし';
    if($_POST['building'] == ''){
        $_POST['building'] = $emp;
    }
}


?>

<!-- 描画するHTML -->
 <!DOCTYPE html>
 <html lang="ja">
 <head>
    <meta charset="UTF-8">
    <title>お問い合わせフォーム</title>
 </head>
 <body>
    <?php if($page === 0){?>
    <!--入力画面-->
    <h1>お問い合わせフォーム</h1>
    <form method = "POST" action = index.php>
        <p>※は必須項目です</p>
        <label for="name">氏名※<label><input type="text" name = "name" required><br><br>
        <label for="nameruby">フリガナ※</label><input type="text" name = "nameruby" required><br><br>
        <label for="gender">性別※</label><input type="text" name = "gender" required><br><br>
        <label for="postalcode">住所(郵便番号)※</label><input type="text" name = "up_postalcode" required>-<input type="text" name="bottom_postalcode"><br><br>
        <label for="prefectures">住所(都道府県)※</label><input type="text" name = "prefectures" required><br><br>
        <label for="town">住所(市区町村)※</label><input type="text" name = "town" required><br><br>
        <label for="housenumber">住所(それ以外の住所)※</label><input type="text" name = "housenumber" required><br><br>
        <label for="building">住所(建物)<label><input type="text" name = "building"><br><br>
        <label for="content">お問い合わせ内容※</label> <input type="text" name = "content" required><br><br>
        <input type="submit" name="confirm" value="確認する">
    </form>
    <?php }?>
    <!--確認画面-->
    <?php if($page === 1){?>
        <h1>確認画面</h1>
        <form method = "POST" action = thanks.php>
            <label for="name">氏名：<label><?php echo $_POST['name'] ?><br><br>
            <label for="namerubi">フリガナ：</label><?php echo $_POST['nameruby'] ?><br><br>
            <label for="gender">性別：</label><?php echo $_POST['gender'] ?><br><br>
            <label for="postalcode">住所(郵便番号)：</label><?php echo $_POST['up_postalcode'] ?>-<?php echo $_POST['bottom_postalcode'] ?><br><br>
            <label for="prefectures">住所(都道府県)：</label><?php echo $_POST['prefectures'] ?><br><br>
            <label for="town">住所(市区町村)：</label><?php echo $_POST['town'] ?><br><br>
            <label for="housenumber">住所(それ以外の住所)：</label><?php echo $_POST['housenumber'] ?><br><br>
            <label for="building">住所(建物)：<label><?php echo $_POST['building'] ?><br><br>
            <label for="content">お問い合わせ内容：</label><?php echo $_POST['content'] ?><br><br>
            <input type="submit" name="confirm" value="送信する">
        </form>
    <?php }?>

 </body>
 </html>
