import { useBlockProps, PlainText, InspectorControls } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';
import { PanelBody, SelectControl, BaseControl } from '@wordpress/components';

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
  api,
  action,
  nonce,
  options,
  optionsPageUrl,
  limitValues,
  countryValues,
  langValues,
  // eslint-disable-next-line no-undef
} = litoalAjaxValues;

const edit = (props) => {
  const blockProps = useBlockProps({ className: 'wp-applink-wrapper' });
  const { attributes, setAttributes } = props;
  const { app, entity } = attributes;
  const [result, setResult] = useState({});
  const [term, setTerm] = useState('');
  const [tempTerm, setTempTerm] = useState('');
  // const [entity, setEntity] = useState('software');
  const [state, setState] = useState('');
  const [limit, setLimit] = useState(options.limit || 10);
  const [lang, setLang] = useState(options.lang || 'ja_JP');
  const [country, setCountry] = useState(options.country || 'JP');

  const fetchData = async () => {
    const searchParams = new URLSearchParams();
    searchParams.append('lang', lang);
    searchParams.append('country', country);
    searchParams.append('entity', entity);
    searchParams.append('term', term);
    searchParams.append('limit', limit);
    searchParams.append('at', options.token || '11l64V');

    const url = 'https://itunes.apple.com/search?' + searchParams.toString();
    const params = new URLSearchParams();
    params.append('action', action);
    params.append('nonce', nonce);
    params.append('url', url);

    setAttributes({ app: {} });

    try {
      const res = await fetch(api, { method: 'post', body: params });
      const result = await res.json();
      await setResult(result);
      setState('result-success');
    } catch (e) {
      setState('result-error');
      console.error(e);
    }
  };

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
    else if (item.wrapperType === 'collection' && item.collectionType === 'Album')
      return musicAlbumAtts(item);
    else return appAtts(item);
  };

  const ResultList = () => {
    const list = result.results.map((item, i) => {
      const app = itemAtts(item);

      return (
        <div
          className={`wp-applink-item wp-applink-${item.kind}`}
          key={i}
          onClick={() => {
            setAttributes({ app: app });
          }}
        >
          <div className="wp-applink-figure">
            <img className="wp-applink-img" src={app.iconUrl} />
          </div>
          <div className="wp-applink-content">
            <div className="wp-applink-title">{app.title}</div>
            <div className="wp-applink-artist">{app.artist}</div>
          </div>
          <button
            className="components-button is-secondary"
            onClick={() => {
              setAttributes({ app: app });
            }}
          >
            選択
          </button>
        </div>
      );
    });

    return (
      <>
        <div className="wp-applink-result-num">検索結果{result.resultCount} 件</div>
        {result.resultCount > 0 && <div className="wp-applink-list">{list}</div>}
      </>
    );
  };

  const InfoText = (props) => {
    return <div className="">{props.children}</div>;
  };

  const Display = () => {
    switch (state) {
      case 'search':
        return (
          <ReactLoading class="" type="spin" color="rgb(253 210 59)" width="20px" height="20px" />
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
  }, [term, entity, limit, lang, country]);

  useEffect(() => {
    if (hasApp) setResult({});
  }, [app]);

  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title="検索条件設定">
          <BaseControl label="">
            <SelectControl
              label="検索結果数"
              value={limit}
              onChange={(value) => setLimit(value)}
              options={limitValues}
            />

            <SelectControl
              label="検索対象ストア"
              value={country}
              onChange={(value) => setCountry(value)}
              options={countryValues}
            />

            <SelectControl
              label="表示言語"
              value={lang}
              onChange={(value) => setLang(value)}
              options={langValues}
            />

            <p>
              <a
                href={optionsPageUrl}
                target="_blank"
                rel="nofollow noreferrer noopener"
                className="components-button is-tertiary"
              >
                設定ページでデフォルト値を設定する
              </a>
            </p>
          </BaseControl>
        </PanelBody>
      </InspectorControls>

      <div className="wp-applink-control">
        <SelectControl
          className="wp-applink-type"
          value={entity}
          onChange={(value) => {
            // setEntity(value);
            setAttributes({ entity: value });
            setTermIfChanged();
          }}
          options={entityOptions}
        />

        <PlainText
          className="wp-applink-input"
          tagName="input"
          placeholder="検索ワードを入力してEnter"
          value={tempTerm}
          onChange={(value) => setTempTerm(value)}
          onKeyPress={onKeyPress}
        />
      </div>

      <Display />
      {hasApp && <Applink app={app} />}
      {hasResult && <ResultList />}
    </div>
  );
};

export default edit;
