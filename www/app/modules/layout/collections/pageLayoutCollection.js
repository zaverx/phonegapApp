define([
    'backbone',
    "modules/layout/models/pageLayoutModel",
],
	
function(backbone,PageLayoutModel) {

  	return Backbone.Collection.extend({
        model:PageLayoutModel,
        url : './server/classes/requestNews.php',
	    getData : function() {
            this.fetch({
                dataType:'text'
                
            });
	    },
        parse :function(response){
            var x = JSON.parse(response)
            console.log(response.toJSON());
        }

    });

});
