<?php
require_once 'private/bootstrap.php';
require_once 'private/database.php';
// 実装
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

if(isset($_POST['reasons'])){
   $reasons = $_POST['reasons'];
}else{
   $reasons = '';
}

if(is_string($reasons)){
   $reasonsArray = explode(',',$reasons);
}else{
   $reasonsArray = (array)$reasons;
}
//DBにデータを入れる処理
$connection = connectDB();
$sql = "INSERT INTO contacts(name,namerb,email,gender,top_postalcode,bottom_postalcode,prefecture,town,housenumber,building,content,reason)
         VALUES (:name,:namerb,:email,:gender,:top_postalcode,:bottom_postalcode,:prefecture,:town,:housenumber,:building,:content,:reasons)";

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
$stmt->bindValue(':reasons',implode(",",$reasonsArray),PDO::PARAM_STR);

$stmt->execute();
// $stmt->execute([
//    ':name' => $name,
//    ':namerb' => $namerb,
//    ':email' => $email,
//    ':gender' => $gender,
//    ':top_postalcode' => $top_postalcode,
//    ':bottom_postalcode' => $bottom_postalcode,
//    ':prefecture' => $prefecture,
//    ':town' => $town,
//    ':housenumber' => $housenumber,
//    ':building' => $building,
//    ':content' => $content,
//    ':reasons' => implode(",",$reasonsArray)
// ]);

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
