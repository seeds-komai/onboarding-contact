<?php if($comfirm === true && $input === false){?>
    <!--確認画面-->
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

