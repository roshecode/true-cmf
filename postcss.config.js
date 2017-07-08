let path = require('path'),
    postcss = require('postcss');

module.exports = {
    map: 'inline',
    parser: 'postcss-scss',
    plugins: [
        require('postcss-import'),
        require('postcss-nesting'),
        require('postcss-url')({
            from: path.resolve(__dirname, 'resources/assets/css/style.css'),
            to: path.resolve(__dirname, 'public/css/style.css')
        }),
        require('postcss-apply'),
        require('postcss-color-function'),
        require('css-mqpacker'),
        require('postcss-custom-media'),
        require('postcss-css-variables'),
        require('postcss-calc'),
        require('postcss-media-minmax'),
        require('postcss-custom-selectors'),
        // require('postcss-smart-import')({ /* ...options */ }),
        // require('precss')({ /* ...options */ }),
        // require('autoprefixer'),
        // require('cssnano')({
        //     zindex: false,
        //     discardComments: {
        //         removeAll: true
        //     }
        // }),
    ]
};
