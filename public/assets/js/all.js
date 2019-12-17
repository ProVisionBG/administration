/*
 Media manager
 */
'use strict';

function runMedia(id) {
    var modal = $('#' + id);
    var itemsContainer = modal.find('.media-items-container');

    /*
     clean data attributes
     */
    modal.removeData('bs.modal');

    /*
     clean old data if exist
     */
    itemsContainer.html('');

    /*
     load items
     */
    $.ajax({
        method: "GET",
        url: modal.attr('data-route-index'),
        data: modal.data()
    }).done(function (response) {
        itemsContainer.append(response);
    });

    /*
     upload file
     */
    modal.find('input.upload-input').fileupload({
        url: modal.attr('data-route-store'),
        method: 'POST',
        formData: modal.data(),
        add: function (e, data) {
            data.submit();
        },
        done: function (e, data) {
            itemsContainer.find('div.callout').remove(); //скриване съобщението за липсващи елементи
            itemsContainer.append(data.result);
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    /*
     sortable
     */
    if (itemsContainer.sortable("instance") !== undefined) {
        itemsContainer.sortable("destroy");
    }
    itemsContainer.sortable({
        cancel: '.callout', //да не може да се драгва съобщението за липсващи елементи
        update: function (e, ui) {
            $.ajax({
                type: "PUT",
                url: ui.item.attr('data-route-update'),
                data: {
                    'type': 'sort',
                    'before_id': ui.item.next().attr('data-id')
                },
                success: function (data) {
                    //code on success
                },
                error: function (data) {
                    //alert('Error');
                }
            });
        }
    });
    itemsContainer.disableSelection();

    /*
     style checkboxes
     */
    itemsContainer.find('input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });


    /*
     select / deselect all
     */
    modal.find('.btn-select-all').unbind('click').on('click', function () {
        var $this = $(this);

        modal.find('.media-items-container>.media-item input[type=checkbox]').each(function () {
            $(this).prop('checked', ($this.attr('data-status') == 'true' ? true : false));
        });
    });

    modal.find('.btn-delete-selected').unbind('click').on('click', function () {
        var $this = $(this);

        var checked = [];
        modal.find('.media-items-container>.media-item input[type=checkbox]:checked').each(function () {
            checked.push($(this).val());
        });

        if (checked.length < 1) {
            return false;
        }

        $.ajax({
            type: "DELETE",
            url: itemsContainer.find(".media-item").first().attr('data-route-destroy'),
            data: {
                checked: checked
            },
            success: function (data) {
                $.each(data, function (e, id) {
                    $('div.media-item[data-id=' + id + ']').remove();
                })
            },
            error: function (data) {
            }
        });


    });
}

/*
 confirmations
 */
$(document).on('click', '.modal-media button.btn-delete', function () {

    var element = $(this).closest('.media-item');

    $.confirm({
        title: translates.confirm_title,
        content: translates.confirm_text,
        confirmButton: translates.yes,
        cancelButton: translates.no,
        confirmButtonClass: 'btn-danger',
        cancelButtonClass: 'btn-info',
        confirm: function () {
            $.ajax({
                type: "DELETE",
                url: element.attr('data-route-destroy'),
                success: function (data) {
                    element.remove();
                },
                error: function (data) {
                }
            });
        },
        cancel: function () {
        }
    });
});

/*
 choice language
 */
$(document).on('click', '.media-item a.choice-lang', function () {

    var $this = $(this);

    var mediaItem = $this.closest('.media-item');

    $.ajax({
        type: "PUT",
        url: mediaItem.attr('data-route-update'),
        data: {
            'type': 'choice-lang',
            'lang': $(this).attr('data-lang')
        },
        success: function (data) {
            //select new lang icon
            mediaItem.find('button.btn-selected-lang>span').attr('lang', $this.attr('data-lang'));
        },
        error: function (data) {
            //alert('Error');
        }
    });
});






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