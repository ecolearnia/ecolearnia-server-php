var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

/*
elixir(function(mix) {
    mix.sass('app.scss');
});
*/

// @see http://stackoverflow.com/questions/34029772/compiling-zurb-foundation-6-with-laravels-elixir-gulp
elixir(function(mix) {
    mix.sass(
        'app.scss',
        'public/css/app.css',
        { includePaths:
            [
                'resources/assets/bower_components/foundation-sites/scss/',
                'resources/assets/bower_components/motion-ui/src/'
            ]
        });
});
