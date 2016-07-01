/*
 Media manager
 */
function runMedia(id) {
    var modal = $(id);
    console.log(modal);
    modal.find('input.upload-input').fileupload({
        url: 'test',
        dataType: 'json',
        add: function (e, data) {
            data.context = $('<p/>').text('Uploading...').appendTo(document.body);
            data.submit();
        },
        done: function (e, data) {
            data.context.text('Upload finished.');
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
}