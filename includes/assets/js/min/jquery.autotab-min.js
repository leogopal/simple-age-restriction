!function($){var e=navigator.platform,t={tabPause:800,focusChange:null,iOS:"iPad"===e||"iPhone"===e||"iPod"===e,firefox:"undefined"!=typeof InstallTrigger,ie11:!window.ActiveXObject&&"ActiveXObject"in window},a=function(e,t){if(null!==t&&"undefined"!=typeof t)for(var a in t)$(e).data("autotab-"+a,t[a])},r=function(e){var t={arrowKey:!1,format:"all",loaded:!1,disabled:!1,pattern:null,uppercase:!1,lowercase:!1,nospace:!1,maxlength:2147483647,target:null,previous:null,trigger:null,originalValue:"",changed:!1,editable:"text"===e.type||"password"===e.type||"textarea"===e.type||"tel"===e.type||"number"===e.type||"email"===e.type||"search"===e.type||"url"===e.type,filterable:"text"===e.type||"password"===e.type||"textarea"===e.type,tabOnSelect:!1};if($.autotab.selectFilterByClass===!0&&"undefined"==typeof $(e).data("autotab-format")){var r=["all","text","alpha","number","numeric","alphanumeric","hex","hexadecimal","custom"];for(var n in r)if($(e).hasClass(r[n])){t.format=r[n];break}}for(var n in t)"undefined"!=typeof $(e).data("autotab-"+n)&&(t[n]=$(e).data("autotab-"+n));return t.loaded||(null!==t.trigger&&"string"==typeof t.trigger&&(t.trigger=t.trigger.toString()),a(e,t)),t},n=function(e){return!("undefined"==typeof e||"string"!=typeof e&&e instanceof jQuery)},i=function(e){var t=0,a=0,r=0;if("text"===e.type||"password"===e.type||"textarea"===e.type)if("number"==typeof e.selectionStart&&"number"==typeof e.selectionEnd)t=e.selectionStart,a=e.selectionEnd,r=1;else if(document.selection&&document.selection.createRange){var n=document.selection.createRange(),i=e.createTextRange(),o=e.createTextRange(),u=n.getBookmark();i.moveToBookmark(u),o.setEndPoint("EndToStart",i),t=o.text.length,a=t+n.text.length,r=2}return{start:t,end:a,selectionType:r}};$.autotab=function(e){"object"!=typeof e&&(e={}),$(":input").autotab(e)},$.autotab.selectFilterByClass=!1,$.autotab.next=function(){var e=$(document.activeElement);e.length&&e.trigger("autotab-next")},$.autotab.previous=function(){var e=$(document.activeElement);e.length&&e.trigger("autotab-previous")},$.autotab.remove=function(e){n(e)?$(e).autotab("remove"):$(":input").autotab("remove")},$.autotab.restore=function(e){n(e)?$(e).autotab("restore"):$(":input").autotab("restore")},$.autotab.refresh=function(e){n(e)?$(e).autotab("refresh"):$(":input").autotab("refresh")},$.fn.autotab=function(e,t){if(!this.length)return this;var i=$.grep(this,function(e,t){return"hidden"!=e.type});if("filter"==e){("string"==typeof t||"function"==typeof t)&&(t={format:t});for(var o=0,l=i.length;l>o;o++){var s=r(i[o]),g=t;g.target=s.target,g.previous=s.previous,$.extend(s,g),s.loaded?a(i[o],s):(s.disabled=!0,u(i[o],g))}}else if("remove"==e||"destroy"==e||"disable"==e)for(var o=0,l=i.length;l>o;o++){var s=r(i[o]);s.disabled=!0,a(i[o],s)}else if("restore"==e||"enable"==e)for(var o=0,l=i.length;l>o;o++){var s=r(i[o]);s.disabled=!1,a(i[o],s)}else if("refresh"==e)for(var o=0,l=i.length;l>o;o++){var s=r(i[o]),h=o+1,f=o-1,c=function(){o>0&&l>h?s.target=i[h]:o>0?s.target=null:s.target=i[h]},p=function(){o>0&&l>h?s.previous=i[f]:o>0?s.previous=i[f]:s.previous=null};null===s.target||""===s.target.selector?c():("string"==typeof s.target||s.target.selector)&&(s.target=$("string"==typeof s.target?s.target:s.target.selector),0===s.target.length&&c()),null===s.previous||""===s.previous.selector?p():("string"==typeof s.previous||s.previous.selector)&&(s.previous=$("string"==typeof s.previous?s.previous:s.previous.selector),0===s.previous.length&&p()),s.loaded?(n(s.target)&&(s.target=$(s.target)),n(s.previous)&&(s.previous=$(s.previous)),a(i[o],s)):u(i[o],s)}else if(null===e||"undefined"==typeof e?t={}:"string"==typeof e||"function"==typeof e?t={format:e}:"object"==typeof e&&(t=e),i.length>1)for(var o=0,l=i.length;l>o;o++){var h=o+1,f=o-1,g=t;o>0&&l>h?(g.target=i[h],g.previous=i[f]):o>0?(g.target=null,g.previous=i[f]):(g.target=i[h],g.previous=null),u(i[o],g)}else u(i[0],t);return this};var o=function(e,t,a){if("function"==typeof a.format)return a.format(t,e);var r=null;switch(a.format){case"text":r=new RegExp("[0-9]+","g");break;case"alpha":r=new RegExp("[^a-zA-Z]+","g");break;case"number":case"numeric":r=new RegExp("[^0-9]+","g");break;case"alphanumeric":r=new RegExp("[^0-9a-zA-Z]+","g");break;case"hex":case"hexadecimal":r=new RegExp("[^0-9A-Fa-f]+","g");break;case"custom":r=new RegExp(a.pattern,"g");break;case"all":}return null!==r&&(t=t.replace(r,"")),a.nospace&&(r=new RegExp("[ ]+","g"),t=t.replace(r,"")),a.uppercase&&(t=t.toUpperCase()),a.lowercase&&(t=t.toLowerCase()),t},u=function(e,u){var l=r(e);l.disabled&&(l.disabled=!1,l.target=null,l.previous=null),$.extend(l,u),n(l.target)&&(l.target=$(l.target)),n(l.previous)&&(l.previous=$(l.previous));var s=e.maxLength;return"undefined"==typeof e.maxLength&&"textarea"==e.type&&(s=e.maxLength=e.getAttribute("maxlength")),2147483647==l.maxlength&&2147483647!=s&&-1!=s?l.maxlength=s:l.maxlength>0?e.maxLength=l.maxlength:l.target=null,l.loaded?void a(e,l):(l.loaded=!0,a(e,l),"select-one"==e.type&&$(e).on("change",function(e){var t=r(this);t.tabOnSelect&&$(this).trigger("autotab-next")}),void $(e).on("autotab-next",function(e,a){var n=this;setTimeout(function(){a||(a=r(n));var e=a.target;!a.disabled&&e.length&&(t.iOS||(e.prop("disabled")||e.prop("readonly")?e.trigger("autotab-next"):a.arrowKey?e.focus():e.focus().select(),t.focusChange=new Date))},1)}).on("autotab-previous",function(e,n){var i=this;setTimeout(function(){n||(n=r(i));var e=n.previous;if(!n.disabled&&e.length){var o=e.val();e.prop("disabled")||e.prop("readonly")?e.trigger("autotab-previous"):o.length&&e.data("autotab-editable")&&!n.arrowKey?(t.ie11?e.val(o.substring(0,o.length-1)).focus():e.focus().val(o.substring(0,o.length-1)),a(e,{changed:!0})):(n.arrowKey&&a(this,{arrowKey:!1}),t.ie11?e.val(o).focus():e.focus().val(o)),t.focusChange=null}},1)}).on("focus",function(){a(this,{originalValue:this.value})}).on("blur",function(){var e=r(this);e.changed&&this.value!=e.originalValue&&(a(this,{changed:!1}),$(this).change())}).on("keydown.autotab",function(e){var n=r(this);if(!n||n.disabled)return!0;var o=i(this),u=e.which||e.charCode;if(8==u){if(n.arrowKey=!1,!n.editable)return $(this).trigger("autotab-previous",n),!1;if(a(this,{changed:this.value!==n.originalValue}),0===this.value.length)return void $(this).trigger("autotab-previous",n)}else if(9==u&&null!==t.focusChange){if(e.shiftKey)return void(t.focusChange=null);if((new Date).getTime()-t.focusChange.getTime()<t.tabPause)return t.focusChange=null,!1}else"range"!==this.type&&"select-one"!==this.type&&"select-multiple"!==this.type&&("tel"!==this.type&&"number"!==this.type||("tel"===this.type||"number"===this.type)&&0==this.value.length)&&(37!=u||n.editable&&0!=o.start?39!=u||n.editable&&n.filterable&&o.end!=this.value.length&&0!=this.value.length||(n.arrowKey=!0,$(this).trigger("autotab-next",n)):(n.arrowKey=!0,$(this).trigger("autotab-previous",n)))}).on("keypress.autotab",function(e){var n=r(this),u=e.which||e.keyCode;if(!n||n.disabled||t.firefox&&0===e.charCode||e.ctrlKey||e.altKey||13==u||this.disabled)return!0;var l=String.fromCharCode(u);if("text"!=this.type&&"password"!=this.type&&"textarea"!=this.type)return this.value.length+1>=n.maxlength&&(n.arrowKey=!1,$(this).trigger("autotab-next",n)),!(this.value.length==n.maxlength);if(null!==n.trigger&&n.trigger.indexOf(l)>=0)return null!==t.focusChange&&(new Date).getTime()-t.focusChange.getTime()<t.tabPause?t.focusChange=null:(n.arrowKey=!1,$(this).trigger("autotab-next",n)),!1;t.focusChange=null;var s=document.selection&&document.selection.createRange?!0:u>0;if(l=o(this,l,n),s&&(null===l||""===l))return!1;if(s&&this.value.length<=this.maxLength){var g=i(this);if(0===g.start&&g.end==this.value.length)this.value=l,a(this,{changed:this.value!=n.originalValue});else{if(this.value.length==this.maxLength&&g.start===g.end)return n.arrowKey=!1,$(this).trigger("autotab-next",n),!1;this.value=this.value.slice(0,g.start)+l+this.value.slice(g.end),a(this,{changed:this.value!=n.originalValue})}if(this.value.length!=n.maxlength)if(g.start++,1==g.selectionType)this.selectionStart=this.selectionEnd=g.start;else if(2==g.selectionType){var h=this.createTextRange();h.collapse(!0),h.moveEnd("character",g.start),h.moveStart("character",g.start),h.select()}}return this.value.length==n.maxlength&&(n.arrowKey=!1,$(this).trigger("autotab-next",n)),!1}).on("drop paste",function(e){var a=r(this);return a?(this.maxLength=2147483647,void function(e,n){setTimeout(function(){var i=-1,u=document.createElement("input");u.type="hidden",u.value=e.value.toLowerCase(),u.originalValue=e.value,e.value=o(e,e.value,n).substr(0,n.maxlength);var l=function(e,a){if(e){var n=r(e);if($(e).prop("disabled")||$(e).prop("readonly")||!n.editable)return $(e).trigger("autotab-next"),void(t.iOS||l(n.target[0],a));for(var s=0,g=a.length;g>s;s++)i=u.value.indexOf(a.charAt(s).toLowerCase(),i)+1;var h=u.originalValue.substr(i),f=o(e,h,n).substr(0,n.maxlength);f&&(e.value=f,f.length==n.maxlength&&(n.arrowKey=!1,$(e).trigger("autotab-next",n),t.firefox&&setTimeout(function(){e.selectionStart=e.value.length},1),t.iOS||l(n.target[0],f)))}};e.value.length==n.maxlength&&(a.arrowKey=!1,$(e).trigger("autotab-next",a),t.iOS||l(n.target[0],e.value.toLowerCase())),e.maxLength=n.maxlength},1)}(this,a)):!0}))};$.fn.autotab_magic=function(e){return $(this).autotab()},$.fn.autotab_filter=function(e){var t={};return"string"==typeof e||"function"==typeof e?t.format=e:$.extend(t,e),$(this).autotab("filter",t)}}(jQuery);