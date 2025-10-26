<?php

/**
 * Plugin Name:       LitoBlocks Applink
 * Description:       iPhone / iPad / Macアプリや音楽トラック、Apple Booksなどの紹介リンクを簡単に作成できます。
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Takashi Fujiskai
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lito-applink
 *
 * @package           lito-applink
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'inc/define.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-page.php';


function litoal_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'litoal_init' );

/**
 * Categories
 *
 * @param array $categories Categories.
 * @param array $post Post.
 */
function litob_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'lito-blocks', // ブロックカテゴリーのスラッグ.
				'title' => 'LitoBlocks', // ブロックカテゴリーの表示名.
				// 'icon'  => 'wordpress',    //アイコンの指定（Dashicons名）.
			),
		)
	);
}
add_filter( 'block_categories_all', 'litob_categories', 10, 2 );



// オプション値の初期化
function litoal_register_activation() {
	$options = get_option( 'litoal-setting' );

	if ( ! $options ) {
		$default = array(
			'token'   => '11l64V',
			'country' => 'JP',
			'lang'    => 'auto',
		);

		update_option( 'litoal-setting', $default );
	}
}
// プラグイン有効時に実行
register_activation_hook( __FILE__, 'litoal_register_activation' );

function litoal_admin_enqueue_scripts() {
	/**
	 * PHPで生成した値をJavaScriptに渡す
	 * 定数はPHP側で一元管理し、JavaScriptに渡す
	 *
	 * 第1引数: 渡したいJavaScriptの名前（wp_enqueue_scriptの第1引数に書いたもの）
	 * 第2引数: JavaScript内でのオブジェクト名
	 * 第3引数: 渡したい値の配列
	 */
	wp_localize_script(
		'lito-applink-editor-script',
		'litoalAjaxValues',
		array(
			'optionsPageUrl'   => admin_url( 'options-general.php?page=litoal-setting' ),
			'options'          => get_option( 'litoal-setting' ),
			'limitValues'      => LITOAL_LIMIT_VALUES,
			'countryValues'    => LITOAL_COUNTRY_VALUES,
			'langValues'       => LITOAL_LANG_VALUES,
			'countryToLangMap' => LITOAL_COUNTRY_TO_LANG_MAP,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'litoal_admin_enqueue_scripts' );
