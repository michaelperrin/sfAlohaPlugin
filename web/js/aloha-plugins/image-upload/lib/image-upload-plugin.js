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
        /**
         * Initalization executed on plugin load
         */
        init: function() {
            this.createButtons();
            this.initImageUpload();
        },

        /**
         * Adds button to the Aloha ribbon
         */
        createButtons: function() {
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

        /**
         * Image button click
         */
        insertImageButtonClick: function() {
            // Simulate click on the form file input element
            jQuery('#sf_aloha_image_upload_image').click();
        },

        /**
         * Inits image upload element
         */
        initImageUpload: function() {
            var formData = false;
            var that = this;

            if (window.FormData) {
                formData = new FormData();
            }

            jQuery("#sf_aloha_image_upload_image").change(function () {
                var i = 0, len = this.files.length, img, reader, file;

                if (len >= 1) {
                    file = this.files[0];

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

        /**
         * Displays the uploaded image
         *
         * @param string fileName name of the file to insert
         */
        showUploadedItem: function(fileName) {
            var range = Aloha.Selection.getRangeObject();
            if (Aloha.activeEditable) {
                var img = jQuery("<img />").attr("src", fileName);

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
