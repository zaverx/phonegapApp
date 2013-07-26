(function( $ ){
    
    /* Plugin handlers and settings.*/
    var settings ={
            refreshContent : '.refresh-content',
            contentWrapper : '.content-wrapper'   
    };
        
            
    $.pullRefreshDone = function(){
        
    }
    
	$.fn.pullRefresh = function( params ) {
        
        var container    = $(this),
            content      = $(settings.contentWrapper),
            handle       = $(settings.refreshContent),
			handleHeight = handle.height();
        
            /* Push content up 1px to let you scroll down initially  */
			content.on('touchstart', function () {
                if (container.scrollTop() == 0) {
                    container.scrollTop(1);
				}
			}).on('touchmove', function () {
                var topPos =  container.scrollTop();
                console.log(topPos)
                //if(-topPos)
			   handle.css("position",'static');  
			}).on('touchend', function() {
                 var topPos =  container.scrollTop();
                if(topPos>0){
                    handle.css("position",'absolute');
                    container.scrollTop(1);
                    return;
                }
                if(params.callback){
                    params.callback();  
                }
			});
		

	};
})( jQuery );