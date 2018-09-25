import chug from 'gulp-chug';
import gulp from 'gulp';
import yargs from 'yargs';

const { argv } = yargs
  .options({
    rootPath: {
      description: '<path> path to web assets directory',
      type: 'string',
      requiresArg: true,
      required: false,
    },
    nodeModulesPath: {
      description: '<path> path to node_modules directory',
      type: 'string',
      requiresArg: true,
      required: false,
    },
  });

const config = [
  '--rootPath',
  argv.rootPath || '../../../../../../../tests/Application/web/assets',
  '--nodeModulesPath',
  argv.nodeModulesPath || '../../../../../../../tests/Application/node_modules',
];

const setonoPickupPointConfig = [
    '--rootPath',
    argv.rootPath || 'tests/Application/web/assets',
    '--nodeModulesPath',
    argv.nodeModulesPath || 'tests/Application/node_modules',
];

// admin assets
export const buildAdmin = function buildAdmin() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config }));
};
buildAdmin.description = 'Build admin assets.';

export const watchAdmin = function watchAdmin() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config, tasks: 'watch' }));
};
watchAdmin.description = 'Watch admin asset sources and rebuild on changes.';

// shop assets
export const buildShop = function buildShop() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config }));
};
buildShop.description = 'Build shop assets.';

export const watchShop = function watchShop() {
  return gulp.src('../../vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/gulpfile.babel.js', { read: false })
    .pipe(chug({ args: config, tasks: 'watch' }));
};
watchShop.description = 'Watch shop asset sources and rebuild on changes.';

// setono pickup point plugin assets
export const buildSetonoPickupPoint = function buildSetonoPickupPoint() {
    return gulp.src('../../gulpfile.babel.js', { read: false })
        .pipe(chug({ args: setonoPickupPointConfig }));
};
buildSetonoPickupPoint.description = 'Build SetonoPickupPointPlugin assets.';

export const watchSetonoPickupPoint = function watchSetonoPickupPoint() {
    return gulp.src('../../gulpfile.babel.js', { read: false })
        .pipe(chug({ args: setonoPickupPointConfig, tasks: 'watch' }));
};
watchSetonoPickupPoint.description = 'Watch SetonoPickupPointPlugin asset sources and rebuild on changes.';

export const build = gulp.parallel(buildAdmin, buildShop, buildSetonoPickupPoint);
build.description = 'Build assets.';

gulp.task('admin', buildAdmin);
gulp.task('admin-watch', watchAdmin);
gulp.task('shop', buildShop);
gulp.task('shop-watch', watchShop);
gulp.task('setono-pickup-point', buildSetonoPickupPoint);
gulp.task('setono-pickup-point-watch', watchSetonoPickupPoint);

export default build;
