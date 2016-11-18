$(function () {
    'use strict';

    /*
     navigation toggle set cookies
     */
    $('#slidebar-toggle-button').click(function (e) {
        $.cookie('administration-navigation-collapsed', !$('body').hasClass('sidebar-collapse'), {expires: 777, path: "/"});
    });

    /*
     init ckeditors
     */
    /*
     * Възможност за празни i/span тагове
     */
    CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
    CKEDITOR.config.protectedSource.push(/<span[^>]*><\/span>/g);

    /*
     custom plugins
     */
    CKEDITOR.plugins.addExternal('media', '/vendor/provision/administration/js/ckeditor-plugins/media/', 'plugin.js');
    //CKEDITOR.plugins.addExternal('media', '/ckeditor-plugins/media/', 'plugin.js');

    /*
     * textarea ckeditor
     */
    var ckEditorContentsCss = [];
    var ckEditors = $("textarea.provision-ckeditor");
    if (ckEditors.length) {

        // var finder = new CKFinder();
        // finder.basePath = '/ckeditor/browse/';

        ckEditors.each(function (index) {

            /*
             * add bootstrap css
             */
            ckEditorContentsCss.push('/vendor/provision/administration/css/bootstrap.min.css');
            ckEditorContentsCss.push('/vendor/provision/administration/css/ckeditor.css');
            ckEditorContentsCss.push('/vendor/provision/administration/js/ckeditor-plugins/media/dialogs/style.css');
            //ckEditorContentsCss.push('/ckeditor-plugins/media/dialogs/style.css');

            var ckEditorConfig = {
                customConfig: '',
                //extraPlugins: 'pbckcode,youtube,sourcedialog,codemirror,oembed,widget,bootstrapBreadcrumbs,bootstrapButtons,bootstrapCarousel,bootstrapCollapse,bootstrapListGroup,bootstrapPanel,bootstrapTab,textShadow',
                extraPlugins: 'media',

                /*
                 * bootstrapCarousel config
                 */
                // bootstrapCarousel_managePopupTitle : true,
                // bootstrapCarousel_managePopupCaption : true,
                // bootstrapCarousel_fileManager : 'ckfinder',
                // bootstrapCarousel_kcFinderAbsolutePath :  'http://'+domain+'/ckeditor/browse/',
                // bootstrapCarousel_fileManagerAbsolutePath : 'http://'+domain+'/ckeditor/browse/',

                /*
                 * popUp
                 */
                // bootstrapCollapse_managePopupContent : true,
                // bootstrapListGroup_managePopupContent : true,
                // bootstrapPanel_managePopupContent : true,
                // bootstrapTab_managePopupContent : true,


                /*
                 * oembed config
                 */
                // oembed_WrapperClass: 'embededContent',
                // oembed_maxWidth: '560',
                // oembed_maxHeight: '315',

                // toolbar: [['Source', 'Preview', '-', 'Templates'], ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'], ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'], ['Image', 'Youtube', 'oembed', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'], '/', ['Format'], ['TextColor', 'BGColor'], ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'], ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'pbckcode', 'CreateDiv'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Link', 'Unlink', 'Anchor'], ['Maximize', 'ShowBlocks'], '/', {
                //     name: 'insert',
                //     // items: ['BootstrapBreadcrumbs', 'BootstrapButtons', 'BootstrapCarousel', 'BootstrapCollapse', 'BootstrapListGroup', 'BootstrapPanel', 'BootstrapTab', 'TextShadow']
                // }],
                language: language,
                height: '300px',
                width: '100%',
                allowedContent: true, //allow html content
                pbckcode: {
                    cls: '',
                    highlighter: 'PRETTIFY',
                    modes: [['HTML', 'html'], ['CSS', 'css'], ['PHP', 'php'], ['JS', 'javascript'], ['Java', 'java'], ['C/C++', 'c_pp'], ['C#', 'csharp'], ['Diff', 'diff'], ['Java', 'java'], ['JSON', 'json'], ['JSP', 'jsp'], ['LESS', 'less'], ['Perl', 'perl'], ['Python', 'python'], ['R', 'ruby'], ['SCSS/Sass', 'scss'], ['SQL', 'sql'], ['XML', 'xml'], ['YAML', 'yaml']],
                    theme: 'textmate',
                    tab_size: '4'
                },
                youtube_related: false,
                disableNativeSpellChecker: false,
                resize_enabled: true,
                //filebrowserBrowseUrl : '/ckeditor/browse/',
                contentsCss: ckEditorContentsCss,
                contentsLangDirection: $(this).attr('data-direction'),
                // filebrowserUploadUrl : '/ckeditor/upload/',
            };

            $(this).ckeditor(function () {
            }, ckEditorConfig);
        });
    }

    /*
     iCheck

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
     */
});