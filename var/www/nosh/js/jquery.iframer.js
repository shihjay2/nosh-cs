/*
 * jQuery iFramer
 * By: Trent Richardson [http://trentrichardson.com]
 * Version 0.1
 * Last Modified: 6/5/2009
 * 
 * Copyright 2009 Trent Richardson
 * Dual licensed under the MIT and GPL licenses.
 * http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 * http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 * 
 */
(function($){

	$.fn.extend({
		iframer: function(options){
			options = $.extend({},{ iframe: 'iframer_iframe', returnType: 'html', onComplete:function(){} },options);
			
			var $theframe = $('<iframe name='+ options.iframe +' id="'+ options.iframe +'" width="0" height="0" frameborder="0" style="border: none; display: none; visibility: hidden;"></iframe>');

			$(this).append($theframe).attr('target',options.iframe).submit(function(){
				$('#'+ options.iframe).load(function(){
					var data = $('#'+ options.iframe).contents().find('body').html();
					if(options.returnType.toLowerCase() == 'json')
						eval('data='+ data);
					options.onComplete(data);
					$('#'+ options.iframe).contents().find('body').html('');
					$('#'+ options.iframe).unbind('load');
				});
				
				return true;
			});			
		}
	});

})(jQuery);
