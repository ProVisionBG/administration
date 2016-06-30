var elixir = require('laravel-elixir');
var autoprefixer = require('gulp-autoprefixer');


elixir.config.css.autoprefix = {
    enabled: false,
    options: {
        browsers: ['last 5 versions', '> 1%'],
        cascade: true,
        remove: false
    }
};

elixir(function (mix) {
    mix.combine([
        'resources/assets/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'resources/assets/bower_components/font-awesome/css/font-awesome.min.css',
        'resources/assets/bower_components/ionicons/css/ionicons.min.css',
        'resources/assets/bower_components/iCheck/skins/square/blue.css',
        'resources/assets/bower_components/datatables/media/css/dataTables.bootstrap.css',
        'resources/assets/bower_components/jquery-ui/themes/base/jquery-ui.min.css',
        'resources/assets/bower_components/dropzone/dist/dropzone.css',
        'resources/assets/bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.css',

        'resources/assets/bower_components/AdminLTE/dist/css/AdminLTE.min.css',
        'resources/assets/bower_components/AdminLTE/dist/css/skins/_all-skins.min.css',
        'resources/assets/css/styles.css'
    ], 'public/assets/css/all.css');


    mix.combine([
        'resources/assets/bower_components/jquery/dist/jquery.min.js',
        'resources/assets/bower_components/jquery-ui/jquery-ui.min.js',
        'resources/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        'resources/assets/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'resources/assets/bower_components/iCheck/icheck.js',
        'resources/assets/bower_components/fastclick/lib/fastclick.js',
        'resources/assets/bower_components/ckeditor/ckeditor.js',
        'resources/assets/bower_components/datatables/media/js/jquery.dataTables.min.js',
        'resources/assets/bower_components/datatables/media/js/dataTables.bootstrap.min.js',
        'resources/assets/bower_components/datatables-responsive/js/dataTables.responsive.js',
        'resources/assets/bower_components/datatables-rowreorder/js/dataTables.rowReorder.js',
        'resources/assets/bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js',
        'resources/assets/bower_components/jqueryui-timepicker-addon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js',
        'resources/assets/bower_components/jquery-validation/dist/jquery.validate.min.js',
        'resources/assets/bower_components/jquery-validation/src/localization/messages_bg.js',
        'resources/assets/bower_components/AdminLTE/dist/js/app.min.js',

        'resources/assets/js/scripts.js'
    ], 'public/assets/js/all.js');

    //mix.version(["public/assets/css/all.css", "public/assets/js/all.js"],'public/vendor/provision/administration');

    mix.copy('resources/assets/bower_components/font-awesome/fonts', 'public/assets/fonts/')
        .copy('resources/assets/bower_components/glyphicons/fonts', 'public/assets/fonts/')
        .copy('resources/assets/bower_components/iCheck/skins/square/blu*.png', 'public/assets/css/');

});


