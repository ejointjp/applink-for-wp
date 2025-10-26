<?php

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
	if ( 'options-general.php' !== $parent_file ) {
		require_once ABSPATH . 'wp-admin/options-head.php';
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

// オプション値のサニタイズ
function litoal_sanitize_options( $input ) {
	$sanitized = array();

	// トークンのサニタイズ（英数字のみ許可）
	if ( isset( $input['token'] ) ) {
		$sanitized['token'] = sanitize_text_field( $input['token'] );
	}

	// nocssのサニタイズ（1 または 空）
	$sanitized['nocss'] = isset( $input['nocss'] ) ? 1 : 0;

	// limitのサニタイズ（許可された値のみ）
	$allowed_limits = array( 10, 25, 50, 100, 200 );
	if ( isset( $input['limit'] ) && in_array( (int) $input['limit'], $allowed_limits, true ) ) {
		$sanitized['limit'] = (int) $input['limit'];
	} else {
		$sanitized['limit'] = 10; // デフォルト値
	}

	// countryのサニタイズ（許可された値のみ）
	$allowed_countries = array( 'JP', 'KR', 'CN', 'TW', 'HK', 'US', 'GB', 'CA', 'AU', 'SG', 'TH', 'IN', 'DE', 'FR', 'BR' );
	if ( isset( $input['country'] ) && in_array( $input['country'], $allowed_countries, true ) ) {
		$sanitized['country'] = sanitize_text_field( $input['country'] );
	} else {
		$sanitized['country'] = 'JP'; // デフォルト値
	}

	// langのサニタイズ（許可された値のみ）
	$allowed_langs = array( 'auto', 'en_us' );
	if ( isset( $input['lang'] ) && in_array( $input['lang'], $allowed_langs, true ) ) {
		$sanitized['lang'] = sanitize_text_field( $input['lang'] );
	} else {
		$sanitized['lang'] = 'auto'; // デフォルト値
	}

	return $sanitized;
}

// ページの初期化
function litoal_page_init() {
	register_setting(
		'litoal-setting',
		'litoal-setting',
		array(
			'sanitize_callback' => 'litoal_sanitize_options',
		)
	);
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
