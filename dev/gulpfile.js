'use strict';

// Suppress warnings
process.env.SASS_QUIET_DEPS = "true";

const gulp = require('gulp');

// Styles
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');

// JS
const terser = require('gulp-terser');

// Utils
const plumber = require('gulp-plumber');

const paths = {
    scripts: {
        src: './assets/js/**/*.js',
        dest: '../assets/js'
    },
    styles: {
        src: {
            main: './assets/scss/style.scss',
            all: './assets/scss/**/*.scss'
        },
        dest: '../assets/css'
    }
};

/**
 * JavaScript task
 */
function scripts() {
    return gulp
        .src(paths.scripts.src)
        .pipe(plumber())
        .pipe(
            terser({
                compress: true,
                mangle: {
                    toplevel: true
                }
            })
        )
        .pipe(gulp.dest(paths.scripts.dest));
}

/**
 * Styles task
 */
function styles() {
    return gulp
        .src(paths.styles.src.main)
        .pipe(plumber())
        .pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
        .pipe(
            postcss([
                autoprefixer()
            ])
        )
        .pipe(
            cleanCSS({
                level: 2
            })
        )
        .pipe(gulp.dest(paths.styles.dest));
}

/**
 * Watch task
 */
function watchFiles() {
    gulp.watch(paths.styles.src.all, styles);
    gulp.watch(paths.scripts.src, scripts);
}

/**
 * Build tasks
 */
exports.styles = styles;
exports.scripts = scripts;
exports.watch = watchFiles;
exports.default = gulp.series(
    gulp.parallel(styles, scripts)
);