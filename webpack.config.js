const path = require('path')
const CompressionPlugin = require('compression-webpack-plugin')
const zopfli = require('node-zopfli')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')

// 共通のプラグイン
const commonPlugins = [
  // ビルドの度にビルドフォルダーを削除する
  new CleanWebpackPlugin(),

  // CSSファイルを別ファイルで出力する
  new MiniCssExtractPlugin({
    filename: '[name].css'
  }),

  // 空のjsファイルを削除する
  new RemoveEmptyScriptsPlugin()
]

const config = {
  cache: {
    type: 'filesystem',
    buildDependencies: {
      config: [__filename]
    }
  },

  entry: {
    front: path.resolve(__dirname, 'src', 'js', 'front.js'),
    block: path.resolve(__dirname, 'src', 'js', 'block.js'),
    style: path.resolve(__dirname, 'src', 'css', 'style.css'),
    'editor-style': path.resolve(__dirname, 'src', 'css', 'editor-style.css')

  },

  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/dist'),
    // 本番のアセットパスを指定
    publicPath: '',
    // アセットを書き出すパス
    assetModuleFilename: 'assets/[name][ext][query]'
  },

  devtool: 'source-map',

  module: {
    rules: [
      {
        test: /\.(jsx?)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              '@babel/preset-env',
              [
                '@babel/preset-react',
                {
                // 通常は'React.createElement.xxxx'を記述しますが、WordPressのブロックエディターはWordPress用のReactを使用するため、以下のように記述します
                  pragma: 'wp.element.createElement',
                  pragmaFrag: 'wp.element.Fragment'
                }
              ]
            ]
          }
        }
      },
      {
        test: /\.css$/,
        use: [
          { loader: MiniCssExtractPlugin.loader },
          { loader: 'css-loader' },
          { loader: 'postcss-loader' }
        ]
      },
      {
        test: /\.(ico|svg|jpe?g|png|webp)$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 4 * 1024 // <--- 4kb
          }
        }
      }
    ]
  },

  resolve: {
    extensions: ['', '.js', '.jsx', '.ts', '.tsx', '.css']
  }
}

module.exports = (env, options) => {
  const inProduction = options.mode === 'production'
  if (inProduction) {
    config.plugins = [
      ...commonPlugins,

      // GZIP生成
      new CompressionPlugin({
        test: /\.(css)|(js)$/,
        exclude: /\.map$/,
        algorithm (input, compressionOptions, callback) {
          return zopfli.gzip(input, compressionOptions, callback)
        },
        threshold: 8192
      })
    ]
  } else {
    config.plugins = [
      ...commonPlugins
    ]
  }

  return config
}
