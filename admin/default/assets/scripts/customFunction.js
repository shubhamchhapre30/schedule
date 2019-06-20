/**
 * @author comp-117
 */
// var Custom = function () {
 function blockUI(el, centerY) {
			
            var el = jQuery(el); 
            el.block({
                    message: '<img src="'+baseThemeUrl+'/assets/img/ajax-loading.gif" align="">',
                    centerY: centerY != undefined ? centerY : true,
                    css: {
                        top: '10%',
                        border: 'none',
                        padding: '2px',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.08,
                        cursor: 'wait'
                    }
                });
        }
        function unblockUI(el) {
        	
            jQuery(el).unblock({
                    onUnblock: function () {
                        jQuery(el).removeAttr("style");
                    }
                });
        }
//}