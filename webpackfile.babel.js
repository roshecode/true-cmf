'use strict';

import webpack from 'webpack';
import path from 'path';
import ExtractTextPlugin from 'extract-text-webpack-plugin';
import ProgressCallback from './progress-webpack-plugin';
// import StyleLintPlugin from 'stylelint-webpack-plugin';

let NODE_ENV = process.env.NODE_ENV || 'development';

// const extractCSS = new ExtractTextPlugin({filename: 'css/[name].css', allChunks: true});
const extractCSS = new ExtractTextPlugin('css/[name].css');
// const extractDocs = new ExtractTextPlugin('docs/docs.md');

const pathResolve = value => path.resolve(__dirname, value);

export default {
    context: pathResolve('./resources'),

    entry: {
        vendor: ['vue', 'vuex', 'vue-router', 'vue-resource', 'vue-touch'],
        app: './scripts/app',
        style: './styles/style.pcss',
        main: ['./scripts/views/main.js', './styles/themes/default/main.pcss'],
        admin: ['./scripts/views/admin.js', './styles/themes/default/admin.pcss'],
    },

    output: {
        path: pathResolve('./public'),
        publicPath: '/',
        filename: 'js/[name].js',
        // library: '[name]' // add global variable
    },

    resolve: {
        alias: {
            styles: pathResolve('resources/styles'),
            'vue$': 'vue/dist/vue.common.js'
        },
        extensions: ['.html', '.js', '.vue', '.json', '.yml', '.png']
    },

    watch: NODE_ENV === 'development',

    devtool: NODE_ENV === 'development' ? 'inline-source-map' : null,

    module: {
        rules: [
            // {test: /\.js$/, exclude: /node_modules/, loader: 'eslint', enforce: 'pre'},
            // {test: /\.vue$/, exclude: /node_modules/, loader: 'eslint', enforce: 'pre'},

            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: 'babel-loader'
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                // include: path.join(__dirname, 'resources'),
                exclude: /node_modules/,
                options: {
                    loaders: {
                        css: extractCSS.extract({
                            fallback: 'vue-style-loader',
                            use: 'css-loader'
                        }),
                        postcss: extractCSS.extract({
                            fallback: 'vue-style-loader',
                            use: [
                                {
                                    loader: 'css-loader',
                                    options: {
                                        importLoaders: 1
                                    },
                                },
                                'postcss-loader',
                                {
                                    loader: 'sass-resources-loader',
                                    options: {
                                        resources: [
                                            pathResolve('resources/styles/framework/general/**/*.pcss'),
                                        ]
                                    }
                                }
                            ]
                        }),
                        // html: [
                        //     {
                        //         loader: 'vue-html-loader',
                        //         options: {
                        //             minimize: true
                        //         }
                        //     }
                        // ]
                        // docs: extractDocs.extract('raw-loader')
                    },
                    // cssModules: {
                    //     localIdentName: '[path][name]---[local]---[hash:base64:5]',
                    //     camelCase: true
                    // }
                }
            },
            {
                test: /\.html$/,
                use: [
                    {
                        loader: 'html-loader',
                        options: {
                            minimize: true
                        }
                    },
                    'posthtml-loader'
                ]
            },
            {
                test: /\.json/,
                use: 'json-loader'
            },
            {
                test: /\.yml$/,
                use: ['json-loader', 'yaml-loader']
            },
            {
                test: /\.css$/,
                use: extractCSS.extract({
                    fallback: 'style-loader',
                    use: 'css-loader'
                })
            },
            {
                test: /\.pcss$/,
                use: extractCSS.extract({
                    fallback: 'style-loader',
                    use: [
                        {
                            loader: 'css-loader',
                            options: {
                                importLoaders: 1
                            },
                        },
                        'postcss-loader'
                    ]
                })
            },
            {
                test: /\.(gif|jpg|png)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'images/[name].[ext]'
                        }
                    }
                ]
            }
        ]
    },

    // watchOptions: {
    //     agregateTimeout: 100 // set less for more speed
    // },

    plugins: [
        // new webpack.NoErrorsPlugin(),
        new webpack.DefinePlugin({
            NODE_ENV: JSON.stringify(NODE_ENV)
        }),
        // new StyleLintPlugin({
        //
        // }),
        new webpack.optimize.CommonsChunkPlugin({
            name: "vendor",
            filename: "js/[name].bundle.js"
        }),
        // new webpack.optimize.UglifyJsPlugin(),

        // new webpack.optimize.CommonsChunkPlugin({
        //     name: 'common',
        //     // filename: 'common.js',
        //     // async: true,
        //     // minChunk: Infinity
        //     minChunk: 3
        // }),
        new webpack.LoaderOptionsPlugin({
            // minimize: true,
            debug: true
        }),
        new webpack.ProgressPlugin(ProgressCallback),
        extractCSS,
        // extractDocs
    ],
};
