let mix = require('laravel-mix');
let WebpackRTLPlugin = require('webpack-rtl-plugin');
mix.disableNotifications();
mix.js('themes/default/assets/blog/src/js/blog.js', 'themes/default/assets/blog/js')
        .sass('themes/default/assets/blog/src/scss/blog.scss', 'themes/default/assets/blog/css')
        /*.webpackConfig({
            plugins: [
                new WebpackRTLPlugin()
            ]
        })*/
        .options({
            processCssUrls: false
        });

mix.sourceMaps(true, 'source-map');
mix.extract();
