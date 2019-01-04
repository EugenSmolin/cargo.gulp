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

// === НАСТРОЙКИ СЕРВЕРА
const config = {
    proxy: 'cargo.gulp',
    host: 'localhost',
    port: 6600
};

// === SASS
gulp.task('scss', function () {
    return multipipe(
        gulp.src('app/front/scss/*.*'),
        plumber({
            errorHandler: notify.onError(function (err) {
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
        gulp.dest('app/front/css')
    ).on('error', notify.onError());
});

// // === CLEAN
gulp.task('clean', function () {
    return del('app/front/css');
});

// // === ASSETS
gulp.task('assets', function () {
    return gulp.src('app/front/**', {since: gulp.lastRun('assets')}) // обновляет последние измененные файлы
    // .pipe(newer('public')) // не повторяет файлы, компилит новые
    // .pipe(debug({title: 'assets'}))
        .pipe(gulp.dest('app/front/'));
});

// === PRODUCTION
gulp.task('build', gulp.series('clean', gulp.parallel('scss', 'assets')));

// === WATCH
gulp.task('watch', function () {
    gulp.watch('app/front/scss/*.*', gulp.series('scss'));
    // gulp.watch('app/front/**/*.*', gulp.series('assets'));
});

// === SERVER
gulp.task('server', function () {
    browserSync.init({
        proxy: config.proxy,
        host: config.host,
        port: config.port,
        notify: false
    });

    browserSync.watch('app/front/**/*.*').on('change', browserSync.reload);
});

// === START
gulp.task('start', gulp.series('build', gulp.parallel('watch', 'server')));
