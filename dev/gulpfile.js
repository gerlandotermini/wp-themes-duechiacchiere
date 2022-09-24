'use strict';

const gulp = require( 'gulp' ),

    // Minify CSS
    clean = require( 'gulp-clean-css' ),

    // Minify JS
    minify = require( 'gulp-minify' ),

    // Compile SCSS
    sass = require( 'gulp-sass' )( require( 'sass' ) );

// Where to find the source code and where to save the output
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

exports.scripts = scripts;
function scripts() {
    return (
        gulp
            .src( paths.style.src.main )
 
            // Use sass with the files found, and log any errors
            .pipe( sass() ).on( 'error', sass.logError )

            // Minify the output
            .pipe( clean( { level: { 1: { specialComments: 0 } } } ) )
 
            // What is the destination for the compiled file?
            .pipe( gulp.dest( paths.style.dest ) )
    );
}

// Define tasks after requiring dependencies
// $ gulp style
exports.styles = styles;
function styles() {
    return (
        gulp
            .src( paths.styles.src.main )
 
            // Use sass with the files found, and log any errors
            .pipe( sass() ).on( 'error', sass.logError )

            // Minify the output
            .pipe( clean( { level: { 1: { specialComments: 0 } } } ) )
 
            // What is the destination for the compiled file?
            .pipe( gulp.dest( paths.styles.dest ) )
    );
}

// $ gulp watch
exports.watch = watch
function watch() {
    gulp.watch( paths.scripts.src, scripts );
    gulp.watch( paths.styles.src.all, styles );
}
    
