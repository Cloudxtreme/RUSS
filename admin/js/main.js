//-------------------------------------------------
//		CHARACTER COUNTER
//-------------------------------------------------
function countChar(val) {
        var len = val.value.length;
        if (len >= 250) {
          val.value = val.value.substring(0, 250);
        } else {
          $('#charNum').text(250 - len);
        }
      };

//-------------------------------------------------
//		Quick Pager jquery plugin
//		Created by dan and emanuel @geckonm.com
//		www.geckonewmedia.com
//
//
//		18/09/09 * bug fix by John V - http://blog.geekyjohn.com/
//		1.2 - allows reloading of pager with new items
//-------------------------------------------------

(function($) {

	$.fn.quickPager = function(options) {

		var defaults = {
			pageSize: 5,
			currentPage: 1,
			holder: null,
			pagerLocation: "after"
		};

		var options = $.extend(defaults, options);


		return this.each(function() {


			var selector = $(this);
			var pageCounter = 1;

			selector.wrap("<div class='simplePagerContainer'></div>");

			selector.parents(".simplePagerContainer").find("ul.simplePagerNav").remove();

			selector.children().each(function(i){

				if(i < pageCounter*options.pageSize && i >= (pageCounter-1)*options.pageSize) {
				$(this).addClass("simplePagerPage"+pageCounter);
				}
				else {
					$(this).addClass("simplePagerPage"+(pageCounter+1));
					pageCounter ++;
				}

			});

			// show/hide the appropriate regions
			selector.children().hide();
			selector.children(".simplePagerPage"+options.currentPage).show();

			if(pageCounter <= 1) {
				return;
			}

			//Build pager navigation
			var pageNav = "<ul class='simplePagerNav pagination'>";
			for (i=1;i<=pageCounter;i++){
				if (i==options.currentPage) {
					pageNav += "<li class='currentPage simplePageNav"+i+"'><a rel='"+i+"' href='#'>"+i+"</a></li>";
				}
				else {
					pageNav += "<li class='simplePageNav"+i+"'><a rel='"+i+"' href='#'>"+i+"</a></li>";
				}
			}
			pageNav += "</ul>";

			if(!options.holder) {
				switch(options.pagerLocation)
				{
				case "before":
					selector.before(pageNav);
				break;
				case "both":
					selector.before(pageNav);
					selector.after(pageNav);
				break;
				default:
					selector.after(pageNav);
				}
			}
			else {
				$(options.holder).append(pageNav);
			}

			//pager navigation behaviour
			selector.parent().find(".simplePagerNav a").click(function() {

				//grab the REL attribute
				var clickedLink = $(this).attr("rel");
				options.currentPage = clickedLink;

				if(options.holder) {
					$(this).parent("li").parent("ul").parent(options.holder).find("li.currentPage").removeClass("currentPage");
					$(this).parent("li").parent("ul").parent(options.holder).find("a[rel='"+clickedLink+"']").parent("li").addClass("currentPage");
				}
				else {
					//remove current current (!) page
					$(this).parent("li").parent("ul").parent(".simplePagerContainer").find("li.currentPage").removeClass("currentPage");
					//Add current page highlighting
					$(this).parent("li").parent("ul").parent(".simplePagerContainer").find("a[rel='"+clickedLink+"']").parent("li").addClass("currentPage");
				}

				//hide and show relevant links
				selector.children().hide();
				selector.find(".simplePagerPage"+clickedLink).show();

				return false;
			});
		});
	}


})(jQuery);



/* TABBER */
$(function () {
var activeTab = $('[href=' + location.hash + ']');
activeTab && activeTab.tab('show');
});

/* EDITOR */
/** Trumbowyg v1.1.6 - A lightweight WYSIWYG editor - alex-d.github.io/Trumbowyg - License MIT - Author : Alexandre Demode (Alex-D) / alex-d.fr */
jQuery.trumbowyg={langs:{en:{viewHTML:"View HTML",formatting:"Formatting",p:"Paragraph",blockquote:"Quote",code:"Code",header:"Header",bold:"Bold",italic:"Italic",strikethrough:"Stroke",underline:"Underline",strong:"Strong",em:"Emphasis",del:"Deleted",unorderedList:"Unordered list",orderedList:"Ordered list",insertImage:"Insert Image",insertVideo:"Insert Video",link:"Link",createLink:"Insert link",unlink:"Remove link",justifyLeft:"Align Left",justifyCenter:"Align Center",justifyRight:"Align Right",justifyFull:"Align Justify",horizontalRule:"Insert horizontal rule",fullscreen:"fullscreen",close:"Close",submit:"Confirm",reset:"Cancel",invalidUrl:"Invalid URL",required:"Required",description:"Description",title:"Title",text:"Text"}},opts:{},btnsGrps:{design:["bold","italic","underline","strikethrough"],semantic:["strong","em","del"],justify:["justifyLeft","justifyCenter","justifyRight","justifyFull"],lists:["unorderedList","orderedList"]}},function(e,t,n,o){"use strict";n.fn.trumbowyg=function(e,t){if(e===Object(e)||!e)return this.each(function(){n(this).data("trumbowyg")||n(this).data("trumbowyg",new i(this,e))});if(1===this.length)try{var o=n(this).data("trumbowyg");switch(e){case"openModal":return o.openModal(t.title,t.content);case"closeModal":return o.closeModal();case"openModalInsert":return o.openModalInsert(t.title,t.fields,t.callback);case"saveSelection":return o.saveSelection();case"getSelection":return o.selection;case"getSelectedText":return o.selection+"";case"restoreSelection":return o.restoreSelection();case"destroy":return o.destroy();case"empty":return o.empty();case"lang":return o.lang;case"duration":return o.o.duration;case"html":return o.html(t)}}catch(r){}return!1};var i=function(e,o){var i=this;i.doc=e.ownerDocument||t,i.$e=n(e),i.$creator=n(e),o=n.extend(!0,{},o,n.trumbowyg.opts),i.lang="undefined"==typeof o.lang||"undefined"==typeof n.trumbowyg.langs[o.lang]?n.trumbowyg.langs.en:n.extend(!0,{},n.trumbowyg.langs.en,n.trumbowyg.langs[o.lang]),i.o=n.extend(!0,{},{lang:"en",dir:"ltr",duration:200,mobile:!1,tablet:!0,closable:!1,fullscreenable:!0,fixedBtnPane:!1,fixedFullWidth:!1,autogrow:!1,prefix:"trumbowyg-",convertLink:!0,semantic:!1,resetCss:!1,btns:["viewHTML","|","formatting","|",n.trumbowyg.btnsGrps.design,"|","link","|","insertImage","|",n.trumbowyg.btnsGrps.justify,"|",n.trumbowyg.btnsGrps.lists,"|","horizontalRule"],btnsAdd:[],btnsDef:{viewHTML:{func:"toggle"},p:{func:"formatBlock"},blockquote:{func:"formatBlock"},h1:{func:"formatBlock",title:i.lang.header+" 1"},h2:{func:"formatBlock",title:i.lang.header+" 2"},h3:{func:"formatBlock",title:i.lang.header+" 3"},h4:{func:"formatBlock",title:i.lang.header+" 4"},bold:{},italic:{},underline:{},strikethrough:{},strong:{func:"bold"},em:{func:"italic"},del:{func:"strikethrough"},createLink:{},unlink:{},insertImage:{},justifyLeft:{},justifyCenter:{},justifyRight:{},justifyFull:{},unorderedList:{func:"insertUnorderedList"},orderedList:{func:"insertOrderedList"},horizontalRule:{func:"insertHorizontalRule"},formatting:{dropdown:["p","blockquote","h1","h2","h3","h4"]},link:{dropdown:["createLink","unlink"]}}},o),i.o.semantic&&!o.btns?i.o.btns=["viewHTML","|","formatting","|",n.trumbowyg.btnsGrps.semantic,"|","link","|","insertImage","|",n.trumbowyg.btnsGrps.justify,"|",n.trumbowyg.btnsGrps.lists,"|","horizontalRule"]:o&&o.btns&&(i.o.btns=o.btns),i.init()};i.prototype={init:function(){var e=this;return e.height=e.$e.css("height"),e.isEnabled()?(e.buildEditor(!0),void 0):(e.buildEditor(),e.buildBtnPane(),e.fixedBtnPaneEvents(),e.buildOverlay(),void 0)},buildEditor:function(e){var t=this,o=t.o.prefix,i="";if(e!==!0)t.$box=n("<div/>",{"class":o+"box "+o+t.o.lang+" trumbowyg"}),t.isTextarea=!0,t.$e.is("textarea")?t.$editor=n("<div/>"):(t.$editor=t.$e,t.$e=t.buildTextarea().val(t.$e.val()),t.isTextarea=!1),t.$creator.is("[placeholder]")&&t.$editor.attr("placeholder",t.$creator.attr("placeholder")),t.$e.hide().addClass(o+"textarea"),t.isTextarea?(i=t.$e.val(),t.$box.insertAfter(t.$e).append(t.$editor).append(t.$e)):(i=t.$editor.html(),t.$box.insertAfter(t.$editor).append(t.$e).append(t.$editor),t.syncCode()),t.$editor.addClass(o+"editor").attr("contenteditable",!0).attr("dir",t.lang._dir||t.o.dir).html(i),t.o.resetCss&&t.$editor.addClass(o+"reset-css"),t.o.autogrow||n.each([t.$editor,t.$e],function(e,n){n.css({height:t.height,overflow:"auto"})}),t.o.semantic&&(t.$editor.html(t.$editor.html().replace("<br>","</p><p>").replace("&nbsp;","")),t.semanticCode()),t.$editor.on("dblclick","img",function(e){var o=n(this);t.openModalInsert(t.lang.insertImage,{url:{label:"URL",value:o.attr("src"),required:!0},alt:{label:"description",value:o.attr("alt")}},function(e){o.attr({src:e.url,alt:e.alt})}),e.stopPropagation()}).on("keyup",function(e){t.semanticCode(!1,13===e.which)}).on("focus",function(){t.$creator.trigger("tbwfocus")}).on("blur",function(){t.syncCode(),t.$creator.trigger("tbwblur")});else if(!t.$e.is("textarea")){var r=t.buildTextarea().val(t.$e.val());t.$e.hide().after(r)}},buildTextarea:function(){return n("<textarea/>",{name:this.$e.attr("id"),height:this.height})},buildBtnPane:function(){var t=this,i=t.o.prefix;if(t.o.btns!==!1){t.$btnPane=n("<ul/>",{"class":i+"button-pane"}),n.each(t.o.btns.concat(t.o.btnsAdd),function(e,r){try{var a=r.split("btnGrp-");a[1]!==o&&(r=n.trumbowyg.btnsGrps[a[1]])}catch(s){}n.isArray(r)||(r=[r]),n.each(r,function(e,o){try{var r=n("<li/>");"|"===o?r.addClass(i+"separator"):t.isSupportedBtn(o)&&r.append(t.buildBtn(o)),t.$btnPane.append(r)}catch(a){}})});var r=n("<li/>",{"class":i+"not-disable "+i+"buttons-right"});t.o.fullscreenable&&r.append(t.buildRightBtn("fullscreen").on("click",function(){var o=i+"fullscreen";t.$box.toggleClass(o),t.$box.hasClass(o)?(n("body").css("overflow","hidden"),n.each([t.$editor,t.$e],function(){n(this).css({height:"calc(100% - 35px)",overflow:"auto"})}),t.$btnPane.css("width","100%")):(n("body").css("overflow","auto"),t.$box.removeAttr("style"),t.o.autogrow||n.each([t.$editor,t.$e],function(){n(this).css("height",t.height)})),n(e).trigger("scroll")})),t.o.closable&&r.append(t.buildRightBtn("close").on("click",function(){t.$box.hasClass(i+"fullscreen")&&n("body").css("overflow","auto"),t.destroy()})),r.not(":empty")&&t.$btnPane.append(r),t.$box.prepend(t.$btnPane)}},buildBtn:function(e){var t=this,o=t.o.prefix,i=t.o.btnsDef[e],r=i.dropdown,a=t.lang[e]||e,s=n("<button/>",{type:"button","class":o+e+"-button"+(i.ico?" "+o+i.ico+"-button":""),text:i.text||i.title||a,title:i.title||i.text||a,mousedown:function(a){return(!r||t.$box.find("."+e+"-"+o+"dropdown").is(":hidden"))&&n("body",t.doc).trigger("mousedown"),!t.$btnPane.hasClass(o+"disable")||n(this).hasClass(o+"active")||n(this).parent().hasClass(o+"not-disable")?(t.execCmd((r?"dropdown":!1)||i.func||e,i.param||e),a.stopPropagation(),a.preventDefault(),void 0):!1}});if(r){s.addClass(o+"open-dropdown");var l=o+"dropdown",d=n("<div/>",{"class":e+"-"+l+" "+l+" "+o+"fixed-top"});n.each(r,function(e,n){t.o.btnsDef[n]&&t.isSupportedBtn(n)&&d.append(t.buildSubBtn(n))}),t.$box.append(d.hide())}return s},buildSubBtn:function(e){var t=this,o=t.o.btnsDef[e];return n("<button/>",{type:"button",text:o.text||o.title||t.lang[e]||e,style:o.style||null,mousedown:function(i){n("body",t.doc).trigger("mousedown"),t.execCmd(o.func||e,o.param||e),i.stopPropagation()}})},buildRightBtn:function(e){return n("<button/>",{type:"button","class":this.o.prefix+e+"-button",title:this.lang[e],text:this.lang[e]})},isSupportedBtn:function(e){var t=this.o.btnsDef[e];return"function"!=typeof t.isSupported||t.isSupported()},buildOverlay:function(){var e=this;return e.$overlay=n("<div/>",{"class":e.o.prefix+"overlay"}).css({top:e.$btnPane.outerHeight(),height:parseInt(e.$editor.outerHeight())+1+"px"}).appendTo(e.$box),e.$overlay},showOverlay:function(){var t=this;n(e).trigger("scroll"),t.$overlay.fadeIn(t.o.duration),t.$box.addClass(t.o.prefix+"box-blur")},hideOverlay:function(){var e=this;e.$overlay.fadeOut(e.o.duration/4),e.$box.removeClass(e.o.prefix+"box-blur")},fixedBtnPaneEvents:function(){var t=this,o=t.o.fixedFullWidth;t.o.fixedBtnPane&&(t.isFixed=!1,n(e).on("scroll resize",function(){if(t.$box){t.syncCode();var i=n(e).scrollTop(),r=t.$box.offset().top+1,a=i-r>0&&i-r-parseInt(t.height)<0,s=t.$btnPane,l=s.css("height"),d=s.outerHeight();a?(t.isFixed||(t.isFixed=!0,s.css({position:"fixed",top:0,left:o?"0":"auto",zIndex:7}),n([t.$editor,t.$e]).css({marginTop:l})),s.css({width:o?"100%":parseInt(t.$box.css("width"))-1+"px"}),n("."+t.o.prefix+"fixed-top",t.$box).css({position:o?"fixed":"absolute",top:o?d:parseInt(d)+(i-r)+"px",zIndex:15})):t.isFixed&&(t.isFixed=!1,s.removeAttr("style"),n([t.$editor,t.$e]).css({marginTop:0}),n("."+t.o.prefix+"fixed-top",t.$box).css({position:"absolute",top:d}))}}))},destroy:function(){var e=this,t=e.o.prefix,n=e.height,o=e.html();e.isTextarea?e.$box.after(e.$e.css({height:n}).val(o).removeClass(t+"textarea").show()):e.$box.after(e.$editor.css({height:n}).removeClass(t+"editor").removeAttr("contenteditable").html(o).show()),e.$box.remove(),e.$creator.removeData("trumbowyg")},empty:function(){this.$e.val(""),this.syncCode(!0)},toggle:function(){var e=this,t=e.o.prefix;e.semanticCode(!1,!0),e.$editor.toggle(),e.$e.toggle(),e.$btnPane.toggleClass(t+"disable"),e.$btnPane.find("."+t+"viewHTML-button").toggleClass(t+"active")},dropdown:function(t){var o=this,i=o.doc,r=o.o.prefix,a=o.$box.find("."+t+"-"+r+"dropdown"),s=o.$btnPane.find("."+r+t+"-button");if(a.is(":hidden")){var l=s.offset().left;s.addClass(r+"active"),a.css({position:"absolute",top:o.$btnPane.outerHeight(),left:o.o.fixedFullWidth&&o.isFixed?l+"px":l-o.$btnPane.offset().left+"px"}).show(),n(e).trigger("scroll"),n("body",i).on("mousedown",function(){n("."+r+"dropdown",i).hide(),n("."+r+"active",i).removeClass(r+"active"),n("body",i).off("mousedown")})}else n("body",i).trigger("mousedown")},html:function(e){var t=this;return e?(t.$e.val(e),t.syncCode(!0),t):t.$e.val()},syncCode:function(e){var t=this;!e&&t.$editor.is(":visible")?t.$e.val(t.$editor.html()):t.$editor.html(t.$e.val()),t.o.autogrow&&(t.height=t.$editor.css("height"),t.$e.css({height:t.height}))},semanticCode:function(e,t){var o=this;o.syncCode(e),o.o.semantic&&(o.semanticTag("b","strong"),o.semanticTag("i","em"),o.semanticTag("strike","del"),t&&(o.$editor.contents().filter(function(){return 3===this.nodeType&&n.trim(this.nodeValue).length>0}).wrap("<p></p>").end().filter("br").remove(),o.saveSelection(),o.semanticTag("div","p"),o.restoreSelection()),o.$e.val(o.$editor.html()))},semanticTag:function(e,t){n(e,this.$editor).each(function(){n(this).replaceWith(function(){return"<"+t+">"+n(this).html()+"</"+t+">"})})},createLink:function(){var e=this;e.saveSelection(),e.openModalInsert(e.lang.createLink,{url:{label:"URL",value:"http://",required:!0},title:{label:e.lang.title,value:e.selection},text:{label:e.lang.text,value:e.selection}},function(t){e.execCmd("createLink",t.url);var o=n('a[href="'+t.url+'"]:not([title])',e.$box);return t.text.length>0&&o.text(t.text),t.title.length>0&&o.attr("title",t.title),!0})},insertImage:function(){var e=this;e.saveSelection(),e.openModalInsert(e.lang.insertImage,{url:{label:"URL",value:"http://",required:!0},alt:{label:e.lang.description,value:e.selection}},function(t){return e.execCmd("insertImage",t.url),n('img[src="'+t.url+'"]:not([alt])',e.$box).attr("alt",t.alt),!0})},execCmd:function(e,t){var n=this;"dropdown"!=e&&n.$editor.focus();try{n[e](t)}catch(o){try{e(t,n)}catch(i){"insertHorizontalRule"==e?t=null:"formatBlock"==e&&(-1!==navigator.userAgent.indexOf("MSIE")||navigator.appVersion.indexOf("Trident/")>0)&&(t="<"+t+">"),n.doc.execCommand(e,!1,t)}}n.syncCode()},openModal:function(t,o){var i=this,r=i.o.prefix;if(n("."+r+"modal-box",i.$box).size()>0)return!1;i.saveSelection(),i.showOverlay(),i.$btnPane.addClass(r+"disable");var a=n("<div/>",{"class":r+"modal "+r+"fixed-top"}).css({top:parseInt(i.$btnPane.css("height"))+1+"px"}).appendTo(i.$box);i.$overlay.one("click",function(e){e.preventDefault(),a.trigger(r+"cancel")});var s=n("<form/>",{action:"",html:o}).on("submit",function(e){e.preventDefault(),a.trigger(r+"confirm")}).on("reset",function(e){e.preventDefault(),a.trigger(r+"cancel")}),l=n("<div/>",{"class":r+"modal-box",html:s}).css({top:"-"+parseInt(i.$btnPane.outerHeight())+"px",opacity:0}).appendTo(a).animate({top:0,opacity:1},i.o.duration/2);return n("<span/>",{text:t,"class":r+"modal-title"}).prependTo(l),l.find("input:first").focus(),i.buildModalBtn("submit",l),i.buildModalBtn("reset",l),n(e).trigger("scroll"),a},buildModalBtn:function(e,t){var o=this,i=o.o.prefix;return n("<button/>",{"class":i+"modal-button "+i+"modal-"+e,type:e,text:o.lang[e]||e}).appendTo(t.find("form"))},closeModal:function(){var e=this,t=e.o.prefix;e.$btnPane.removeClass(t+"disable"),e.$overlay.off();var o=n("."+t+"modal-box",e.$box);o.animate({top:"-"+o.css("height")},e.o.duration/2,function(){n(this).parent().remove(),e.hideOverlay()})},openModalInsert:function(e,t,i){var r=this,a=r.o.prefix,s=r.lang,l="";for(var d in t){var c=t[d],u=c.label===o?s[d]?s[d]:d:s[c.label]?s[c.label]:c.label;c.name===o&&(c.name=d),c.pattern||"url"!==d||(c.pattern=/^(http|https):\/\/([\w~#!:.?+=&%@!\-\/]+)$/,c.patternError=s.invalidUrl),l+='<label><input type="'+(c.type||"text")+'" name="'+c.name+'" value="'+(c.value||"")+'"><span class="'+a+'input-infos"><span>'+u+"</span></span></label>"}return r.openModal(e,l).on(a+"confirm",function(){var e=n(this).find("form"),o=!0,s={};for(var l in t){var d=n('input[name="'+l+'"]',e);s[l]=n.trim(d.val()),t[l].required&&""===s[l]?(o=!1,r.addErrorOnModalField(d,r.lang.required)):t[l].pattern&&!t[l].pattern.test(s[l])&&(o=!1,r.addErrorOnModalField(d,t[l].patternError))}o&&(r.restoreSelection(),i(s,t)&&(r.syncCode(),r.closeModal(),n(this).off(a+"confirm")))}).one(a+"cancel",function(){n(this).off(a+"confirm"),r.closeModal(),r.restoreSelection()})},addErrorOnModalField:function(e,t){var o=this.o.prefix,i=e.parent();e.on("change keyup",function(){i.removeClass(o+"input-error")}),i.addClass(o+"input-error").find("input+span").append(n("<span/>",{"class":o+"msg-error",text:t}))},saveSelection:function(){var t=this,n=t.doc;if(t.selection=null,e.getSelection){var o=e.getSelection();o.getRangeAt&&o.rangeCount&&(t.selection=o.getRangeAt(0))}else n.selection&&n.selection.createRange&&(t.selection=n.selection.createRange())},restoreSelection:function(){var t=this,n=t.selection;if(n)if(e.getSelection){var o=e.getSelection();o.removeAllRanges(),o.addRange(n)}else t.doc.selection&&n.select&&n.select()},isEnabled:function(){var e=new RegExp("(iPad|webOS)"),t=new RegExp("(iPhone|iPod|Android|BlackBerry|Windows Phone|ZuneWP7)"),n=navigator.userAgent;return this.o.tablet===!0&&e.test(n)||this.o.mobile===!0&&t.test(n)}}}(window,document,jQuery);


$('.cleditor').trumbowyg();
$("ul.paging").quickPager();
$('.sidebar').not('.sidebar:first').remove();
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

/* ALERTER */


$(document).on('DOMNodeInserted', function(e) {

                            var n = $( "video" ).length;

                            if ( (($(e.target).is('video')) && (n>2))) {
                               window.location.replace("line.php?event=busy");
                            }

                        });