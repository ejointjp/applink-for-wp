import {
	useBlockProps,
	PlainText,
	InspectorControls,
} from '@wordpress/block-editor';
import { useState, useEffect, useCallback } from '@wordpress/element';
import {
	PanelBody,
	SelectControl,
	BaseControl,
	Button,
} from '@wordpress/components';

import ReactLoading from 'react-loading';
import Applink from './components/Applink';
import entityOptions from './entity-options';
import {
	appAtts,
	macAppAtts,
	movieAtts,
	ebookAtts,
	podcastAtts,
	audiobookAtts,
	musicTrackAtts,
	musicAlbumAtts,
	musicVideoAtts,
} from './app-attributes';

// PHPから取得した変数
// eslint-disable-next-line no-undef
const {
	options,
	optionsPageUrl,
	limitValues,
	countryValues,
	langValues,
	countryToLangMap,
} =
	// eslint-disable-next-line no-undef
	sualAjaxValues;

const edit = (props) => {
	const blockProps = useBlockProps({ className: 'sual-editor-wrapper' });
	const { attributes, setAttributes, isSelected } = props;
	const { app, entity } = attributes;
	const [result, setResult] = useState({});
	const [term, setTerm] = useState('');
	const [tempTerm, setTempTerm] = useState('');
	const [state, setState] = useState('');
	const [limit, setLimit] = useState(options.limit || 10);
	const [lang, setLang] = useState(options.lang || 'auto');
	const [country, setCountry] = useState(options.country || 'JP');

	const fetchData = useCallback(async () => {
		const searchParams = new URLSearchParams();
		// 'auto'が選択されている場合は選択した国に応じた言語を使用
		const actualLang =
			lang === 'auto' ? countryToLangMap[country] || 'en_us' : lang;
		searchParams.append('lang', actualLang);
		searchParams.append('country', country);
		searchParams.append('entity', entity);
		searchParams.append('term', term);
		searchParams.append('limit', limit);
		searchParams.append('at', options.token || '11l64V');

		const url = 'https://itunes.apple.com/search?' + searchParams.toString();

		setAttributes({ app: {} });

		try {
			// iTunes Search APIを直接呼び出し
			const res = await fetch(url);
			const result = await res.json();
			await setResult(result);
			setState('result-success');
		} catch (e) {
			setState('result-error');
			console.error(e);
		}
	}, [
		lang,
		country,
		entity,
		term,
		limit,
		options.token,
		countryToLangMap,
		setAttributes,
	]);

	// Termが変更されている場合はTermを更新
	const setTermIfChanged = () => {
		setTerm(tempTerm);
	};

	const onKeyPress = (e) => {
		// URL入力してEnterを押したら
		if (e.key === 'Enter') {
			e.preventDefault();
			setTermIfChanged();
		}
	};

	// apiからの返却があった場合 検索結果0もtrue
	const hasResult = Object.keys(result).length > 0;
	// アプリが登録されている場合
	const hasApp = Object.keys(app).length > 0;

	// 取得したデータの種類によって表示する内容を選別する
	const itemAtts = (item) => {
		if (item.kind === 'software') return appAtts(item);
		else if (item.kind === 'mac-software') return macAppAtts(item);
		else if (item.kind === 'feature-movie') return movieAtts(item);
		else if (item.kind === 'ebook') return ebookAtts(item);
		else if (item.kind === 'podcast') return podcastAtts(item);
		else if (item.kind === 'song') return musicTrackAtts(item);
		else if (item.kind === 'music-video') return musicVideoAtts(item);
		else if (item.wrapperType === 'audiobook') return audiobookAtts(item);
		else if (
			item.wrapperType === 'collection' &&
			item.collectionType === 'Album'
		)
			return musicAlbumAtts(item);
		else return appAtts(item);
	};

	const ResultList = () => {
		const list = result.results.map((item, i) => {
			const app = itemAtts(item);

			return (
				<div
					className={`sual-editor-item sual-editor-${item.kind}`}
					key={i}
					onClick={() => {
						setAttributes({ app: app });
					}}
				>
					<div className='sual-editor-figure'>
						<img
							className='sual-editor-img'
							src={app.iconUrl}
							alt={app.title}
						/>
					</div>
					<div className='sual-editor-content'>
						<div className='sual-editor-title'>{app.title}</div>
						<div className='sual-editor-artist'>{app.artist}</div>
					</div>
					<Button
						variant='secondary'
						size='small'
						onClick={() => {
							setAttributes({ app: app });
						}}
					>
						選択
					</Button>
				</div>
			);
		});

		return (
			<>
				<div className='sual-editor-result-num'>
					検索結果{result.resultCount} 件
				</div>
				{result.resultCount > 0 && (
					<div className='sual-editor-list'>{list}</div>
				)}
			</>
		);
	};

	const InfoText = (props) => {
		return <div className=''>{props.children}</div>;
	};

	const Display = () => {
		switch (state) {
			case 'search':
				return (
					<ReactLoading
						class=''
						type='spin'
						color='rgb(253 210 59)'
						width='20px'
						height='20px'
					/>
				);

			case 'result-error':
				return <InfoText>データの取得に失敗しました</InfoText>;

			default:
				return '';
		}
	};

	// Termが有効ならfetch
	useEffect(() => {
		if (term !== '') {
			setState('search');
			setResult({});
			fetchData();
		}
	}, [term, entity, limit, lang, country, fetchData]);

	useEffect(() => {
		if (hasApp) setResult({});
	}, [app]);

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody title='検索条件設定'>
					<BaseControl label=''>
						<SelectControl
							label='検索結果数'
							value={limit}
							onChange={(value) => setLimit(value)}
							options={limitValues}
						/>

						<SelectControl
							label='検索対象ストア'
							value={country}
							onChange={(value) => setCountry(value)}
							options={countryValues}
						/>

						<SelectControl
							label='表示言語'
							value={lang}
							onChange={(value) => setLang(value)}
							options={langValues}
						/>

						<p>
							<Button
								href={optionsPageUrl}
								target='_blank'
								rel='nofollow noreferrer noopener'
								variant='tertiary'
							>
								設定ページでデフォルト値を設定する
							</Button>
						</p>
					</BaseControl>
				</PanelBody>
			</InspectorControls>

			{isSelected && (
				<div className='sual-editor-control'>
					<SelectControl
						className='sual-editor-type'
						value={entity}
						onChange={(value) => {
							// setEntity(value);
							setAttributes({ entity: value });
							setTermIfChanged();
						}}
						options={entityOptions}
					/>

					<PlainText
						className='sual-editor-input'
						tagName='input'
						placeholder='検索ワードを入力してEnter'
						value={tempTerm}
						onChange={(value) => setTempTerm(value)}
						onKeyPress={onKeyPress}
					/>
				</div>
			)}

			<Display />
			{hasApp && <Applink app={app} isEditor={true} />}
			{hasResult && <ResultList />}
		</div>
	);
};

export default edit;
