define([
  // Application.
  "app",
  "modules/layout/views/pageLayout",
  "modules/navigation/views/navigation",
  "modules/layout/collections/pageLayoutCollection"
],

function(app, PageLayout, Navigation, PageLayoutCollection) {

  // Defining the application router, you can attach sub routers here.
  var Router = Backbone.Router.extend({
    routes: {
      "": "index"
    },
    initialize : function(){
        
      this.layout = new PageLayout({collection:new PageLayoutCollection()});
      this.nav = new Navigation();
      this.index();
      
    },
    index: function() {
        
        this.layout.collection.getData();
    }
  });

  return Router;

});
