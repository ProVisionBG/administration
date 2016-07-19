CKEDITOR.dialog.add('media', function (editor) {

    /*
     load media items
     */
    var formID = $('textarea[name="' + editor.name + '"]').closest('form').attr('id');
    if (formModels[formID] === undefined) {
        return {
            title: 'media error',
            contents: [
                {
                    id: 'info',
                    elements: [
                        {
                            type: 'html',
                            label: 'Media items',
                            id: 'items',
                            html: '<div id="media-container">Error formModels[' + formID + '] is undefined!</div>'
                        },
                    ]
                }]
        };
    }

    var itemsContainer;
    $.ajax({
        method: "GET",
        async: false,
        url: mediaRouteIndex,
        data: {
            mode: 'ckeditor',
            itemId: formModels[formID].id,
            moduleName: formModels[formID].module,
            moduleSubName: formModels[formID].sub_module
        }
    }).done(function (response) {
        itemsContainer = response;
    });


    return {
        title: 'Insert media',
        minWidth: 800,
        minHeight: 200,
        contents: [
            {
                id: 'info',
                elements: [
                    {
                        type: 'html',
                        label: 'Media items',
                        id: 'item',
                        html: '<div id="media-container">' + itemsContainer + '</div>',
                        setup: function (widget) {
                            if (widget.data.item !== undefined) {
                                $('#media-container input[data-id=' + widget.data.item.id + ']').prop('checked', true);
                            }
                        },
                        commit: function (widget) {
                            var item = {};
                            $('#media-container input[type=radio]:checked').each(function (index, el) {
                                item = {
                                    data: $(this).data()
                                };
                            });
                            widget.setData('item', item);
                        }
                    },
                    {
                        type: 'text',
                        id: 'caption',
                        label: 'caption',
                        'default': '',
                        setup: function (widget) {
                            //console.log(widget.data.caption);
                            this.setValue(widget.data.caption);
                        },
                        commit: function (widget) {
                            widget.setData('caption', this.getValue());
                        }
                    },
                    {
                        type: 'text',
                        id: 'alt',
                        label: 'alt',
                        'default': '',
                        setup: function (widget) {
                            this.setValue(widget.data.alt);
                        },
                        commit: function (widget) {
                            widget.setData('alt', this.getValue());
                        }
                    },
                    {
                        id: 'align',
                        type: 'select',
                        label: 'Align',
                        items: [
                            [editor.lang.common.notSet, ''],
                            [editor.lang.common.alignLeft, 'left'],
                            [editor.lang.common.alignRight, 'right'],
                            [editor.lang.common.alignCenter, 'center']
                        ],
                        // When setting up this field, set its value to the "align" value from widget data.
                        // Note: Align values used in the widget need to be the same as those defined in the "items" array above.
                        setup: function (widget) {
                            this.setValue(widget.data.align);
                        },
                        // When committing (saving) this field, set its value to the widget data.
                        commit: function (widget) {
                            widget.setData('align', this.getValue());
                        }
                    }
                ]
            }
        ]
    };
});