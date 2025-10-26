<?php

require_once plugin_dir_path( __FILE__ ) . 'define.php';

// 管理画面に設定画面を追加
function litoal_add_admin_page() {
	add_options_page(
		'LitoBlocks Applink',
		'LitoBlocks Applink',
		'manage_options',
		'litoal-setting',
		'litoal_options_page_html'
	);
}
add_action( 'admin_menu', 'litoal_add_admin_page' );

// ページの内容
function litoal_options_page_html() {
	?>
	<div class="wrap">
	<h2>LitoBlocks Applink</h2>

	<?php
	global $parent_file;
	if ( $parent_file != 'options-general.php' ) {
		require ABSPATH . 'wp-admin/options-head.php';
	}
	?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'litoal-setting' );
		do_settings_sections( 'litoal-setting' );
		submit_button();
		?>
	</form>
	</div>
	<?php
}

// ページの初期化
function litoal_page_init() {
	register_setting( 'litoal-setting', 'litoal-setting', 'sanitize' );
	add_settings_section( 'litoal-setting-section-id', '', '', 'litoal-setting' );

	add_settings_field( 'token', 'PHGトークン', 'litoal_token_callback', 'litoal-setting', 'litoal-setting-section-id' );
	add_settings_field( 'nocss', 'プラグインのCSSを使わない', 'litoal_nocss_callback', 'litoal-setting', 'litoal-setting-section-id' );
	add_settings_field( 'limit', 'デフォルトの検索結果数', 'litoal_limit_callback', 'litoal-setting', 'litoal-setting-section-id' );
	add_settings_field( 'country', 'デフォルトの検索対象ストア', 'litoal_country_callback', 'litoal-setting', 'litoal-setting-section-id' );
	add_settings_field( 'lang', 'デフォルトの表示言語', 'litoal_lang_callback', 'litoal-setting', 'litoal-setting-section-id' );
}
add_action( 'admin_init', 'litoal_page_init' );

// トークンの設定セクション
function litoal_token_callback() {
	$options = get_option( 'litoal-setting' );
	$token   = isset( $options['token'] ) ? $options['token'] : '';
	printf( '<input type="text" name="litoal-setting[token]" size="30" value="%s">', esc_attr( $token ) );
}

// CSSの設定セクション
function litoal_nocss_callback() {
	$options = get_option( 'litoal-setting' );
	$checked = isset( $options['nocss'] ) ? checked( $options['nocss'], 1, false ) : '';
	printf( '<input type="checkbox" id="nocss" name="litoal-setting[nocss]" value="1" %s>', $checked );
}

// 検索結果数の設定セクション
function litoal_limit_callback() {
	$options    = get_option( 'litoal-setting' );
	$option_val = isset( $options['limit'] ) ? $options['limit'] : 10;
	$values     = array( 10, 25, 50, 100, 200 );

	echo '<select name="litoal-setting[limit]">';
	foreach ( $values as $val ) {
		printf( '<option value="%1$d" %2$s>%1$d</option>', $val, selected( $option_val, $val, false ) );
	}
	echo '</select>';
}

// 国の設定セクション
function litoal_country_callback() {
	$options    = get_option( 'litoal-setting' );
	$option_val = isset( $options['country'] ) ? $options['country'] : '';

	echo '<select name="litoal-setting[country]">';
	foreach ( LITOAL_COUNTRY_VALUES as $item ) {
		printf( '<option value="%s" %s>%s</option>', $item['value'], selected( $option_val, $item['value'], false ), $item['label'] );
	}
	echo '</select>';
}

// 言語の設定セクション
function litoal_lang_callback() {
	$options    = get_option( 'litoal-setting' );
	$option_val = isset( $options['lang'] ) ? $options['lang'] : '';

	echo '<select name="litoal-setting[lang]">';
	foreach ( LITOAL_LANG_VALUES as $item ) {
		printf( '<option value="%s" %s>%s</option>', $item['value'], selected( $option_val, $item['value'], false ), $item['label'] );
	}
	echo '</select>';
}
