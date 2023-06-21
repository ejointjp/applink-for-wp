<?php
/*!
Plugin Name: Applink Block for WP
Plugin URI: https://e-joint.jp/works/applink-block-for-wp
Description: プラグインの説明
Author: Takashi Fujisaki
Version: 0.1.0
Author URI: http://e-joint.jp
License:     GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
WP Blogcard is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Blogcard is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Blogcard. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

include_once plugin_dir_path(__FILE__) . 'define.php';
include plugin_dir_path(__FILE__) . 'admin-page.php';


// プラグイン有効時に実行
if (function_exists('wpalb_register_activation')) {
  register_activation_hook(__FILE__, 'wpalb_register_activation');
}

// オプション値の初期化
function wpalb_register_activation()
{
  $options = get_option('wpalb-setting');

  if (!$options) {
    $default = [
      'token' => '11l64V',
      'country' => 'JP',
      'lang' => 'ja_jp'
    ];

    update_option('wpalb-setting', $default);
  }
}

/**
 * ブロックを登録
 */
function wpalb_register_block()
{
  // Main JS
  wp_register_script(
    'wp-applink-block-editor',
    plugins_url('dist/block.js', __FILE__),
    ['wp-element', 'wp-block-editor', 'wp-blocks', 'wp-components'],
    filemtime(plugin_dir_path(__FILE__) . 'dist/block.js'),
    true
  );

  // JS for frontend
  wp_register_script(
    'wp-applink-block',
    plugins_url('dist/front.js', __FILE__),
    [],
    filemtime(plugin_dir_path(__FILE__) . 'dist/front.js'),
    true
  );

  // エディタ用CSS
  wp_register_style(
    'wp-applink-block-editor',
    plugins_url('dist/editor-style.css', __FILE__),
    [],
    filemtime(plugin_dir_path(__FILE__) . 'dist/editor-style.css'),
    'all'
  );

  $options = get_option('wpalb-setting');
  if (!isset($options['nocss'])) {
    // フロント・エディタ両方用CSS
    wp_register_style(
      'wp-applink-block',
      plugins_url('dist/style.css', __FILE__),
      [],
      filemtime(plugin_dir_path(__FILE__) . 'dist/style.css'),
      'all'
    );
  }

  // ブロックを登録
  register_block_type('su/applink', [
    'script'        => 'wp-applink-block',
    'editor_script' => 'wp-applink-block-editor',
    'style'         => 'wp-applink-block',
    'editor_style'  => 'wp-applink-block-editor'
  ]);
}
add_action('init', 'wpalb_register_block');

// ブロックのカテゴリー登録
if (!function_exists('wpalb_su_categories')) {
  function wpalb_su_categories($categories, $post)
  {
    return array_merge(
      $categories,
      [
        [
          'slug'  => 'su',   // ブロックカテゴリーのスラッグ.
          'title' => 'su blocks'  // ブロックカテゴリーの表示名.
        ],
      ]
    );
  }
  add_filter('block_categories_all', 'wpalb_su_categories', 10, 2);
}


function wpalb_admin_enqueue_scripts()
{
  /**
   * PHPで生成した値をJavaScriptに渡す
   *
   * 第1引数: 渡したいJavaScriptの名前（wp_enqueue_scriptの第1引数に書いたもの）
   * 第2引数: JavaScript内でのオブジェクト名
   * 第3引数: 渡したい値の配列
   */
  wp_localize_script(
    'wp-applink-block-editor',
    'wpalbAjaxValues',
    [
      'api' => admin_url('admin-ajax.php'),
      'action' => 'wpalb-action',
      'nonce' => wp_create_nonce('wpalb-ajax'),
      'optionsPageUrl' => admin_url('options-general.php?page=wpalb-setting'),
      'options' => get_option('wpalb-setting'),
      'limitValues' => WPALB_LIMIT_VALUES,
      'countryValues' => WPALB_COUNTRY_VALUES,
      'langValues' => WPALB_LANG_VALUES
    ]
  );
}
add_action('admin_enqueue_scripts', 'wpalb_admin_enqueue_scripts');


// Ajaxで返す値
function wpalb_ajax()
{
  if (wp_verify_nonce($_POST['nonce'], 'wpalb-ajax')) {

    $URL = $_POST['url']; //取得したいサイトのURL
    echo file_get_contents($URL);
    die();
  }
}
add_action('wp_ajax_wpalb-action', 'wpalb_ajax');
