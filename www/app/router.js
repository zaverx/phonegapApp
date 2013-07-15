define([
  // Application.
  "app",
  "modules/layout/views/pageLayout",
  "modules/layout/collections/pageLayoutCollection"
],

function(app, PageLayout, PageLayoutCollection, PageLayoutTpl) {

  // Defining the application router, you can attach sub routers here.
  var Router = Backbone.Router.extend({
    routes: {
      "": "index"
    },
    initialize : function(){
      this.layout = new PageLayout({collection:new PageLayoutCollection()});
      this.index();
      
    },
    index: function() {
        
        this.layout.collection.getData();
    }
  });

  return Router;

});
