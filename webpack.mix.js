let mix = require('laravel-mix');

mix.js('src/js/main.js', 'dist/one-page-checkout.js').vue();
mix.postCss("src/css/one-page-checkout.css", "dist/one-page-checkout.css", [
    require("tailwindcss"),
]);