!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=34)}([function(e,t,n){"use strict";var r=n(2),o=n(11),a=Object.prototype.toString;function i(e){return"[object Array]"===a.call(e)}function l(e){return null!==e&&"object"==typeof e}function u(e){return"[object Function]"===a.call(e)}function s(e,t){if(null!=e)if("object"!=typeof e&&(e=[e]),i(e))for(var n=0,r=e.length;n<r;n++)t.call(null,e[n],n,e);else for(var o in e)Object.prototype.hasOwnProperty.call(e,o)&&t.call(null,e[o],o,e)}e.exports={isArray:i,isArrayBuffer:function(e){return"[object ArrayBuffer]"===a.call(e)},isBuffer:o,isFormData:function(e){return"undefined"!=typeof FormData&&e instanceof FormData},isArrayBufferView:function(e){return"undefined"!=typeof ArrayBuffer&&ArrayBuffer.isView?ArrayBuffer.isView(e):e&&e.buffer&&e.buffer instanceof ArrayBuffer},isString:function(e){return"string"==typeof e},isNumber:function(e){return"number"==typeof e},isObject:l,isUndefined:function(e){return void 0===e},isDate:function(e){return"[object Date]"===a.call(e)},isFile:function(e){return"[object File]"===a.call(e)},isBlob:function(e){return"[object Blob]"===a.call(e)},isFunction:u,isStream:function(e){return l(e)&&u(e.pipe)},isURLSearchParams:function(e){return"undefined"!=typeof URLSearchParams&&e instanceof URLSearchParams},isStandardBrowserEnv:function(){return("undefined"==typeof navigator||"ReactNative"!==navigator.product&&"NativeScript"!==navigator.product&&"NS"!==navigator.product)&&("undefined"!=typeof window&&"undefined"!=typeof document)},forEach:s,merge:function e(){var t={};function n(n,r){"object"==typeof t[r]&&"object"==typeof n?t[r]=e(t[r],n):t[r]=n}for(var r=0,o=arguments.length;r<o;r++)s(arguments[r],n);return t},deepMerge:function e(){var t={};function n(n,r){"object"==typeof t[r]&&"object"==typeof n?t[r]=e(t[r],n):t[r]="object"==typeof n?e({},n):n}for(var r=0,o=arguments.length;r<o;r++)s(arguments[r],n);return t},extend:function(e,t,n){return s(t,(function(t,o){e[o]=n&&"function"==typeof t?r(t,n):t})),e},trim:function(e){return e.replace(/^\s*/,"").replace(/\s*$/,"")}}},function(e,t,n){e.exports=n(10)},function(e,t,n){"use strict";e.exports=function(e,t){return function(){for(var n=new Array(arguments.length),r=0;r<n.length;r++)n[r]=arguments[r];return e.apply(t,n)}}},function(e,t,n){"use strict";var r=n(0);function o(e){return encodeURIComponent(e).replace(/%40/gi,"@").replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"+").replace(/%5B/gi,"[").replace(/%5D/gi,"]")}e.exports=function(e,t,n){if(!t)return e;var a;if(n)a=n(t);else if(r.isURLSearchParams(t))a=t.toString();else{var i=[];r.forEach(t,(function(e,t){null!=e&&(r.isArray(e)?t+="[]":e=[e],r.forEach(e,(function(e){r.isDate(e)?e=e.toISOString():r.isObject(e)&&(e=JSON.stringify(e)),i.push(o(t)+"="+o(e))})))})),a=i.join("&")}if(a){var l=e.indexOf("#");-1!==l&&(e=e.slice(0,l)),e+=(-1===e.indexOf("?")?"?":"&")+a}return e}},function(e,t,n){"use strict";e.exports=function(e){return!(!e||!e.__CANCEL__)}},function(e,t,n){"use strict";(function(t){var r=n(0),o=n(17),a={"Content-Type":"application/x-www-form-urlencoded"};function i(e,t){!r.isUndefined(e)&&r.isUndefined(e["Content-Type"])&&(e["Content-Type"]=t)}var l,u={adapter:(void 0!==t&&"[object process]"===Object.prototype.toString.call(t)?l=n(6):"undefined"!=typeof XMLHttpRequest&&(l=n(6)),l),transformRequest:[function(e,t){return o(t,"Accept"),o(t,"Content-Type"),r.isFormData(e)||r.isArrayBuffer(e)||r.isBuffer(e)||r.isStream(e)||r.isFile(e)||r.isBlob(e)?e:r.isArrayBufferView(e)?e.buffer:r.isURLSearchParams(e)?(i(t,"application/x-www-form-urlencoded;charset=utf-8"),e.toString()):r.isObject(e)?(i(t,"application/json;charset=utf-8"),JSON.stringify(e)):e}],transformResponse:[function(e){if("string"==typeof e)try{e=JSON.parse(e)}catch(e){}return e}],timeout:0,xsrfCookieName:"XSRF-TOKEN",xsrfHeaderName:"X-XSRF-TOKEN",maxContentLength:-1,validateStatus:function(e){return e>=200&&e<300}};u.headers={common:{Accept:"application/json, text/plain, */*"}},r.forEach(["delete","get","head"],(function(e){u.headers[e]={}})),r.forEach(["post","put","patch"],(function(e){u.headers[e]=r.merge(a)})),e.exports=u}).call(this,n(16))},function(e,t,n){"use strict";var r=n(0),o=n(18),a=n(3),i=n(20),l=n(21),u=n(7);e.exports=function(e){return new Promise((function(t,s){var c=e.data,d=e.headers;r.isFormData(c)&&delete d["Content-Type"];var f=new XMLHttpRequest;if(e.auth){var p=e.auth.username||"",m=e.auth.password||"";d.Authorization="Basic "+btoa(p+":"+m)}if(f.open(e.method.toUpperCase(),a(e.url,e.params,e.paramsSerializer),!0),f.timeout=e.timeout,f.onreadystatechange=function(){if(f&&4===f.readyState&&(0!==f.status||f.responseURL&&0===f.responseURL.indexOf("file:"))){var n="getAllResponseHeaders"in f?i(f.getAllResponseHeaders()):null,r={data:e.responseType&&"text"!==e.responseType?f.response:f.responseText,status:f.status,statusText:f.statusText,headers:n,config:e,request:f};o(t,s,r),f=null}},f.onabort=function(){f&&(s(u("Request aborted",e,"ECONNABORTED",f)),f=null)},f.onerror=function(){s(u("Network Error",e,null,f)),f=null},f.ontimeout=function(){s(u("timeout of "+e.timeout+"ms exceeded",e,"ECONNABORTED",f)),f=null},r.isStandardBrowserEnv()){var h=n(22),v=(e.withCredentials||l(e.url))&&e.xsrfCookieName?h.read(e.xsrfCookieName):void 0;v&&(d[e.xsrfHeaderName]=v)}if("setRequestHeader"in f&&r.forEach(d,(function(e,t){void 0===c&&"content-type"===t.toLowerCase()?delete d[t]:f.setRequestHeader(t,e)})),e.withCredentials&&(f.withCredentials=!0),e.responseType)try{f.responseType=e.responseType}catch(t){if("json"!==e.responseType)throw t}"function"==typeof e.onDownloadProgress&&f.addEventListener("progress",e.onDownloadProgress),"function"==typeof e.onUploadProgress&&f.upload&&f.upload.addEventListener("progress",e.onUploadProgress),e.cancelToken&&e.cancelToken.promise.then((function(e){f&&(f.abort(),s(e),f=null)})),void 0===c&&(c=null),f.send(c)}))}},function(e,t,n){"use strict";var r=n(19);e.exports=function(e,t,n,o,a){var i=new Error(e);return r(i,t,n,o,a)}},function(e,t,n){"use strict";var r=n(0);e.exports=function(e,t){t=t||{};var n={};return r.forEach(["url","method","params","data"],(function(e){void 0!==t[e]&&(n[e]=t[e])})),r.forEach(["headers","auth","proxy"],(function(o){r.isObject(t[o])?n[o]=r.deepMerge(e[o],t[o]):void 0!==t[o]?n[o]=t[o]:r.isObject(e[o])?n[o]=r.deepMerge(e[o]):void 0!==e[o]&&(n[o]=e[o])})),r.forEach(["baseURL","transformRequest","transformResponse","paramsSerializer","timeout","withCredentials","adapter","responseType","xsrfCookieName","xsrfHeaderName","onUploadProgress","onDownloadProgress","maxContentLength","validateStatus","maxRedirects","httpAgent","httpsAgent","cancelToken","socketPath"],(function(r){void 0!==t[r]?n[r]=t[r]:void 0!==e[r]&&(n[r]=e[r])})),n}},function(e,t,n){"use strict";function r(e){this.message=e}r.prototype.toString=function(){return"Cancel"+(this.message?": "+this.message:"")},r.prototype.__CANCEL__=!0,e.exports=r},function(e,t,n){"use strict";var r=n(0),o=n(2),a=n(12),i=n(8);function l(e){var t=new a(e),n=o(a.prototype.request,t);return r.extend(n,a.prototype,t),r.extend(n,t),n}var u=l(n(5));u.Axios=a,u.create=function(e){return l(i(u.defaults,e))},u.Cancel=n(9),u.CancelToken=n(25),u.isCancel=n(4),u.all=function(e){return Promise.all(e)},u.spread=n(26),e.exports=u,e.exports.default=u},function(e,t){
/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <https://feross.org>
 * @license  MIT
 */
e.exports=function(e){return null!=e&&null!=e.constructor&&"function"==typeof e.constructor.isBuffer&&e.constructor.isBuffer(e)}},function(e,t,n){"use strict";var r=n(0),o=n(3),a=n(13),i=n(14),l=n(8);function u(e){this.defaults=e,this.interceptors={request:new a,response:new a}}u.prototype.request=function(e){"string"==typeof e?(e=arguments[1]||{}).url=arguments[0]:e=e||{},(e=l(this.defaults,e)).method=e.method?e.method.toLowerCase():"get";var t=[i,void 0],n=Promise.resolve(e);for(this.interceptors.request.forEach((function(e){t.unshift(e.fulfilled,e.rejected)})),this.interceptors.response.forEach((function(e){t.push(e.fulfilled,e.rejected)}));t.length;)n=n.then(t.shift(),t.shift());return n},u.prototype.getUri=function(e){return e=l(this.defaults,e),o(e.url,e.params,e.paramsSerializer).replace(/^\?/,"")},r.forEach(["delete","get","head","options"],(function(e){u.prototype[e]=function(t,n){return this.request(r.merge(n||{},{method:e,url:t}))}})),r.forEach(["post","put","patch"],(function(e){u.prototype[e]=function(t,n,o){return this.request(r.merge(o||{},{method:e,url:t,data:n}))}})),e.exports=u},function(e,t,n){"use strict";var r=n(0);function o(){this.handlers=[]}o.prototype.use=function(e,t){return this.handlers.push({fulfilled:e,rejected:t}),this.handlers.length-1},o.prototype.eject=function(e){this.handlers[e]&&(this.handlers[e]=null)},o.prototype.forEach=function(e){r.forEach(this.handlers,(function(t){null!==t&&e(t)}))},e.exports=o},function(e,t,n){"use strict";var r=n(0),o=n(15),a=n(4),i=n(5),l=n(23),u=n(24);function s(e){e.cancelToken&&e.cancelToken.throwIfRequested()}e.exports=function(e){return s(e),e.baseURL&&!l(e.url)&&(e.url=u(e.baseURL,e.url)),e.headers=e.headers||{},e.data=o(e.data,e.headers,e.transformRequest),e.headers=r.merge(e.headers.common||{},e.headers[e.method]||{},e.headers||{}),r.forEach(["delete","get","head","post","put","patch","common"],(function(t){delete e.headers[t]})),(e.adapter||i.adapter)(e).then((function(t){return s(e),t.data=o(t.data,t.headers,e.transformResponse),t}),(function(t){return a(t)||(s(e),t&&t.response&&(t.response.data=o(t.response.data,t.response.headers,e.transformResponse))),Promise.reject(t)}))}},function(e,t,n){"use strict";var r=n(0);e.exports=function(e,t,n){return r.forEach(n,(function(n){e=n(e,t)})),e}},function(e,t){var n,r,o=e.exports={};function a(){throw new Error("setTimeout has not been defined")}function i(){throw new Error("clearTimeout has not been defined")}function l(e){if(n===setTimeout)return setTimeout(e,0);if((n===a||!n)&&setTimeout)return n=setTimeout,setTimeout(e,0);try{return n(e,0)}catch(t){try{return n.call(null,e,0)}catch(t){return n.call(this,e,0)}}}!function(){try{n="function"==typeof setTimeout?setTimeout:a}catch(e){n=a}try{r="function"==typeof clearTimeout?clearTimeout:i}catch(e){r=i}}();var u,s=[],c=!1,d=-1;function f(){c&&u&&(c=!1,u.length?s=u.concat(s):d=-1,s.length&&p())}function p(){if(!c){var e=l(f);c=!0;for(var t=s.length;t;){for(u=s,s=[];++d<t;)u&&u[d].run();d=-1,t=s.length}u=null,c=!1,function(e){if(r===clearTimeout)return clearTimeout(e);if((r===i||!r)&&clearTimeout)return r=clearTimeout,clearTimeout(e);try{r(e)}catch(t){try{return r.call(null,e)}catch(t){return r.call(this,e)}}}(e)}}function m(e,t){this.fun=e,this.array=t}function h(){}o.nextTick=function(e){var t=new Array(arguments.length-1);if(arguments.length>1)for(var n=1;n<arguments.length;n++)t[n-1]=arguments[n];s.push(new m(e,t)),1!==s.length||c||l(p)},m.prototype.run=function(){this.fun.apply(null,this.array)},o.title="browser",o.browser=!0,o.env={},o.argv=[],o.version="",o.versions={},o.on=h,o.addListener=h,o.once=h,o.off=h,o.removeListener=h,o.removeAllListeners=h,o.emit=h,o.prependListener=h,o.prependOnceListener=h,o.listeners=function(e){return[]},o.binding=function(e){throw new Error("process.binding is not supported")},o.cwd=function(){return"/"},o.chdir=function(e){throw new Error("process.chdir is not supported")},o.umask=function(){return 0}},function(e,t,n){"use strict";var r=n(0);e.exports=function(e,t){r.forEach(e,(function(n,r){r!==t&&r.toUpperCase()===t.toUpperCase()&&(e[t]=n,delete e[r])}))}},function(e,t,n){"use strict";var r=n(7);e.exports=function(e,t,n){var o=n.config.validateStatus;!o||o(n.status)?e(n):t(r("Request failed with status code "+n.status,n.config,null,n.request,n))}},function(e,t,n){"use strict";e.exports=function(e,t,n,r,o){return e.config=t,n&&(e.code=n),e.request=r,e.response=o,e.isAxiosError=!0,e.toJSON=function(){return{message:this.message,name:this.name,description:this.description,number:this.number,fileName:this.fileName,lineNumber:this.lineNumber,columnNumber:this.columnNumber,stack:this.stack,config:this.config,code:this.code}},e}},function(e,t,n){"use strict";var r=n(0),o=["age","authorization","content-length","content-type","etag","expires","from","host","if-modified-since","if-unmodified-since","last-modified","location","max-forwards","proxy-authorization","referer","retry-after","user-agent"];e.exports=function(e){var t,n,a,i={};return e?(r.forEach(e.split("\n"),(function(e){if(a=e.indexOf(":"),t=r.trim(e.substr(0,a)).toLowerCase(),n=r.trim(e.substr(a+1)),t){if(i[t]&&o.indexOf(t)>=0)return;i[t]="set-cookie"===t?(i[t]?i[t]:[]).concat([n]):i[t]?i[t]+", "+n:n}})),i):i}},function(e,t,n){"use strict";var r=n(0);e.exports=r.isStandardBrowserEnv()?function(){var e,t=/(msie|trident)/i.test(navigator.userAgent),n=document.createElement("a");function o(e){var r=e;return t&&(n.setAttribute("href",r),r=n.href),n.setAttribute("href",r),{href:n.href,protocol:n.protocol?n.protocol.replace(/:$/,""):"",host:n.host,search:n.search?n.search.replace(/^\?/,""):"",hash:n.hash?n.hash.replace(/^#/,""):"",hostname:n.hostname,port:n.port,pathname:"/"===n.pathname.charAt(0)?n.pathname:"/"+n.pathname}}return e=o(window.location.href),function(t){var n=r.isString(t)?o(t):t;return n.protocol===e.protocol&&n.host===e.host}}():function(){return!0}},function(e,t,n){"use strict";var r=n(0);e.exports=r.isStandardBrowserEnv()?{write:function(e,t,n,o,a,i){var l=[];l.push(e+"="+encodeURIComponent(t)),r.isNumber(n)&&l.push("expires="+new Date(n).toGMTString()),r.isString(o)&&l.push("path="+o),r.isString(a)&&l.push("domain="+a),!0===i&&l.push("secure"),document.cookie=l.join("; ")},read:function(e){var t=document.cookie.match(new RegExp("(^|;\\s*)("+e+")=([^;]*)"));return t?decodeURIComponent(t[3]):null},remove:function(e){this.write(e,"",Date.now()-864e5)}}:{write:function(){},read:function(){return null},remove:function(){}}},function(e,t,n){"use strict";e.exports=function(e){return/^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(e)}},function(e,t,n){"use strict";e.exports=function(e,t){return t?e.replace(/\/+$/,"")+"/"+t.replace(/^\/+/,""):e}},function(e,t,n){"use strict";var r=n(9);function o(e){if("function"!=typeof e)throw new TypeError("executor must be a function.");var t;this.promise=new Promise((function(e){t=e}));var n=this;e((function(e){n.reason||(n.reason=new r(e),t(n.reason))}))}o.prototype.throwIfRequested=function(){if(this.reason)throw this.reason},o.source=function(){var e;return{token:new o((function(t){e=t})),cancel:e}},e.exports=o},function(e,t,n){"use strict";e.exports=function(e){return function(t){return e.apply(null,t)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(28),a=n(30);function i(e,t,n){o.default(),n.classList.add("cms-form-loading");let a=new FormData(n);a.set("ignore_validation","1");let i=n.action;r.post(i,a).then((function(r){let o=r.data,a=(new DOMParser).parseFromString(o,"text/html").querySelector(e);null!==a&&(n.parentNode.replaceChild(a,n),t?t():l(e))})).catch((function(e){console.log(e)})).finally((function(){}))}let l=function(e,t=!1){let n=document.querySelector(e);if(null!==n){let r=n.querySelectorAll("[data-condition]"),o=n.querySelectorAll("button[data-condition]");r.forEach(r=>{r.addEventListener("change",()=>{i(e,t,n)})}),o.forEach(r=>{r.addEventListener("click",()=>{i(e,t,n)})}),a.default(n)}};t.default=l},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.default=function(){for(let e in window.CKEDITOR.instances)window.CKEDITOR.instances[e].updateElement()}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(31),o=n(32);t.default=function(e){if(r.default(),void 0!==e.refresh&&e.refresh){let t=document.getElementById("page-frame");if(null!==t){let n=e.refresh,r=new CustomEvent("refreshElement",{detail:{elementUuid:n}});t.contentDocument.dispatchEvent(r)}}else if(void 0!==e.refresh&&null===e.refresh)window.location.reload();else if(void 0!==e.modal&&e.modal){let t={url:e.modal};o.default("openModal",t,document)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(35);t.default=function(e){e.querySelectorAll('select[data-widget="select2"]:not(.select2-hidden-accessible)').forEach(e=>{$(e).select2({theme:"bootstrap"})}),e.querySelectorAll(".ckeditor-custom").forEach(e=>{let t=e.id;if(window.CKEDITOR.instances[t]){null===e.parentElement.querySelector(".cke")&&(window.CKEDITOR.remove(t),window.CKEDITOR.replace(e,JSON.parse(e.dataset.config)))}else window.CKEDITOR.replace(e,JSON.parse(e.dataset.config))});let t=e.querySelectorAll(".btn-file-select");t.forEach(e=>{e.addEventListener("click",n=>{n.preventDefault(),t.forEach(e=>{e.classList.remove("text-success")}),e.classList.add("text-success");let r=e.dataset.uuid,o=e.dataset.version,a=e.dataset.title;$(e).parentsUntil(".tab-content").parent().find("input.existing-file-uuid").val(r),$(e).parentsUntil(".tab-content").parent().find("input.existing-file-version").val(o),$(e).parentsUntil("form").parent().find("input.file-title").val(a)})}),e.querySelectorAll(".btn-create").forEach(e=>{e.addEventListener("click",t=>{t.preventDefault();let n=new CustomEvent("createElement",{detail:{parent:e.dataset.uuid,elementName:e.dataset.elementName}});document.dispatchEvent(n)})});let n=new CustomEvent("bindWidgets",{detail:{element:e}});document.dispatchEvent(n),r.default("bindWidgets",$(e),"body")}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(38),o=n(39);t.default=function(){let e=document.getElementById("pageUuid"),t=e?e.value:null,n=document.getElementById("userId"),a=e?n.value:null;t&&a&&(o.default(t,a),r.default(t,a))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.default=function(e,t,n){let r=new CustomEvent(e,{detail:t});n.dispatchEvent(r)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;t.default=function(e,t,n){e.addEventListener("submit",o=>{o.preventDefault(),t();let a=new FormData(e),i=e.action;r.post(i,a).then((function(e){n(e.data,!0)})).catch((function(e){n(e.data,!1)})).finally((function(){}))})}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(27),a=n(30),i=n(36),l=n(37),u=n(29),s=n(40),c=n(41),d=n(32),f=n(31);let p=void 0!==window.translations?window.translations:{confirmDelete:"Delete?",confirmDuplicate:"Duplicate?"};document.addEventListener("DOMContentLoaded",()=>{l.default(),document.addEventListener("trix-initialize",e=>{let t=e.target.parentElement.querySelector("input[name=href]");null!==t&&(t.type="text",t.pattern="(https?://|/).+")}),o.default("form[name=page]");let e=document.querySelector("form.edit-form"),t=document.querySelector("form.new-form");(document.body.classList.contains("edit")||document.body.classList.contains("new"))&&(e||t)&&a.default(document.body),$(document).on("easyadmin.collection.item-added",()=>{a.default(document.body)}),i.default(document.body),document.addEventListener("bindWidgets",e=>{i.default(e.detail.element)}),document.querySelectorAll(".action-cms_delete_aggregate").forEach(e=>{e.addEventListener("click",()=>{confirm(p.confirmDelete)||(event.preventDefault(),event.stopPropagation())})}),document.querySelectorAll(".action-cms_clone_aggregate").forEach(e=>{e.addEventListener("click",()=>{confirm(p.confirmDuplicate)||(event.preventDefault(),event.stopPropagation())})}),document.body.classList.contains("edit-page")&&(f.default(),document.addEventListener("iframeReady",()=>{let e=document.getElementById("page-frame");if(null!==e&&!e.classList.contains("size-AutoWidth")){let t=e.contentDocument.body.clientWidth,n=e.clientWidth,r=n-t;r>0&&e.setAttribute("width",n+r+"px")}}),document.addEventListener("openAjax",e=>{let t=e.detail.url+"?ajax=1";r.get(t).then((function(e){u.default(e.data)})).catch((function(e){console.log(e)})).finally((function(){}))}),document.addEventListener("openModal",e=>{c.default(e.detail.url)}),document.addEventListener("openTab",e=>{s.default(e.detail.url)}),document.addEventListener("editElement",e=>{let t={url:`/admin/page/edit-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openModal",t,document)}),document.addEventListener("shiftElement",e=>{let t={url:`/admin/page/shift-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}/${e.detail.direction}`};d.default("openAjax",t,document)}),document.addEventListener("deleteElement",e=>{let t={url:`/admin/page/delete-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("addElement",e=>{let t={url:`/admin/page/add-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.parent}`};d.default("openModal",t,document)}),document.addEventListener("createElement",e=>{let t=window.pageData.uuid,n=window.pageData.version,r=e.detail.parent,o={url:`/admin/page/create-element/${e.detail.elementName}/${t}/${n}/${r}`};d.default("openModal",o,document)}),document.addEventListener("disableElement",e=>{let t={url:`/admin/page/disable-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("enableElement",e=>{let t={url:`/admin/page/enable-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("duplicateElement",e=>{let t={url:`/admin/page/duplicate-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("createSection",e=>{let t={url:`/admin/page/create-section/${window.pageData.uuid}/${window.pageData.version}/${e.detail.section}`};d.default("openAjax",t,document)}),document.addEventListener("createColumn",e=>{let t={url:`/admin/page/create-column/${window.pageData.uuid}/${window.pageData.version}/${e.detail.parent}/${e.detail.size}/${e.detail.breakpoint}`};d.default("openAjax",t,document)}),document.addEventListener("resizeColumn",e=>{let t={url:`/admin/page/resize-column/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}/${e.detail.size}/${e.detail.breakpoint}`};d.default("openAjax",t,document)}))})},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.default=function(e,t,n){$(n).trigger(e,t)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;function o(e){document.body.classList.remove("file-picker-open"),e.classList.remove("d-flex"),e.classList.add("d-none"),e.remove()}t.default=function(e){let t=e.querySelectorAll("[data-file-picker]");t.forEach(t=>{t.addEventListener("click",n=>{n.preventDefault(),e.querySelectorAll(".cms-file-picker").forEach(e=>{e.remove()});let a=t.dataset.filePicker,i=t.dataset.filePickerUpload,l=t.dataset.filePickerMimeTypes;document.body.insertAdjacentHTML("beforeend",'<div class="cms-file-picker d-none flex-column"></div>');let u=document.querySelector(".cms-file-picker");if(null!==u){let e=l?{mimeTypes:l}:null;r.get("/admin/file/picker",{params:e}).then((function(e){document.body.classList.add("file-picker-open"),u.classList.remove("d-none"),u.classList.add("d-flex"),u.innerHTML=e.data,function(e,t,n){e.querySelectorAll("[data-file-path]").forEach(r=>{r.addEventListener("click",a=>{a.preventDefault();let i=r.dataset.thumbPath,l=r.dataset.filePath,u=r.dataset.title,s=r.dataset.mimeType,c=r.dataset.size,d=r.dataset.width,f=r.dataset.height,p=document.getElementById(n+"_file");null!==p&&p.setAttribute("value",l);let m=document.getElementById(n+"_title");null!==m&&m.setAttribute("value",u);let h=document.getElementById(n+"_mimeType");null!==h&&h.setAttribute("value",s);let v=document.getElementById(n+"_size");null!==v&&v.setAttribute("value",c);let g=document.getElementById(n+"_width");null!==g&&g.setAttribute("value",d);let y=document.getElementById(n+"_height");null!==y&&y.setAttribute("value",f);let w=document.getElementById(t);null!==w&&w.removeAttribute("required");let b=document.getElementById("cms-img-"+n+"_file");null!==b&&(b.setAttribute("src",i),b.classList.remove("d-none")),o(e)})});let r=e.querySelector(".cms-file-picker-close");null!==r&&r.addEventListener("click",t=>{t.preventDefault(),o(e)})}(u,i,a)})).catch((function(e){console.log(e)})).finally((function(){}))}})})}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;t.default=function(){document.querySelectorAll(".cms-admin-menu-root").forEach(e=>{let t=e.dataset.uuid,n=e.dataset.version;$(e).sortable({group:"serialization",containerSelector:".cms-admin-menu",handle:".cms-admin-menu-item-move"});let o=document.querySelector('.btn-save-order[data-uuid="'+t+'"]');null!==o&&o.addEventListener("click",o=>{o.preventDefault();let a=$(e).sortable("serialize").get(),i=JSON.stringify(a,null," "),l=`/admin/menu/save-order/${t}/${n}?ajax=1`;r.post(l,i).then((function(){})).catch((function(e){console.log(e)})).finally((function(){window.location.reload()}))})})}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;function o(){let e=document.querySelector("#page-tree .cms_tree");null!==e&&e.classList.remove("valid-target-tree"),document.querySelectorAll("#page-tree .cms_tree-node-item").forEach(e=>{e.classList.remove("valid-target")})}t.default=function(e,t){let n="/admin/api/page-tree/"+e+"/"+t;r.get(n).then((function(e){let t=document.getElementById("page-tree");null!==t&&(t.innerHTML=e.data),function(){let e=document.querySelector(".btn-tree-save"),t=$("#page-tree > .cms_tree").sortable({group:"serialization",containerSelector:".cms_tree",nested:!0,itemSelector:".cms_tree-node",placeholder:'<div class="placeholder"><i class="fas fa-arrow-right"></i></div>',isValidTarget:function(e,t){return t.el[0].classList.contains("valid-target-tree")},onCancel:function(e,t,n){o()},onDrop:function(e,t,n){o()},onDragStart:function(e,t,n){let r=e[0],o=r.dataset.elementName;if("Section"===o)return!1;document.querySelectorAll("#page-tree .cms_tree").forEach(e=>{let t=$.contains(r,e),n="children"in e.dataset?e.dataset.children:"";if(!1!==t||"all"!==n&&-1===n.split(",").indexOf(o)){e.classList.remove("valid-target-tree"),e.parentElement.querySelectorAll(".cms_tree-node-item").forEach(e=>{e.classList.remove("valid-target")})}else{e.classList.add("valid-target-tree"),e.parentElement.querySelectorAll(".cms_tree-node-item").forEach(e=>{e.classList.add("valid-target")})}})},afterMove:function(){null!==e&&(e.classList.remove("hidden"),e.classList.remove("d-none"))}});null!==e&&(e.classList.add("d-none"),e.addEventListener("click",e=>{e.preventDefault();let n=document.getElementById("tree-pageUuid").value,o=document.getElementById("tree-onVersion").value,a=t.sortable("serialize").get(),i=`/admin/page/save-order/${n}/${o}?ajax=1`;r.post(i,a).then((function(e){})).catch((function(e){})).finally((function(){window.location.reload()}))})),document.querySelectorAll(".cms_tree-node-item").forEach(e=>{let t=`[data-uuid="${e.parentElement.dataset.uuid}"]`,n=document.getElementById("page-frame").contentDocument.querySelector(t);e.addEventListener("mouseenter",()=>{n.classList.add("editor-highlight")}),e.addEventListener("mouseleave",()=>{n.classList.remove("editor-highlight")})})}()})).catch((function(e){console.log(e)})).finally((function(){}))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;function o(e,t){document.querySelectorAll(e).forEach(e=>{e.addEventListener("click",n=>{n.preventDefault();let r=new CustomEvent(t,{detail:{url:e.getAttribute("href")}});document.dispatchEvent(r)})})}t.default=function(e,t){let n="/admin/api/page-info/"+e+"/"+t;r.get(n).then((function(e){window.pageData=e.data;let t=document.getElementById("admin-bar");null!==t&&(t.innerHTML=e.data.html),function(){o("[data-target=modal]","openModal"),o("[data-target=parent]","openModal"),o("[data-target=ajax]","openAjax"),o("[data-target=tab]","openTab");let e=document.querySelector(".toggle-tree");null!==e&&e.addEventListener("click",t=>{t.preventDefault(),e.classList.contains("active")?e.classList.remove("active"):e.classList.add("active");let n=document.getElementById("page-tree");null!==n&&(n.classList.contains("hidden")?n.classList.remove("hidden"):n.classList.add("hidden"))});let t=document.querySelector(".toggle-editor");null!==t&&t.addEventListener("click",e=>{e.preventDefault(),t.classList.contains("active")?t.classList.remove("active"):t.classList.add("active");let n=document.getElementById("page-frame");if(null!==n){let e=n.contentDocument.body;e.classList.contains("hide-editor")?e.classList.remove("hide-editor"):e.classList.add("hide-editor")}});let n=document.querySelector(".toggle-contrast");null!==n&&n.addEventListener("click",e=>{e.preventDefault(),n.classList.contains("active")?n.classList.remove("active"):n.classList.add("active");let t=document.getElementById("page-frame");if(null!==t){let e=t.contentDocument.body;e.classList.contains("editor-dark")?e.classList.remove("editor-dark"):e.classList.add("editor-dark")}})}()})).catch((function(e){console.log(e)})).finally((function(){}))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(27),a=n(33),i=n(29),l=n(28);t.default=function(e){let t=document.querySelector(".page-tabs-tab-settings");null!==t&&(t.innerHTML="",e+="?ajax=1",r.get(e).then((function(n){let r=n.data,u=(new DOMParser).parseFromString(r,"text/html").querySelector(".content-wrapper .content");null!==u&&(t.insertAdjacentElement("beforeend",u),function e(t){let n=document.querySelector(".page-tabs-tab-editor"),r=document.querySelector(".page-tabs-tab-settings"),u=r.querySelector("form"),s=u.getAttribute("name")?'form[name="'+u.getAttribute("name")+'"]':"#main form";u.setAttribute("action",t),o.default(s,()=>{e(t)}),a.default(u,l.default,(o,a)=>{if(a&&o.success)r.classList.remove("active"),n.classList.add("active"),i.default(o);else{let n=o,r=(new DOMParser).parseFromString(n,"text/html").querySelector(s);null!==r&&(u.parentNode.replaceChild(r,u),e(t))}});let c=r.querySelector(".content-header .global-actions");if(null!==c){if(null===c.querySelector(".btn-close-tab")){c.insertAdjacentHTML("beforeend",'<button class="btn btn-sm btn-close-tab"><span class="fa fa-times"></span></button>');let e=c.querySelector(".btn-close-tab");null!==e&&e.addEventListener("click",e=>{e.preventDefault(),r.innerHTML="",r.classList.remove("active"),n.classList.add("active")})}}n.classList.remove("active"),r.classList.add("active")}(e))})).catch((function(e){console.log(e)})).finally((function(){})))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(27),a=n(33),i=n(29),l=n(28);t.default=function(e){let t=document.querySelector("#editor-modal .modal-body");null!==t&&(t.innerHTML="",e+="?ajax=1",r.get(e).then((function(n){let r=n.data,u=(new DOMParser).parseFromString(r,"text/html").querySelector(".content-wrapper .content");null!==u&&(t.insertAdjacentElement("beforeend",u),function e(t){let n=document.getElementById("editor-modal");if(null===n)return;let r=n.querySelector(".content-wrapper");null!==r&&r.classList.remove("content-wrapper");let u=n.querySelector("form");if(null!==u){let r="#main form";u.name&&(r='form[name="'+u.name+'"]'),u.action=t,o.default(r,()=>{e(t)}),a.default(u,l.default,(o,a)=>{if(a&&o.success)$(n).modal("hide"),i.default(o);else{let n=o,a=(new DOMParser).parseFromString(n,"text/html").querySelector(r);null!==a&&(u.parentNode.replaceChild(a,u),e(t))}})}let s=document.getElementById("editor-modal-title"),c=n.querySelector(".content-header .title");null!==c&&(s.innerHTML=c.innerHTML);let d=n.querySelector(".content-header");null!==d&&d.remove();let f=n.querySelector(".modal-nav-content"),p=n.querySelector(".modal-nav");null!==p&&null!==f?(p.innerHTML=f.innerHTML,f.remove()):null!==p&&(p.innerHTML=""),$(n).modal("show")}(e))})).catch((function(e){console.log(e)})).finally((function(){})))}}]);