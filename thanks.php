<?php
require_once 'private/bootstrap.php';
require_once 'private/database.php';

require_once 'mapping.php';

// 実装
// PHPMailerを使用
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';


$name = $_POST['name'];
$namerb = $_POST['namerb'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$top_postalcode = $_POST['top_postalcode'];
$bottom_postalcode = $_POST['bottom_postalcode'];
$prefecture = $_POST['prefecture'];
$town = $_POST['town'];
$housenumber = $_POST['housenumber'];
$content = $_POST['content'];


if($_POST['building'] == 'なし'){
    //データベースにはbuildingの欄をなしで表示、メールでは記載しない
    $building = '';
    $building_db = $_POST['building'];
}else{
    $building_db = $_POST['building'];
}

if(isset($_POST['reasons'])){
	$reasons = $_POST['reasons'];

}else{
    $reasons = [];
}


if(is_string($reasons)){
    $reasonsArray = explode(',',$reasons);
}else{
    $reasonsArray = (array)$reasons;
}

//都道府県、経由、性別のアルファベットを日本語に変換
$select_prefecture = $prefecture_map[$prefecture];

$select_gender = $gender_map[$gender];

$select_reasons = array_map(function($reason) use ($reason_map) {
    return $reason_map[$reason];
}, $reasonsArray);
$select_reasons = implode(',',$select_reasons);


//DBにデータを入れる処理
$connection = connectDB();
$sql = "INSERT INTO contacts(name,namerb,email,gender,top_postalcode,bottom_postalcode,prefecture,town,housenumber,building,content,reason) VALUES (:name,:namerb,:email,:gender,:top_postalcode,:bottom_postalcode,:prefecture,:town,:housenumber,:building_db,:content,:reasons)";

$stmt = $connection->prepare($sql);
$stmt->bindValue(':name',$name,PDO::PARAM_STR);
$stmt->bindValue(':namerb',$namerb,PDO::PARAM_STR);
$stmt->bindValue(':email',$email,PDO::PARAM_STR);
$stmt->bindValue(':gender',$gender,PDO::PARAM_STR);
$stmt->bindValue(':top_postalcode',$top_postalcode,PDO::PARAM_STR);
$stmt->bindValue(':bottom_postalcode',$bottom_postalcode,PDO::PARAM_STR);
$stmt->bindValue(':prefecture',$prefecture,PDO::PARAM_STR);
$stmt->bindValue(':town',$town,PDO::PARAM_STR);
$stmt->bindValue(':housenumber',$housenumber,PDO::PARAM_STR);
$stmt->bindValue(':building_db',$building_db,PDO::PARAM_STR);
$stmt->bindValue(':content',$content,PDO::PARAM_STR);
$stmt->bindValue(':reasons',implode(",",$reasonsArray),PDO::PARAM_STR);

$stmt->execute();

//メールを送信する処理
// PHPMailerのインスタンス作成
$mail = new PHPMailer(true);
try{
    $mail->isSMTP();
    $mail->Host = 'mailhog';
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = false;
    $mail->Port = 1025;

    //送信者と受信者の設定
    $mail->setFrom('from@example.com', '問い合わせ管理担当者');
    $mail->addAddress($email, $name);

    //メールの内容
    $mail->isHTML(true);
    $mail->Subject = 'お問合せありがとうございます';
    $mail->Body = "
        氏名：$name \n
        フリガナ：$namerb \n
        メール：$email \n
        性別：$select_gender \n
        郵便番号：$top_postalcode-$bottom_postalcode \n
        住所：$select_prefecture $town $housenumber $building \n
        お問合せ内容：$content \n
        このフォームを知った経由：$select_reasons \n
    ";
    $mail->CharSet = 'UTF-8';
    //メール送信
    $mail->send();
    echo 'メールは正常に送信されました';
}catch(Exception $e){
    echo "メール送信中にエラーが発生しました：{$mail->ErrorInfo}";
}




?>

<!-- 描画するHTML -->
 <!DOCTYPE html>
 <html lang="ja">
 <head>
    <meta charset="UTF-8">
    <title>ありがとうございました</title>
 </head>
 <body>
    <p>お問い合わせありがとうございました。</p>
    <a href="index.php">戻る</a>
 </body>
 </html>
