'use strict';

const gulp = require('gulp'); // основной gulp
const sass = require('gulp-sass'); // компилирует в css
const rigger = require('gulp-rigger'); // сборка html файлов
const sourcemaps = require('gulp-sourcemaps'); // карта css
const debug = require('gulp-debug'); // дебажит ошибки
const del = require('del'); // очищает папку
// const gulpIf = require('gulp-if'); // 
// const concat = require('gulp-concat'); // объеденяет файлы в один
// const remember = require('gulp-remember');
// const gulpPath = require('path');
const autoprefixer = require('gulp-autoprefixer'); // добавляет в css префиксы
const browserSync = require('browser-sync').create(); // запускает локальный браузер Синк
const notify = require('gulp-notify'); // отлов ошибок
const multipipe = require('multipipe'); // отлов ошибок, объеденяет воединный поток и передает данные
const plumber = require('gulp-plumber');
const imagemin = require('gulp-imagemin'); // сжимает картинки
const pngquant = require('imagemin-pngquant'); // доп. сжимает png
// const minify = require('gulp-minify-css'); // минифицирует css
// const uglify = require('gulp-uglify'); // минифицирует js

// === ПУТИ
const folder = {
	prodaction: { // Складываем готовые файлы после сборки
      html: 'prodaction/',
      js: 'prodaction/js/',
      css: 'prodaction/css/',
      img: 'prodaction/images/',
      fonts: 'prodaction/fonts/'
  },
  development: { // Пути откуда брать исходники
      html: 'development/**/*.html',
      js: 'development/js/*.js',
      scss: 'development/scss/*.scss',
      img: 'development/images/**/*.*',
      fonts: 'development/fonts/**/*.*'
  },
  watch: { // Указываем, за изменением каких файлов мы хотим наблюдать
      html: 'development/**/*.html',
      js: 'development/js/**/*.js',
      scss: 'development/scss/**/*.scss',
      img: 'development/images/**/*.*',
      fonts: 'development/fonts/**/*.*'
  },
  clean: './prodaction/front/'
};

// === НАСТРОЙКИ СЕРВЕРА
const config = {
    proxy: 'cargo.gulp',
    host: 'localhost',
    port: 6600
};

// === SASS
gulp.task('scss', function() {
	return multipipe(
		gulp.src(folder.development.scss),
		plumber({
			errorHandler: notify.onError(function(err) {
				return {
					title: 'scss',
					message: err.message
				};
			})
		}),
		autoprefixer({
			browsers: ['last 16 versions'],
      	cascade: false
		}),
		sourcemaps.init(),
		sass(),
		sourcemaps.write('.'),
		gulp.dest(folder.prodaction.css)
	).on('error', notify.onError());
});

// === HTML
gulp.task('html', function () {
  return gulp.src(folder.development.html)
    .pipe(rigger())
    .pipe(gulp.dest(folder.prodaction.html));
    // .pipe(reload({stream: true})); // И перезагрузим наш сервер для обновлений
});

// === JS
// gulp.task('js', function() {
//   return gulp.src(folder.development.js)
//     .pipe(concat('libs.js'))
//     .pipe(gulp.dest(folder.prodaction.js));
// });

// === IMG
gulp.task('image', function () {
  return gulp.src(folder.development.img) 
    .pipe(imagemin({ 
        progressive: true,
        svgoPlugins: [{removeViewBox: false}],
        use: [pngquant()],
        interlaced: true
    }))
    .pipe(gulp.dest(folder.prodaction.img));
});

// === CLEAN
gulp.task('clean', function() {
	return del('prodaction/');
});

// === ASSETS
gulp.task('assets', function() {
	return gulp.src('development/**', {since: gulp.lastRun('assets')}) // обновляет последние измененные файлы
		// .pipe(newer('public')) // не повторяет файлы, компилит новые
		// .pipe(debug({title: 'assets'}))
		.pipe(gulp.dest('prodaction/'));
});

// === PRODACTION
// gulp.task('prodaction', gulp.series('clean', gulp.parallel('scss', 'assets'), ['html', 'image']));
gulp.task('prodaction', gulp.series('clean', ['assets', 'scss', 'html', 'image']));

// === WATCH
gulp.task('watch', function() {
	gulp.watch(folder.development.scss, gulp.series('scss'));
	gulp.watch(folder.development.html, gulp.series('html'));
	gulp.watch('development/**/*.*', gulp.series('assets'));
});

// === SERVER
gulp.task('server', function() {
	browserSync.init({
    proxy: config.proxy,
    host: config.host,
    port: config.port,
    notify: false
	});

	browserSync.watch('prodaction/**/*.*').on('change', browserSync.reload);
});

// === START
gulp.task('start', gulp.series('prodaction', gulp.parallel('watch', 'server')));
