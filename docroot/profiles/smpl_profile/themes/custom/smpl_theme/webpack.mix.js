/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your application. See https://github.com/JeffreyWay/laravel-mix.
 |
*/
require('dotenv').config({ path: '.env.local' });
const mix = require('laravel-mix');
const glob = require('glob');
require('laravel-mix-stylelint');
require('laravel-mix-copy-watched');

/*
  |--------------------------------------------------------------------------
  | Configuration
  |--------------------------------------------------------------------------
*/
mix
	.sourceMaps()
	.webpackConfig({
		devtool: 'source-map',
	})
	.disableNotifications()
	.options({
		processCssUrls: false,
	});

/*
  |--------------------------------------------------------------------------
  | Browsersync
  |--------------------------------------------------------------------------
*/
mix.browserSync({
	proxy: process.env.DRUPAL_BASE_URL,
	files: [
		'components/**/*.css',
		'components/**/*.js',
		'components/**/*.twig',
		'templates/**/*.twig',
	],
	stream: true,
});

/*
  |--------------------------------------------------------------------------
  | SASS
  |--------------------------------------------------------------------------
*/
mix.sass("src/scss/main.style.scss", "assets/css/main.style.css");

for (const sourcePath of glob.sync("components/**/*.scss")) {
  const destinationPath = sourcePath.replace(/\.scss$/, ".css");
  mix.sass(sourcePath, destinationPath);
}

const scssFiles = glob.sync('src/scss/libraries/*.scss');
scssFiles.forEach(filename => {
  mix.sass(filename, 'assets/css');
});

/*
  |--------------------------------------------------------------------------
  | JS
  |--------------------------------------------------------------------------
*/

for (const sourcePath of glob.sync('components/**/_*.js')) {
  const destinationPath = sourcePath.replace(/\/_([^/]+\.js)$/, "/$1");
  mix.js(sourcePath, destinationPath);
}

const jsFiles = glob.sync('src/js/*.js');
jsFiles.forEach(filename => {
  mix.js(filename, 'assets/js');
});

/*
  |--------------------------------------------------------------------------
  | Style Lint
  |--------------------------------------------------------------------------
*/
mix.stylelint({
	configFile: './.stylelintrc.json',
	context: './src',
	failOnError: false,
	files: ['**/*.scss'],
	quiet: false,
	customSyntax: 'postcss-scss',
});

/*
  |--------------------------------------------------------------------------
  * IMAGES / ICONS / VIDEOS / FONTS
  |--------------------------------------------------------------------------
  */
// * Directly copies the images, icons and fonts with no optimizations on the images
mix.copyDirectoryWatched('src/assets/images', 'assets/assets/images');
mix.copyDirectoryWatched('src/assets/icons', 'assets/assets/icons');
mix.copyDirectoryWatched('src/assets/videos', 'assets/assets/videos');
mix.copyDirectoryWatched('src/assets/fonts/**/*', 'assets/fonts');
