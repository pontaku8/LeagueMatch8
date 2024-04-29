# LeagueMatch8
LeagueMatch8はリーグ戦の対戦表を作成してくれるライブラリです。

# 使ってみる
対戦表を作りたいチーム名が入った配列を指定してインスタンスを生成。
```php
$leagueMatch = new LeagueMatch8([
  'data' => ["チームA", "チームB", "チームC", "チームD", "チームE"],
]);
```
対戦表を出力します。
```php
$leagueMatch->output();
```
出力結果。
```php
Array
(
    [0] => Array
        (
            [1] => Array
                (
                    [0] => チームB
                    [1] => チームE
                )

            [2] => Array
                (
                    [0] => チームC
                    [1] => チームD
                )

        ) 
続く....
```

# CSV形式で出力
```php
$leagueMatch = new LeagueMatch8([
  'data' => ["チームA", "チームB", "チームC", "チームD", "チームE"],
  'output_type' => 'csv',
  'csv_headers' => ["X日目", "コート東側", "コート西側"],
]);
```
# 設定
| 設定値 | 型 | デフォルト値 | 説明 |
| ---- | ---- | ---- |---- |
| data | Array | なし | 対戦表を作りたいチーム名が入った配列を指定することができます。 |
| output_type | String | 'array' | 'array', 'json', 'csv', 'tsv'の4種類から指定することができます。 |
| data_max_length | Int | 20 | data配列の要素の最大の長さを指定することができます。 |
| data_text_max_length | Int | 50 | data配列の値の最大の長さを指定することができます。 |
| error_message_max_length | Int | 20 | error配列の要素の最大の長さを指定することができます。 |
| csv_headers | Array | なし | output_typeのcsv指定時に出力されるヘッダを配列で指定することができます。要素の長さは3つ必須です。 |
| csv_headr_text_max_length | Int | 20 | csvヘッダ文字の最大の長さを指定することができます。|
| tsv_headers | Array | なし | output_typeのtsv指定時に出力されるヘッダを配列で指定することができます。要素の長さは3つ必須です。 |
| tsv_headr_text_max_length | Int | 20 | tsvヘッダ文字の最大の長さを指定することができます。|


# メソッド
| メソッド名 | 引数 | 戻り値 | 説明 |
| ---- | ---- | ---- | ---- |
| output | なし | Array | 生成されたリーグ表を取得できます。<br>※output_typeの設定により戻り値が変わります。 |
| isValid | なし | Boolean | 設定値が正しいかを検証します。 |
| getErrors | なし | Array | 設定値に誤りがあった場合に、エラーメッセージが入った配列を返します。 |

# ご利用に関して
自由にご利用できますが、バグなどによる損害の責任は負いかねますでご自身の判断でご利用ください。*:smile:*