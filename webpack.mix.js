const mix = require("laravel-mix");

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

// App Mix
mix.js("resources/js/app.js", "public/assets/assets/js/webpacks")
    .sass("resources/sass/app.scss", "public/assets/assets/css/webpacks")
    .sourceMaps()
    .version();

// User Mix
mix.js("resources/js/user.js", "public/assets/assets/js/webpacks")
    .sass("resources/sass/user.scss", "public/assets/assets/css/webpacks")
    .version();
