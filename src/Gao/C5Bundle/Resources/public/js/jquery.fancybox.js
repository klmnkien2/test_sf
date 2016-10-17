/*!
 * fancyBox - jQuery Plugin
 * version: 2.1.5 (Fri, 14 Jun 2013)
 * @requires jQuery v1.6 or later
 *
 * Examples at http://fancyapps.com/fancybox/
 * License: www.fancyapps.com/fancybox/#license
 *
 * Copyright 2012 Janis Skarnelis - janis@fancyapps.com
 *
 */
(function(e,t,n,r){"use strict";var i=n("html"),s=n(e),o=n(t),u=n.fancybox=function(){u.open.apply(this,arguments)},a=navigator.userAgent.match(/msie/i),f=null,l=t.createTouch!==r,c=function(e){return e&&e.hasOwnProperty&&e instanceof n},h=function(e){return e&&n.type(e)==="string"},p=function(e){return h(e)&&e.indexOf("%")>0},d=function(e){return e&&!(e.style.overflow&&e.style.overflow==="hidden")&&(e.clientWidth&&e.scrollWidth>e.clientWidth||e.clientHeight&&e.scrollHeight>e.clientHeight)},v=function(e,t){var n=parseInt(e,10)||0;if(t&&p(e)){n=u.getViewport()[t]/100*n}return Math.ceil(n)},m=function(e,t){return v(e,t)+"px"};n.extend(u,{version:"2.1.5",defaults:{padding:15,margin:20,width:800,height:600,minWidth:100,minHeight:100,maxWidth:9999,maxHeight:9999,pixelRatio:1,autoSize:true,autoHeight:false,autoWidth:false,autoResize:true,autoCenter:!l,fitToView:true,aspectRatio:false,topRatio:.5,leftRatio:.5,scrolling:"auto",wrapCSS:"",arrows:true,closeBtn:true,closeClick:false,nextClick:false,mouseWheel:true,autoPlay:false,playSpeed:3e3,preload:3,modal:false,loop:true,ajax:{dataType:"html",headers:{"X-fancyBox":true}},iframe:{scrolling:"auto",preload:true},swf:{wmode:"transparent",allowfullscreen:"true",allowscriptaccess:"always"},keys:{next:{13:"left",34:"up",39:"left",40:"up"},prev:{8:"right",33:"down",37:"right",38:"down"},close:[27],play:[32],toggle:[70]},direction:{next:"left",prev:"right"},scrollOutside:true,index:0,type:null,href:null,content:null,title:null,tpl:{wrap:'<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',image:'<img class="fancybox-image" src="{href}" alt="" />',iframe:'<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen'+(a?' allowtransparency="true"':"")+"></iframe>",error:'<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',closeBtn:'<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',next:'<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',prev:'<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'},openEffect:"fade",openSpeed:250,openEasing:"swing",openOpacity:true,openMethod:"zoomIn",closeEffect:"fade",closeSpeed:250,closeEasing:"swing",closeOpacity:true,closeMethod:"zoomOut",nextEffect:"elastic",nextSpeed:250,nextEasing:"swing",nextMethod:"changeIn",prevEffect:"elastic",prevSpeed:250,prevEasing:"swing",prevMethod:"changeOut",helpers:{overlay:true,title:true},onCancel:n.noop,beforeLoad:n.noop,afterLoad:n.noop,beforeShow:n.noop,afterShow:n.noop,beforeChange:n.noop,beforeClose:n.noop,afterClose:n.noop},group:{},opts:{},previous:null,coming:null,current:null,isActive:false,isOpen:false,isOpened:false,wrap:null,skin:null,outer:null,inner:null,player:{timer:null,isActive:false},ajaxLoad:null,imgPreload:null,transitions:{},helpers:{},open:function(e,t){if(!e){return}if(!n.isPlainObject(t)){t={}}if(false===u.close(true)){return}if(!n.isArray(e)){e=c(e)?n(e).get():[e]}n.each(e,function(i,s){var o={},a,f,l,p,d,v,m;if(n.type(s)==="object"){if(s.nodeType){s=n(s)}if(c(s)){o={href:s.data("fancybox-href")||s.attr("href"),title:s.data("fancybox-title")||s.attr("title"),isDom:true,element:s};if(n.metadata){n.extend(true,o,s.metadata())}}else{o=s}}a=t.href||o.href||(h(s)?s:null);f=t.title!==r?t.title:o.title||"";l=t.content||o.content;p=l?"html":t.type||o.type;if(!p&&o.isDom){p=s.data("fancybox-type");if(!p){d=s.prop("class").match(/fancybox\.(\w+)/);p=d?d[1]:null}}if(h(a)){if(!p){if(u.isImage(a)){p="image"}else if(u.isSWF(a)){p="swf"}else if(a.charAt(0)==="#"){p="inline"}else if(h(s)){p="html";l=s}}if(p==="ajax"){v=a.split(/\s+/,2);a=v.shift();m=v.shift()}}if(!l){if(p==="inline"){if(a){l=n(h(a)?a.replace(/.*(?=#[^\s]+$)/,""):a)}else if(o.isDom){l=s}}else if(p==="html"){l=a}else if(!p&&!a&&o.isDom){p="inline";l=s}}n.extend(o,{href:a,type:p,content:l,title:f,selector:m});e[i]=o});u.opts=n.extend(true,{},u.defaults,t);if(t.keys!==r){u.opts.keys=t.keys?n.extend({},u.defaults.keys,t.keys):false}u.group=e;return u._start(u.opts.index)},cancel:function(){var e=u.coming;if(!e||false===u.trigger("onCancel")){return}u.hideLoading();if(u.ajaxLoad){u.ajaxLoad.abort()}u.ajaxLoad=null;if(u.imgPreload){u.imgPreload.onload=u.imgPreload.onerror=null}if(e.wrap){e.wrap.stop(true,true).trigger("onReset").remove()}u.coming=null;if(!u.current){u._afterZoomOut(e)}},close:function(e){u.cancel();if(false===u.trigger("beforeClose")){return}u.unbindEvents();if(!u.isActive){return}if(!u.isOpen||e===true){n(".fancybox-wrap").stop(true).trigger("onReset").remove();u._afterZoomOut()}else{u.isOpen=u.isOpened=false;u.isClosing=true;n(".fancybox-item, .fancybox-nav").remove();u.wrap.stop(true,true).removeClass("fancybox-opened");u.transitions[u.current.closeMethod]()}},play:function(e){var t=function(){clearTimeout(u.player.timer)},n=function(){t();if(u.current&&u.player.isActive){u.player.timer=setTimeout(u.next,u.current.playSpeed)}},r=function(){t();o.unbind(".player");u.player.isActive=false;u.trigger("onPlayEnd")},i=function(){if(u.current&&(u.current.loop||u.current.index<u.group.length-1)){u.player.isActive=true;o.bind({"onCancel.player beforeClose.player":r,"onUpdate.player":n,"beforeLoad.player":t});n();u.trigger("onPlayStart")}};if(e===true||!u.player.isActive&&e!==false){i()}else{r()}},next:function(e){var t=u.current;if(t){if(!h(e)){e=t.direction.next}u.jumpto(t.index+1,e,"next")}},prev:function(e){var t=u.current;if(t){if(!h(e)){e=t.direction.prev}u.jumpto(t.index-1,e,"prev")}},jumpto:function(e,t,n){var i=u.current;if(!i){return}e=v(e);u.direction=t||i.direction[e>=i.index?"next":"prev"];u.router=n||"jumpto";if(i.loop){if(e<0){e=i.group.length+e%i.group.length}e=e%i.group.length}if(i.group[e]!==r){u.cancel();u._start(e)}},reposition:function(e,t){var r=u.current,i=r?r.wrap:null,s;if(i){s=u._getPosition(t);if(e&&e.type==="scroll"){delete s.position;i.stop(true,true).animate(s,200)}else{i.css(s);r.pos=n.extend({},r.dim,s)}}},update:function(e){var t=e&&e.type,n=!t||t==="orientationchange";if(n){clearTimeout(f);f=null}if(!u.isOpen||f){return}f=setTimeout(function(){var r=u.current;if(!r||u.isClosing){return}u.wrap.removeClass("fancybox-tmp");if(n||t==="load"||t==="resize"&&r.autoResize){u._setDimension()}if(!(t==="scroll"&&r.canShrink)){u.reposition(e)}u.trigger("onUpdate");f=null},n&&!l?0:300)},toggle:function(e){if(u.isOpen){u.current.fitToView=n.type(e)==="boolean"?e:!u.current.fitToView;if(l){u.wrap.removeAttr("style").addClass("fancybox-tmp");u.trigger("onUpdate")}u.update()}},hideLoading:function(){o.unbind(".loading");n("#fancybox-loading").remove()},showLoading:function(){var e,t;u.hideLoading();e=n('<div id="fancybox-loading"><div></div></div>').click(u.cancel).appendTo("body");o.bind("keydown.loading",function(e){if((e.which||e.keyCode)===27){e.preventDefault();u.cancel()}});if(!u.defaults.fixed){t=u.getViewport();e.css({position:"absolute",top:t.h*.5+t.y,left:t.w*.5+t.x})}},getViewport:function(){var t=u.current&&u.current.locked||false,n={x:s.scrollLeft(),y:s.scrollTop()};if(t){n.w=t[0].clientWidth;n.h=t[0].clientHeight}else{n.w=l&&e.innerWidth?e.innerWidth:s.width();n.h=l&&e.innerHeight?e.innerHeight:s.height()}return n},unbindEvents:function(){if(u.wrap&&c(u.wrap)){u.wrap.unbind(".fb")}o.unbind(".fb");s.unbind(".fb")},bindEvents:function(){var e=u.current,t;if(!e){return}s.bind("orientationchange.fb"+(l?"":" resize.fb")+(e.autoCenter&&!e.locked?" scroll.fb":""),u.update);t=e.keys;if(t){o.bind("keydown.fb",function(i){var s=i.which||i.keyCode,o=i.target||i.srcElement;if(s===27&&u.coming){return false}if(!i.ctrlKey&&!i.altKey&&!i.shiftKey&&!i.metaKey&&!(o&&(o.type||n(o).is("[contenteditable]")))){n.each(t,function(t,o){if(e.group.length>1&&o[s]!==r){u[t](o[s]);i.preventDefault();return false}if(n.inArray(s,o)>-1){u[t]();i.preventDefault();return false}})}})}if(n.fn.mousewheel&&e.mouseWheel){u.wrap.bind("mousewheel.fb",function(t,r,i,s){var o=t.target||null,a=n(o),f=false;while(a.length){if(f||a.is(".fancybox-skin")||a.is(".fancybox-wrap")){break}f=d(a[0]);a=n(a).parent()}if(r!==0&&!f){if(u.group.length>1&&!e.canShrink){if(s>0||i>0){u.prev(s>0?"down":"left")}else if(s<0||i<0){u.next(s<0?"up":"right")}t.preventDefault()}}})}},trigger:function(e,t){var r,i=t||u.coming||u.current;if(!i){return}if(n.isFunction(i[e])){r=i[e].apply(i,Array.prototype.slice.call(arguments,1))}if(r===false){return false}if(i.helpers){n.each(i.helpers,function(t,r){if(r&&u.helpers[t]&&n.isFunction(u.helpers[t][e])){u.helpers[t][e](n.extend(true,{},u.helpers[t].defaults,r),i)}})}o.trigger(e)},isImage:function(e){return h(e)&&e.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i)},isSWF:function(e){return h(e)&&e.match(/\.(swf)((\?|#).*)?$/i)},_start:function(e){var t={},r,i,s,o,a;e=v(e);r=u.group[e]||null;if(!r){return false}t=n.extend(true,{},u.opts,r);o=t.margin;a=t.padding;if(n.type(o)==="number"){t.margin=[o,o,o,o]}if(n.type(a)==="number"){t.padding=[a,a,a,a]}if(t.modal){n.extend(true,t,{closeBtn:false,closeClick:false,nextClick:false,arrows:false,mouseWheel:false,keys:null,helpers:{overlay:{closeClick:false}}})}if(t.autoSize){t.autoWidth=t.autoHeight=true}if(t.width==="auto"){t.autoWidth=true}if(t.height==="auto"){t.autoHeight=true}t.group=u.group;t.index=e;u.coming=t;if(false===u.trigger("beforeLoad")){u.coming=null;return}s=t.type;i=t.href;if(!s){u.coming=null;if(u.current&&u.router&&u.router!=="jumpto"){u.current.index=e;return u[u.router](u.direction)}return false}u.isActive=true;if(s==="image"||s==="swf"){t.autoHeight=t.autoWidth=false;t.scrolling="visible"}if(s==="image"){t.aspectRatio=true}if(s==="iframe"&&l){t.scrolling="scroll"}t.wrap=n(t.tpl.wrap).addClass("fancybox-"+(l?"mobile":"desktop")+" fancybox-type-"+s+" fancybox-tmp "+t.wrapCSS).appendTo(t.parent||"body");n.extend(t,{skin:n(".fancybox-skin",t.wrap),outer:n(".fancybox-outer",t.wrap),inner:n(".fancybox-inner",t.wrap)});n.each(["Top","Right","Bottom","Left"],function(e,n){t.skin.css("padding"+n,m(t.padding[e]))});u.trigger("onReady");if(s==="inline"||s==="html"){if(!t.content||!t.content.length){return u._error("content")}}else if(!i){return u._error("href")}if(s==="image"){u._loadImage()}else if(s==="ajax"){u._loadAjax()}else if(s==="iframe"){u._loadIframe()}else{u._afterLoad()}},_error:function(e){n.extend(u.coming,{type:"html",autoWidth:true,autoHeight:true,minWidth:0,minHeight:0,scrolling:"no",hasError:e,content:u.coming.tpl.error});u._afterLoad()},_loadImage:function(){var e=u.imgPreload=new Image;e.onload=function(){this.onload=this.onerror=null;u.coming.width=this.width/u.opts.pixelRatio;u.coming.height=this.height/u.opts.pixelRatio;u._afterLoad()};e.onerror=function(){this.onload=this.onerror=null;u._error("image")};e.src=u.coming.href;if(e.complete!==true){u.showLoading()}},_loadAjax:function(){var e=u.coming;u.showLoading();u.ajaxLoad=n.ajax(n.extend({},e.ajax,{url:e.href,error:function(e,t){if(u.coming&&t!=="abort"){u._error("ajax",e)}else{u.hideLoading()}},success:function(t,n){if(n==="success"){e.content=t;u._afterLoad()}}}))},_loadIframe:function(){var e=u.coming,t=n(e.tpl.iframe.replace(/\{rnd\}/g,(new Date).getTime())).attr("scrolling",l?"auto":e.iframe.scrolling).attr("src",e.href);n(e.wrap).bind("onReset",function(){try{n(this).find("iframe").hide().attr("src","//about:blank").end().empty()}catch(e){}});if(e.iframe.preload){u.showLoading();t.one("load",function(){n(this).data("ready",1);if(!l){n(this).bind("load.fb",u.update)}n(this).parents(".fancybox-wrap").width("100%").removeClass("fancybox-tmp").show();u._afterLoad()})}e.content=t.appendTo(e.inner);if(!e.iframe.preload){u._afterLoad()}},_preloadImages:function(){var e=u.group,t=u.current,n=e.length,r=t.preload?Math.min(t.preload,n-1):0,i,s;for(s=1;s<=r;s+=1){i=e[(t.index+s)%n];if(i.type==="image"&&i.href){(new Image).src=i.href}}},_afterLoad:function(){var e=u.coming,t=u.current,r="fancybox-placeholder",i,s,o,a,f,l;u.hideLoading();if(!e||u.isActive===false){return}if(false===u.trigger("afterLoad",e,t)){e.wrap.stop(true).trigger("onReset").remove();u.coming=null;return}if(t){u.trigger("beforeChange",t);t.wrap.stop(true).removeClass("fancybox-opened").find(".fancybox-item, .fancybox-nav").remove()}u.unbindEvents();i=e;s=e.content;o=e.type;a=e.scrolling;n.extend(u,{wrap:i.wrap,skin:i.skin,outer:i.outer,inner:i.inner,current:i,previous:t});f=i.href;switch(o){case"inline":case"ajax":case"html":if(i.selector){s=n("<div>").html(s).find(i.selector)}else if(c(s)){if(!s.data(r)){s.data(r,n('<div class="'+r+'"></div>').insertAfter(s).hide())}s=s.show().detach();i.wrap.bind("onReset",function(){if(n(this).find(s).length){s.hide().replaceAll(s.data(r)).data(r,false)}})}break;case"image":s=i.tpl.image.replace("{href}",f);break;case"swf":s='<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="'+f+'"></param>';l="";n.each(i.swf,function(e,t){s+='<param name="'+e+'" value="'+t+'"></param>';l+=" "+e+'="'+t+'"'});s+='<embed src="'+f+'" type="application/x-shockwave-flash" width="100%" height="100%"'+l+"></embed></object>";break}if(!(c(s)&&s.parent().is(i.inner))){i.inner.append(s)}u.trigger("beforeShow");i.inner.css("overflow",a==="yes"?"scroll":a==="no"?"hidden":a);u._setDimension();u.reposition();u.isOpen=false;u.coming=null;u.bindEvents();if(!u.isOpened){n(".fancybox-wrap").not(i.wrap).stop(true).trigger("onReset").remove()}else if(t.prevMethod){u.transitions[t.prevMethod]()}u.transitions[u.isOpened?i.nextMethod:i.openMethod]();u._preloadImages()},_setDimension:function(){var e=u.getViewport(),t=0,r=false,i=false,s=u.wrap,o=u.skin,a=u.inner,f=u.current,l=f.width,c=f.height,h=f.minWidth,d=f.minHeight,g=f.maxWidth,y=f.maxHeight,b=f.scrolling,w=f.scrollOutside?f.scrollbarWidth:0,E=f.margin,S=v(E[1]+E[3]),x=v(E[0]+E[2]),T,N,C,k,L,A,O,M,_,D,P,H,B,j,I;s.add(o).add(a).width("auto").height("auto").removeClass("fancybox-tmp");T=v(o.outerWidth(true)-o.width());N=v(o.outerHeight(true)-o.height());C=S+T;k=x+N;L=p(l)?(e.w-C)*v(l)/100:l;A=p(c)?(e.h-k)*v(c)/100:c;if(f.type==="iframe"){j=f.content;if(f.autoHeight&&j.data("ready")===1){try{if(j[0].contentWindow.document.location){a.width(L).height(9999);I=j.contents().find("body");if(w){I.css("overflow-x","hidden")}A=I.outerHeight(true)}}catch(q){}}}else if(f.autoWidth||f.autoHeight){a.addClass("fancybox-tmp");if(!f.autoWidth){a.width(L)}if(!f.autoHeight){a.height(A)}if(f.autoWidth){L=a.width()}if(f.autoHeight){A=a.height()}a.removeClass("fancybox-tmp")}l=v(L);c=v(A);_=L/A;h=v(p(h)?v(h,"w")-C:h);g=v(p(g)?v(g,"w")-C:g);d=v(p(d)?v(d,"h")-k:d);y=v(p(y)?v(y,"h")-k:y);O=g;M=y;if(f.fitToView){g=Math.min(e.w-C,g);y=Math.min(e.h-k,y)}H=e.w-S;B=e.h-x;if(f.aspectRatio){if(l>g){l=g;c=v(l/_)}if(c>y){c=y;l=v(c*_)}if(l<h){l=h;c=v(l/_)}if(c<d){c=d;l=v(c*_)}}else{l=Math.max(h,Math.min(l,g));if(f.autoHeight&&f.type!=="iframe"){a.width(l);c=a.height()}c=Math.max(d,Math.min(c,y))}if(f.fitToView){a.width(l).height(c);s.width(l+T);D=s.width();P=s.height();if(f.aspectRatio){while((D>H||P>B)&&l>h&&c>d){if(t++>19){break}c=Math.max(d,Math.min(y,c-10));l=v(c*_);if(l<h){l=h;c=v(l/_)}if(l>g){l=g;c=v(l/_)}a.width(l).height(c);s.width(l+T);D=s.width();P=s.height()}}else{l=Math.max(h,Math.min(l,l-(D-H)));c=Math.max(d,Math.min(c,c-(P-B)))}}if(w&&b==="auto"&&c<A&&l+T+w<H){l+=w}a.width(l).height(c);s.width(l+T);D=s.width();P=s.height();r=(D>H||P>B)&&l>h&&c>d;i=f.aspectRatio?l<O&&c<M&&l<L&&c<A:(l<O||c<M)&&(l<L||c<A);n.extend(f,{dim:{width:m(D),height:m(P)},origWidth:L,origHeight:A,canShrink:r,canExpand:i,wPadding:T,hPadding:N,wrapSpace:P-o.outerHeight(true),skinSpace:o.height()-c});if(!j&&f.autoHeight&&c>d&&c<y&&!i){a.height("auto")}},_getPosition:function(e){var t=u.current,n=u.getViewport(),r=t.margin,i=u.wrap.width()+r[1]+r[3],s=u.wrap.height()+r[0]+r[2],o={position:"absolute",top:r[0],left:r[3]};if(t.autoCenter&&t.fixed&&!e&&s<=n.h&&i<=n.w){o.position="fixed"}else if(!t.locked){o.top+=n.y;o.left+=n.x}o.top=m(Math.max(o.top,o.top+(n.h-s)*t.topRatio));o.left=m(Math.max(o.left,o.left+(n.w-i)*t.leftRatio));return o},_afterZoomIn:function(){var e=u.current;if(!e){return}u.isOpen=u.isOpened=true;u.wrap.css("overflow","visible").addClass("fancybox-opened");u.update();if(e.closeClick||e.nextClick&&u.group.length>1){u.inner.css("cursor","pointer").bind("click.fb",function(t){if(!n(t.target).is("a")&&!n(t.target).parent().is("a")){t.preventDefault();u[e.closeClick?"close":"next"]()}})}if(e.closeBtn){n(e.tpl.closeBtn).appendTo(u.skin).bind("click.fb",function(e){e.preventDefault();u.close()})}if(e.arrows&&u.group.length>1){if(e.loop||e.index>0){n(e.tpl.prev).appendTo(u.outer).bind("click.fb",u.prev)}if(e.loop||e.index<u.group.length-1){n(e.tpl.next).appendTo(u.outer).bind("click.fb",u.next)}}u.trigger("afterShow");if(!e.loop&&e.index===e.group.length-1){u.play(false)}else if(u.opts.autoPlay&&!u.player.isActive){u.opts.autoPlay=false;u.play()}},_afterZoomOut:function(e){e=e||u.current;n(".fancybox-wrap").trigger("onReset").remove();n.extend(u,{group:{},opts:{},router:false,current:null,isActive:false,isOpened:false,isOpen:false,isClosing:false,wrap:null,skin:null,outer:null,inner:null});u.trigger("afterClose",e)}});u.transitions={getOrigPosition:function(){var e=u.current,t=e.element,n=e.orig,r={},i=50,s=50,o=e.hPadding,a=e.wPadding,f=u.getViewport();if(!n&&e.isDom&&t.is(":visible")){n=t.find("img:first");if(!n.length){n=t}}if(c(n)){r=n.offset();if(n.is("img")){i=n.outerWidth();s=n.outerHeight()}}else{r.top=f.y+(f.h-s)*e.topRatio;r.left=f.x+(f.w-i)*e.leftRatio}if(u.wrap.css("position")==="fixed"||e.locked){r.top-=f.y;r.left-=f.x}r={top:m(r.top-o*e.topRatio),left:m(r.left-a*e.leftRatio),width:m(i+a),height:m(s+o)};return r},step:function(e,t){var n,r,i,s=t.prop,o=u.current,a=o.wrapSpace,f=o.skinSpace;if(s==="width"||s==="height"){n=t.end===t.start?1:(e-t.start)/(t.end-t.start);if(u.isClosing){n=1-n}r=s==="width"?o.wPadding:o.hPadding;i=e-r;u.skin[s](v(s==="width"?i:i-a*n));u.inner[s](v(s==="width"?i:i-a*n-f*n))}},zoomIn:function(){var e=u.current,t=e.pos,r=e.openEffect,i=r==="elastic",s=n.extend({opacity:1},t);delete s.position;if(i){t=this.getOrigPosition();if(e.openOpacity){t.opacity=.1}}else if(r==="fade"){t.opacity=.1}u.wrap.css(t).animate(s,{duration:r==="none"?0:e.openSpeed,easing:e.openEasing,step:i?this.step:null,complete:u._afterZoomIn})},zoomOut:function(){var e=u.current,t=e.closeEffect,n=t==="elastic",r={opacity:.1};if(n){r=this.getOrigPosition();if(e.closeOpacity){r.opacity=.1}}u.wrap.animate(r,{duration:t==="none"?0:e.closeSpeed,easing:e.closeEasing,step:n?this.step:null,complete:u._afterZoomOut})},changeIn:function(){var e=u.current,t=e.nextEffect,n=e.pos,r={opacity:1},i=u.direction,s=200,o;n.opacity=.1;if(t==="elastic"){o=i==="down"||i==="up"?"top":"left";if(i==="down"||i==="right"){n[o]=m(v(n[o])-s);r[o]="+="+s+"px"}else{n[o]=m(v(n[o])+s);r[o]="-="+s+"px"}}if(t==="none"){u._afterZoomIn()}else{u.wrap.css(n).animate(r,{duration:e.nextSpeed,easing:e.nextEasing,complete:u._afterZoomIn})}},changeOut:function(){var e=u.previous,t=e.prevEffect,r={opacity:.1},i=u.direction,s=200;if(t==="elastic"){r[i==="down"||i==="up"?"top":"left"]=(i==="up"||i==="left"?"-":"+")+"="+s+"px"}e.wrap.animate(r,{duration:t==="none"?0:e.prevSpeed,easing:e.prevEasing,complete:function(){n(this).trigger("onReset").remove()}})}};u.helpers.overlay={defaults:{closeClick:true,speedOut:200,showEarly:true,css:{},locked:!l,fixed:true},overlay:null,fixed:false,el:n("html"),create:function(e){e=n.extend({},this.defaults,e);if(this.overlay){this.close()}this.overlay=n('<div class="fancybox-overlay"></div>').appendTo(u.coming?u.coming.parent:e.parent);this.fixed=false;if(e.fixed&&u.defaults.fixed){this.overlay.addClass("fancybox-overlay-fixed");this.fixed=true}},open:function(e){var t=this;e=n.extend({},this.defaults,e);if(this.overlay){this.overlay.unbind(".overlay").width("auto").height("auto")}else{this.create(e)}if(!this.fixed){s.bind("resize.overlay",n.proxy(this.update,this));this.update()}if(e.closeClick){this.overlay.bind("click.overlay",function(e){if(n(e.target).hasClass("fancybox-overlay")){if(u.isActive){u.close()}else{t.close()}return false}})}this.overlay.css(e.css).show()},close:function(){var e,t;s.unbind("resize.overlay");if(this.el.hasClass("fancybox-lock")){n(".fancybox-margin").removeClass("fancybox-margin");e=s.scrollTop();t=s.scrollLeft();this.el.removeClass("fancybox-lock");s.scrollTop(e).scrollLeft(t)}n(".fancybox-overlay").remove().hide();n.extend(this,{overlay:null,fixed:false})},update:function(){var e="100%",n;this.overlay.width(e).height("100%");if(a){n=Math.max(t.documentElement.offsetWidth,t.body.offsetWidth);if(o.width()>n){e=o.width()}}else if(o.width()>s.width()){e=o.width()}this.overlay.width(e).height(o.height())},onReady:function(e,t){var r=this.overlay;n(".fancybox-overlay").stop(true,true);if(!r){this.create(e)}if(e.locked&&this.fixed&&t.fixed){if(!r){this.margin=o.height()>s.height()?n("html").css("margin-right").replace("px",""):false}t.locked=this.overlay.append(t.wrap);t.fixed=false}if(e.showEarly===true){this.beforeShow.apply(this,arguments)}},beforeShow:function(e,t){var r,i;if(t.locked){if(this.margin!==false){n("*").filter(function(){return n(this).css("position")==="fixed"&&!n(this).hasClass("fancybox-overlay")&&!n(this).hasClass("fancybox-wrap")}).addClass("fancybox-margin");this.el.addClass("fancybox-margin")}r=s.scrollTop();i=s.scrollLeft();this.el.addClass("fancybox-lock");s.scrollTop(r).scrollLeft(i)}this.open(e)},onUpdate:function(){if(!this.fixed){this.update()}},afterClose:function(e){if(this.overlay&&!u.coming){this.overlay.fadeOut(e.speedOut,n.proxy(this.close,this))}}};u.helpers.title={defaults:{type:"float",position:"bottom"},beforeShow:function(e){var t=u.current,r=t.title,i=e.type,s,o;if(n.isFunction(r)){r=r.call(t.element,t)}if(!h(r)||n.trim(r)===""){return}s=n('<div class="fancybox-title fancybox-title-'+i+'-wrap">'+r+"</div>");switch(i){case"inside":o=u.skin;break;case"outside":o=u.wrap;break;case"over":o=u.inner;break;default:o=u.skin;s.appendTo("body");if(a){s.width(s.width())}s.wrapInner('<span class="child"></span>');u.current.margin[2]+=Math.abs(v(s.css("margin-bottom")));break}s[e.position==="top"?"prependTo":"appendTo"](o)}};n.fn.fancybox=function(e){var t,r=n(this),i=this.selector||"",s=function(s){var o=n(this).blur(),a=t,f,l;if(!(s.ctrlKey||s.altKey||s.shiftKey||s.metaKey)&&!o.is(".fancybox-wrap")){f=e.groupAttr||"data-fancybox-group";l=o.attr(f);if(!l){f="rel";l=o.get(0)[f]}if(l&&l!==""&&l!=="nofollow"){o=i.length?n(i):r;o=o.filter("["+f+'="'+l+'"]');a=o.index(this)}e.index=a;if(u.open(o,e)!==false){s.preventDefault()}}};e=e||{};t=e.index||0;if(!i||e.live===false){r.unbind("click.fb-start").bind("click.fb-start",s)}else{o.undelegate(i,"click.fb-start").delegate(i+":not('.fancybox-item, .fancybox-nav')","click.fb-start",s)}this.filter("[data-fancybox-start=1]").trigger("click");return this};o.ready(function(){var t,s;if(n.scrollbarWidth===r){n.scrollbarWidth=function(){var e=n('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"),t=e.children(),r=t.innerWidth()-t.height(99).innerWidth();e.remove();return r}}if(n.support.fixedPosition===r){n.support.fixedPosition=function(){var e=n('<div style="position:fixed;top:20px;"></div>').appendTo("body"),t=e[0].offsetTop===20||e[0].offsetTop===15;e.remove();return t}()}n.extend(u.defaults,{scrollbarWidth:n.scrollbarWidth(),fixed:n.support.fixedPosition,parent:n("body")});t=n(e).width();i.addClass("fancybox-lock-test");s=n(e).width();i.removeClass("fancybox-lock-test");n("<style type='text/css'>.fancybox-margin{margin-right:"+(s-t)+"px;}</style>").appendTo("head")})})(window,document,jQuery)