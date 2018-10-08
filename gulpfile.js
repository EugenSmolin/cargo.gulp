'use strict';

const gulp = require('gulp'); // основной gulp
const sass = require('gulp-sass'); // компилирует в css
const rigger = require('gulp-rigger'); // сборка html файлов
const sourcemaps = require('gulp-sourcemaps'); // карта css
const debug = require('gulp-debug'); // дебажит ошибки
const del = require('del'); // очищает папку
// const gulpIf = require('gulp-if'); // 
const autoprefixer = require('gulp-autoprefixer'); // добавляет в css префиксы
const browserSync = require('browser-sync').create(); // запускает локальный браузер Синк
const notify = require('gulp-notify'); // отлов ошибок
const multipipe = require('multipipe'); // отлов ошибок, объеденяет воединный поток и передает данные
const plumber = require('gulp-plumber');
const imagemin = require('gulp-imagemin'); // сжимает картинки
const pngquant = require('imagemin-pngquant'); // доп. сжимает png
const minify = require('gulp-minify-css'); // минифицирует css
const uglify = require('gulp-uglify'); // минифицирует js

// === ПУТИ
const path = {
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
		gulp.src(path.development.scss),
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
		gulp.dest(path.prodaction.css)
	).on('error', notify.onError());
});

// === HTML
gulp.task('html', function () {
  return gulp.src(path.development.html)
    .pipe(rigger())
    .pipe(gulp.dest(path.prodaction.html));
    // .pipe(reload({stream: true})); // И перезагрузим наш сервер для обновлений
});

// === IMG
gulp.task('image', function () {
  return gulp.src(path.development.img) 
    .pipe(imagemin({ 
        progressive: true,
        svgoPlugins: [{removeViewBox: false}],
        use: [pngquant()],
        interlaced: true
    }))
    .pipe(gulp.dest(path.prodaction.img));
});

// === CLEAN
gulp.task('clean', function() {
	return del('prodaction/');
});

// === ASSETS
gulp.task('assets', function() {
	return gulp.src('development/**', {since: gulp.lastRun('assets')}) // обновляет последние измененные файлы
		// .pipe(newer('public')) // не повторяет файлы, компилит новые
		.pipe(debug({title: 'assets'}))
		.pipe(gulp.dest('prodaction/'));
});

// === PRODACTION
gulp.task('prodaction', gulp.series('clean', gulp.parallel('scss', 'assets'), ['html', 'image']));

// === WATCH
gulp.task('watch', function() {
	gulp.watch(path.development.scss, gulp.series('scss'));
	gulp.watch(path.development.html, gulp.series('html'));
	gulp.watch('development/**/*.*', gulp.series('assets'));
});

// === SERVER
gulp.task('server', function() {
	browserSync.init({
    proxy: config.proxy,
    host: config.host,
    port: config.port
	});

	browserSync.watch('prodaction/**/*.*').on('change', browserSync.reload);
});

// === START
gulp.task('start', gulp.series('prodaction', gulp.parallel('watch', 'server')));
