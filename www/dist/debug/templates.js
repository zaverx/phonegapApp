this["JST"] = this["JST"] || {};

this["JST"]["app/templates/article.html"] = function(obj){
var __p='';var print=function(){__p+=Array.prototype.join.call(arguments, '')};
with(obj||{}){
__p+='<div id="article-hero"></div>\n<div id="article-title">'+
(articleTitle )+
'</div>\n<div id="article-content">'+
(content)+
'</div>';
}
return __p;
};

this["JST"]["app/templates/hero.html"] = function(obj){
var __p='';var print=function(){__p+=Array.prototype.join.call(arguments, '')};
with(obj||{}){
__p+='<div id="hero-title">'+
(title)+
'</div>';
}
return __p;
};

this["JST"]["app/templates/iframe.html"] = function(obj){
var __p='';var print=function(){__p+=Array.prototype.join.call(arguments, '')};
with(obj||{}){
__p+='<iframe id="article-frame" src="'+
(target)+
'"  width="100%" height="100%">\n</iframe>';
}
return __p;
};

this["JST"]["app/templates/tabs.html"] = function(obj){
var __p='';var print=function(){__p+=Array.prototype.join.call(arguments, '')};
with(obj||{}){
__p+='';
 for(var j=0; j < rows.length; j++){
		
	
;__p+='\n<div class="row-header"> '+
(rows[j])+
'</div>\n<div class="tab-content h-scroll">\n<ul id="list_'+
(j)+
'" class="floated-list" style="position:absolute;">\n    \n\t';
 for(var i=0; i < response[rows[j]].length; i++){
		var img   = response[rows[j]][i].hero;
        var itmId = response[rows[j]][i].itemId;
        var title = response[rows[j]][i].title;
	
;__p+='\n\t\t<li class="row-item" data-itemid="'+
(itmId)+
'"><div class="thumb-image"><img width="80" height="80" src="'+
(img)+
'"/></div><div class="thumb-caption">'+
(title)+
'</div></li>\n\t';
	
	} 
	
;__p+='\n\t\n</ul>\n</div>\n    ';
 } 
;__p+='';
}
return __p;
};