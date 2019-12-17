// Register the plugin within the editor.
CKEDITOR.plugins.add('media', {
    // This plugin requires the Widgets System defined in the 'widget' plugin.
    requires: 'widget',

    // Register the icon used for the toolbar button. It must be the same
    // as the name of the widget.
    icons: 'media',

    // The plugin initialization logic goes inside this method.
    init: function (editor) {
        // Register the editing dialog.
        CKEDITOR.dialog.add('media', this.path + 'dialogs/media.js');

        // Register the simplebox widget.
        editor.widgets.add('media', {
            // Allow all HTML elements, classes, and styles that this widget requires.
            // Read more about the Advanced Content Filter here:
            // * http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter
            // * http://docs.ckeditor.com/#!/guide/plugin_sdk_integration_with_acf
            allowedContent: 'figure(!media-box,align-left,align-right,align-center){width}{data-*};' +
            'figcaption;',

            // Minimum HTML which is required by this widget to work.
            requiredContent: 'figure(media-box)',

            // Define two nested editable areas.
            editables: {
                // caption: {
                //     // Define CSS selector used for finding the element inside widget element.
                //     selector: 'figcaption',
                //     // Define content allowed in this nested editable. Its content will be
                //     // filtered accordingly and the toolbar will be adjusted when this editable
                //     // is focused.
                //     allowedContent: 'p b strong em i a br'
                // }
                // content: {
                //     selector: '.simplebox-content',
                //     allowedContent: 'p br ul ol li strong em'
                // }
            },


            // Define the template of a new Simple Box widget.
            // The template will be used when creating new instances of the Simple Box widget.
            // template: '<div class="media-box">' +
            // '<div class="media-box-content">Image</div>' +
            // '<div class="media-box-note">note</div>' +
            // '</div>',

            template: '<figure class="media-box">' +
            '<img class="img-responsive img-fluid" />' +
            '<figcaption>img caption</figcaption>' +
            '</figure>',

            // Define the label for a widget toolbar button which will be automatically
            // created by the Widgets System. This button will insert a new widget instance
            // created from the template defined above, or will edit selected widget
            // (see second part of this tutorial to learn about editing widgets).
            //
            // Note: In order to be able to translate your widget you should use the
            // editor.lang.simplebox.* property. A string was used directly here to simplify this tutorial.
            button: 'Insert media item',

            // Set the widget dialog window name. This enables the automatic widget-dialog binding.
            // This dialog window will be opened when creating a new widget or editing an existing one.
            dialog: 'media',

            // Check the elements that need to be converted to widgets.
            //
            // Note: The "element" argument is an instance of http://docs.ckeditor.com/#!/api/CKEDITOR.htmlParser.element
            // so it is not a real DOM element yet. This is caused by the fact that upcasting is performed
            // during data processing which is done on DOM represented by JavaScript objects.
            upcast: function (element) {
                // Return "true" (that element needs to converted to a Simple Box widget)
                // for all <div> elements with a "simplebox" class.
                return element.name == 'figure' && element.hasClass('media-box');
            },

            // When a widget is being initialized, we need to read the data ("align" and "width")
            // from DOM and set it by using the widget.setData() method.
            // More code which needs to be executed when DOM is available may go here.
            init: function () {
                // console.log('init');
                var width = this.element.getStyle('width');
                if (width) {
                    this.setData('width', width);
                }

                if (this.element.hasClass('align-left')) {
                    this.setData('align', 'left');
                } else if (this.element.hasClass('align-right')) {
                    this.setData('align', 'right');
                } else if (this.element.hasClass('align-center')) {
                    this.setData('align', 'center');
                }

                this.setData('caption', $(this.element.$).find('figcaption:first').text());

                var img = $(this.element.$).find('img:first');
                this.setData('alt', img.attr('alt'));

                //console.log(img.data());
                if (img.data() != undefined) {
                    this.setData('item', img.data());
                } else {
                    this.setData('item', {});
                }
            },

            // Listen on the widget#data event which is fired every time the widget data changes
            // and updates the widget's view.
            // Data may be changed by using the widget.setData() method, which we use in the
            // Simple Box dialog window.
            data: function () {
                // Check whether "width" widget data is set and remove or set "width" CSS style.
                // The style is set on widget main element (div.media-box).
                if (this.data.width == '') {
                    this.element.removeStyle('width');
                } else {
                    this.element.setStyle('width', this.data.width);
                }

                // Brutally remove all align classes and set a new one if "align" widget data is set.
                this.element.removeClass('align-left');
                this.element.removeClass('align-right');
                this.element.removeClass('align-center');
                if (this.data.align) {
                    this.element.addClass('align-' + this.data.align);
                }

                //set caption
                $(this.element.$)
                    .find('figcaption>p')
                    .html(this.data.caption);

                //set alt
                $(this.element.$).find('img')
                    .attr('alt', this.data.alt);

                //set item
                if (this.data.item != undefined) {
                    var attr = this.data.item;
                    var img = $(this.element.$).find('img');


                    if (attr.data !== undefined) {
                        img.attr('src', attr.data.path + attr.data.file);
                        img.attr('data-cke-saved-src', attr.data.path + attr.data.file);
                    }

                    img.attr('data-id', attr.id);
                    $.each(attr.data, function (key, val) {
                        img.attr('data-' + key, val);
                    })
                }
            }
        });
    }
});
