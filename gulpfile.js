'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function () {

	return gulp.src('./app/assets/scss/*.scss')
		.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
		.pipe(gulp.dest('./app/assets/css/'));

});
 
gulp.task('sass:watch', ['sass'], function () {

	gulp.watch('./app/assets/scss/*.scss', ['sass']);

});

gulp.task('happy', ['sass:watch'], function() {
	// We are calling other watch tasks using dependencies
});