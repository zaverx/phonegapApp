define([
    'backbone'
],
	
	function(backbone) {

  	return Backbone.View.extend({
        el             : "#page-wrapper",
        template       : "layout",
        
				
	    initialize : function() {
            this.listenTo(this.collection, "sync", this.onrender);
	    },
        onrender : function(){
            
          //console.log(this.collection);
          return {};  
            
        }
    });

});
