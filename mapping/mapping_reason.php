<?php
//経由をvalueの値から日本語へ変換するためのマッピング

enum  Reason: string{
    case Family = '家族から聞いた';
    case Friend = '友達から聞いた';
    case Newpaper = '新聞';
    case Radio = 'ラジオ';
    case Web = 'web';
}