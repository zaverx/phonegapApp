define([
    'backbone',
    'tpl!templates/tabs.html',
    'tpl!templates/hero.html'
],
	
	function(backbone,tabs,hero) {

  	return Backbone.View.extend({
        template:tabs,				
	    initialize : function() {
            this.listenTo(this.collection, "sync", this.onRender);
	    },
	    onRender : function(){
	        
	        $('#hero').css({
	            "background-image":"url('"+this.collection.toJSON()[1].hero+"')",
	        }).html(hero({
	            title:this.collection.toJSON()[1].title
	        }));
	        
	       $('#tab-content').html(this.template({
                response: this.collection.toJSON()
                
            }));  
	        
	    }
       
    });

});
