define([
    'backbone',
    'tpl!templates/hero.html'
],
	
	function(backbone,hero) {

  	return Backbone.View.extend({
        el:'#hero',
        template:hero,				
	    initialize : function() {
           
	    },
        
        events : {
            "click .row-item" : "loadArticle"
        },
        
	    onRender : function(collection){
            
            
            var ind = Math.floor(Math.random() * 9);
	        
	        $('#hero').css({
	            "background-image":"url('"+collection[0][this.current.settings[ind]][0].hero+"')",
	        }).html(hero({
	            title:collection[0][this.current.settings[ind]][0].title
	        }));
	        
	        
	    },
        
        loadArticle : function(e){
            $('#site-wrapper').animate({left: '-100%'}, 200);
            
            var itemId=$(e.currentTarget).attr('data-itemid').split('_item_'),
                key =itemId[0],
                val =itemId[1]; 
            this.current.article = this.collection.toJSON()[0][key][val];
            
            if(!this.article){
                this.article = new Article({collection: new ArticleCollection()});
            }
            
            console.log("render directly", this.current.article)
            if(this.current.article.content){
                
                this.article.renderDirectly(this.current.article);
            }
            else{
                
                this.article.collection.getData(this.current.article.url,key);
            }
            
        }
       
    });

});
