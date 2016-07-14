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





