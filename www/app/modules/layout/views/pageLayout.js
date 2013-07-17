define([
    'backbone',
    'tpl!templates/tabs.html',
    'tpl!templates/hero.html',
    'modules/articles/views/article',
    'modules/articles/collections/ArticleCollection'
],
	
	function(backbone,tabs,hero, Article, ArticleCollection) {

  	return Backbone.View.extend({
        el:'#tab-content',
        template:tabs,				
	    initialize : function() {
            this.listenTo(this.collection, "sync", this.onRender);
	    },
        
        events : {
            "click .row-item" : "loadArticle"
        },
        
	    onRender : function(){
            
            var collection =this.collection.toJSON();
            var ind = Math.floor(Math.random() * 9);
            this.current={
                settings:["skai.gr","real.gr","newsbeast.gr","kathimerini.gr","enet.gr","protothema.gr","tovima.gr","rizospastis.gr","metrogreece.gr","lifo.gr"]
            }
	        
	        $('#hero').css({
	            "background-image":"url('"+collection[0][this.current.settings[ind]][0].hero+"')",
	        }).html(hero({
	            title:collection[0][this.current.settings[ind]][0].title
	        }));
	        
	        this.$el.html(this.template({
                response:collection[0],
                rows : this.current.settings
                
            }));  
	       
	    },
        
        loadArticle : function(e){
            
            var itemId=$(e.currentTarget).attr('data-itemid').split('_item_'),
                key =itemId[0],
                val =itemId[1]; 
            
            if(!this.article){
                this.article = new Article({collection: new ArticleCollection()});
            }
            var url = this.collection.toJSON()[0][key][val].url;
            this.article.collection.getData(url);
            
        }
         
       
    });

});
