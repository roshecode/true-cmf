'use strict';

const configFileDir = 'config.yml';

let [
    /**
     * @property through2
     * @property yamljs
     * @property gulp
     * @property sass
     * @property del
     * @property imageminPngquant
     * @property browserSync
     * @property streamCombiner2
     * @property htmlmin
     * @property cleanCss
     * @property uglify
     * @property svgmin
     * @property imagemin
     * @property autoprefixer
     * @property svgstore
     * @property rename
     * @property sourcemaps
     * @property notify
     */
    $,
    /**
     * @property srcMap
     */
    dest,
    src,
    /**
     * @property html
     * @property css
     * @property js
     * @property svg
     * @property img
     */
    watch,
    /**
     * @property preOpt
     * @property mcbw
     * @property mcbw.html
     * @property mcbw.css
     * @property mcbw.js
     * @property mcbw.svg
     * @property mcbw.img
     */
    data,
    plg
    ] = [{}];

Object.keys(require('./package.json').devDependencies).forEach((pkg) => {
    $[pkg.replace('gulp-', '').replace(/-[a-z]/g, (_, ofs, str) => {
        return str[++ofs].toUpperCase();
    })] = require(pkg);
});

const gulp = $.gulp, combine = $.streamCombiner2.obj, YAML = $.yamljs;

/**
 * @property prj
 * @property plg
 * @property dir
 */
var conf = YAML.load(configFileDir);

function configInit(done) {
    conf  = YAML.load(configFileDir);
    data  = conf.prj;
    plg   = conf.plg;
    dest  = conf.dir.dest;
    src   = conf.dir.src;
    watch = Object.assign({}, src, conf.dir.watch);
    done();
}

configInit(function() {});

function go() { return $.through2.obj(function (file, enc, cb) { cb(null, file); }); }
function no(done) { done(); }
function reload(done) { $.browserSync.reload(); done(); }

// HTML BUILD TASK
gulp.task('build:html', (done) => {
    combine(
        gulp.src(src.html),
        data.pre.html ? $[data.pre.html]({data: data}) : go(),
        data.mcbw.html[0] ? $.htmlmin(plg.htmlmin) : go(),
        gulp.dest(dest.html)
    ).on('error', $.notify.onError({ title: "build:html" }));
    done();
});

// CSS BUILD TASK
gulp.task('build:css', (done) => {
    combine(
        gulp.src(src.css),
        $.sourcemaps.init(),
        // data.pre.css ? $[data.pre.css](data.preOpt[data.pre.css]).on('error', $[data.pre.css].logError) : go(),
        data.pre.css ? $[data.pre.css](data.preOpt[data.pre.css]) : go(),
        $.autoprefixer(plg.autoprefixer),
        data.mcbw.css[0] ? $.cleanCss(plg.cleanCss) : go(),
        $.sourcemaps.write(dest.srcMap),
        gulp.dest(dest.css)
    ).on('error', $.notify.onError({ title: "build:css" }));
    done();
});

// JS BUILD TASK
gulp.task('build:js', (done) => {
    combine(
        gulp.src(src.js),
        $.sourcemaps.init(),
        data.pre.js ? $[data.pre.js](data.preOpt[data.pre.js]) : go(),
        data.mcbw.js[0] ? $.uglify(plg.uglify) : go(),
        $.sourcemaps.write(dest.srcMap),
        gulp.dest(dest.js)
    ).on('error', $.notify.onError({ title: "build:js" }));
    done();
});

// SVG BUILD TASK
gulp.task('build:svg', (done) => {
    combine(
        gulp.src(src.svg),
        data.mcbw.svg[0] ? $.svgmin(plg.svgmin) : go(),
        $.rename(function (path) { // Rename files for beauty id's
            let name = path.dirname.split(path.sep);
            name[0] == '.' ? name.shift() : true;
            name.push(path.basename);
            path.basename = name.join('-');
        }),
        $.svgstore(plg.svgstore), // svg concatenation into symbols
        gulp.dest(dest.svg)
    ).on('error', $.notify.onError({ title: "build:svg" }));
    done();
});

// IMAGE BUILD TASK
gulp.task('build:img', (done) => {
    combine(
        gulp.src(src.img),
        data.mcbw.img[0] ? $.imagemin(Object.assign(plg.imagemin, {
            use: [$.imageminPngquant()]
        })) : go(),
        gulp.dest(dest.img)
    ).on('error', $.notify.onError({ title: "build:img" }));
    done();
});

gulp.task('watch:cfg ', () => gulp.watch(configFileDir, gulp.series(configInit, 'build', reload)) );
gulp.task('watch:html', () => gulp.watch(watch.html, gulp.series('build:html', reload)) );
gulp.task('watch:css',  () => gulp.watch(watch.css,  gulp.series('build:css',  reload)) );
gulp.task('watch:js',   () => gulp.watch(watch.js,   gulp.series('build:js',   reload)) );
gulp.task('watch:svg',  () => gulp.watch(watch.svg,  gulp.series('build:svg',  reload)) );
gulp.task('watch:img',  () => gulp.watch(watch.img,  gulp.series('build:img',  reload)) );

gulp.task('clean:html', (done) => { $.del(dest.html); done(); });
gulp.task('clean:css ', (done) => { $.del(dest.css ); done(); });
gulp.task('clean:js  ', (done) => { $.del(dest.js  ); done(); });
gulp.task('clean:svg ', (done) => { $.del(dest.svg ); done(); });
gulp.task('clean:img ', (done) => { $.del(dest.img ); done(); });

// BUILD TASK
gulp.task('build', gulp.series(gulp.parallel(
    data.mcbw.css [2] ? 'build:css'  : no,
    data.mcbw.js  [2] ? 'build:js'   : no,
    data.mcbw.svg [2] ? 'build:svg'  : no,
    data.mcbw.img [2] ? 'build:img'  : no),
    data.mcbw.html[2] ? 'build:html' : no
));

// WATCH TASK
gulp.task('watch', gulp.parallel(
    data.mcbw.html[3] ? 'watch:html' : no,
    data.mcbw.css [3] ? 'watch:css'  : no,
    data.mcbw.js  [3] ? 'watch:js'   : no,
    data.mcbw.svg [3] ? 'watch:svg'  : no,
    data.mcbw.img [3] ? 'watch:img'  : no,
    'watch:cfg '
));

// CLEAN TASK
gulp.task('clean', (done) => {
    let arr = data.mcbw, out = [];
    for (let key in arr) { if (arr.hasOwnProperty(key)) { arr[key][1] ? out.push(dest[key]) : 0; } }
    $.del(out, plg.del);
    done();
});

// SERVER AND BROWSER TASK
gulp.task('sync', (done) => {
    $.browserSync.init(plg.browserSync);
    done();
});

// DEFAULT TASK
gulp.task('default', gulp.parallel(gulp.series('build', 'sync'), 'watch'));
