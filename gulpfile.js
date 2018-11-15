var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('styles', function() {
    gulp.src('assets/scss/style.scss')
    .pipe(sass())
    .pipe(gulp.dest('assets/static/css/'));
});

gulp.task('js', function() {
    gulp.src('node_modules/jquery/jquery.min.js')
    .pipe(gulp.dest('assets/static/js/'));
    gulp.src('node_modules/bootstrap/dist/js/bootstrap.min.js')
    .pipe(gulp.dest('assets/static/js/'));
    gulp.src('node_modules/popper.js/dist/umd/popper.min.js')
    .pipe(gulp.dest('assets/static/js/'));
});

gulp.task('fa', function() {
    gulp.src('assets/scss/fontawesome.scss')
    .pipe(sass())
    .pipe(gulp.dest('assets/static/css/'));
});

gulp.task('icons', function() {
    gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/*')
    .pipe(gulp.dest('assets/static/webfonts/'));
});
