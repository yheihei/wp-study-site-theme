<?php //子テーマ用関数

//子テーマ用のビジュアルエディタースタイルを適用
add_editor_style();

//以下に子テーマ用の関数を書く

require_once(__DIR__ . '/config.php');

$category_main = new YheiStudyTheme\CustomCategory();
$category_main->init();
