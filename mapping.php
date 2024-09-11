<?php
//性別をvalueの値から日本語へ変換するためのマッピング

enum  Gender: string{
    case Male = '男性';
    case Female = '女性';

    public function toJapanese(): string{
        return match ($this) {
            self::Male => '男性',
            self::Female => '女性'
        };
    }

    public static function fromPostValue(string $value): self{
        return match ($value) {
            'male' => self::Male,
            'female' => self::Female
        };
    }
}

