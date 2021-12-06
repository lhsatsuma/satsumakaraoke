!function(t,a,e){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports&&"undefined"==typeof Meteor?module.exports=t(require("jquery")):t(a||e)}(function(t){"use strict";var a=function(a,e,n){var s={invalid:[],getCaret:function(){try{var t,e=0,n=a.get(0),r=document.selection,o=n.selectionStart;return r&&-1===navigator.appVersion.indexOf("MSIE 10")?((t=r.createRange()).moveStart("character",-s.val().length),e=t.text.length):(o||"0"===o)&&(e=o),e}catch(t){}},setCaret:function(t){try{if(a.is(":focus")){var e,n=a.get(0);n.setSelectionRange?n.setSelectionRange(t,t):((e=n.createTextRange()).collapse(!0),e.moveEnd("character",t),e.moveStart("character",t),e.select())}}catch(t){}},events:function(){a.on("keydown.mask",function(t){a.data("mask-keycode",t.keyCode||t.which),a.data("mask-previus-value",a.val()),a.data("mask-previus-caret-pos",s.getCaret()),s.maskDigitPosMapOld=s.maskDigitPosMap}).on(t.jMaskGlobals.useInput?"input.mask":"keyup.mask",s.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){a.keydown().keyup()},100)}).on("change.mask",function(){a.data("changed",!0)}).on("blur.mask",function(){i===s.val()||a.data("changed")||a.trigger("change"),a.data("changed",!1)}).on("blur.mask",function(){i=s.val()}).on("focus.mask",function(a){!0===n.selectOnFocus&&t(a.target).select()}).on("focusout.mask",function(){n.clearIfNotMatch&&!r.test(s.val())&&s.val("")})},getRegexMask:function(){for(var t,a,n,s,r,i,l=[],c=0;c<e.length;c++)(t=o.translation[e.charAt(c)])?(a=t.pattern.toString().replace(/.{1}$|^.{1}/g,""),n=t.optional,(s=t.recursive)?(l.push(e.charAt(c)),r={digit:e.charAt(c),pattern:a}):l.push(n||s?a+"?":a)):l.push(e.charAt(c).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));return i=l.join(""),r&&(i=i.replace(new RegExp("("+r.digit+"(.*"+r.digit+")?)"),"($1)?").replace(new RegExp(r.digit,"g"),r.pattern)),new RegExp(i)},destroyEvents:function(){a.off(["input","keydown","keyup","paste","drop","blur","focusout",""].join(".mask "))},val:function(t){var e,n=a.is("input")?"val":"text";return arguments.length>0?(a[n]()!==t&&a[n](t),e=a):e=a[n](),e},calculateCaretPosition:function(t){var e=s.getMasked(),n=s.getCaret();if(t!==e){var r=a.data("mask-previus-caret-pos")||0,o=e.length,i=t.length,l=0,c=0,u=0,k=0,f=0;for(f=n;f<o&&s.maskDigitPosMap[f];f++)c++;for(f=n-1;f>=0&&s.maskDigitPosMap[f];f--)l++;for(f=n-1;f>=0;f--)s.maskDigitPosMap[f]&&u++;for(f=r-1;f>=0;f--)s.maskDigitPosMapOld[f]&&k++;if(n>i)n=10*o;else if(r>=n&&r!==i){if(!s.maskDigitPosMapOld[n]){var d=n;n-=k-u,n-=l,s.maskDigitPosMap[n]&&(n=d)}}else n>r&&(n+=u-k,n+=c)}return n},behaviour:function(e){e=e||window.event,s.invalid=[];var n=a.data("mask-keycode");if(-1===t.inArray(n,o.byPassKeys)){var r=s.getMasked(),i=s.getCaret(),l=a.data("mask-previus-value")||"";return setTimeout(function(){s.setCaret(s.calculateCaretPosition(l))},t.jMaskGlobals.keyStrokeCompensation),s.val(r),s.setCaret(i),s.callbacks(e)}},getMasked:function(t,a){var r,i,l,c=[],u=void 0===a?s.val():a+"",k=0,f=e.length,d=0,p=u.length,v=1,h="push",m=-1,g=0,M=[];for(n.reverse?(h="unshift",v=-1,r=0,k=f-1,d=p-1,i=function(){return k>-1&&d>-1}):(r=f-1,i=function(){return k<f&&d<p});i();){var y=e.charAt(k),b=u.charAt(d),w=o.translation[y];w?(b.match(w.pattern)?(c[h](b),w.recursive&&(-1===m?m=k:k===r&&k!==m&&(k=m-v),r===m&&(k-=v)),k+=v):b===l?(g--,l=void 0):w.optional?(k+=v,d-=v):w.fallback?(c[h](w.fallback),k+=v,d-=v):s.invalid.push({p:d,v:b,e:w.pattern}),d+=v):(t||c[h](y),b===y?(M.push(d),d+=v):(l=y,M.push(d+g),g++),k+=v)}var C=e.charAt(r);f!==p+1||o.translation[C]||c.push(C);var j=c.join("");return s.mapMaskdigitPositions(j,M,p),j},mapMaskdigitPositions:function(t,a,e){var r=n.reverse?t.length-e:0;s.maskDigitPosMap={};for(var o=0;o<a.length;o++)s.maskDigitPosMap[a[o]+r]=1},callbacks:function(t){var r=s.val(),o=r!==i,l=[r,t,a,n],c=function(t,a,e){"function"==typeof n[t]&&a&&n[t].apply(this,e)};c("onChange",!0===o,l),c("onKeyPress",!0===o,l),c("onComplete",r.length===e.length,l),c("onInvalid",s.invalid.length>0,[r,t,a,s.invalid,n])}};a=t(a);var r,o=this,i=s.val();e="function"==typeof e?e(s.val(),void 0,a,n):e,o.mask=e,o.options=n,o.remove=function(){var t=s.getCaret();return o.options.placeholder&&a.removeAttr("placeholder"),a.data("mask-maxlength")&&a.removeAttr("maxlength"),s.destroyEvents(),s.val(o.getCleanVal()),s.setCaret(t),a},o.getCleanVal=function(){return s.getMasked(!0)},o.getMaskedVal=function(t){return s.getMasked(!1,t)},o.init=function(i){if(i=i||!1,n=n||{},o.clearIfNotMatch=t.jMaskGlobals.clearIfNotMatch,o.byPassKeys=t.jMaskGlobals.byPassKeys,o.translation=t.extend({},t.jMaskGlobals.translation,n.translation),o=t.extend(!0,{},o,n),r=s.getRegexMask(),i)s.events(),s.val(s.getMasked());else{n.placeholder&&a.attr("placeholder",n.placeholder),a.data("mask")&&a.attr("autocomplete","off");for(var l=0,c=!0;l<e.length;l++){var u=o.translation[e.charAt(l)];if(u&&u.recursive){c=!1;break}}c&&a.attr("maxlength",e.length).data("mask-maxlength",!0),s.destroyEvents(),s.events();var k=s.getCaret();s.val(s.getMasked()),s.setCaret(k)}},o.init(!a.is("input"))};t.maskWatchers={};var e=function(){var e=t(this),s={},r=e.attr("data-mask");if(e.attr("data-mask-reverse")&&(s.reverse=!0),e.attr("data-mask-clearifnotmatch")&&(s.clearIfNotMatch=!0),"true"===e.attr("data-mask-selectonfocus")&&(s.selectOnFocus=!0),n(e,r,s))return e.data("mask",new a(this,r,s))},n=function(a,e,n){n=n||{};var s=t(a).data("mask"),r=JSON.stringify,o=t(a).val()||t(a).text();try{return"function"==typeof e&&(e=e(o)),"object"!=typeof s||r(s.options)!==r(n)||s.mask!==e}catch(t){}};t.fn.mask=function(e,s){s=s||{};var r=this.selector,o=t.jMaskGlobals,i=o.watchInterval,l=s.watchInputs||o.watchInputs,c=function(){if(n(this,e,s))return t(this).data("mask",new a(this,e,s))};return t(this).each(c),r&&""!==r&&l&&(clearInterval(t.maskWatchers[r]),t.maskWatchers[r]=setInterval(function(){t(document).find(r).each(c)},i)),this},t.fn.masked=function(t){return this.data("mask").getMaskedVal(t)},t.fn.unmask=function(){return clearInterval(t.maskWatchers[this.selector]),delete t.maskWatchers[this.selector],this.each(function(){var a=t(this).data("mask");a&&a.remove().removeData("mask")})},t.fn.cleanVal=function(){return this.data("mask").getCleanVal()},t.applyDataMask=function(a){((a=a||t.jMaskGlobals.maskElements)instanceof t?a:t(a)).filter(t.jMaskGlobals.dataMaskAttr).each(e)};var s,r,o,i={maskElements:"input,td,span,div",dataMaskAttr:"[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,keyStrokeCompensation:10,useInput:!/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent)&&(s="input",o=document.createElement("div"),(r=(s="on"+s)in o)||(o.setAttribute(s,"return;"),r="function"==typeof o[s]),o=null,r),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};t.jMaskGlobals=t.jMaskGlobals||{},(i=t.jMaskGlobals=t.extend(!0,{},i,t.jMaskGlobals)).dataMask&&t.applyDataMask(),setInterval(function(){t.jMaskGlobals.watchDataMask&&t.applyDataMask()},i.watchInterval)},window.jQuery,window.Zepto);
var num_grp_sep=".",dec_sep=",",sig_digits=2;function round_up(t,e){return Math.ceil(t*Math.pow(10,e))/Math.pow(10,e)}if(void 0===unformatNumberNoParse){function unformatNumberNoParse(t,e,r){if(void 0===e||void 0===r)return t;if((t=t?t.toString():"").length>0){if(""!=e&&(num_grp_sep_re=new RegExp("\\"+e,"g"),t=t.replace(num_grp_sep_re,"")),t=t.replace(r,"."),"undefined"!=typeof CurrencySymbols)for(var n in CurrencySymbols)t=t.replace(CurrencySymbols[n],"");return t}return""}function formatNumber(t,e,r,n,o){if(void 0===e||void 0===r)return t;if(!(t=t?t.toString():"").split)return t;if((t=t.split(".")).length>2)return t.join(".");if(void 0!==n&&(n>0&&t.length>1&&(t[1]=parseFloat("0."+t[1]),t[1]=Math.round(t[1]*Math.pow(10,n))/Math.pow(10,n),t[1]=t[1].toString().split(".")[1]),n<=0&&(t[0]=Math.round(parseInt(t[0],10)*Math.pow(10,n))/Math.pow(10,n),t[1]="")),void 0!==o&&o>=0&&(t.length>1&&void 0!==t[1]?t[1]=t[1].substring(0,o):t[1]="",t[1].length<o))for(var u=t[1].length;u<o;u++)t[1]+="0";for(regex=/(\d+)(\d{3})/;""!=e&&regex.test(t[0]);)t[0]=t[0].toString().replace(regex,"$1"+e+"$2");return t[0]+(t.length>1&&""!=t[1]?r+t[1]:"")}function unformatNumber(t,e,r){var n=unformatNumberNoParse(t,e,r);return(n=n.toString()).length>0?parseFloat(n):""}function unformat2Number(t){return unformatNumber(t,num_grp_sep,dec_sep)}function format2Number(t,e){return void 0===e&&(e=sig_digits),num=Number(t),(t=(t=2==e?formatCurrency(num):num.toFixed(e)).split(/,/).join("{,}").split(/\./).join("{.}")).split("{,}").join(num_grp_sep).split("{.}").join(dec_sep)}function formatCurrency(t){t=t.toString().replace(/\$|\,/g,""),dblValue=parseFloat(t),blnSign=dblValue==(dblValue=Math.abs(dblValue)),dblValue=Math.floor(100*dblValue+.50000000001),intCents=dblValue%100,strCents=intCents.toString(),dblValue=Math.floor(dblValue/100).toString(),intCents<10&&(strCents="0"+strCents);for(var e=0;e<Math.floor((dblValue.length-(1+e))/3);e++)dblValue=dblValue.substring(0,dblValue.length-(4*e+3))+","+dblValue.substring(dblValue.length-(4*e+3));return(blnSign?"":"-")+dblValue+"."+strCents}function formatNumber(t,e,r,n,o){if(void 0===e||void 0===r)return t;if(0===t&&(t="0"),!(t=t?t.toString():"").split)return t;if((t=t.split(".")).length>2)return t.join(".");if(void 0!==n&&(n>0&&t.length>1&&(t[1]=parseFloat("0."+t[1]),t[1]=Math.round(t[1]*Math.pow(10,n))/Math.pow(10,n),t[1].toString().includes(".")?t[1]=t[1].toString().split(".")[1]:(t[0]=(parseInt(t[0])+t[1]).toString(),t[1]="")),n<=0&&(t[0]=Math.round(parseInt(t[0],10)*Math.pow(10,n))/Math.pow(10,n),t[1]="")),void 0!==o&&o>=0&&(t.length>1&&void 0!==t[1]?t[1]=t[1].substring(0,o):t[1]="",t[1].length<o))for(var u=t[1].length;u<o;u++)t[1]+="0";for(regex=/(\d+)(\d{3})/;""!==e&&regex.test(t[0]);)t[0]=t[0].toString().replace(regex,"$1"+e+"$2");return t[0]+(t.length>1&&""!==t[1]?r+t[1]:"")}}function AjaxRet(t){var e=t.responseJSON;return void 0===e.status?(alert("Erro ao realizar a ação!"),console.log(t),!1):e}
!function(e){"use strict";e.browser||(e.browser={},e.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase()),e.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase()),e.browser.opera=/opera/.test(navigator.userAgent.toLowerCase()),e.browser.msie=/msie/.test(navigator.userAgent.toLowerCase()),e.browser.device=/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));var t={prefix:"",suffix:"",affixesStay:!0,thousands:",",decimal:".",precision:2,allowZero:!1,allowNegative:!1,doubleClickSelection:!0,allowEmpty:!1,bringCaretAtEndOnFocus:!0},n={destroy:function(){return e(this).unbind(".maskMoney"),e.browser.msie&&(this.onpaste=null),this},applyMask:function(t){return r(t,e(this).data("settings"))},mask:function(t){return this.each(function(){var n=e(this);return"number"==typeof t&&n.val(t),n.trigger("mask")})},unmasked:function(){return this.map(function(){var t,n=e(this).val()||"0",a=-1!==n.indexOf("-");return e(n.split(/\D/).reverse()).each(function(e,n){if(n)return t=n,!1}),n=(n=n.replace(/\D/g,"")).replace(new RegExp(t+"$"),"."+t),a&&(n="-"+n),parseFloat(n)})},unmaskedWithOptions:function(){return this.map(function(){var n=e(this).val()||"0",a=e(this).data("settings")||t,r=new RegExp(a.thousandsForUnmasked||a.thousands,"g");return n=n.replace(r,""),parseFloat(n)})},init:function(n){return n=e.extend(e.extend({},t),n),this.each(function(){var t,i,o=e(this);function s(){var e,t,n,a,r,i=o.get(0),s=0,l=0;return"number"==typeof i.selectionStart&&"number"==typeof i.selectionEnd?(s=i.selectionStart,l=i.selectionEnd):(t=document.selection.createRange())&&t.parentElement()===i&&(a=i.value.length,e=i.value.replace(/\r\n/g,"\n"),(n=i.createTextRange()).moveToBookmark(t.getBookmark()),(r=i.createTextRange()).collapse(!1),n.compareEndPoints("StartToEnd",r)>-1?s=l=a:(s=-n.moveStart("character",-a),s+=e.slice(0,s).split("\n").length-1,n.compareEndPoints("EndToEnd",r)>-1?l=a:(l=-n.moveEnd("character",-a),l+=e.slice(0,l).split("\n").length-1))),{start:s,end:l}}function l(e){var n,a,i=o.val().length;o.val(r(o.val(),t)),n=o.val().length,t.reverse||(e-=i-n),a=e,t.formatOnBlur||o.each(function(e,t){if(t.setSelectionRange)t.focus(),t.setSelectionRange(a,a);else if(t.createTextRange){var n=t.createTextRange();n.collapse(!0),n.moveEnd("character",a),n.moveStart("character",a),n.select()}})}function c(){var e=o.val();if(!t.allowEmpty||""!==e){var n=!isNaN(e)?e.indexOf("."):e.indexOf(t.decimal);if(t.precision>0)if(n<0)e+=t.decimal+new Array(t.precision+1).join(0);else{var a=e.slice(0,n),i=e.slice(n+1);e=a+t.decimal+i+new Array(t.precision+1-i.length).join(0)}else n>0&&(e=e.slice(0,n));o.val(r(e,t))}}function u(e){e.preventDefault?e.preventDefault():e.returnValue=!1}function d(n){var a,r,i,l,c,d,v=(n=n||window.event).which||n.charCode||n.keyCode,p=t.decimal.charCodeAt(0);return void 0!==v&&(!(v<48||v>57)||v===p&&t.reverse?(a=!(o.val().length>=o.attr("maxlength")&&o.attr("maxlength")>=0),r=s(),i=r.start,l=r.end,c=!(r.start===r.end||!o.val().substring(i,l).match(/\d/)),d="0"===o.val().substring(0,1),!!(a||c||d)&&((v!==p||!function(){if(e=o.val().length,n=s(),0===n.start&&n.end===e)return!1;var e,n;return o.val().indexOf(t.decimal)>-1}())&&(!!t.formatOnBlur||(u(n),f(n),!1)))):function(n,a){return 45===n?(o.val((r=o.val(),t.allowNegative?""!==r&&"-"===r.charAt(0)?r.replace("-",""):"-"+r:r)),!1):43===n?(o.val(o.val().replace("-","")),!1):13===n||9===n||(!(!e.browser.mozilla||37!==n&&39!==n||0!==a.charCode)||(u(a),!0));var r}(v,n))}function f(e){var t,n,a,r,i=(e=e||window.event).which||e.charCode||e.keyCode,c="";i>=48&&i<=57&&(c=String.fromCharCode(i)),n=(t=s()).start,a=t.end,r=o.val(),o.val(r.substring(0,n)+c+r.substring(a,r.length)),l(n+1)}function v(){setTimeout(function(){c()},0)}function p(){return(parseFloat("0")/Math.pow(10,t.precision)).toFixed(t.precision).replace(new RegExp("\\.","g"),t.decimal)}t=e.extend({},n),t=e.extend(t,o.data()),o.data("settings",t),e.browser.device&&o.attr("type","tel"),o.unbind(".maskMoney"),o.bind("keypress.maskMoney",d),o.bind("keydown.maskMoney",function(e){var n,a,r,i,c,d=(e=e||window.event).which||e.charCode||e.keyCode;return void 0!==d&&(a=(n=s()).start,r=n.end,8!==d&&46!==d&&63272!==d||(u(e),i=o.val(),a===r&&(8===d?""===t.suffix?a-=1:(c=i.split("").reverse().join("").search(/\d/),r=1+(a=i.length-c-1)):r+=1),o.val(i.substring(0,a)+i.substring(r,i.length)),l(a),!1))}),o.bind("blur.maskMoney",function(n){if(e.browser.msie&&d(n),t.formatOnBlur&&o.val()!==i&&f(n),""===o.val()&&t.allowEmpty)o.val("");else if(""===o.val()||o.val()===a(p(),t))t.allowZero?t.affixesStay?o.val(a(p(),t)):o.val(p()):o.val("");else if(!t.affixesStay){var r=o.val().replace(t.prefix,"").replace(t.suffix,"");o.val(r)}o.val()!==i&&o.change()}),o.bind("focus.maskMoney",function(){i=o.val(),c();var e,n=o.get(0);t.selectAllOnFocus?n.select():n.createTextRange&&t.bringCaretAtEndOnFocus&&((e=n.createTextRange()).collapse(!1),e.select())}),o.bind("click.maskMoney",function(){var e,n=o.get(0);t.selectAllOnFocus||(n.setSelectionRange&&t.bringCaretAtEndOnFocus?(e=o.val().length,n.setSelectionRange(e,e)):o.val(o.val()))}),o.bind("dblclick.maskMoney",function(){var e,n,a=o.get(0);a.setSelectionRange&&t.bringCaretAtEndOnFocus?(n=o.val().length,e=t.doubleClickSelection?0:n,a.setSelectionRange(e,n)):o.val(o.val())}),o.bind("cut.maskMoney",v),o.bind("paste.maskMoney",v),o.bind("mask.maskMoney",c)})}};function a(e,t){var n="";return e.indexOf("-")>-1&&(e=e.replace("-",""),n="-"),e.indexOf(t.prefix)>-1&&(e=e.replace(t.prefix,"")),e.indexOf(t.suffix)>-1&&(e=e.replace(t.suffix,"")),n+t.prefix+e+t.suffix}function r(e,t){return t.allowEmpty&&""===e?"":t.reverse?function(e,t){var n,r=e.indexOf("-")>-1&&t.allowNegative?"-":"",o=e.replace(t.prefix,"").replace(t.suffix,""),s=o.split(t.decimal)[0],l="";""===s&&(s="0");if(n=i(s,r,t),t.precision>0){var c=o.split(t.decimal);c.length>1&&(l=c[1]),n+=t.decimal+l;var u=Number.parseFloat(s+"."+l).toFixed(t.precision),d=u.toString().split(t.decimal)[1];n=n.split(t.decimal)[0]+"."+d}return a(n,t)}(e,t):function(e,t){var n,r,o,s=e.indexOf("-")>-1&&t.allowNegative?"-":"",l=e.replace(/[^0-9]/g,""),c=l.slice(0,l.length-t.precision);if(n=i(c,s,t),t.precision>0){if(!isNaN(e)&&e.indexOf(".")){var u=e.substr(e.indexOf(".")+1);l+=new Array(t.precision+1-u.length).join(0),c=l.slice(0,l.length-t.precision),n=i(c,s,t)}r=l.slice(l.length-t.precision),o=new Array(t.precision+1-r.length).join(0),n+=t.decimal+o+r}return a(n,t)}(e,t)}function i(e,t,n){return""===(e=(e=e.replace(/^0*/g,"")).replace(/\B(?=(\d{3})+(?!\d))/g,n.thousands))&&(e="0"),t+e}e.fn.maskMoney=function(t){return n[t]?n[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist on jQuery.maskMoney"):n.init.apply(this,arguments)}}(window.jQuery||window.Zepto);
function AddMaskCurrency(elemento){ $(elemento).maskMoney({allowNegative: true, thousands:'.', decimal:',', affixesStay: false}); }
!function(n){n.fn.focusTextToEnd=function(){this.focus();var n=this.val();return this.val("").val(n),this},n.fn.AddMaskCurrency=function(){this.maskMoney({allowNegative:!0,thousands:num_grp_sep,decimal:dec_sep,affixesStay:!1})}}(jQuery);


var CepFieldsCfg = {};

function validateEmail(email)
{
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function showLoadingIcon(elm)
{
	if(!$(elm).find('.loading-icon').length){
		$(elm).append(` <img class="loading-icon" src="${_APP.app_url}images/loading.gif" />`);
	}
	hideErrorIcon($(elm));
    $(elm).find('.loading-icon').show();
	$(elm).prop('disabled', true).css('cursor', 'not-allowed');
}
function hideLoadingIcon(elm)
{
    $(elm).find('.loading-icon').hide();
    $(elm).prop('disabled', false).css('cursor', 'pointer');
}
function showErrorIcon(elm)
{
	if(!$(elm).find('.error-icon').length){
		$(elm).append(` <i class="fas fa-times error-icon"></i>`);
	}
    $(elm).find('.error-icon').show();
}
function hideErrorIcon(elm)
{
    $(elm).find('.error-icon').hide();
}
function isValidCPF(cpf)
{
    if (typeof cpf !== "string") return false
    cpf = cpf.replace(/[\s.-]*/igm, '')
    if (
        !cpf ||
        cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999" 
    ) {
        return false
    }
    var soma = 0
    var resto
    for (var i = 1; i <= 9; i++) 
        soma = soma + parseInt(cpf.substring(i-1, i)) * (11 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(9, 10)) ) return false
    soma = 0
    for (var i = 1; i <= 10; i++) 
        soma = soma + parseInt(cpf.substring(i-1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(10, 11) ) ) return false
    return true
}

function validaCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
    
}


Date.prototype.addDays = function(days)
{
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

function getDates(startDate, stopDate)
{
    var dateArray = new Array();
    var currentDate = startDate;
    while (currentDate <= stopDate) {
        dateArray.push(new Date (currentDate));
        currentDate = currentDate.addDays(1);
    }
    return dateArray;
}

function adicionaZero(numero)
{
    if (numero <= 9) 
        return "0" + numero;
    else
        return numero; 
}

function formatDate(data)
{
	return (adicionaZero(data.getDate().toString()) + "/" + (adicionaZero(data.getMonth()+1).toString()) + "/" + data.getFullYear())
}

function formatDateTime(data)
{
	return formatDate(data)+' '+adicionaZero(data.getHours().toString())+':'+adicionaZero(data.getMinutes().toString());
}

function insertMaskInputs()
{
	$(document).find('input').each(function(){
		if($(this).attr('custom_type_validation')){
			let cstm_validation = $(this).attr('custom_type_validation');
			if(cstm_validation == 'cpf'){
				$(this).mask('999.999.999-99');
				if($(this).val() == '..-'){
					$(this).val('');
				}
			}else if(cstm_validation == 'rg'){
				$(this).mask('99.999.999-9');
				if($(this).val() == '..-'){
					$(this).val('');
				}
			}else if(cstm_validation == 'cnpj'){
				$(this).mask('99.999.999/9999-99');
				if($(this).val() == '../-'){
					$(this).val('');
				}
			}else if(cstm_validation == 'cep'){
				$(this).mask('99999-999');
				if($(this).val() == '-'){
					$(this).val('');
				}
			}
		}
	});
}
function checkStrongPassword(str)
{
	let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})');
	console.log(strongPassword.test(str));
	return strongPassword.test(str);
}
function ValidateForm(fm, elm)
{
	let f = $('#'+fm);
	let is_valid = true;
	
	if(!!elm){
		showLoadingIcon(elm);
	}
	//Reset all displayed errors
	rmvValidateError();
	//All validations for <input>
	let recordId = $('input[name="id"]').val();
	$(f).find('input').each(function(){
		$(this).removeClass('invalid-value');
		let is_required = !!$(this).attr('required');
		if($(this).attr('name') == 'senha_nova'){
			if(is_required){
				if($(this).val() == ''
				&& (!recordId || $('input[name="confirm_senha_nova"]').val() !== '')){
					addValidateError(this, 'É necessário digitar uma nova senha.', true);
					is_valid = false;
					return;
				}
			}
			if($(this).val() !== '' && !checkStrongPassword($(this).val())){
				addValidateError(this, 'A senha deve possuir pelo menos 8 caracteres.<br />(1 letra maiúscula, 1 letra minúscula, 1 número e 1 caracter especial)', true);
				is_valid = false;
				return;
			}
		}else if($(this).attr('name') == 'confirm_senha_nova'){
			if($('input[name="senha_nova"]').val() !== '' || $(this).val() !== ''){
				if($(this).val() !== $('input[name="senha_nova"]').val()){
					addValidateError(this, 'As senhas não conferem.', true);
					is_valid = false;
					return;
				}
			}
		}else{
			if($(this).is(':visible') && !$(this).is(':disabled')){

				var its_blank = false;
				if($(this).attr('type') == 'radio'){
					let nameInput = $(this).attr('name');
					if(!$('input[name="'+nameInput+'"]:checked').length){
						its_blank = true;
					}
				}else if($(this).val().trim() == ''){
					its_blank = true;
				}

				if(is_required && its_blank){
					addValidateError(this, 'é obrigatório.');
					is_valid = false;
					return;
				}
				if($(this).attr('field_type_related')){
					let elm_related = $(this).attr('field_type_related');
					if(!its_blank && $('input[name="'+elm_related+'"]').val() == ''){
						addValidateError(this, 'O registro de nome '+$(this).val()+' não foi encontrado.', true);
						is_valid = false;
						return;
					}
				}
				
				if($(this).attr('custom_type_validation')){
					let cstm_validation = $(this).attr('custom_type_validation');
					if(cstm_validation == 'file'){
						var max_size = parseInt($(this).attr('max_size'));
						if(max_size && !!this.files[0]){
							if(this.files[0].size > (max_size * 1024)){
								addValidateError(this, 'é um arquivo muito grande.');
								is_valid = false;
								return;
							}
						}
					}else if(cstm_validation == 'int'){
						let clear_val = $(this).val().replace(/[^0-9\.]+/g, '');
						if($(this).val().length !== clear_val.length){
							addValidateError(this, 'deve conter apenas números.');
							is_valid = false;
							return;
						}else if(is_required && clear_val == 0){
							addValidateError(this, 'é obrigatório.');
							is_valid = false;
							return;
						}else if($(this).attr('max')){
							if(parseInt(clear_val) > parseInt($(this).attr('max'))){
								addValidateError(this, 'não pode ser maior que '+$(this).attr('max')+'.');
								is_valid = false;
								return
							}
						}
					}else if(cstm_validation == 'cpf'){
						let clear_val = $(this).val().replace(/[^0-9\.]+/g, '');
						if(is_required || clear_val){
							if(!isValidCPF(clear_val)){
								addValidateError(this, 'não é um CPF válido.');
								is_valid = false;
								return
							}
						}

					}else if(cstm_validation == 'cnpj'){
						let clear_val = $(this).val().replace(/[^0-9\.]+/g, '');
						if(is_required || clear_val){
							if(!validaCNPJ(clear_val)){
								addValidateError(this, 'não é um CNPJ válido.');
								is_valid = false;
								return
							}
						}
					}else if(cstm_validation == 'currency'){
						let clear_val = $(this).val().replace(/[^0-9\.]+/g, '');
						if(parseInt(clear_val) < 0.01 && !!$(this).attr('required')){
							addValidateError(this, 'é obrigatório.');
							is_valid = false;
							return
						}
					}else if(cstm_validation == 'email'){
						if($(this).val().trim() && !validateEmail($(this).val().trim())){
							addValidateError(this, 'não é um email válido.');
							is_valid = false;
							return;
						}
					}
				}
				
				if($(this).attr('minlength')){
					if($(this).val().length < $(this).attr('minlength')){
						addValidateError(this, 'deve conter ao menos '+$(this).attr('minlength')+' caracteres.');
						is_valid = false;
						return;
					}
				}
				if($(this).attr('maxlength')){
					if($(this).val().length > $(this).attr('maxlength')){
						addValidateError(this, 'deve conter até '+$(this).attr('maxlength')+' caracteres.');
						is_valid = false;
						return;
					}
				}
			}
		}
	});
	
	//All validations for <select>
	$(f).find('select').each(function(){
		$(this).removeClass('invalid-value');
		if($(this).is(':visible') && !$(this).is(':disabled')){
			let is_required = !!$(this).attr('required');
			if(is_required && $(this).val() == ''){
				addValidateError(this, 'é obrigatório.');
				is_valid = false;
				return;
			}
		}
	});
	
	//All validations for <textarea>
	$(f).find('textarea').each(function(){
		$(this).removeClass('invalid-value');
		if($(this).is(':visible') && !$(this).is(':disabled')){
			let is_required = !!$(this).attr('required');
			var its_blank = false;
			if($(this).val().trim() == ''){
				its_blank = true;
			}
			if(is_required && its_blank){
				addValidateError(this, 'é obrigatório.');
				is_valid = false;
				return;
			}
			if($(this).attr('minlength')){
				if($(this).val().length < $(this).attr('minlength')){
					addValidateError(this, 'deve conter ao menos '+$(this).attr('minlength')+' caracteres.');
					is_valid = false;
					return;
				}
			}
			if($(this).attr('maxlength')){
				if($(this).val().length > $(this).attr('maxlength')){
					addValidateError(this, 'deve conter até '+$(this).attr('maxlength')+' caracteres.');
					is_valid = false;
					return;
				}
			}
		}
	});
	//If form is valid, let's submit
	
	if(is_valid){
		$(f).find('input[type="checkbox"]').each((idx, ipt) => {
			if($(ipt).attr('name')){
				let field = $(ipt).attr('name').replace('checkbox_', '');
				let checked = ($(ipt).is(':checked') ? '1' : '0');
				$(f).find('input[name="'+field+'"]').val(checked);
			}
		});
		$('#'+fm).trigger('submit');
	}else{
		if(!!elm){
			hideLoadingIcon(elm);
		}
	}
}
function switchCheckbox(name)
{
	$('#checkbox_'+name+', .slider-for-'+name).on('change click', (e) => {
		let field = '';
		let checked = 0;
		if(e.handleObj.type == 'click'){
			field = $(e.currentTarget).attr('class').replace('slider round slider-for-', '');
			checked = ((!$('input[name="checkbox_'+field+'"]').is(':checked')) ? 1 : 0);
		}else{
			field = $(this).attr('name').replace('checkbox_', '');
			checked = ((!$(this).is(':checked')) ? 1 : 0);
		}
		$('input[name="'+field+'"]').val(checked);
		if(checked){
			$('input[name="checkbox_'+field+'"]').prop('checked');
		}else{
			$('input[name="checkbox_'+field+'"]').removeProp('checked');
		}
	});
}
function setCepField(args = {})
{
	CepFieldsCfg[args.elm] = args;
	var elmCfg = CepFieldsCfg[args.elm];
	
	$(elmCfg.elm).css('width', '53%').after(' <button type="button" class="btn btn-outline-info btn-rounded consulta-cep" id="consulta_cep_'+$(elmCfg.elm).attr('name')+'"><img class="loading-icon" src="'+_APP.app_url+'images/loading.gif" />buscar</button>');
	$('#consulta_cep_'+$(elmCfg.elm).attr('name')).on('click', (e) =>{
		if(!$(elmCfg.elm).val()){
			return;
		}
		showLoadingIcon(e.currentTarget);
		$.ajax({
			"url": _APP.app_url+'ajax_requests/getCep',
			"dataType": 'json',
			"method": 'post',
			headers: {
			  "Content-Type": "application/json",
			  "X-Requested-With": "XMLHttpRequest"
			},
			"data": JSON.stringify({
				"cep": $(elmCfg.elm).val(),
			}),
			success: function(d){
				
			},
			complete: function(d){
				var r = d.responseJSON;
				if(!!r.status && !!r.detail){
					if(!!r.detail.endereco){
						if(elmCfg.fillFields){
							$.each(elmCfg.fillFields, function(idx, ipt){
								$('input[name="'+ipt+'"]').val(r.detail.endereco[idx]);
								$('select[name="'+ipt+'"]').val(r.detail.endereco[idx]);
							});
						}
						if(elmCfg.callback_select){
							var fn = window[elmCfg.callback_select];
							if(typeof fn !== 'function')
								return;
							fn.apply(window, [r.detail]);
						}
					}else{
						Swal.fire({
							title: 'CEP não encontrado!',
							text: '',
							icon: 'error',

						})
					}
				}
				hideLoadingIcon('#consulta_cep_'+$(elmCfg.elm).attr('name'));
			}
		});
		
	});
}
function addValidateError(ipt, msg, override = false)
{
	let msg_error = '';
	
	if(override){
		msg_error = msg;
	}else{
		msg_error = "O campo "+$('label[for="'+$(ipt).attr('name')+'"]:first').text()+" "+msg;
	}
	if($(ipt).parent().find('.consulta-cep').length > 0){
		$(ipt).parent().append("<p class='validate-error required'>"+msg_error+"</p>");
	}else if($(ipt).attr('type') == 'radio'){
		$(ipt).parent().append("<p class='validate-error required'>"+msg_error+"</p>");
	}else{
		$(ipt).after("<p class='validate-error required'>"+msg_error+"</p>");
	}
	$(ipt).addClass('invalid-value').trigger('focus');
}
function rmvValidateError()
{
	$('.validate-error').remove();
}
function rmvRequired(elm)
{
	$(elm).removeClass('invalid-value').attr('required', false).parent().parent().find('.required').remove();
}
function addRequired(elm)
{
	rmvRequired(elm);
	$(elm).removeClass('invalid-value').prop('required', true).parent().parent().find('label').after(' <span class="required">*</span>');
}
function validateEmail(email)
{
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function GoToPage(elm, page)
{
	if($('#filtroForm').length > 0){
		let action = $('#filtroForm').attr('action');
		if(action.substr(action.length - 1) == '/'){
			action += page;
		}else{
			action += '/'+page;
		}

		if(_APP.ajax_pagination && $('#filtroForm').parent().find('.tb-rst-fltr').length > 0){
			//Lets try to get Pagination with Ajax

			let formData = new FormData(document.getElementById('filtroForm'));

			let formValues = Object.fromEntries(formData.entries());

			fireLoading({
				title: 'Aguarde...',
				text: 'Estamos buscando os registros...',
				didOpen: () => {
					Swal.showLoading();
					handleAjax({
						url: action+'?bdOnly=1',
						dontfireError: true,
						data: JSON.stringify(formValues),
						callback: (res) => {
							$('#filtroForm').parent().find('.tb-rst-fltr').remove();
							$('#filtroForm').parent().find('.table-pagination').remove();
							$('#filtroForm').after(res.detail);
							orderByFiltro();
							if(typeof addEventRowData != 'undefined'){
								addEventRowData();
							}
							window.history.pushState({"html":document.html,"pageTitle":document.pageTitle},"", action);
							window.scrollTo(0,0);
						},
						callbackError: (res) => {
							fireErrorGeneric();
						},
						callbackAll: (res) => {
							Swal.close();
						}
					});
				}
			});
		}else{
			$('#filtroForm').attr('action', action);
			$('#filtroForm').trigger('submit');
		}
	}else{
		location.href = $(elm).attr('og_loc');
	}
}
function QuickGoToPage(elm)
{
	var pageTo =  parseInt($(elm).parent().find('.QuickGoToPage').val());
	if(isNaN(pageTo)){
		pageTo = 1;
	}
	GoToPage(elm, pageTo);
}

function acceptCookies()
{	
	$('div[name="banner-lgpd"]').addClass('accept');
	localStorage.setItem("cookies", "accept");
};

function rdct_login()
{
	location.href = _APP.app_url+'login?rdct_url='+encodeURIComponent(document.URL);
}
function hideShowFields(hideF, showF)
{
	$.each(hideF, function(idx, ipt){
		$('label[for="'+idx+'"]').hide().parent().hide();
		$('label[for="'+idx+'"]').attr('required', false).parent().find('.required').remove();
	});
	$.each(showF, function(idx, ipt){
		$('label[for="'+idx+'"]').show().parent().show();
		
		//Remover span e atributo de required para evitar duplicatas
		$('label[for="'+idx+'"]').attr('required', false).parent().find('.required').remove();
		
		if(ipt){
			$('label[for="'+idx+'"]').attr('required', true).after(' <span class="required">*</span>');
		}
	});
}

function fireAndClose(args = {}){
    $('#modalGeneric').modal('hide');
    if(Swal.isVisible()){
        Swal.close();
    }
    Swal.fire(args);
}

function fireLoading(args = {}){
    let argsFire = {
        title:'Aguarde...',
        text: 'Estamos processando a requisição...',
        showConfirmButton: false,
        allowOutsideClick: false
    };
    argsFire = $.extend(argsFire, args);
    fireAndClose(argsFire);
}

function fireAjaxLoading(args = {}){
	fireLoading({
		didOpen: () => {
			Swal.showLoading();
			handleAjax(args);
		}
	});
}
function fireErrorGeneric(msg = null){
	let argsFire = {
        title:'Ops! Algo deu errado!',
        html: '<p>Tente novamente mais tarde ou entre em contato com o administrador.</p>',
        icon: 'error',
        allowOutsideClick: false,
    };
	if(msg){
		argsFire.text = msg;
		delete argsFire.html;
	}
    fireAndClose(argsFire);
}
function fireWarning(msg = null){
    fireAndClose({
        title:'Ops!',
		text: msg,
        icon: 'warning',
        allowOutsideClick: false,
		didOpen: () => {
			$('.swal2-confirm').trigger('focus');
		}
    });
}
function fireInfo(msg = null){
    fireAndClose({
        title:'Ops!',
		text: msg,
        icon: 'info',
        allowOutsideClick: false,
		didOpen: () => {
			$('.swal2-confirm').trigger('focus');
		}
    });
}
function handleAjax(args){
    let argsFire = {
        url: args.url,
        method: 'post',
        dataType: 'json',
		headers: {
			"Content-Type": "application/json",
			"X-Requested-With": "XMLHttpRequest"
		},
        success: function(d){
            if(!!d.status){
				if(typeof args.callback == 'function'){
                	args.callback(d);
				}
            }else{
				if(!args.dontFireError){
					fireErrorGeneric();
				}
                if(typeof args.callbackError == 'function'){
                    args.callbackError(d);
                }
            }
            if(!!args.callbackAll){
                args.callbackAll(d);
            }
        },
        error: function(d){
            let r = d.responseJSON;
			if(!args.dontFireError){
				if(!!r && !!r.detail){
					fireAndClose({
						title:'Ops! Algo deu errado!',
						html: r.detail,
						icon: 'error',
						allowOutsideClick: false,
					});
				}else{
					fireErrorGeneric();
				}
			}
            if(!!args.callbackAll){
                args.callbackAll(d);
            }
            if(!!args.callbackError){
                args.callbackError(d);
            }
        }
    }
	if(typeof args.data == 'object'){
		args.data = JSON.stringify(args.data);
	}
    argsFire = $.extend(argsFire, args);
    $.ajax(argsFire);
}


function fixNameUtf8(str)
{
	return str.trim().replace('ﾠ - ', '');
}


function changeByTraco2(str){
    
    exploded = str.split(' - ');
    if(exploded.length > 1){
        return exploded[1]+' - '+exploded[0];
    }
    
    exploded = str.split(' : ');
    if(exploded.length > 1){
		return exploded[1]+' - '+exploded[0];
    }
    
    exploded = str.split(' in the Style of ');
    if(exploded.length > 1){
        return exploded[1]+' - '+exploded[0];
    }

	return str;
};

document.getScrollTop = function() {
    if (window.pageYOffset != undefined) {
        return pageYOffset;
    } else {
        var sx, sy, d = document,
            r = d.documentElement,
            b = d.body;
        sx = r.scrollLeft || b.scrollLeft || 0;
        sy = r.scrollTop || b.scrollTop || 0;
        return sy;
    }
}

document.setScrollTop = function(y) {
	window.scrollTo(0, y);
}

function toggleDarkMode(setAjax = true)
{
	if($('#darkmodecss').length > 0){
		$('#darkmodecss').remove();	
		localStorage.dark_mode_active = 0;
	}else{
		$('head').append('<link id="darkmodecss" rel="stylesheet" href="'+_APP.app_url+'cssManager/dark.css?v='+_APP.ch_ver+'">');
		localStorage.dark_mode_active = 1;
	}
	if(setAjax){
		handleAjax({
			url: _APP.app_url+'ajax_requests/toogle_dark_mode',
			data: JSON.stringify({
				dark_mode: localStorage.dark_mode_active,
			}),
			callback: (res) => {

			},
			callbackError: (res) => {
				console.log(res);
			}
		});
	}
}
if((
		(_APP._CTRL_NAME[0] == 'usuarios' ||
			_APP._CTRL_NAME[0] == 'admin' 
		) &&
		_APP._ACTION_NAME == 'login'
	) ||
	_APP._ACTION_NAME == 'criarconta'){
	//Check if cached dark mode
	if(localStorage.dark_mode_active == '1'){
		toggleDarkMode(false);
	}
}