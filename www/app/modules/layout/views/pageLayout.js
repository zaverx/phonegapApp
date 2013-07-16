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
            
            
            var collection =this.collection.toJSON();
            var ind = Math.floor(Math.random() * 9);
            this.current={
                settings:["skai.gr","real.gr","newsbeast.gr","kathimerini.gr","enet.gr","protothema.gr","tovima.gr","rizospastis.gr","metrogreece.gr","lifo.gr"]
            }
	        
            
            console.log(collection, collection[0][this.current.settings[ind]][0].hero)
	        $('#hero').css({
	            "background-image":"url('"+collection[0][this.current.settings[ind]][0].hero+"')",
	        }).html(hero({
	            title:collection[0][this.current.settings[ind]][0].title
	        }));
	        
	       $('#tab-content').html(this.template({
                response:collection[0],
                rows : this.current.settings
                
            }));  
	        
	    }
       
    });

});
