$(function () {
    'use strict';

    /*
     navigation toggle set cookies
     */
    $('#slidebar-toggle-button').click(function (e) {
        $.cookie('administration-navigation-collapsed', !$('body').hasClass('sidebar-collapse'), {
            expires: 777,
            path: "/"
        });
    });

    if (typeof window.tinymceConfig == 'undefined') {
        window.tinymceConfig = {}; //само на вътрешните страници се генерира конфигурацията на tinymce - в layouts.master
    }
    tinymce.init(window.tinymceConfig);

    /*
     jQuery confirm default settings
     */
    jconfirm.defaults = {
        theme: 'supervan',
        columnClass: 'col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
    }

    /**
     * Лимит на инпутите
     *
     * data-minlength
     * data-maxlength
     */
    $('input[data-maxlength]').each(function () {
        var $this = $(this);

        var current = $this.val().length;
        var min = ($this.data('minlength') ? $this.data('minlength') : 0);
        var max = $this.data('maxlength');
        var formGroup = $this.closest('div.form-group');
        var counter = $('<span class="help-block length-counter">' + current + '/' + max + '</span>');
        $this.after(counter);

        function changeInput() {
            current = $this.val().length;

            //remove old classes
            formGroup.removeClass(function (index, className) {
                return (className.match(/(^|\s)has-\S+/g) || []).join(' ');
            });

            if (current > max || current < min) {
                formGroup.addClass('has-error');
            } else if (current >= min && current <= max) {
                formGroup.addClass('has-success');
            }

            counter.html(current + '/' + max);
        }

        $this.bind('input', changeInput);
        changeInput();
    });

    /**
     * Color pickers
     */
    $(".color-picker").colorpicker();

});