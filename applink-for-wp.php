<?php

/**
 * Plugin Name:       Applink for WP
 * Description:       iPhone / iPad / Macアプリや音楽トラック、Apple Booksなどの紹介リンクを簡単に作成できます。
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Takashi Fujiskai
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       applink-for-wp
 *
 * @package           applink-for-wp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'inc/define.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-page.php';


function alfwp_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'alfwp_init' );

/**
 * Categories
 *
 * @param array $categories Categories.
 * @param array $post Post.
 */
function alfwpb_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'alfwp-blocks', // ブロックカテゴリーのスラッグ.
				'title' => 'AlfwpBlocks', // ブロックカテゴリーの表示名.
				// 'icon'  => 'wordpress',    //アイコンの指定（Dashicons名）.
			),
		)
	);
}
add_filter( 'block_categories_all', 'alfwpb_categories', 10, 2 );



// オプション値の初期化
function alfwp_register_activation() {
	$options = get_option( 'alfwp-setting' );

	if ( ! $options ) {
		$default = array(
			'token'   => '11l64V',
			'country' => 'JP',
			'lang'    => 'auto',
		);

		update_option( 'alfwp-setting', $default );
	}
}
// プラグイン有効時に実行
register_activation_hook( __FILE__, 'alfwp_register_activation' );

function alfwp_admin_enqueue_scripts() {
	// PHPからJavaScriptに値を渡す
	wp_add_inline_script(
		'wp-block-editor',
		'const alfwpAjaxValues = ' . wp_json_encode(
			array(
				'optionsPageUrl'   => admin_url( 'options-general.php?page=applink-for-wp' ),
				'options'          => get_option( 'alfwp-setting' ),
				'limitValues'      => ALFWP_LIMIT_VALUES,
				'countryValues'    => ALFWP_COUNTRY_VALUES,
				'langValues'       => ALFWP_LANG_VALUES,
				'countryToLangMap' => ALFWP_COUNTRY_TO_LANG_MAP,
			)
		) . ';',
		'before'
	);
}
add_action( 'admin_enqueue_scripts', 'alfwp_admin_enqueue_scripts' );
