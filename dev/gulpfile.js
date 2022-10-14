'use strict';

const gulp = require( 'gulp' ),

    // Minify CSS
    clean_css = require( 'gulp-clean-css' ),

    // Minify JS
    clean_js = require( 'gulp-minify' ),

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
            all: './assets/scss/**/*.?css'
        },
        dest: '../assets/css'
    },
    vendor: {
        normalize: {
            src: './node_modules/normalize.css/normalize.css',
            dest: './assets/scss/vendor/'
        }
    }
}

exports.scripts = scripts;
function scripts() {
    return (
        gulp
            .src( paths.scripts.src )

            // Minify the output
            .pipe( clean_js( { mangle: { toplevel: true }, ext: { min: '.js' }, noSource: true } ) )
 
            // What is the destination for the compiled file?
            .pipe( gulp.dest( paths.scripts.dest ) )
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
            .pipe( clean_css( { level: { 1: { specialComments: 0 } } } ) ) // Remove comments
 
            // What is the destination for the compiled file?
            .pipe( gulp.dest( paths.styles.dest ) )
    );
}

// Copy vendor files (Normalize)
// $ gulp vendor
exports.vendor = vendor;
function vendor() {
    return (
        gulp.src( paths.vendor.normalize.src ).pipe( gulp.dest( paths.vendor.normalize.dest ) )
    );
}

// Watch folders
// $ gulp watch
exports.watch = watch
function watch() {
    gulp.watch( paths.scripts.src, { awaitWriteFinish: true }, scripts );
    gulp.watch( paths.styles.src.all, { awaitWriteFinish: true }, styles );
}
    
