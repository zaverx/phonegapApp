define([
    'backbone',
    "modules/layout/models/pageLayoutModel",
],
	
function(backbone,PageLayoutModel) {

  	return Backbone.Collection.extend({
        model:PageLayoutModel,
        //url : './json/results.json',
        url : "http://localhost/~ahaitalis/phonegapApp/www/server/controler/controler.php",
	    getData : function() {
	   
            this.fetch({
                dataType : 'json',
                error : function(response,xhr){
                    console.log(response,xhr)
                    console.log(xhr.status, xhr.responseText)
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
