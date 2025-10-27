<?php

// 管理画面に設定画面を追加
function alfwp_add_admin_page() {
	add_options_page(
		'Applink for WP',
		'Applink for WP',
		'manage_options',
		'applink-for-wp',
		'alfwp_options_page_html'
	);
}
add_action( 'admin_menu', 'alfwp_add_admin_page' );

// ページの内容
function alfwp_options_page_html() {
	?>
	<div class="wrap">
	<h2>Applink for WP</h2>

	<?php
	global $parent_file;
	if ( 'options-general.php' !== $parent_file ) {
		require_once ABSPATH . 'wp-admin/options-head.php';
	}
	?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'alfwp-setting' );
		do_settings_sections( 'alfwp-setting' );
		submit_button();
		?>
	</form>
	</div>
	<?php
}

// オプション値のサニタイズ
function alfwp_sanitize_options( $input ) {
	$sanitized = array();

	// トークンのサニタイズ（英数字のみ許可）
	if ( isset( $input['token'] ) ) {
		$sanitized['token'] = sanitize_text_field( $input['token'] );
	}

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
function alfwp_page_init() {
	register_setting(
		'alfwp-setting',
		'alfwp-setting',
		array(
			'sanitize_callback' => 'alfwp_sanitize_options',
		)
	);
	add_settings_section( 'alfwp-setting-section-id', '', '', 'alfwp-setting' );

	add_settings_field( 'token', 'PHGトークン', 'alfwp_token_callback', 'alfwp-setting', 'alfwp-setting-section-id' );
	add_settings_field( 'limit', 'デフォルトの検索結果数', 'alfwp_limit_callback', 'alfwp-setting', 'alfwp-setting-section-id' );
	add_settings_field( 'country', 'デフォルトの検索対象ストア', 'alfwp_country_callback', 'alfwp-setting', 'alfwp-setting-section-id' );
	add_settings_field( 'lang', 'デフォルトの表示言語', 'alfwp_lang_callback', 'alfwp-setting', 'alfwp-setting-section-id' );
}
add_action( 'admin_init', 'alfwp_page_init' );

// トークンの設定セクション
function alfwp_token_callback() {
	$options = get_option( 'alfwp-setting' );
	$token   = isset( $options['token'] ) ? $options['token'] : '';
	printf( '<input type="text" name="alfwp-setting[token]" size="30" value="%s">', esc_attr( $token ) );
}

// 検索結果数の設定セクション
function alfwp_limit_callback() {
	$options    = get_option( 'alfwp-setting' );
	$option_val = isset( $options['limit'] ) ? $options['limit'] : 10;
	$values     = array( 10, 25, 50, 100, 200 );

	echo '<select name="alfwp-setting[limit]">';
	foreach ( $values as $val ) {
		printf( '<option value="%1$d" %2$s>%1$d</option>', $val, selected( $option_val, $val, false ) );
	}
	echo '</select>';
}

// 国の設定セクション
function alfwp_country_callback() {
	$options    = get_option( 'alfwp-setting' );
	$option_val = isset( $options['country'] ) ? $options['country'] : 'JP';

	echo '<select name="alfwp-setting[country]">';
	foreach ( ALFWP_COUNTRY_VALUES as $item ) {
		printf( '<option value="%s" %s>%s</option>', $item['value'], selected( $option_val, $item['value'], false ), $item['label'] );
	}
	echo '</select>';
	echo '<p class="description">検索するApp Storeの国を選択します。</p>';
}

// 言語の設定セクション
function alfwp_lang_callback() {
	$options    = get_option( 'alfwp-setting' );
	$option_val = isset( $options['lang'] ) ? $options['lang'] : 'auto';

	echo '<select name="alfwp-setting[lang]">';
	foreach ( ALFWP_LANG_VALUES as $item ) {
		printf( '<option value="%s" %s>%s</option>', $item['value'], selected( $option_val, $item['value'], false ), $item['label'] );
	}
	echo '</select>';
	echo '<p class="description">Applinkの表示言語を選択します。</p>';
}
