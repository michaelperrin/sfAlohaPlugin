/**
 * Handles Aloha Editor
 */
var AlohaEditor = {
    /**
     * Inits Aloha
     */
    init: function() {
        ( function ( window, undefined ) {
            var Aloha = window.Aloha || ( window.Aloha = {} );

            Aloha.settings = {
                logLevels: { 'error': false, 'warn': false, 'info': false, 'debug': false, 'deprecated': false },
                errorhandling: false,
                ribbon: false,
                floatingmenu: {
                    width: 630,
                    behaviour: 'topalign'
                },
                bundles: {
                    sfAloha: '../../../js/aloha-plugins'
                },
                jQuery: $
            };
        } )( window );

        AlohaEditor.initEditableElements();
    },

    /**
     * Inits elements to be used with Aloha
     */
    initEditableElements: function() {
        Aloha.ready(function() {
            Aloha.require(['aloha', 'aloha/jquery'], function(Aloha, $) {
                $('.editable').aloha();
            });
        });
    }
};
