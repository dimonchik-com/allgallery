(function($){$.prettyPhoto={version:"2.5.4"};$.fn.prettyPhoto=function(settings){settings=jQuery.extend({animationSpeed:"normal",padding:40,opacity:0.8,showTitle:true,allowresize:true,counter_separator_label:"/",theme:"light_rounded",hideflash:false,modal:false,changepicturecallback:function(){},callback:function(){}},settings);if($.browser.msie&&$.browser.version==6){settings.theme="light_square";}if($(".pp_overlay").size()==0){_buildOverlay();}else{$pp_pic_holder=$(".pp_pic_holder");$ppt=$(".ppt");}var doresize=true,percentBased=false,correctSizes,$pp_pic_holder,$ppt,pp_contentHeight,pp_contentWidth,pp_containerHeight,pp_containerWidth,pp_type="image",setPosition=0,$scrollPos=_getScroll();$(window).scroll(function(){$scrollPos=_getScroll();_centerOverlay();_resizeOverlay();});$(window).resize(function(){_centerOverlay();_resizeOverlay();});$(document).keydown(function(e){if($pp_pic_holder.is(":visible")){switch(e.keyCode){case 37:$.prettyPhoto.changePage("previous");break;case 39:$.prettyPhoto.changePage("next");break;case 27:if(!settings.modal){$.prettyPhoto.close();}break;}}});$(this).each(function(){$(this).bind("click",function(){link=this;theRel=$(this).attr("rel");galleryRegExp=/\[(?:.*)\]/;theGallery=galleryRegExp.exec(theRel);var images=new Array(),titles=new Array(),descriptions=new Array();if(theGallery){$("a[rel*="+theGallery+"]").each(function(i){if($(this)[0]===$(link)[0]){setPosition=i;}images.push($(this).attr("href"));titles.push($(this).find("img").attr("alt"));descriptions.push($(this).attr("title"));});}else{images=$(this).attr("href");titles=($(this).find("img").attr("alt"))?$(this).find("img").attr("alt"):"";descriptions=($(this).attr("title"))?$(this).attr("title"):"";}$.prettyPhoto.open(images,titles,descriptions);return false;});});$.prettyPhoto.open=function(gallery_images,gallery_titles,gallery_descriptions){if($.browser.msie&&$.browser.version==6){$("select").css("visibility","hidden");}if(settings.hideflash){$("object,embed").css("visibility","hidden");}images=$.makeArray(gallery_images);titles=$.makeArray(gallery_titles);descriptions=$.makeArray(gallery_descriptions);if($(".pp_overlay").size()==0){_buildOverlay();}else{$pp_pic_holder=$(".pp_pic_holder");$ppt=$(".ppt");}$pp_pic_holder.attr("class","pp_pic_holder "+settings.theme);isSet=($(images).size()>0)?true:false;_getFileType(images[setPosition]);_centerOverlay();_checkPosition($(images).size());$(".pp_loaderIcon").show();$("div.pp_overlay").show().fadeTo(settings.animationSpeed,settings.opacity,function(){$pp_pic_holder.fadeIn(settings.animationSpeed,function(){$pp_pic_holder.find("p.currentTextHolder").text((setPosition+1)+settings.counter_separator_label+$(images).size());if(descriptions[setPosition]){$pp_pic_holder.find(".pp_description").show().html(unescape(descriptions[setPosition]));}else{$pp_pic_holder.find(".pp_description").hide().text("");}if(titles[setPosition]&&settings.showTitle){hasTitle=true;$ppt.html(unescape(titles[setPosition]));}else{hasTitle=false;}if(pp_type=="image"){imgPreloader=new Image();nextImage=new Image();if(isSet&&setPosition>$(images).size()){nextImage.src=images[setPosition+1];}prevImage=new Image();if(isSet&&images[setPosition-1]){prevImage.src=images[setPosition-1];}pp_typeMarkup='<img id="fullResImage" src="" />';$pp_pic_holder.find("#pp_full_res")[0].innerHTML=pp_typeMarkup;$pp_pic_holder.find(".pp_content").css("overflow","hidden");$pp_pic_holder.find("#fullResImage").attr("src",images[setPosition]);imgPreloader.onload=function(){correctSizes=_fitToViewport(imgPreloader.width,imgPreloader.height);_showContent();};imgPreloader.src=images[setPosition];}else{movie_width=(parseFloat(grab_param("width",images[setPosition])))?grab_param("width",images[setPosition]):"425";movie_height=(parseFloat(grab_param("height",images[setPosition])))?grab_param("height",images[setPosition]):"344";if(movie_width.indexOf("%")!=-1||movie_height.indexOf("%")!=-1){movie_height=($(window).height()*parseFloat(movie_height)/100)-100;movie_width=($(window).width()*parseFloat(movie_width)/100)-100;percentBased=true;}movie_height=parseFloat(movie_height);movie_width=parseFloat(movie_width);if(pp_type=="quicktime"){movie_height+=15;}correctSizes=_fitToViewport(movie_width,movie_height);if(pp_type=="youtube"){pp_typeMarkup='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+correctSizes["width"]+'" height="'+correctSizes["height"]+'"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://www.youtube.com/v/'+grab_param("v",images[setPosition])+'" /><embed src="http://www.youtube.com/v/'+grab_param("v",images[setPosition])+'" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'+correctSizes["width"]+'" height="'+correctSizes["height"]+'"></embed></object>';}else{if(pp_type=="quicktime"){pp_typeMarkup='<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="'+correctSizes["height"]+'" width="'+correctSizes["width"]+'"><param name="src" value="'+images[setPosition]+'"><param name="autoplay" value="true"><param name="type" value="video/quicktime"><embed src="'+images[setPosition]+'" height="'+correctSizes["height"]+'" width="'+correctSizes["width"]+'" autoplay="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>';}else{if(pp_type=="flash"){flash_vars=images[setPosition];flash_vars=flash_vars.substring(images[setPosition].indexOf("flashvars")+10,images[setPosition].length);filename=images[setPosition];filename=filename.substring(0,filename.indexOf("?"));pp_typeMarkup='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+correctSizes["width"]+'" height="'+correctSizes["height"]+'"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'+filename+"?"+flash_vars+'" /><embed src="'+filename+"?"+flash_vars+'" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'+correctSizes["width"]+'" height="'+correctSizes["height"]+'"></embed></object>';}else{if(pp_type=="iframe"){movie_url=images[setPosition];movie_url=movie_url.substr(0,movie_url.indexOf("iframe")-1);pp_typeMarkup='<iframe src ="'+movie_url+'" width="'+(correctSizes["width"]-10)+'" height="'+(correctSizes["height"]-10)+'" frameborder="no"></iframe>';}}}}_showContent();}});});};$.prettyPhoto.changePage=function(direction){if(direction=="previous"){setPosition--;if(setPosition<0){setPosition=0;return;}}else{if($(".pp_arrow_next").is(".disabled")){return;}setPosition++;}if(!doresize){doresize=true;}_hideContent();$("a.pp_expand,a.pp_contract").fadeOut(settings.animationSpeed,function(){$(this).removeClass("pp_contract").addClass("pp_expand");$.prettyPhoto.open(images,titles,descriptions);});};$.prettyPhoto.close=function(){$pp_pic_holder.find("object,embed").css("visibility","hidden");$("div.pp_pic_holder,div.ppt").fadeOut(settings.animationSpeed);$("div.pp_overlay").fadeOut(settings.animationSpeed,function(){$("div.pp_overlay,div.pp_pic_holder,div.ppt").remove();if($.browser.msie&&$.browser.version==6){$("select").css("visibility","visible");}if(settings.hideflash){$("object,embed").css("visibility","visible");}setPosition=0;settings.callback();});doresize=true;};_showContent=function(){$(".pp_loaderIcon").hide();if($.browser.opera){windowHeight=window.innerHeight;windowWidth=window.innerWidth;}else{windowHeight=$(window).height();windowWidth=$(window).width();}projectedTop=$scrollPos["scrollTop"]+((windowHeight/2)-(correctSizes["containerHeight"]/2));if(projectedTop<0){projectedTop=0+$pp_pic_holder.find(".ppt").height();}$pp_pic_holder.find(".pp_content").animate({"height":correctSizes["contentHeight"]},settings.animationSpeed);$pp_pic_holder.animate({"top":projectedTop,"left":((windowWidth/2)-(correctSizes["containerWidth"]/2)),"width":correctSizes["containerWidth"]},settings.animationSpeed,function(){$pp_pic_holder.width(correctSizes["containerWidth"]);$pp_pic_holder.find(".pp_hoverContainer,#fullResImage").height(correctSizes["height"]).width(correctSizes["width"]);$pp_pic_holder.find("#pp_full_res").fadeIn(settings.animationSpeed);if(isSet&&pp_type=="image"){$pp_pic_holder.find(".pp_hoverContainer").fadeIn(settings.animationSpeed);}else{$pp_pic_holder.find(".pp_hoverContainer").hide();}$pp_pic_holder.find(".pp_details").fadeIn(settings.animationSpeed);if(settings.showTitle&&hasTitle){$ppt.css({"top":$pp_pic_holder.offset().top-20,"left":$pp_pic_holder.offset().left+(settings.padding/2),"display":"none"});$ppt.fadeIn(settings.animationSpeed);}if(correctSizes["resized"]){$("a.pp_expand,a.pp_contract").fadeIn(settings.animationSpeed);}if(pp_type!="image"){$pp_pic_holder.find("#pp_full_res")[0].innerHTML=pp_typeMarkup;}settings.changepicturecallback();});};function _hideContent(){$pp_pic_holder.find("#pp_full_res object,#pp_full_res embed").css("visibility","hidden");$pp_pic_holder.find(".pp_hoverContainer,.pp_details").fadeOut(settings.animationSpeed);$pp_pic_holder.find("#pp_full_res").fadeOut(settings.animationSpeed,function(){$(".pp_loaderIcon").show();});$ppt.fadeOut(settings.animationSpeed);}function _checkPosition(setCount){if(setPosition==setCount-1){$pp_pic_holder.find("a.pp_next").css("visibility","hidden");$pp_pic_holder.find("a.pp_arrow_next").addClass("disabled").unbind("click");}else{$pp_pic_holder.find("a.pp_next").css("visibility","visible");$pp_pic_holder.find("a.pp_arrow_next.disabled").removeClass("disabled").bind("click",function(){$.prettyPhoto.changePage("next");return false;});}if(setPosition==0){$pp_pic_holder.find("a.pp_previous").css("visibility","hidden");$pp_pic_holder.find("a.pp_arrow_previous").addClass("disabled").unbind("click");}else{$pp_pic_holder.find("a.pp_previous").css("visibility","visible");$pp_pic_holder.find("a.pp_arrow_previous.disabled").removeClass("disabled").bind("click",function(){$.prettyPhoto.changePage("previous");return false;});}if(setCount>1){$(".pp_nav").show();}else{$(".pp_nav").hide();}}function _fitToViewport(width,height){hasBeenResized=false;_getDimensions(width,height);imageWidth=width;imageHeight=height;windowHeight=$(window).height();windowWidth=$(window).width();if(((pp_containerWidth>windowWidth)||(pp_containerHeight>windowHeight))&&doresize&&settings.allowresize&&!percentBased){hasBeenResized=true;notFitting=true;while(notFitting){if((pp_containerWidth>windowWidth)){imageWidth=(windowWidth-200);imageHeight=(height/width)*imageWidth;}else{if((pp_containerHeight>windowHeight)){imageHeight=(windowHeight-200);imageWidth=(width/height)*imageHeight;}else{notFitting=false;}}pp_containerHeight=imageHeight;pp_containerWidth=imageWidth;}_getDimensions(imageWidth,imageHeight);}return{width:imageWidth,height:imageHeight,containerHeight:pp_containerHeight,containerWidth:pp_containerWidth,contentHeight:pp_contentHeight,contentWidth:pp_contentWidth,resized:hasBeenResized};}function _getDimensions(width,height){$pp_pic_holder.find(".pp_details").width(width).find(".pp_description").width(width-parseFloat($pp_pic_holder.find("a.pp_close").css("width")));pp_contentHeight=height+$pp_pic_holder.find(".pp_details").height()+parseFloat($pp_pic_holder.find(".pp_details").css("marginTop"))+parseFloat($pp_pic_holder.find(".pp_details").css("marginBottom"));pp_contentWidth=width;pp_containerHeight=pp_contentHeight+$pp_pic_holder.find(".ppt").height()+$pp_pic_holder.find(".pp_top").height()+$pp_pic_holder.find(".pp_bottom").height();pp_containerWidth=width+settings.padding;}function _getFileType(itemSrc){if(itemSrc.match(/youtube\.com\/watch/i)){pp_type="youtube";}else{if(itemSrc.indexOf(".mov")!=-1){pp_type="quicktime";}else{if(itemSrc.indexOf(".swf")!=-1){pp_type="flash";}else{if(itemSrc.indexOf("iframe")!=-1){pp_type="iframe";}else{pp_type="image";}}}}}function _centerOverlay(){if($.browser.opera){windowHeight=window.innerHeight;windowWidth=window.innerWidth;}else{windowHeight=$(window).height();windowWidth=$(window).width();}if(doresize){$pHeight=$pp_pic_holder.height();$pWidth=$pp_pic_holder.width();$tHeight=$ppt.height();projectedTop=(windowHeight/2)+$scrollPos["scrollTop"]-($pHeight/2);if(projectedTop<0){projectedTop=0+$tHeight;}$pp_pic_holder.css({"top":projectedTop,"left":(windowWidth/2)+$scrollPos["scrollLeft"]-($pWidth/2)});$ppt.css({"top":projectedTop-$tHeight,"left":(windowWidth/2)+$scrollPos["scrollLeft"]-($pWidth/2)+(settings.padding/2)});}}function _getScroll(){if(self.pageYOffset){scrollTop=self.pageYOffset;scrollLeft=self.pageXOffset;}else{if(document.documentElement&&document.documentElement.scrollTop){scrollTop=document.documentElement.scrollTop;scrollLeft=document.documentElement.scrollLeft;}else{if(document.body){scrollTop=document.body.scrollTop;scrollLeft=document.body.scrollLeft;}}}return{scrollTop:scrollTop,scrollLeft:scrollLeft};}function _resizeOverlay(){$("div.pp_overlay").css({"height":$(document).height(),"width":$(window).width()});}function _buildOverlay(){toInject="";toInject+="<div class='pp_overlay'></div>";toInject+='<div class="pp_pic_holder"><div class="pp_top"><div class="pp_left"></div><div class="pp_middle"></div><div class="pp_right"></div></div><div class="pp_content"><a href="#" class="pp_expand" title="Expand the image">Expand</a><div class="pp_loaderIcon"></div><div class="pp_hoverContainer"><a class="pp_next" href="#">next</a><a class="pp_previous" href="#">previous</a></div><div id="pp_full_res"></div><div class="pp_details clearfix"><a class="pp_close" href="#">Close</a><p class="pp_description"></p><div class="pp_nav"><a href="#" class="pp_arrow_previous">Previous</a><p class="currentTextHolder">0'+settings.counter_separator_label+'0</p><a href="#" class="pp_arrow_next">Next</a></div></div></div><div class="pp_bottom"><div class="pp_left"></div><div class="pp_middle"></div><div class="pp_right"></div></div></div>';toInject+='<div class="ppt"></div>';$("body").append(toInject);$("div.pp_overlay").css("opacity",0);$pp_pic_holder=$(".pp_pic_holder");$ppt=$(".ppt");$("div.pp_overlay").css("height",$(document).height()).hide().bind("click",function(){if(!settings.modal){$.prettyPhoto.close();}});$("a.pp_close").bind("click",function(){$.prettyPhoto.close();return false;});$("a.pp_expand").bind("click",function(){$this=$(this);if($this.hasClass("pp_expand")){$this.removeClass("pp_expand").addClass("pp_contract");doresize=false;}else{$this.removeClass("pp_contract").addClass("pp_expand");doresize=true;}_hideContent();$pp_pic_holder.find(".pp_hoverContainer, .pp_details").fadeOut(settings.animationSpeed);$pp_pic_holder.find("#pp_full_res").fadeOut(settings.animationSpeed,function(){$.prettyPhoto.open(images,titles,descriptions);});return false;});$pp_pic_holder.find(".pp_previous, .pp_arrow_previous").bind("click",function(){$.prettyPhoto.changePage("previous");return false;});$pp_pic_holder.find(".pp_next, .pp_arrow_next").bind("click",function(){$.prettyPhoto.changePage("next");return false;});$pp_pic_holder.find(".pp_hoverContainer").css({"margin-left":settings.padding/2});}};function grab_param(name,url){name=name.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var regexS="[\\?&]"+name+"=([^&#]*)";var regex=new RegExp(regexS);var results=regex.exec(url);if(results==null){return"";}else{return results[1];}}})(jQuery);