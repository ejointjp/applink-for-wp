<?php

include_once plugin_dir_path(__FILE__) . 'define.php';

// 管理画面に設定画面を追加
function wpalb_add_admin_page()
{
  add_options_page(
    'Applink Block for WP',
    'Applink Block for WP',
    'manage_options',
    'wpalb-setting',
    'wpalb_options_page_html'
  );
}
add_action('admin_menu', 'wpalb_add_admin_page');

// ページの内容
function wpalb_options_page_html()
{
?>
  <div class="wrap">
    <h2>Applink Block for WP</h2>

    <?php
    global $parent_file;
    if ($parent_file != 'options-general.php') {
      require(ABSPATH . 'wp-admin/options-head.php');
    }
    ?>

    <form method="post" action="options.php">
      <?php
      settings_fields('wpalb-setting');
      do_settings_sections('wpalb-setting');
      submit_button();
      ?>
    </form>

  </div>
<?php
}

// ページの初期化
function wpalb_page_init()
{
  register_setting('wpalb-setting', 'wpalb-setting', 'sanitize');
  add_settings_section('wpalb-setting-section-id', '', '', 'wpalb-setting');

  add_settings_field('token', 'PHGトークン', 'wpalb_token_callback', 'wpalb-setting', 'wpalb-setting-section-id');
  add_settings_field('nocss', 'プラグインのCSSを使わない', 'wpalb_nocss_callback', 'wpalb-setting', 'wpalb-setting-section-id');
  add_settings_field('limit', 'デフォルトの検索結果数', 'wpalb_limit_callback', 'wpalb-setting', 'wpalb-setting-section-id');
  add_settings_field('country', 'デフォルトの検索対象ストア', 'wpalb_country_callback', 'wpalb-setting', 'wpalb-setting-section-id');
  add_settings_field('lang', 'デフォルトの表示言語', 'wpalb_lang_callback', 'wpalb-setting', 'wpalb-setting-section-id');
}
add_action('admin_init', 'wpalb_page_init');

// トークンの設定セクション
function wpalb_token_callback()
{
  $options = get_option('wpalb-setting');
  $token = isset($options['token']) ? $options['token'] : '';
  printf('<input type="text" name="wpalb-setting[token]" size="30" value="%s">', esc_attr($token));
}

// CSSの設定セクション
function wpalb_nocss_callback()
{
  $options = get_option('wpalb-setting');
  $checked = isset($options['nocss']) ? checked($options['nocss'], 1, false) : '';
  printf('<input type="checkbox" id="nocss" name="wpalb-setting[nocss]" value="1" %s>', $checked);
}

// 検索結果数の設定セクション
function wpalb_limit_callback()
{
  $options = get_option('wpalb-setting');
  $option_val = isset($options['limit']) ? $options['limit'] : 10;
  $values = [10, 25, 50, 100, 200];

  echo '<select name="wpalb-setting[limit]">';
  foreach ($values as $val) {
    printf('<option value="%1$d" %2$s>%1$d</option>', $val, selected($option_val, $val, false));
  }
  echo '</select>';
}

// 国の設定セクション
function wpalb_country_callback()
{
  $options = get_option('wpalb-setting');
  $option_val = isset($options['country']) ? $options['country'] : '';

  echo '<select name="wpalb-setting[country]">';
  foreach (WPALB_COUNTRY_VALUES as $item) {
    printf('<option value="%s" %s>%s</option>', $item['value'], selected($option_val, $item['value'], false), $item['label']);
  }
  echo '</select>';
}

// 言語の設定セクション
function wpalb_lang_callback()
{
  $options = get_option('wpalb-setting');
  $option_val = isset($options['lang']) ? $options['lang'] : '';

  echo '<select name="wpalb-setting[lang]">';
  foreach (WPALB_LANG_VALUES as $item) {
    printf('<option value="%s" %s>%s</option>', $item['value'], selected($option_val, $item['value'], false), $item['label']);
  }
  echo '</select>';
}
