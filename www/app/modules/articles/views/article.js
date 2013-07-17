define([
    'backbone',
    'tpl!templates/iframe.html'
],
	
	function(backbone,iframe) {

  	return Backbone.View.extend({
        template:iframe,				
	    initialize : function() {
           this.listenTo(this.collection, "sync", this.onRender);
	    },
	    onRender : function(){
            var collection = this.collection.toJSON();
	        $('#article-wrapper').html(collection[0].content);  
            $('#article-wrapper').animate({left: '0'}, 500);
            
	    }
       
    });

});
