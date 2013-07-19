define([
    "backbone"
],
function(backbone) {
    
    var current = {
        article:{},
        settings:["info-war.gr","skai.gr","real.gr","newsbeast.gr","kathimerini.gr","enet.gr","protothema.gr","tovima.gr","rizospastis.gr","metrogreece.gr","lifo.gr"]
    }
    _.extend(Backbone.View.prototype, {
            'current' : current
        });
   return current;     
});