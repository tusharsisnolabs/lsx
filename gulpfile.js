const gulp         = require('gulp');
const sass         = require('gulp-sass');
const sourcemaps   = require('gulp-sourcemaps');
const jshint       = require('gulp-jshint');
const concat       = require('gulp-concat');
const uglify       = require('gulp-uglify');
const sort         = require('gulp-sort');
const wppot        = require('gulp-wp-pot');
const gettext      = require('gulp-gettext');
const plumber      = require('gulp-plumber');
const autoprefixer = require('gulp-autoprefixer');
const gutil        = require('gulp-util');
const rename       = require('gulp-rename');
const minify       = require('gulp-minify-css');
const map          = require('map-stream');
const browserlist  = ['last 2 version', '> 1%'];

const errorreporter = map(function(file, cb) {
	if (file.jshint.success) {
		return cb(null, file);
	}

	console.log('JSHINT fail in', file.path);

	file.jshint.results.forEach(function (result) {
		if (!result.error) {
			return;
		}

		const err = result.error
		console.log(`  line ${err.line}, col ${err.character}, code ${err.code}, ${err.reason}`);
	});

	cb(null, file);
});

gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp compile-css    to compile the scss to css');
	console.log('gulp compile-js     to compile the js to min.js');
	console.log('gulp watch          to continue watching the files for changes');
	console.log('gulp wordpress-lang to compile the lsx.pot, en_EN.po and en_EN.mo');
});

gulp.task('styles', function () {
	return gulp.src(['assets/css/scss/*.scss'])
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'compact',
			includePaths: ['assets/css/scss']
		}).on('error', gutil.log))
		.pipe(autoprefixer({
			browsers: browserlist,
			casacade: true
		}))
		.pipe(sourcemaps.write('maps'))
		.pipe(gulp.dest('assets/css'))
});

gulp.task('vendor-styles', function () {
	return gulp.src(['assets/css/vendor/*.scss'])
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(sass({
			outputStyle: 'compact',
			includePaths: ['assets/css/vendor']
		}).on('error', gutil.log))
		.pipe(autoprefixer({
			browsers: browserlist,
			casacade: true
		}))
		//.pipe(minify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(gulp.dest('assets/css/vendor'))
});

gulp.task('admin-styles', function () {
	return gulp.src(['assets/css/admin/*.scss'])
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'compact',
			includePaths: ['assets/css/admin']
		}).on('error', gutil.log))
		.pipe(autoprefixer({
			browsers: browserlist,
			casacade: true
		}))
		.pipe(sourcemaps.write('maps'))
		.pipe(gulp.dest('assets/css/admin'))
});

gulp.task('plugins-styles', function () {
	return gulp.src(['assets/css/plugins/*.scss'])
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'compact',
			includePaths: ['assets/css/plugins']
		}).on('error', gutil.log))
		.pipe(autoprefixer({
			browsers: browserlist,
			casacade: true
		}))
		.pipe(sourcemaps.write('maps'))
		.pipe(gulp.dest('assets/css/plugins'))
});

/*
gulp.task('sass-woocommerce', function() {
	gulp.src(['sass/woocommerce/woocommerce-layout.scss', 'sass/woocommerce/woocommerce-smallscreen.scss', 'sass/woocommerce/woocommerce.scss'])
		.pipe(sass().on('error', function(err) { console.log('Error!', err); }))
		.pipe(gulp.dest('css/'));
});

gulp.task('sass-sensei', function() {
	gulp.src('sass/sensei/frontend/sensei.scss')
		.pipe(sass().on('error', function(err) { console.log('Error!', err); }))
		.pipe(gulp.dest('css/'));
});
*/

gulp.task('compile-css', ['styles', 'vendor-styles', 'admin-styles', 'plugins-styles']);

gulp.task('js', function() {
	gulp.src('assets/js/src/lsx.js')
		.pipe(jshint())
		.pipe(errorreporter)
		.pipe(concat('lsx.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js'));
});

gulp.task('compile-js', ['js']);


gulp.task('watch-css', function () {
	gulp.watch('assets/css/**/*.scss', ['compile-css']);
});

gulp.task('watch-js', function () {
	gulp.watch('assets/js/src/**/*.js', ['compile-js']);
});

gulp.task('watch', ['watch-css', 'watch-js']);

gulp.task('wordpress-pot', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'lsx',
			package: 'lsx',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/lsx.pot'));
});

gulp.task('wordpress-po', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'lsx',
			package: 'lsx',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/en_EN.po'));
});

gulp.task('wordpress-po-mo', ['wordpress-po'], function() {
	return gulp.src('languages/en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages'));
});

gulp.task('wordpress-lang', (['wordpress-pot', 'wordpress-po-mo']));
