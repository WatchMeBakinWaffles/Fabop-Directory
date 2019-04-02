var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('styles', function() {
    gulp.src('assets/scss/style.scss')
    .pipe(sass())
    .pipe(gulp.dest('public/static/css/'));
});

gulp.task('js', function() {
    gulp.src('node_modules/jquery/dist/jquery.min.js')
    .pipe(gulp.dest('public/static/js/'));
    gulp.src('node_modules/bootstrap/dist/js/bootstrap.min.js')
    .pipe(gulp.dest('public/static/js/'));
    gulp.src('node_modules/popper.js/dist/umd/popper.min.js')
    .pipe(gulp.dest('public/static/js/'));
    gulp.src('assets/js/scripts.js')
    .pipe(gulp.dest('public/static/js/'));
});

gulp.task('fa', function() {
    gulp.src('node_modules/@fortawesome/fontawesome-free/css/all.min.css')
    .pipe(gulp.dest('public/static/css/fontawesome'));
    gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/*')
    .pipe(gulp.dest('public/static/css/webfonts'));
    // gulp.src('node_modules/@fortawesome/fontawesome-free/js/all.js')
    // .pipe(gulp.dest('public/static/js/fontawesome'));
});

gulp.task('animations', function() {
    gulp.src('node_modules/animate.css/animate.css')
    .pipe(gulp.dest('public/static/css/'));
});

gulp.task('images', function() {
    gulp.src('assets/images/*')
    .pipe(gulp.dest('public/static/images/'));
});

gulp.task('fonts', function() {
    gulp.src('assets/fonts/*/*')
    .pipe(gulp.dest('public/static/fonts/'));
});