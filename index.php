<?php
/* ----------------------------------------
 * 必要なファイルを読み込む
 * ---------------------------------------- */
require_once 'private/bootstrap.php';
require_once 'private/database.php';
require_once 'mapping/mapping_gender.php';
require_once 'mapping/mapping_reason.php';
require_once 'mapping/mapping_prefecture.php';


// 実装
//input=trueかつcomfir=falseの時入力画面、input=falseかつcomfirm=trueの時確認画面
$input = true;
$comfirm = false;
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


//POSTのリクエストが来た時の動作
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //確認ボタンが押された時の処理
    //変数に値を代入
    if(isset($_POST['confirm'])){
        $name = $_POST['name'];
        $namerb = $_POST['namerb'];
        $email = $_POST['email'];
        if($_POST['gender'] == "男性"){
            $gender = $_POST['gender'];
            $select_gender = Gender::Male->value;
        }else if($_POST['gender'] == '女性'){
            $gender = $_POST['gender'];
            $select_gender = Gender::Female->value;
        }else{
            $gender = '';
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


//記入必須欄に一つでも記入されていなかったらinputをtrue,comfirmをfalseにする
if($name == "" || $namerb == "" || $gender== "" || $top_postalcode== "" || $bottom_postalcode == "" || $prefecture == "" || $town == "" || $housenumber == "" || $content == ""){
    $input = true;
    $comfirm = false;
}else{
    //全てに記入されていた場合、inputをfalse,comfirmをtureにする
    $input = false;
    $comfirm = true;

    $building = $_POST['building'];
    if($building == ''){
        $building = '';
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //戻るボタンが押された時の処理
    if(isset($_POST['edit'])){
        $input = true;
        $comfirm = false;
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
    <? include('input.php'); ?>
    <? include('comfirm.php'); ?>
 </body>
 </html>
