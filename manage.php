<?php
//必要なデータの読み込み
require_once 'private/bootstrap.php';
require_once 'private/database.php';

//DB接続
$connection = connectDB();

$sql = "SELECT * FROM contacts";
$stmt = $connection->prepare($sql);
$stmt->execute();
$contactData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM reasons";
$stmt = $connection->prepare($sql);
$stmt->execute();
$reasonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者画面</title>
</head>
<body>
<!--お問合せ内容の一覧表示-->
    <h1>お問合せ一覧</h1>
    <table border="1">
        <tr>
            <th>id</th><th>名前</th> <th>フリガナ</th> <th>メールアドレス</th> <th>性別</th> <th>郵便番号</th> <th>都道府県</th>
            <th>市区町村</th> <th>その他</th> <th>建物</th> <th>問い合わせ内容</th> <th>経由</th> <th>投稿日</th>
        </tr>
        <?php foreach ($contactData as $contacts) {
                $reasons_array = [];
                foreach($reasonData as $reasons){
                    if($contacts['id'] == $reasons['id']){
                        $reasons_array[] = $reasons['reason'];
                    }
                }
                $reasons_string = implode(',',$reasons_array);
            ?>
                
            <tr>
                <td><?= htmlspecialchars($contacts['id'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['name'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['namerb'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['email'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['gender'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['top_postalcode'],ENT_QUOTES,'UTF-8')?>-<?=htmlspecialchars($contacts['bottom_postalcode'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['prefecture'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['town'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['housenumber'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['building'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['content'],ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($reasons_string,ENT_QUOTES,'UTF-8')?></td>
                <td><?= htmlspecialchars($contacts['created_at'],ENT_QUOTES,'UTF-8')?></td>
        <?php } ?>
    </table>
</body>
</html>
