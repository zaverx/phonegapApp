define([
    'backbone',
    'tpl!templates/iframe.html',
    'tpl!templates/article.html'
],
	
	function(backbone,IframeTemplate,ArticleTemplate) {

  	return Backbone.View.extend({
        
        id:'article-view',
        tagName:'span',
	    initialize : function() {
           this.listenTo(this.collection, "sync", this.onRender);
           this.listenTo(this.dispatch, "closeArticle", this.onClose);
	    },
        renderDirectly : function(obj){
              this.showContent([obj]);
        },
	    onRender : function(){
            this.showContent(this.collection.toJSON());
	    },
        
        showContent : function(collection){
            
            $('#article-wrapper').html(this.$el);            
            
            if(_.isEmpty(collection)){
                this.$el.html(IframeTemplate({
                    target :this.current.article.url
                }));  
            }
            else{
                
                this.$el.html(ArticleTemplate({
                    content : collection[0].content,
                    articleTitle : this.current.article.title
                }));  
                
                
                $('#article-hero').show();
                
                if(collection[0].mainImage){
                    console.log("has:::",collection[0].mainImage);
                    $('#article-hero').css({
                        "background-image":"url('"+collection[0].mainImage+"')",
                    });    
                }
                else{
                    console.log("not has:::",collection[0].mainImage);
                    $('#article-hero').hide();                           
                }
                this.$el.scrollTop(0);
                
            }
        },
        
        onClose : function(){
            this.$el.html("");
        }
       
    });

});
