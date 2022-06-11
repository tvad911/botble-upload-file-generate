let mix = require('laravel-mix');

let directory = 'lease-contract';

const source = 'platform/plugins/' + directory;
const dist = 'public/vendor/core/plugins/' + directory;

mix
    .sass(source + '/resources/assets/sass/lease-contract-admin.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/lease-contract-file-admin.scss', dist + '/css')
    .js(source + '/resources/assets/js/lease-contract-admin.js', dist + '/js')
    .js(source + '/resources/assets/js/lease-contract-file-admin.js', dist + '/js')

    .copy(source + '/public/images', dist + '/images')
    .copy(dist + '/js', source + '/public/js')
    .copy( dist + '/css', source + '/public/css');