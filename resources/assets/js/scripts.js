$(function () {
    'use strict';

    /*
     navigation toggle set cookies
     */
    $('#slidebar-toggle-button').click(function (e) {
        $.cookie('administration-navigation-collapsed', !$('body').hasClass('sidebar-collapse'), {expires: 777, path: "/"});
    });

    /*
     init tinymce
     */
    tinymce.init({
        selector: "textarea.provision-editor",
        height: 400,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: 'Test 1'},
            {title: 'Test template 2', content: 'Test 2'}
        ],
    });

});