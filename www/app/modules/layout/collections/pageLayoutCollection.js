define([
    'backbone',
    "modules/layout/models/pageLayoutModel",
],
	
function(backbone,PageLayoutModel) {

  	return Backbone.Collection.extend({
        model:PageLayoutModel,
        url : './json/results.json',
        //url : './server/controler/controler.php',
	    getData : function() {
	        
            this.fetch({
                dataType : "json",
                error : function(response,xhr){
                    //console.log(response)
                    console.log(xhr.status, xhr.statusText)
                },
                success : function(){
                    console.log('success')
                }
                
            });
           
	    },
        parse :function(response){
            
            return response;
           
        }

    });

});
