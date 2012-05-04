define( [
  'aloha',
  'aloha/jquery',
  'aloha/plugin',
  'aloha/floatingmenu',
  'css!save/css/save.css'
], function( Aloha, jQuery, plugin, FloatingMenu ) {
    "use strict";

    return plugin.create( 'save', {
        init: function() {
            // Executed on plugin initialization
            this.createButtons();

            var that = this;
        },

        /**
         * Creates buttons in toolbar
         */
        createButtons: function () {
            var that = this;

            // create a new button
            this.savedbButton = new Aloha.ui.Button({
              'name' : 'save',
              'iconClass' : 'aloha-button-save',
              'size' : 'small',
              'onclick' : function () { that.save() },
              'tooltip' : 'Save to DB',
              'toggle' : false
            });

            FloatingMenu.addButton(
              'Aloha.continuoustext',
              this.savedbButton,
              'Save',
              1
            );
        },

        /**
         * Sends content to save
         */
        save: function () {
            var params = {
                name:       Aloha.activeEditable.obj.data("name"),
                body:       Aloha.activeEditable.getContents()
            };

            var saveUrl = jQuery("#aloha-save-url").val();

            if (saveUrl === "") {
                return;
            }

            jQuery.post(
                saveUrl,
                params
            );
        }
    });
});