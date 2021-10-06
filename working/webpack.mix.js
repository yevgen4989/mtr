const mix = require('laravel-mix');

mix.options({
  processCssUrls: false,
  fileLoaderDirs: {
    fonts: `public/fonts`,
    images: `public/img`
  }
});

mix.js('resources/js/script.js', 'public/js/script.js')
    .copy('resources/js/app/app.js', 'public/js/app.js')
    .sass('resources/sass/frontend.scss', 'public/css/style.css').options({
      processCssUrls: false
    })
    .copyDirectory('resources/img', 'public/img')
    .copyDirectory('resources/fonts', 'public/fonts');
