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


$building = $_POST['building'] ?? '';
$reasons = $_POST['reasons'] ?? [];
$reasonsArray = explode(',',$reasons);


//経由、性別のアルファベットを日本語に変換

$gender = $_POST['gender'];
$genderEnum = Gender::fromPostValue($gender);
$mapper = new gender_map();
$select_gender = $mapper->toJapanese($genderEnum);


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
        住所：$prefecture $town $housenumber $building \n
        お問合せ内容：$content \n
        このフォームを知った経由：$reasons \n
    ";
    $mail->CharSet = 'UTF-8';
    //メール送信
    $mail->send();

    $connection = connectDB();
    $sql = "INSERT INTO contacts(name,namerb,email,gender,top_postalcode,bottom_postalcode,prefecture,town,housenumber,building,content) VALUES (:name,:namerb,:email,:gender,:top_postalcode,:bottom_postalcode,:prefecture,:town,:housenumber,:building,:content)";

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
    $stmt->bindValue(':building',$building,PDO::PARAM_STR);
    $stmt->bindValue(':content',$content,PDO::PARAM_STR);

    $stmt->execute();

    //最後に挿入したデータのidを取得
    $id = $connection->lastInsertId();


    $sql = "INSERT INTO reasons(contact_id,reason) VALUE(:id,:reason)";
    $stmt = $connection->prepare($sql);
    foreach($reasonsArray as $reason){
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);
        $stmt->bindValue(':reason',$reason,PDO::PARAM_STR);
        $stmt->execute();
    }

    echo 'メールは正常に送信されました';
    $msg = 'お問合せありがとうございました。';
    $title_msg = 'ありがとうございました';

}catch(Exception $e){
    $error_message = "メール送信中にエラーが発生しました: " . $mail->ErrorInfo;
    echo $error_message;
    $msg = 'お問合せに失敗しました。再度ご記入お願いします';
    $title_msg = '失敗しました';
}




?>

<!-- 描画するHTML -->
 <!DOCTYPE html>
 <html lang="ja">
 <head>
    <meta charset="UTF-8">
    <title><?php echo"$title_msg"; ?></title>
 </head>
 <body>
    <p><?php echo"$msg"; ?></p>
    <a href="index.php">戻る</a>
 </body>
 </html>
