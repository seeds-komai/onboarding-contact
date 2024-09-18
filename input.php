<?php if($input === true && $comfirm === false){?>
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
                <?php foreach(Gender::cases() as $gender){ ?>
                    <?= htmlspecialchars($gender->value) ?><input type="radio" name = "gender" value="<?= htmlspecialchars($gender->value) ?>" <?php echo (isset($_POST['gender']) && $_POST['gender'] === $gender->value) ? 'checked' : ''; ?>>
                <?php } ?>
            </div>
            <label for="postalcode">住所(郵便番号)※</label>
            <div class="right">
                <input type="number" name = "top_postalcode" id = "top_postalcode" value="<?= $top_postalcode; ?>" required>-<input type="number" name="bottom_postalcode" id = "bottom_postalcode" value="<?= $bottom_postalcode; ?>" required>
            </div>
            <label for="prefecture">住所(都道府県)※</label>
            <div  class="right">
                <select name = "prefecture" id = "prefecture" required>
                    <option value="">選択してください</option>
                    <?php foreach (Prefecture::cases() as $prefectures){ ?>
                        <option value="<?= htmlspecialchars($prefectures->value) ?>" <?php echo (isset($prefecture) && $prefecture === $prefectures->value)? 'selected' : ''; ?>>
                            <?= htmlspecialchars($prefectures->value) ?>
                        </option>
                    <?php }; ?>
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
                <?php foreach(Reason::cases() as $reason){ ?>
                    <input type="checkbox" id="checkbox" name="reasons[]" value="<?= htmlspecialchars($reason->value) ?>" <?php echo (isset($_POST['reasons']) && in_array($reason->value, $reasons)) ? 'checked' : ''; ?>><?= htmlspecialchars($reason->value); ?><br>
                <?php }; ?>
            </div>
        </div>
        <input type="submit" name="confirm" id="confirm" value="確認">
        
    </form>
    <?php }?>