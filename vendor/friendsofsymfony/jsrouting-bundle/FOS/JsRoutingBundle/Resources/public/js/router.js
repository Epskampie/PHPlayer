/**
 * Portions of this code are from the Google Closure Library,
 * received from the Closure Authors under the Apache 2.0 license.
 *
 * All other code is (C) 2011 FriendsOfSymfony and subject to the MIT license.
 */
(function() {var f=!1,i=this;function j(a,c){var b=a.split("."),d=i;!(b[0]in d)&&d.execScript&&d.execScript("var "+b[0]);for(var e;b.length&&(e=b.shift());)!b.length&&void 0!==c?d[e]=c:d=d[e]?d[e]:d[e]={}}
function k(a){var c=typeof a;if("object"==c)if(a){if(a instanceof Array)return"array";if(a instanceof Object)return c;var b=Object.prototype.toString.call(a);if("[object Window]"==b)return"object";if("[object Array]"==b||"number"==typeof a.length&&"undefined"!=typeof a.splice&&"undefined"!=typeof a.propertyIsEnumerable&&!a.propertyIsEnumerable("splice"))return"array";if("[object Function]"==b||"undefined"!=typeof a.call&&"undefined"!=typeof a.propertyIsEnumerable&&!a.propertyIsEnumerable("call"))return"function"}else return"null";
else if("function"==c&&"undefined"==typeof a.call)return"object";return c};var l=/^[a-zA-Z0-9\-_.!~*'()]*$/;function m(a){a=""+a;return!l.test(a)?encodeURIComponent(a):a};var n=Array.prototype,p=n.forEach?function(a,c,b){n.forEach.call(a,c,b)}:function(a,c,b){for(var d=a.length,e="string"==typeof a?a.split(""):a,g=0;g<d;g++)g in e&&c.call(b,e[g],g,a)};function q(a){var c=0,b;for(b in a)c++;return c}function r(a){var c={},b;for(b in a)c[b]=a[b];return c};function s(a,c){this.b={};this.a=[];var b=arguments.length;if(1<b){if(b%2)throw Error("Uneven number of arguments");for(var d=0;d<b;d+=2)this.set(arguments[d],arguments[d+1])}else if(a){var e;if(a instanceof s){t(a);d=a.a.concat();t(a);e=[];for(b=0;b<a.a.length;b++)e.push(a.b[a.a[b]])}else{var b=[],g=0;for(d in a)b[g++]=d;d=b;b=[];g=0;for(e in a)b[g++]=a[e];e=b}for(b=0;b<d.length;b++)this.set(d[b],e[b])}}s.prototype.e=0;
function t(a){if(a.e!=a.a.length){for(var c=0,b=0;c<a.a.length;){var d=a.a[c];u(a.b,d)&&(a.a[b++]=d);c++}a.a.length=b}if(a.e!=a.a.length){for(var e={},b=c=0;c<a.a.length;)d=a.a[c],u(e,d)||(a.a[b++]=d,e[d]=1),c++;a.a.length=b}}s.prototype.get=function(a,c){return u(this.b,a)?this.b[a]:c};s.prototype.set=function(a,c){u(this.b,a)||(this.e++,this.a.push(a));this.b[a]=c};function u(a,c){return Object.prototype.hasOwnProperty.call(a,c)};var v,w,x,y;function A(){return i.navigator?i.navigator.userAgent:null}y=x=w=v=f;var B;if(B=A()){var C=i.navigator;v=0==B.indexOf("Opera");w=!v&&-1!=B.indexOf("MSIE");x=!v&&-1!=B.indexOf("WebKit");y=!v&&!x&&"Gecko"==C.product}var D=w,E=y,F=x;var G;if(v&&i.opera){var H=i.opera.version;"function"==typeof H&&H()}else E?G=/rv\:([^\);]+)(\)|;)/:D?G=/MSIE\s+([^\);]+)(\)|;)/:F&&(G=/WebKit\/(\S+)/),G&&G.exec(A());function I(a){if(a[1]){var c=a[0],b=c.indexOf("#");0<=b&&(a.push(c.substr(b)),a[0]=c=c.substr(0,b));b=c.indexOf("?");0>b?a[1]="?":b==c.length-1&&(a[1]=void 0)}return a.join("")}function J(a,c){for(var b in c){var d=b,e=c[b],g=a;if("array"==k(e))for(var h=0;h<e.length;h++)g.push("&",d),""!==e[h]&&g.push("=",m(e[h]));else null!=e&&(g.push("&",d),""!==e&&g.push("=",m(e)))}return a};function K(a,c){this.c=a||{d:"",prefix:""};this.h(c||{})}(function(a){a.f=function(){return a.m||(a.m=new a)}})(K);K.prototype.h=function(a){this.g=new s(a)};K.prototype.i=function(a){this.c.d=a};K.prototype.l=function(){return this.c.d};K.prototype.j=function(a){this.c.prefix=a};
K.prototype.k=function(a,c){var b=this.c.prefix+a;if(u(this.g.b,b))a=b;else if(!u(this.g.b,a))throw Error('The route "'+a+'" does not exist.');var d=this.g.get(a),e=c||{},g=r(e),h="",o=!0;p(d.tokens,function(b){if("text"===b[0])h=b[1]+h,o=f;else if("variable"===b[0]){if(f===o||!(b[3]in d.defaults)||b[3]in e&&e[b[3]]!=d.defaults[b[3]]){var c;if(b[3]in e){c=e[b[3]];var z=b[3];z in g&&delete g[z]}else if(b[3]in d.defaults)c=d.defaults[b[3]];else{if(o)return;throw Error('The route "'+a+'" requires the parameter "'+
b[3]+'".');}if(!(!0===c||f===c||""===c)||!o)h=b[1]+encodeURIComponent(c).replace(/%2F/g,"/")+h;o=f}}else throw Error('The token type "'+b[0]+'" is not supported.');});""===h&&(h="/");0<q(g)&&(h=I(J([h],g)));return this.c.d+h};j("fos.Router",K);j("fos.Router.setData",function(a){var c=K.f();c.i(a.base_url);c.h(a.routes);"prefix"in a&&c.j(a.prefix)});K.getInstance=K.f;K.prototype.setRoutes=K.prototype.h;K.prototype.setBaseUrl=K.prototype.i;K.prototype.getBaseUrl=K.prototype.l;K.prototype.generate=K.prototype.k;K.prototype.setPrefix=K.prototype.j;window.Routing=K.f();})();
