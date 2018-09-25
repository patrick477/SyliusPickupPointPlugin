import concat from 'gulp-concat';
import gulp from 'gulp';
import gulpif from 'gulp-if';
import livereload from 'gulp-livereload';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';
import upath from 'upath';
import yargs from 'yargs';

const { argv } = yargs
    .options({
        rootPath: {
            description: '<path> path to web assets directory',
            type: 'string',
            requiresArg: true,
            required: true,
        },
        vendorPath: {
            description: '<path> path to vendor directory',
            type: 'string',
            requiresArg: true,
            required: false,
        },
        nodeModulesPath: {
            description: '<path> path to node_modules directory',
            type: 'string',
            requiresArg: true,
            required: true,
        },
    });

const env = process.env.GULP_ENV;
const rootPath = upath.normalizeSafe(argv.rootPath);
const shopRootPath = upath.joinSafe(rootPath, 'shop');
const vendorPath = upath.normalizeSafe(argv.vendorPath || '.');
const vendorShopPath = vendorPath === '.' ? '.' : upath.joinSafe(vendorPath, 'SyliusPickupPointPlugin');
const nodeModulesPath = upath.normalizeSafe(argv.nodeModulesPath);

const paths = {
    setonoPickupPoint: {
        js: [
            upath.joinSafe(vendorShopPath, 'src/Resources/private/js/**'),
        ],
    },
};

const sourcePathMap = [
    {
        sourceDir: upath.relative('', upath.joinSafe(vendorShopPath, 'src/Resources/private')),
        destPath: '/SyliusPickupPointPlugin/',
    },
    {
        sourceDir: upath.relative('', nodeModulesPath),
        destPath: '/node_modules/',
    },
];

const mapSourcePath = function mapSourcePath(sourcePath /* , file */) {
    const match = sourcePathMap.find(({ sourceDir }) => (
        sourcePath.substring(0, sourceDir.length) === sourceDir
    ));

    if (!match) {
        return sourcePath;
    }

    const { sourceDir, destPath } = match;

    return upath.joinSafe(destPath, sourcePath.substring(sourceDir.length));
};

export const buildSetonoPickupPointJs = function buildSetonoPickupPointJs() {
    return gulp.src(paths.setonoPickupPoint.js, { base: './' })
        .pipe(gulpif(env !== 'prod', sourcemaps.init()))
        .pipe(concat('setono-pickup-point.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(gulpif(env !== 'prod', sourcemaps.mapSources(mapSourcePath)))
        .pipe(gulpif(env !== 'prod', sourcemaps.write('./')))
        .pipe(gulp.dest(upath.joinSafe(shopRootPath, 'js')))
        .pipe(livereload());
};
buildSetonoPickupPointJs.description = 'Build SetonoPickupPointPlugin js assets.';

export const watchSetonoPickupPoint = function watchShop() {
    livereload.listen();

    gulp.watch(paths.setonoPickupPoint.js, buildSetonoPickupPointJs);
};
watchSetonoPickupPoint.description = 'Watch SetonoPickupPointPlugin asset sources and rebuild on changes.';

export const build = gulp.parallel(buildSetonoPickupPointJs);
build.description = 'Build assets.';

export const watch = gulp.parallel(build, watchSetonoPickupPoint);
watch.description = 'Watch asset sources and rebuild on changes.';

gulp.task('setono-pickup-point-js', buildSetonoPickupPointJs);
gulp.task('setono-pickup-point-watch', watchSetonoPickupPoint);

export default build;
