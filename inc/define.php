<?php

// 検索結果数オプション
define(
	'LITOAL_LIMIT_VALUES',
	array(
		array(
			'value' => 10,
			'label' => '10件',
		),
		array(
			'value' => 25,
			'label' => '25件',
		),
		array(
			'value' => 50,
			'label' => '50件',
		),
		array(
			'value' => 100,
			'label' => '100件',
		),
		array(
			'value' => 200,
			'label' => '200件',
		),
	)
);

// 言語設定オプション（自動と英語のみ）
define(
	'LITOAL_LANG_VALUES',
	array(
		array(
			'value' => 'auto',
			'label' => '自動（選択したストアに応じて）',
		),
		array(
			'value' => 'en_us',
			'label' => '英語',
		),
	)
);

// 国コードから言語コードへのマッピング
define(
	'LITOAL_COUNTRY_TO_LANG_MAP',
	array(
		'JP' => 'ja_jp',
		'KR' => 'ko_kr',
		'CN' => 'zh_cn',
		'TW' => 'zh_tw',
		'HK' => 'zh_tw',
		'US' => 'en_us',
		'GB' => 'en_us',
		'CA' => 'en_us',
		'AU' => 'en_us',
		'SG' => 'en_us',
		'TH' => 'en_us',
		'IN' => 'en_us',
		'DE' => 'de_de',
		'FR' => 'fr_fr',
		'BR' => 'pt_pt',
	)
);

// 国設定オプション（主要15カ国、使用頻度順）
define(
	'LITOAL_COUNTRY_VALUES',
	array(
		// アジア主要国
		array(
			'value' => 'JP',
			'label' => '日本',
		),
		array(
			'value' => 'KR',
			'label' => '韓国',
		),
		array(
			'value' => 'CN',
			'label' => '中国',
		),
		array(
			'value' => 'TW',
			'label' => '台湾',
		),
		array(
			'value' => 'HK',
			'label' => '香港',
		),
		// 英語圏主要国
		array(
			'value' => 'US',
			'label' => '米国',
		),
		array(
			'value' => 'GB',
			'label' => '英国',
		),
		array(
			'value' => 'CA',
			'label' => 'カナダ',
		),
		array(
			'value' => 'AU',
			'label' => 'オーストラリア',
		),
		// その他主要国
		array(
			'value' => 'SG',
			'label' => 'シンガポール',
		),
		array(
			'value' => 'TH',
			'label' => 'タイ',
		),
		array(
			'value' => 'IN',
			'label' => 'インド',
		),
		array(
			'value' => 'DE',
			'label' => 'ドイツ',
		),
		array(
			'value' => 'FR',
			'label' => 'フランス',
		),
		array(
			'value' => 'BR',
			'label' => 'ブラジル',
		),
	)
);
