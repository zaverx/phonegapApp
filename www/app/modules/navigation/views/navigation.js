define([
    'backbone',
    'tpl!templates/tabs.html',
    'tpl!templates/hero.html',
    'modules/articles/views/article',
    'modules/articles/collections/ArticleCollection'
],
	
	function(backbone,tabs,hero, Article, ArticleCollection) {

  	return Backbone.View.extend({
        el:'#page-menu',
        			
	    initialize : function() {
            
	    },
        
        events : {
            
            "click #options"  : "showMain"
        },
            
        
        showMain : function(){
            $('#site-wrapper').animate({left: '0'}, 200);
            this.dispatch.trigger("closeArticle");
        }
         
       
    });

});
