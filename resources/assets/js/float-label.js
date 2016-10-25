(function(e,t,n,r){function o(t,n){this.$element=e(t);this.settings=e.extend({},s,n);this.init()}var i="floatlabel",s={slideInput:true,labelStartTop:"0px",labelEndTop:"0px",paddingOffset:"12px",transitionDuration:.1,transitionEasing:"ease-in-out",labelClass:"",typeMatches:/text|password|email|number|search|url|tel/};o.prototype={init:function(){var e=this,n=this.settings,r=n.transitionDuration,i=n.transitionEasing,s=this.$element;var o={"-webkit-transition":"all "+r+"s "+i,"-moz-transition":"all "+r+"s "+i,"-o-transition":"all "+r+"s "+i,"-ms-transition":"all "+r+"s "+i,transition:"all "+r+"s "+i};if(s.prop("tagName").toUpperCase()!=="INPUT"){return}if(!n.typeMatches.test(s.attr("type"))){return}var u=s.attr("id");if(!u){u=Math.floor(Math.random()*100)+1;s.attr("id",u)}var a=s.attr("placeholder");var f=s.data("label");var l=s.data("class");if(!l){l=""}if(!a||a===""){a="You forgot to add placeholder attribute!"}if(!f||f===""){f=a}this.inputPaddingTop=parseFloat(s.css("padding-top"))+parseFloat(n.paddingOffset);s.wrap('<div class="floatlabel-wrapper" style="position:relative"></div>');s.before('<label for="'+u+'" class="label-floatlabel '+n.labelClass+" "+l+'">'+f+"</label>");this.$label=s.prev("label");this.$label.css({position:"absolute",top:n.labelStartTop,left:"8px",display:"none","-moz-opacity":"0","-khtml-opacity":"0","-webkit-opacity":"0",opacity:"0","font-size":"11px","font-weight":"bold",color:"#838780"});if(!n.slideInput){s.css({"padding-top":this.inputPaddingTop})}s.on("keyup blur change",function(t){e.checkValue(t)});s.on("blur",function(){s.prev("label").css({color:"#838780"})});s.on("focus",function(){s.prev("label").css({color:"#2996cc"})});t.setTimeout(function(){e.$label.css(o);e.$element.css(o)},100);this.checkValue()},checkValue:function(e){if(e){var t=e.keyCode||e.which;if(t===9){return}}var n=this.$element,r=n.data("flout");if(n.val()!==""){n.data("flout","1")}if(n.val()===""){n.data("flout","0")}if(n.data("flout")==="1"&&r!=="1"){this.showLabel()}if(n.data("flout")==="0"&&r!=="0"){this.hideLabel()}},showLabel:function(){var e=this;e.$label.css({display:"block"});t.setTimeout(function(){e.$label.css({top:e.settings.labelEndTop,"-moz-opacity":"1","-khtml-opacity":"1","-webkit-opacity":"1",opacity:"1"});if(e.settings.slideInput){e.$element.css({"padding-top":e.inputPaddingTop})}e.$element.addClass("active-floatlabel")},50)},hideLabel:function(){var e=this;e.$label.css({top:e.settings.labelStartTop,"-moz-opacity":"0","-khtml-opacity":"0","-webkit-opacity":"0",opacity:"0"});if(e.settings.slideInput){e.$element.css({"padding-top":parseFloat(e.inputPaddingTop)-parseFloat(this.settings.paddingOffset)})}e.$element.removeClass("active-floatlabel");t.setTimeout(function(){e.$label.css({display:"none"})},e.settings.transitionDuration*1e3)}};e.fn[i]=function(t){return this.each(function(){if(!e.data(this,"plugin_"+i)){e.data(this,"plugin_"+i,new o(this,t))}})}})(jQuery,window,document)