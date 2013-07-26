define([
    "backbone"
],
function(backbone) {
    
    var init = {
        dispatch : _.extend({}, Backbone.Events)
    };
    
    var current = {
        
        article:{},
        settings:["info-war.gr","skai.gr","real.gr","newsbeast.gr","newsbomb.gr", "kathimerini.gr","enet.gr","protothema.gr","tovima.gr","rizospastis.gr","metrogreece.gr","lifo.gr"]
    
    };
    
    _.extend(Backbone.View.prototype, {
            'current'  : current,
            'dispatch' : init.dispatch
    });
    
   return current;     
});