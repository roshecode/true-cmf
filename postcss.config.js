let path = require('path'),
    postcss = require('postcss');

module.exports = {
    sourceMap: 'inline',
    parser: 'postcss-scss',
    plugins: [
        require('postcss-import'),
        require('postcss-nesting'),
        require('postcss-css-variables'),
        require('postcss-custom-selectors'),
        require('postcss-calc'),
        require('postcss-url')({
            from: path.resolve(__dirname, 'resources/assets/css/style.css'),
            to: path.resolve(__dirname, 'public/css/style.css')
        }),
        require('postcss-apply'),
        require('postcss-color-function'),
        require('postcss-media-minmax'),
        require('postcss-custom-media'),
        require('css-mqpacker'),
        require('postcss-custom-selectors'),
        // require('postcss-smart-import')({ /* ...options */ }),
        // require('precss')({ /* ...options */ }),
        // require('autoprefixer')({
        //     browsers: ['last 2 versions']
        // }),
        require('cssnano')({
            zindex: false,
            discardComments: {
                removeAll: true
            }
        })
    ]
};
