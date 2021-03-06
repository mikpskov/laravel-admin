const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the SASS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableSuccessNotifications()
mix.disableNotifications()

mix.js('resources/js/admin/app.js', 'public/js/admin.js')
    .sass('resources/sass/admin/app.scss', 'public/css/admin.css')
    .sourceMaps()
    .js('resources/js/app.js', 'public/js/app.js')
    .postCss("resources/css/app.css", "public/css", [
        require("tailwindcss"),
    ]);
