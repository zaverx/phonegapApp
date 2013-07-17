define([
    'backbone',
    "modules/articles/models/articleModel"
],
	
function(backbone, ArticleModel) {

  	return Backbone.Collection.extend({
        model:ArticleModel,
        //url : './json/results.json',
        url : "http://localhost/~ahaitalis/phonegapApp/www/server/controler/controler.php",
	    getData : function(link) {
	   
            this.fetch({
                type:'POST',
                data:{lnk:link, flag:'article'},
                dataType : 'json',
                error : function(response,xhr){
                    console.log(response,xhr)
                    console.log(xhr.status, xhr.statusText)
                },
                success : function(response,xhr){
                    console.log("success")
                }
                
            });
           
	    },
        parse :function(response){
            
            return response;
           
        }

    });

});
