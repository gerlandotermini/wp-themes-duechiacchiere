'use strict';

const gulp = require( 'gulp' );
const sass = require( 'gulp-sass' )( require( 'sass' ) );

const paths = {
    style: {
        // By using styles/**/*.sass we're telling gulp to check all folders for any sass file
        src: './assets/scss/style.scss',

        // Compiled files will end up in whichever folder it's found in (partials are not compiled)
        dest: '../assets/css'
    },
    script: {
        src: './assets/js/script.js',
        dest: '../assets/js'
    }
};

// Define tasks after requiring dependencies
// $ gulp style
exports.style = style;
function style() {
    // Where should gulp look for the sass files?
    return (
        gulp
            .src( paths.style.src )
 
            // Use sass with the files found, and log any errors
            .pipe( sass() ).on( 'error', sass.logError )
 
            // What is the destination for the compiled file?
            .pipe( gulp.dest( paths.style.dest ) )
    );
}
 
// $ gulp watch
exports.watch = watch
function watch(){
    // gulp.watch takes in the location of the files to watch for changes
    // and the name of the function we want to run on change
    gulp.watch( paths.style.src, style )
}
    
