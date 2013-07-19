define([
    'backbone',
    "modules/articles/models/articleModel"
],
	
function(backbone, ArticleModel) {

  	return Backbone.Collection.extend({
        model:ArticleModel,
      //url : './json/results.json',
        url : "http://localhost/~ahaitalis/phonegapApp/www/server/controler/controler.php",
	    getData : function(link,handler) {
	        /*case "kathimerini.gr":
             iframe
              break;
            */
            
            
            
            this.fetch({
                type:'POST',
                data:{lnk:link, flag:'article',key:handler},
                dataType : 'json',
                error : function(response,xhr){
                    console.log(response,xhr)
                    console.log(xhr.status, xhr.statusText)
                },
                success : function(response,xhr){
                    console.log("success", link)
                }
                
            });
           
	    },
        parse :function(response){
            
            return response;
           
        }

    });

});
