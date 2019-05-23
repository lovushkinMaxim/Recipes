'use strict';

var gulp = require('gulp');
var concat = require('gulp-concat');
var watch = require('gulp-watch');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

var scripts = {
    output:'../wp-content/themes/qt-infinity/js',
    input:[
        './js/app.js'
    ]
};

var styles = {
    output:'../wp-content/themes/qt-infinity/',
    input:[
        './node_modules/normalize.css/normalize.css',
        './scss/app.scss'
    ]
};

gulp.task('css', function(){
    return gulp.src(styles.input)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('style.css'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(styles.output));
});

gulp.task('js', function(){
    return gulp.src(scripts.input)
        .pipe(sourcemaps.init())
        .pipe(concat('app.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(scripts.output));
});

 gulp.task('watch', function () {
     gulp.watch(styles, ['css']);
     gulp.watch(scripts, ['js']);
});

gulp.task('default', ['css','js','watch']);

