<?php //子テーマ用関数

//子テーマ用のビジュアルエディタースタイルを適用
add_editor_style();

//以下に子テーマ用の関数を書く

/**
 * カテゴリー登録・編集ページのカスタマイズ 下記を追加する
 * * トップページでの優先度
 * * トープページに表示するか否か
 * */
add_action ('category_add_form_fields', 'yhei_category_add_fields' );
function yhei_category_add_fields( $tag ) {
    $t_id = $tag->term_id;
    // 値があればvalueに入れるのでtermmetaを取得
    $cat_meta = get_term_meta($t_id);
?>

<div class="form-field form-wrap">
  <label for="yhei_category_top_show">トップページに表示する</label>
  <input type="checkbox" name="Cat_meta[yhei_category_top_show]" id="extra_text" size="25" value="1" <?php if(isset ( $cat_meta['yhei_category_top_show']) && intval($cat_meta['yhei_category_top_show'][0])=== 1) echo 'checked="checked"'; ?> />
  <p class="description">チェックするとトップにそのカテゴリーの一覧が表示されます</p>
</div>
<div class="form-field form-wrap">
  <label for="yhei_category_weight">トップページでの優先度(子テーマ設定)</label>
  <input type="text" name="Cat_meta[yhei_category_weight]" id="extra_text" size="25" value="<?php if(isset ( $cat_meta['yhei_category_weight'])) echo esc_html($cat_meta['yhei_category_weight'][0]) ?>" />
  <p class="description">値が小さいほど最初に表示</p>
</div>

<?php
}
add_action ( 'edit_category_form_fields', 'yhei_category_edit_fields');
function yhei_category_edit_fields( $tag ) {
    $t_id = $tag->term_id;
    // 値があればvalueに入れるのでtermmetaを取得
    $cat_meta = get_term_meta($t_id);
?>
<tr class="form-field">
  <th><label for="yhei_category_top_show">トップページに表示する</label></th>
  <td>
    <input type="checkbox" name="Cat_meta[yhei_category_top_show]" id="extra_text" size="25" value="1" <?php if(isset ( $cat_meta['yhei_category_top_show']) && intval($cat_meta['yhei_category_top_show'][0])=== 1) echo 'checked="checked"'; ?> />
    <p class="description">チェックするとトップにそのカテゴリーの一覧が表示されます</p>
  </td>
</tr>
<tr class="form-field">
  <th><label for="yhei_category_weight">トップページでの優先度(子テーマ設定)</label></th>
  <td>
    <input type="text" name="Cat_meta[yhei_category_weight]" id="extra_text" size="25" value="<?php if(isset ( $cat_meta['yhei_category_weight'])) echo esc_html($cat_meta['yhei_category_weight'][0]) ?>" />
    <p class="description">値が小さいほど最初に表示</p>
  </td>
</tr>

<?php
}
// カテゴリー登録・編集ページの保存処理
add_action ( 'create_category', 'yhei_insert_category_fields');
add_action ( 'edited_term', 'yhei_update_category_fields');
function yhei_insert_category_fields( $term_id ) {
  yhei_save_category_fields($term_id, false);
}
function yhei_update_category_fields( $term_id ) {
  yhei_save_category_fields($term_id, true);
}
function yhei_save_category_fields( $term_id, $update=false ) {
  if ( isset( $_POST['Cat_meta'] ) ) {
    $cat_keys = array_keys($_POST['Cat_meta']);
    foreach ($cat_keys as $key){
      if (isset($_POST['Cat_meta'][$key])){
        _save_category_field($term_id, $key, $_POST['Cat_meta'][$key], $update);
      }
    }
    // チェックボックスフィールドの保存
    save_category_check_fields($term_id, $cat_keys, $update);
  }
}
/**
 * カテゴリー編集ページのチェックボックス系の保存
 * @param int $term_id
 * @param array $checked_keys
 * @param bool $update
 * */
function save_category_check_fields($term_id, $checked_keys, $update=false) {
  // トップページに表示する
  if( in_array('yhei_category_top_show', $checked_keys) ) {
    _save_category_field($term_id, 'yhei_category_top_show', $_POST['Cat_meta']['yhei_category_top_show'], $update);
  } else {
    _save_category_field($term_id, 'yhei_category_top_show', 0, $update);
  }
}
function _save_category_field($term_id, $key, $value, $update=false) {
  if($update) {
    update_term_meta($term_id, $key, $value);
  } else {
    add_term_meta($term_id, $key, $value);
  }
}


/**
 * トップページに表示するカテゴリーの優先度取得
 * */
function get_category_weight_in_top($term_id) {
  $cat_data = get_term_meta($term_id);
  if( !isset($cat_data['yhei_category_weight'][0]) ) {
    return 0;
  }
  
  $category_weight = $cat_data['yhei_category_weight'][0];
  if( empty($category_weight) ) {
    return 0;
  }
  
  if( !is_numeric($category_weight) ) {
    return 0;
  }
  
  return intval($category_weight);
}
/**
 * トップページに表示するか否か取得
 * */
function is_category_shown_in_top($term_id) {
  $cat_data = get_term_meta($term_id);
  if( !isset($cat_data['yhei_category_top_show'][0]) ) {
    return 0;
  }
  
  return intval($cat_data['yhei_category_top_show'][0]);
}