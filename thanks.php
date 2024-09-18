<?php
require_once 'private/bootstrap.php';
require_once 'private/database.php';

require_once 'mapping/mapping_gender.php';

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

//メールを送信する処理
// PHPMailerのインスタンス作成
$mail = new PHPMailer(true);
try{
    $mail->isSMTP();
    $mail->Host = 'mailhog';
    $mail->Port = 1025;

    //送信者と受信者の設定
    $mail->setFrom('from@example.com', '問い合わせ管理担当者');
    $mail->addAddress($email, $name);

    //メールの内容
    $mail->isHTML(true);
    $mail->Subject = 'お問合せありがとうございます';
    $mail->Body = "
        氏名：" . htmlspecialchars($name,ENT_QUOTES,'UTF-8') . " \n
        フリガナ：" . htmlspecialchars($namerb,ENT_QUOTES,'UTF-8') . " \n
        メール：" . htmlspecialchars($email,ENT_QUOTES,'UTF-8') . " \n
        性別：" . htmlspecialchars($gender,ENT_QUOTES,'UTF-8') . " \n
        郵便番号：" . htmlspecialchars($top_postalcode,ENT_QUOTES,'UTF-8') . "-" . htmlspecialchars($bottom_postalcode,ENT_QUOTES,'UTF-8') . " \n
        住所：" . htmlspecialchars($prefecture,ENT_QUOTES,'UTF-8') . "" . htmlspecialchars($town,ENT_QUOTES,'UTF-8') . "" . htmlspecialchars($housenumber,ENT_QUOTES,'UTF-8') . "" . htmlspecialchars($building,ENT_QUOTES,'UTF-8') . " \n
        お問合せ内容：" . htmlspecialchars($content,ENT_QUOTES,'UTF-8') . " \n
        このフォームを知った経由：" . htmlspecialchars($reasons,ENT_QUOTES,'UTF-8') . " \n
    ";
    $mail->CharSet = 'UTF-8';
    //メール送信
    $mail->send();
    try{
        $connection = connectDB();
        //トランザクション開始
        $connection->beginTransaction();

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
        $connection->commit();
    }catch(Exception $e){
        $connection->rollBack();
        echo "エラーが発生しました" . $e->getMessage();
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
