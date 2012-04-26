define( [
  'aloha',
  'aloha/jquery',
  'aloha/plugin',
  'aloha/floatingmenu',
  'css!image-upload/css/image-upload.css'
],
function(Aloha, jQuery, Plugin, FloatingMenu) {
    "use strict";

    return Plugin.create( 'image-upload', {
        init: function() {
            // Executed on plugin initialization
            this.createButtons();
            this.initImageUpload();
        },

        createButtons: function () {
            var that = this;

            // create a new button
            this.imageUploadButton = new Aloha.ui.Button({
              'name' : 'image-upload',
              'iconClass' : 'aloha-button-image-upload',
              'size' : 'small',
              'onclick' : function () { that.insertImageButtonClick(); },
              'tooltip' : 'Upload and insert an image at cursor position',
              'toggle' : false
            });

            FloatingMenu.addButton(
              'Aloha.continuoustext',
              this.imageUploadButton,
              'Insert',
              1
            );
        },

        insertImageButtonClick: function() {
            this.initUploadForm();

            jQuery('#aloha-image-upload-form input[name="image"]').click();
        },

        /**
         * Inits upload form
         */
        initUploadForm: function() {

          console.log("setse");
            var formElt = jQuery("#aloha-image-upload-form");

            if (formElt.size() == 1) {
                // The form has already been initialized
                return;
            }

            formElt = jQuery('<form method="post" enctype="multipart/form-data" id="aloha-image-upload-form"></form>');
            formElt.attr('action', jQuery('#aloha-image-upload-form-url').val());

            var inputFileElt    = jQuery('<input type="file" name="image"/>');
            var submitElt       = jQuery('<button type="submit"></button>');

            inputFileElt.appendTo(formElt);
            submitElt.appendTo(formElt);

            formElt.css({
                "visibility": "hidden",
                "height": "0",
                "width": "0",
                "overflow": "hidden",
            });

            formElt.insertAfter(jQuery('#' + Aloha.activeEditable.getId()));

            this.initImageUpload();
        },

        // TODO : jsdoc
        initImageUpload: function() {
            var formData = false;
            var that = this;

            if (window.FormData) {
                formData = new FormData();
            }

            jQuery('#aloha-image-upload-form input[name="image"]').change(function () {
                var i = 0, len = this.files.length, img, reader, file;

                if (len >= 1) {
                    file = this.files[0];

                    // TODO : check
                    if (!!file.type.match(/image.*/)) {
                        if ( window.FileReader ) {
                            reader = new FileReader();
                            reader.readAsDataURL(file);
                        }
                        if (formData) {
                            formData.append("image", file);
                        }
                    }
                }

                if (formData) {
                    jQuery.ajax({
                        url: jQuery("#aloha-image-upload-form").attr("action"),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) { that.showUploadedItem(data.imageUrl); },
                        dataType: "json"
                    });
                }
            });
        },

        // TODO : jsdoc
        showUploadedItem: function(fileName) {
            var range = Aloha.Selection.getRangeObject();
            if (Aloha.activeEditable) {
                // TODO : change
                var img = jQuery('<p><img src="' + fileName + '"/></p>');
                GENTICS.Utils.Dom.insertIntoDOM(
                    img,
                    range,
                    jQuery(Aloha.activeEditable.obj),
                    true
                );
                range.select();
            }
        }
    });
});
