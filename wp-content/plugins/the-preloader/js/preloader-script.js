jQuery(window).load(function() {

    jQuery('#wptime-plugin-preloader').delay(150).fadeOut(2000);

    setTimeout(wptime_plugin_remove_preloader, 2000);
    function wptime_plugin_remove_preloader() {
        jQuery('#wptime-plugin-preloader').remove();
    }

});