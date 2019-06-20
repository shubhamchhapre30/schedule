/**
 * @author comp-117
 */
 var AjaxFunction = function () {
 	
 	var baseThemeUrl='';
 	var redirectPage='';
 	    var testHandle=function()
 	    {
 	    	alert(baseThemeUrl);
 	    }
         return {

        //main function to initiate template pages
        init: function (option) {
        	
        	baseThemeUrl=option['baseThemeUrl'];
 			redirectPage=option['redirectPage'];
 			
 			testHandle();
 			
        	}
        }
}();