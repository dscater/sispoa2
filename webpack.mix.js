const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .styles([
        'resources/assets/css/all.min.css',
        'resources/assets/css/icheck-bootstrap.css',
        'resources/assets/css/adminlte.css',
        'resources/assets/css/config.css'
    ], 'public/css/plantilla.css')
    .scripts([
        'resources/assets/js/adminlte.min.js',
        'resources/assets/js/demo.js',
    ], 'public/js/plantilla.js')
    .copy('resources/assets/imgs', 'public/imgs')
    .copy('resources/assets/webfonts', 'public/webfonts');

mix.disableNotifications();