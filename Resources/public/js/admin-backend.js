!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=35)}([function(e,t,n){"use strict";var r=n(2),o=Object.prototype.toString;function a(e){return"[object Array]"===o.call(e)}function i(e){return void 0===e}function l(e){return null!==e&&"object"==typeof e}function u(e){return"[object Function]"===o.call(e)}function s(e,t){if(null!=e)if("object"!=typeof e&&(e=[e]),a(e))for(var n=0,r=e.length;n<r;n++)t.call(null,e[n],n,e);else for(var o in e)Object.prototype.hasOwnProperty.call(e,o)&&t.call(null,e[o],o,e)}e.exports={isArray:a,isArrayBuffer:function(e){return"[object ArrayBuffer]"===o.call(e)},isBuffer:function(e){return null!==e&&!i(e)&&null!==e.constructor&&!i(e.constructor)&&"function"==typeof e.constructor.isBuffer&&e.constructor.isBuffer(e)},isFormData:function(e){return"undefined"!=typeof FormData&&e instanceof FormData},isArrayBufferView:function(e){return"undefined"!=typeof ArrayBuffer&&ArrayBuffer.isView?ArrayBuffer.isView(e):e&&e.buffer&&e.buffer instanceof ArrayBuffer},isString:function(e){return"string"==typeof e},isNumber:function(e){return"number"==typeof e},isObject:l,isUndefined:i,isDate:function(e){return"[object Date]"===o.call(e)},isFile:function(e){return"[object File]"===o.call(e)},isBlob:function(e){return"[object Blob]"===o.call(e)},isFunction:u,isStream:function(e){return l(e)&&u(e.pipe)},isURLSearchParams:function(e){return"undefined"!=typeof URLSearchParams&&e instanceof URLSearchParams},isStandardBrowserEnv:function(){return("undefined"==typeof navigator||"ReactNative"!==navigator.product&&"NativeScript"!==navigator.product&&"NS"!==navigator.product)&&("undefined"!=typeof window&&"undefined"!=typeof document)},forEach:s,merge:function e(){var t={};function n(n,r){"object"==typeof t[r]&&"object"==typeof n?t[r]=e(t[r],n):t[r]=n}for(var r=0,o=arguments.length;r<o;r++)s(arguments[r],n);return t},deepMerge:function e(){var t={};function n(n,r){"object"==typeof t[r]&&"object"==typeof n?t[r]=e(t[r],n):t[r]="object"==typeof n?e({},n):n}for(var r=0,o=arguments.length;r<o;r++)s(arguments[r],n);return t},extend:function(e,t,n){return s(t,(function(t,o){e[o]=n&&"function"==typeof t?r(t,n):t})),e},trim:function(e){return e.replace(/^\s*/,"").replace(/\s*$/,"")}}},function(e,t,n){e.exports=n(10)},function(e,t,n){"use strict";e.exports=function(e,t){return function(){for(var n=new Array(arguments.length),r=0;r<n.length;r++)n[r]=arguments[r];return e.apply(t,n)}}},function(e,t,n){"use strict";var r=n(0);function o(e){return encodeURIComponent(e).replace(/%40/gi,"@").replace(/%3A/gi,":").replace(/%24/g,"$").replace(/%2C/gi,",").replace(/%20/g,"+").replace(/%5B/gi,"[").replace(/%5D/gi,"]")}e.exports=function(e,t,n){if(!t)return e;var a;if(n)a=n(t);else if(r.isURLSearchParams(t))a=t.toString();else{var i=[];r.forEach(t,(function(e,t){null!=e&&(r.isArray(e)?t+="[]":e=[e],r.forEach(e,(function(e){r.isDate(e)?e=e.toISOString():r.isObject(e)&&(e=JSON.stringify(e)),i.push(o(t)+"="+o(e))})))})),a=i.join("&")}if(a){var l=e.indexOf("#");-1!==l&&(e=e.slice(0,l)),e+=(-1===e.indexOf("?")?"?":"&")+a}return e}},function(e,t,n){"use strict";e.exports=function(e){return!(!e||!e.__CANCEL__)}},function(e,t,n){"use strict";(function(t){var r=n(0),o=n(16),a={"Content-Type":"application/x-www-form-urlencoded"};function i(e,t){!r.isUndefined(e)&&r.isUndefined(e["Content-Type"])&&(e["Content-Type"]=t)}var l,u={adapter:(("undefined"!=typeof XMLHttpRequest||void 0!==t&&"[object process]"===Object.prototype.toString.call(t))&&(l=n(6)),l),transformRequest:[function(e,t){return o(t,"Accept"),o(t,"Content-Type"),r.isFormData(e)||r.isArrayBuffer(e)||r.isBuffer(e)||r.isStream(e)||r.isFile(e)||r.isBlob(e)?e:r.isArrayBufferView(e)?e.buffer:r.isURLSearchParams(e)?(i(t,"application/x-www-form-urlencoded;charset=utf-8"),e.toString()):r.isObject(e)?(i(t,"application/json;charset=utf-8"),JSON.stringify(e)):e}],transformResponse:[function(e){if("string"==typeof e)try{e=JSON.parse(e)}catch(e){}return e}],timeout:0,xsrfCookieName:"XSRF-TOKEN",xsrfHeaderName:"X-XSRF-TOKEN",maxContentLength:-1,validateStatus:function(e){return e>=200&&e<300}};u.headers={common:{Accept:"application/json, text/plain, */*"}},r.forEach(["delete","get","head"],(function(e){u.headers[e]={}})),r.forEach(["post","put","patch"],(function(e){u.headers[e]=r.merge(a)})),e.exports=u}).call(this,n(15))},function(e,t,n){"use strict";var r=n(0),o=n(17),a=n(3),i=n(19),l=n(22),u=n(23),s=n(7);e.exports=function(e){return new Promise((function(t,c){var d=e.data,f=e.headers;r.isFormData(d)&&delete f["Content-Type"];var p=new XMLHttpRequest;if(e.auth){var m=e.auth.username||"",h=e.auth.password||"";f.Authorization="Basic "+btoa(m+":"+h)}var v=i(e.baseURL,e.url);if(p.open(e.method.toUpperCase(),a(v,e.params,e.paramsSerializer),!0),p.timeout=e.timeout,p.onreadystatechange=function(){if(p&&4===p.readyState&&(0!==p.status||p.responseURL&&0===p.responseURL.indexOf("file:"))){var n="getAllResponseHeaders"in p?l(p.getAllResponseHeaders()):null,r={data:e.responseType&&"text"!==e.responseType?p.response:p.responseText,status:p.status,statusText:p.statusText,headers:n,config:e,request:p};o(t,c,r),p=null}},p.onabort=function(){p&&(c(s("Request aborted",e,"ECONNABORTED",p)),p=null)},p.onerror=function(){c(s("Network Error",e,null,p)),p=null},p.ontimeout=function(){var t="timeout of "+e.timeout+"ms exceeded";e.timeoutErrorMessage&&(t=e.timeoutErrorMessage),c(s(t,e,"ECONNABORTED",p)),p=null},r.isStandardBrowserEnv()){var g=n(24),y=(e.withCredentials||u(v))&&e.xsrfCookieName?g.read(e.xsrfCookieName):void 0;y&&(f[e.xsrfHeaderName]=y)}if("setRequestHeader"in p&&r.forEach(f,(function(e,t){void 0===d&&"content-type"===t.toLowerCase()?delete f[t]:p.setRequestHeader(t,e)})),r.isUndefined(e.withCredentials)||(p.withCredentials=!!e.withCredentials),e.responseType)try{p.responseType=e.responseType}catch(t){if("json"!==e.responseType)throw t}"function"==typeof e.onDownloadProgress&&p.addEventListener("progress",e.onDownloadProgress),"function"==typeof e.onUploadProgress&&p.upload&&p.upload.addEventListener("progress",e.onUploadProgress),e.cancelToken&&e.cancelToken.promise.then((function(e){p&&(p.abort(),c(e),p=null)})),void 0===d&&(d=null),p.send(d)}))}},function(e,t,n){"use strict";var r=n(18);e.exports=function(e,t,n,o,a){var i=new Error(e);return r(i,t,n,o,a)}},function(e,t,n){"use strict";var r=n(0);e.exports=function(e,t){t=t||{};var n={},o=["url","method","params","data"],a=["headers","auth","proxy"],i=["baseURL","url","transformRequest","transformResponse","paramsSerializer","timeout","withCredentials","adapter","responseType","xsrfCookieName","xsrfHeaderName","onUploadProgress","onDownloadProgress","maxContentLength","validateStatus","maxRedirects","httpAgent","httpsAgent","cancelToken","socketPath"];r.forEach(o,(function(e){void 0!==t[e]&&(n[e]=t[e])})),r.forEach(a,(function(o){r.isObject(t[o])?n[o]=r.deepMerge(e[o],t[o]):void 0!==t[o]?n[o]=t[o]:r.isObject(e[o])?n[o]=r.deepMerge(e[o]):void 0!==e[o]&&(n[o]=e[o])})),r.forEach(i,(function(r){void 0!==t[r]?n[r]=t[r]:void 0!==e[r]&&(n[r]=e[r])}));var l=o.concat(a).concat(i),u=Object.keys(t).filter((function(e){return-1===l.indexOf(e)}));return r.forEach(u,(function(r){void 0!==t[r]?n[r]=t[r]:void 0!==e[r]&&(n[r]=e[r])})),n}},function(e,t,n){"use strict";function r(e){this.message=e}r.prototype.toString=function(){return"Cancel"+(this.message?": "+this.message:"")},r.prototype.__CANCEL__=!0,e.exports=r},function(e,t,n){"use strict";var r=n(0),o=n(2),a=n(11),i=n(8);function l(e){var t=new a(e),n=o(a.prototype.request,t);return r.extend(n,a.prototype,t),r.extend(n,t),n}var u=l(n(5));u.Axios=a,u.create=function(e){return l(i(u.defaults,e))},u.Cancel=n(9),u.CancelToken=n(25),u.isCancel=n(4),u.all=function(e){return Promise.all(e)},u.spread=n(26),e.exports=u,e.exports.default=u},function(e,t,n){"use strict";var r=n(0),o=n(3),a=n(12),i=n(13),l=n(8);function u(e){this.defaults=e,this.interceptors={request:new a,response:new a}}u.prototype.request=function(e){"string"==typeof e?(e=arguments[1]||{}).url=arguments[0]:e=e||{},(e=l(this.defaults,e)).method?e.method=e.method.toLowerCase():this.defaults.method?e.method=this.defaults.method.toLowerCase():e.method="get";var t=[i,void 0],n=Promise.resolve(e);for(this.interceptors.request.forEach((function(e){t.unshift(e.fulfilled,e.rejected)})),this.interceptors.response.forEach((function(e){t.push(e.fulfilled,e.rejected)}));t.length;)n=n.then(t.shift(),t.shift());return n},u.prototype.getUri=function(e){return e=l(this.defaults,e),o(e.url,e.params,e.paramsSerializer).replace(/^\?/,"")},r.forEach(["delete","get","head","options"],(function(e){u.prototype[e]=function(t,n){return this.request(r.merge(n||{},{method:e,url:t}))}})),r.forEach(["post","put","patch"],(function(e){u.prototype[e]=function(t,n,o){return this.request(r.merge(o||{},{method:e,url:t,data:n}))}})),e.exports=u},function(e,t,n){"use strict";var r=n(0);function o(){this.handlers=[]}o.prototype.use=function(e,t){return this.handlers.push({fulfilled:e,rejected:t}),this.handlers.length-1},o.prototype.eject=function(e){this.handlers[e]&&(this.handlers[e]=null)},o.prototype.forEach=function(e){r.forEach(this.handlers,(function(t){null!==t&&e(t)}))},e.exports=o},function(e,t,n){"use strict";var r=n(0),o=n(14),a=n(4),i=n(5);function l(e){e.cancelToken&&e.cancelToken.throwIfRequested()}e.exports=function(e){return l(e),e.headers=e.headers||{},e.data=o(e.data,e.headers,e.transformRequest),e.headers=r.merge(e.headers.common||{},e.headers[e.method]||{},e.headers),r.forEach(["delete","get","head","post","put","patch","common"],(function(t){delete e.headers[t]})),(e.adapter||i.adapter)(e).then((function(t){return l(e),t.data=o(t.data,t.headers,e.transformResponse),t}),(function(t){return a(t)||(l(e),t&&t.response&&(t.response.data=o(t.response.data,t.response.headers,e.transformResponse))),Promise.reject(t)}))}},function(e,t,n){"use strict";var r=n(0);e.exports=function(e,t,n){return r.forEach(n,(function(n){e=n(e,t)})),e}},function(e,t){var n,r,o=e.exports={};function a(){throw new Error("setTimeout has not been defined")}function i(){throw new Error("clearTimeout has not been defined")}function l(e){if(n===setTimeout)return setTimeout(e,0);if((n===a||!n)&&setTimeout)return n=setTimeout,setTimeout(e,0);try{return n(e,0)}catch(t){try{return n.call(null,e,0)}catch(t){return n.call(this,e,0)}}}!function(){try{n="function"==typeof setTimeout?setTimeout:a}catch(e){n=a}try{r="function"==typeof clearTimeout?clearTimeout:i}catch(e){r=i}}();var u,s=[],c=!1,d=-1;function f(){c&&u&&(c=!1,u.length?s=u.concat(s):d=-1,s.length&&p())}function p(){if(!c){var e=l(f);c=!0;for(var t=s.length;t;){for(u=s,s=[];++d<t;)u&&u[d].run();d=-1,t=s.length}u=null,c=!1,function(e){if(r===clearTimeout)return clearTimeout(e);if((r===i||!r)&&clearTimeout)return r=clearTimeout,clearTimeout(e);try{r(e)}catch(t){try{return r.call(null,e)}catch(t){return r.call(this,e)}}}(e)}}function m(e,t){this.fun=e,this.array=t}function h(){}o.nextTick=function(e){var t=new Array(arguments.length-1);if(arguments.length>1)for(var n=1;n<arguments.length;n++)t[n-1]=arguments[n];s.push(new m(e,t)),1!==s.length||c||l(p)},m.prototype.run=function(){this.fun.apply(null,this.array)},o.title="browser",o.browser=!0,o.env={},o.argv=[],o.version="",o.versions={},o.on=h,o.addListener=h,o.once=h,o.off=h,o.removeListener=h,o.removeAllListeners=h,o.emit=h,o.prependListener=h,o.prependOnceListener=h,o.listeners=function(e){return[]},o.binding=function(e){throw new Error("process.binding is not supported")},o.cwd=function(){return"/"},o.chdir=function(e){throw new Error("process.chdir is not supported")},o.umask=function(){return 0}},function(e,t,n){"use strict";var r=n(0);e.exports=function(e,t){r.forEach(e,(function(n,r){r!==t&&r.toUpperCase()===t.toUpperCase()&&(e[t]=n,delete e[r])}))}},function(e,t,n){"use strict";var r=n(7);e.exports=function(e,t,n){var o=n.config.validateStatus;!o||o(n.status)?e(n):t(r("Request failed with status code "+n.status,n.config,null,n.request,n))}},function(e,t,n){"use strict";e.exports=function(e,t,n,r,o){return e.config=t,n&&(e.code=n),e.request=r,e.response=o,e.isAxiosError=!0,e.toJSON=function(){return{message:this.message,name:this.name,description:this.description,number:this.number,fileName:this.fileName,lineNumber:this.lineNumber,columnNumber:this.columnNumber,stack:this.stack,config:this.config,code:this.code}},e}},function(e,t,n){"use strict";var r=n(20),o=n(21);e.exports=function(e,t){return e&&!r(t)?o(e,t):t}},function(e,t,n){"use strict";e.exports=function(e){return/^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(e)}},function(e,t,n){"use strict";e.exports=function(e,t){return t?e.replace(/\/+$/,"")+"/"+t.replace(/^\/+/,""):e}},function(e,t,n){"use strict";var r=n(0),o=["age","authorization","content-length","content-type","etag","expires","from","host","if-modified-since","if-unmodified-since","last-modified","location","max-forwards","proxy-authorization","referer","retry-after","user-agent"];e.exports=function(e){var t,n,a,i={};return e?(r.forEach(e.split("\n"),(function(e){if(a=e.indexOf(":"),t=r.trim(e.substr(0,a)).toLowerCase(),n=r.trim(e.substr(a+1)),t){if(i[t]&&o.indexOf(t)>=0)return;i[t]="set-cookie"===t?(i[t]?i[t]:[]).concat([n]):i[t]?i[t]+", "+n:n}})),i):i}},function(e,t,n){"use strict";var r=n(0);e.exports=r.isStandardBrowserEnv()?function(){var e,t=/(msie|trident)/i.test(navigator.userAgent),n=document.createElement("a");function o(e){var r=e;return t&&(n.setAttribute("href",r),r=n.href),n.setAttribute("href",r),{href:n.href,protocol:n.protocol?n.protocol.replace(/:$/,""):"",host:n.host,search:n.search?n.search.replace(/^\?/,""):"",hash:n.hash?n.hash.replace(/^#/,""):"",hostname:n.hostname,port:n.port,pathname:"/"===n.pathname.charAt(0)?n.pathname:"/"+n.pathname}}return e=o(window.location.href),function(t){var n=r.isString(t)?o(t):t;return n.protocol===e.protocol&&n.host===e.host}}():function(){return!0}},function(e,t,n){"use strict";var r=n(0);e.exports=r.isStandardBrowserEnv()?{write:function(e,t,n,o,a,i){var l=[];l.push(e+"="+encodeURIComponent(t)),r.isNumber(n)&&l.push("expires="+new Date(n).toGMTString()),r.isString(o)&&l.push("path="+o),r.isString(a)&&l.push("domain="+a),!0===i&&l.push("secure"),document.cookie=l.join("; ")},read:function(e){var t=document.cookie.match(new RegExp("(^|;\\s*)("+e+")=([^;]*)"));return t?decodeURIComponent(t[3]):null},remove:function(e){this.write(e,"",Date.now()-864e5)}}:{write:function(){},read:function(){return null},remove:function(){}}},function(e,t,n){"use strict";var r=n(9);function o(e){if("function"!=typeof e)throw new TypeError("executor must be a function.");var t;this.promise=new Promise((function(e){t=e}));var n=this;e((function(e){n.reason||(n.reason=new r(e),t(n.reason))}))}o.prototype.throwIfRequested=function(){if(this.reason)throw this.reason},o.source=function(){var e;return{token:new o((function(t){e=t})),cancel:e}},e.exports=o},function(e,t,n){"use strict";e.exports=function(e){return function(t){return e.apply(null,t)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(28),a=n(31),i=n(29);function l(e,t,n){o.default(),n.classList.add("cms-form-loading");let a=new FormData(n);a.set("ignore_validation","1");let l=n.action;r.post(l,a).then((function(r){let o=r.data,a=(new DOMParser).parseFromString(o,"text/html").querySelector(e);null!==a&&(n.parentNode.replaceChild(a,n),i.default(a),t?t():u(e))})).catch((function(e){console.log(e)})).finally((function(){}))}let u=function(e,t=!1){let n=document.querySelector(e);if(null!==n){let r=n.querySelectorAll("[data-condition]"),o=n.querySelectorAll("button[data-condition]");r.forEach(r=>{r.addEventListener("change",()=>{l(e,t,n)})}),o.forEach(r=>{r.addEventListener("click",r=>{r.preventDefault(),l(e,t,n)})}),a.default(n)}};t.default=u},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.default=function(){for(let e in window.CKEDITOR.instances)window.CKEDITOR.instances[e].updateElement()}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});let r=function(e){if(!0===function(e){return"SCRIPT"===e.tagName}(e)&&e.parentNode)e.parentNode.replaceChild(function(e){let t=document.createElement("script");t.text=e.innerHTML;for(let n=e.attributes.length-1;n>=0;n--)t.setAttribute(e.attributes[n].name,e.attributes[n].value);return t}(e),e);else{let t=0,n=e.childNodes;for(;t<n.length;){let e=n[t++];r(e)}}return e};t.default=r},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(32),o=n(33);t.default=function(e){if(r.default(),void 0!==e.refresh&&e.refresh){let t=document.getElementById("page-frame");if(null!==t){let n=e.refresh,r=new CustomEvent("refreshElement",{detail:{elementUuid:n}});t.contentDocument.dispatchEvent(r)}}else if(void 0!==e.refresh&&null===e.refresh)window.location.reload();else if(void 0!==e.modal&&e.modal){let t={url:e.modal};o.default("openModal",t,document)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(36);t.default=function(e){e.querySelectorAll('select[data-widget="select2"]:not(.select2-hidden-accessible)').forEach(e=>{$(e).select2({theme:"bootstrap"})}),e.querySelectorAll(".ckeditor-custom").forEach(e=>{let t=e.id;if(window.CKEDITOR.instances[t]){null===e.parentElement.querySelector(".cke")&&(window.CKEDITOR.remove(t),window.CKEDITOR.replace(e,JSON.parse(e.dataset.config)))}else window.CKEDITOR.replace(e,JSON.parse(e.dataset.config))});let t=e.querySelectorAll(".btn-file-select");t.forEach(e=>{e.addEventListener("click",n=>{n.preventDefault(),t.forEach(e=>{e.classList.remove("text-success")}),e.classList.add("text-success");let r=e.dataset.uuid,o=e.dataset.version,a=e.dataset.title;$(e).parentsUntil(".tab-content").parent().find("input.existing-file-uuid").val(r),$(e).parentsUntil(".tab-content").parent().find("input.existing-file-version").val(o),$(e).parentsUntil("form").parent().find("input.file-title").val(a)})}),e.querySelectorAll(".btn-create").forEach(e=>{e.addEventListener("click",t=>{t.preventDefault();let n=new CustomEvent("createElement",{detail:{parent:e.dataset.uuid,elementName:e.dataset.elementName}});document.dispatchEvent(n)})});let n=new CustomEvent("bindWidgets",{detail:{element:e}});document.dispatchEvent(n),r.default("bindWidgets",$(e),"body")}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(39),o=n(40);t.default=function(){let e=document.getElementById("pageUuid"),t=e?e.value:null,n=document.getElementById("userId"),a=e?n.value:null;t&&a&&(o.default(t,a),r.default(t,a))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.default=function(e,t,n){let r=new CustomEvent(e,{detail:t});n.dispatchEvent(r)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;t.default=function(e,t,n){e.addEventListener("submit",o=>{o.preventDefault(),t();let a=new FormData(e),i=e.action;r.post(i,a).then((function(e){n(e.data,!0)})).catch((function(e){n(e.data,!1)})).finally((function(){}))})}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(27),a=n(31),i=n(37),l=n(38),u=n(30),s=n(41),c=n(42),d=n(33),f=n(32);let p=void 0!==window.translations?window.translations:{confirmDelete:"Delete?",confirmDuplicate:"Duplicate?"};document.addEventListener("DOMContentLoaded",()=>{l.default(),document.addEventListener("trix-initialize",e=>{let t=e.target.parentElement.querySelector("input[name=href]");null!==t&&(t.type="text",t.pattern="(https?://|/).+")}),o.default("form[name=page]"),(document.body.classList.contains("edit")||document.body.classList.contains("new"))&&a.default(document.body),document.querySelector('form[name="element"]')&&a.default(document.body),$(document).on("easyadmin.collection.item-added",()=>{a.default(document.body)}),i.default(document.body),document.addEventListener("bindWidgets",e=>{i.default(e.detail.element)}),document.querySelectorAll(".action-cms_delete_aggregate").forEach(e=>{e.addEventListener("click",()=>{confirm(p.confirmDelete)||(event.preventDefault(),event.stopPropagation())})}),document.querySelectorAll(".action-cms_clone_aggregate").forEach(e=>{e.addEventListener("click",()=>{confirm(p.confirmDuplicate)||(event.preventDefault(),event.stopPropagation())})}),document.body.classList.contains("edit-page")&&(f.default(),document.addEventListener("iframeReady",()=>{let e=document.getElementById("page-frame");if(null!==e&&!e.classList.contains("size-AutoWidth")){let t=e.contentDocument.body.clientWidth,n=e.clientWidth,r=n-t;r>0&&e.setAttribute("width",n+r+"px")}}),document.addEventListener("openAjax",e=>{let t=e.detail.url+"?ajax=1";r.get(t).then((function(e){u.default(e.data)})).catch((function(e){console.log(e)})).finally((function(){}))}),document.addEventListener("openModal",e=>{c.default(e.detail.url)}),document.addEventListener("openTab",e=>{s.default(e.detail.url)}),document.addEventListener("editElement",e=>{let t={url:`/admin/page/edit-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openModal",t,document)}),document.addEventListener("shiftElement",e=>{let t={url:`/admin/page/shift-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}/${e.detail.direction}`};d.default("openAjax",t,document)}),document.addEventListener("deleteElement",e=>{let t={url:`/admin/page/delete-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("addElement",e=>{let t={url:`/admin/page/add-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.parent}`};d.default("openModal",t,document)}),document.addEventListener("createElement",e=>{let t=window.pageData.uuid,n=window.pageData.version,r=e.detail.parent,o={url:`/admin/page/create-element/${e.detail.elementName}/${t}/${n}/${r}`};d.default("openModal",o,document)}),document.addEventListener("disableElement",e=>{let t={url:`/admin/page/disable-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("enableElement",e=>{let t={url:`/admin/page/enable-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("duplicateElement",e=>{let t={url:`/admin/page/duplicate-element/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}`};d.default("openAjax",t,document)}),document.addEventListener("createSection",e=>{let t={url:`/admin/page/create-section/${window.pageData.uuid}/${window.pageData.version}/${e.detail.section}`};d.default("openAjax",t,document)}),document.addEventListener("createColumn",e=>{let t={url:`/admin/page/create-column/${window.pageData.uuid}/${window.pageData.version}/${e.detail.parent}/${e.detail.size}/${e.detail.breakpoint}`};d.default("openAjax",t,document)}),document.addEventListener("resizeColumn",e=>{let t={url:`/admin/page/resize-column/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}/${e.detail.size}/${e.detail.breakpoint}`};d.default("openAjax",t,document)}),document.addEventListener("changePadding",e=>{let t={url:`/admin/page/change-element-padding/${window.pageData.uuid}/${window.pageData.version}/${e.detail.uuid}/${e.detail.padding}`};d.default("openAjax",t,document)}))})},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});t.default=function(e,t,n){$(n).trigger(e,t)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;function o(e){document.body.classList.remove("file-picker-open"),e.classList.remove("d-flex"),e.classList.add("d-none"),e.remove()}t.default=function(e){let t=e.querySelectorAll("[data-file-picker]");t.forEach(t=>{t.addEventListener("click",n=>{n.preventDefault(),e.querySelectorAll(".cms-file-picker").forEach(e=>{e.remove()});let a=t.dataset.filePicker,i=t.dataset.filePickerUpload,l=t.dataset.filePickerMimeTypes;document.body.insertAdjacentHTML("beforeend",'<div class="cms-file-picker d-none flex-column"></div>');let u=document.querySelector(".cms-file-picker");if(null!==u){let e=l?{mimeTypes:l}:null;r.get("/admin/file/picker",{params:e}).then((function(e){document.body.classList.add("file-picker-open"),u.classList.remove("d-none"),u.classList.add("d-flex"),u.innerHTML=e.data,function(e,t,n){e.querySelectorAll("[data-file-path]").forEach(r=>{r.addEventListener("click",a=>{a.preventDefault();let i=r.dataset.thumbPath,l=r.dataset.filePath,u=r.dataset.title,s=r.dataset.mimeType,c=r.dataset.size,d=r.dataset.width,f=r.dataset.height,p=document.getElementById(n+"_file");null!==p&&p.setAttribute("value",l);let m=document.getElementById(n+"_title");null!==m&&m.setAttribute("value",u);let h=document.getElementById(n+"_mimeType");null!==h&&h.setAttribute("value",s);let v=document.getElementById(n+"_size");null!==v&&v.setAttribute("value",c);let g=document.getElementById(n+"_width");null!==g&&g.setAttribute("value",d);let y=document.getElementById(n+"_height");null!==y&&y.setAttribute("value",f);let b=document.getElementById(t);null!==b&&b.removeAttribute("required");let w=document.getElementById("cms-img-"+n+"_file");null!==w&&(w.setAttribute("src",i),w.classList.remove("d-none")),o(e)})});let r=e.querySelector(".cms-file-picker-close");null!==r&&r.addEventListener("click",t=>{t.preventDefault(),o(e)})}(u,i,a)})).catch((function(e){console.log(e)})).finally((function(){}))}})})}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;t.default=function(){document.querySelectorAll(".cms-admin-menu-root").forEach(e=>{let t=e.dataset.uuid,n=e.dataset.version;$(e).sortable({group:"serialization",containerSelector:".cms-admin-menu",handle:".cms-admin-menu-item-move"});let o=document.querySelector('.btn-save-order[data-uuid="'+t+'"]');null!==o&&o.addEventListener("click",o=>{o.preventDefault();let a=$(e).sortable("serialize").get(),i=JSON.stringify(a,null," "),l=`/admin/menu/save-order/${t}/${n}?ajax=1`;r.post(l,i).then((function(){})).catch((function(e){console.log(e)})).finally((function(){window.location.reload()}))})})}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;function o(){let e=document.querySelector("#page-tree .cms_tree");null!==e&&e.classList.remove("valid-target-tree"),document.querySelectorAll("#page-tree .cms_tree-node-item").forEach(e=>{e.classList.remove("valid-target")})}t.default=function(e,t){let n="/admin/api/page-tree/"+e+"/"+t;r.get(n).then((function(e){let t=document.getElementById("page-tree");null!==t&&(t.innerHTML=e.data),function(){let e=document.querySelector(".btn-tree-save"),t=$("#page-tree > .cms_tree").sortable({group:"serialization",containerSelector:".cms_tree",nested:!0,itemSelector:".cms_tree-node",placeholder:'<div class="placeholder"><i class="fas fa-arrow-right"></i></div>',isValidTarget:function(e,t){return t.el[0].classList.contains("valid-target-tree")},onCancel:function(e,t,n){o()},onDrop:function(t,n,r){o(),null!==e&&e.parentElement.classList.remove("d-none")},onDragStart:function(t,n,r){let o=t[0],a=o.dataset.elementName;if("Section"===a)return!1;null!==e&&e.parentElement.classList.remove("d-none"),document.querySelectorAll("#page-tree .cms_tree").forEach(e=>{let t=$.contains(o,e),n="children"in e.dataset?e.dataset.children:"";if(!1!==t||"all"!==n&&-1===n.split(",").indexOf(a)){e.classList.remove("valid-target-tree"),e.parentElement.querySelectorAll(".cms_tree-node-item").forEach(e=>{e.classList.remove("valid-target")})}else{e.classList.add("valid-target-tree"),e.parentElement.querySelectorAll(".cms_tree-node-item").forEach(e=>{e.classList.add("valid-target")})}})},afterMove:()=>{null!==e&&e.parentElement.classList.remove("d-none")}});null!==e&&e.addEventListener("click",e=>{e.preventDefault();let n=document.getElementById("tree-pageUuid").value,o=document.getElementById("tree-onVersion").value,a=t.sortable("serialize").get(),i=`/admin/page/save-order/${n}/${o}?ajax=1`;r.post(i,a).then((function(e){})).catch((function(e){})).finally((function(){window.location.reload()}))}),document.querySelectorAll(".cms_tree-node-item").forEach(e=>{let t=`[data-uuid="${e.parentElement.dataset.uuid}"]`,n=document.getElementById("page-frame").contentDocument.querySelector(t);e.addEventListener("mouseenter",()=>{n.classList.add("editor-highlight")}),e.addEventListener("mouseleave",()=>{n.classList.remove("editor-highlight")})})}()})).catch((function(e){console.log(e)})).finally((function(){}))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default;function o(e,t){document.querySelectorAll(e).forEach(e=>{e.addEventListener("click",n=>{n.preventDefault();let r=new CustomEvent(t,{detail:{url:e.getAttribute("href")}});document.dispatchEvent(r)})})}t.default=function(e,t){let n="/admin/api/page-info/"+e+"/"+t;r.get(n).then((function(e){window.pageData=e.data;let t=document.getElementById("admin-bar");null!==t&&(t.innerHTML=e.data.html),function(){o("[data-target=modal]","openModal"),o("[data-target=parent]","openModal"),o("[data-target=ajax]","openAjax"),o("[data-target=tab]","openTab");let e=document.querySelector(".toggle-tree");null!==e&&e.addEventListener("click",t=>{t.preventDefault(),e.classList.contains("active")?e.classList.remove("active"):e.classList.add("active");let n=document.getElementById("page-tree");null!==n&&(n.classList.contains("hidden")?n.classList.remove("hidden"):n.classList.add("hidden"))});let t=document.querySelector(".toggle-editor");null!==t&&t.addEventListener("click",e=>{e.preventDefault(),t.classList.contains("active")?t.classList.remove("active"):t.classList.add("active");let n=document.getElementById("page-frame");if(null!==n){let e=n.contentDocument.body;e.classList.contains("hide-editor")?e.classList.remove("hide-editor"):e.classList.add("hide-editor")}});let n=document.querySelector(".toggle-contrast");null!==n&&n.addEventListener("click",e=>{e.preventDefault(),n.classList.contains("active")?n.classList.remove("active"):n.classList.add("active");let t=document.getElementById("page-frame");if(null!==t){let e=t.contentDocument.body;e.classList.contains("editor-dark")?e.classList.remove("editor-dark"):e.classList.add("editor-dark")}});let r=document.querySelector(".main-sidebar"),a=document.querySelector(".edit-page > .wrapper > .content-wrapper"),i=document.querySelector(".btn-maximize-editor"),l=document.querySelector(".btn-minimize-editor");null!==a&&null!==r&&null!==i&&null!==l&&(i.addEventListener("click",e=>{e.preventDefault(),i.classList.add("d-none"),l.classList.remove("d-none"),r.classList.add("d-none"),a.classList.add("p-0")}),l.addEventListener("click",e=>{e.preventDefault(),l.classList.add("d-none"),i.classList.remove("d-none"),r.classList.remove("d-none"),a.classList.remove("p-0")}))}()})).catch((function(e){console.log(e)})).finally((function(){}))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(27),a=n(34),i=n(30),l=n(28),u=n(29);t.default=function(e){let t=document.querySelector(".page-tabs-tab-settings");null!==t&&(t.innerHTML="",e+="?ajax=1",r.get(e).then((function(n){let r=n.data,s=(new DOMParser).parseFromString(r,"text/html").querySelector(".content-wrapper .content");null!==s&&(t.insertAdjacentElement("beforeend",s),function e(t){let n=document.querySelector(".page-tabs-tab-editor"),r=document.querySelector(".page-tabs-tab-settings");u.default(r);let s=r.querySelector("form"),c=s.getAttribute("name")?'form[name="'+s.getAttribute("name")+'"]':"#main form";s.setAttribute("action",t),o.default(c,()=>{e(t)}),a.default(s,l.default,(o,a)=>{if(a&&o.success)r.classList.remove("active"),n.classList.add("active"),i.default(o);else{let n=o,r=(new DOMParser).parseFromString(n,"text/html").querySelector(c);null!==r&&(s.parentNode.replaceChild(r,s),e(t))}});let d=r.querySelector(".content-header .global-actions");if(null!==d){if(null===d.querySelector(".btn-close-tab")){d.insertAdjacentHTML("beforeend",'<button class="btn btn-sm btn-close-tab"><span class="fa fa-times"></span></button>');let e=d.querySelector(".btn-close-tab");null!==e&&e.addEventListener("click",e=>{e.preventDefault(),r.innerHTML="",r.classList.remove("active"),n.classList.add("active")})}}n.classList.remove("active"),r.classList.add("active")}(e))})).catch((function(e){console.log(e)})).finally((function(){})))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});const r=n(1).default,o=n(27),a=n(34),i=n(30),l=n(28),u=n(29);t.default=function(e){let t=document.querySelector("#editor-modal .modal-body");null!==t&&(t.innerHTML="",e+="?ajax=1",r.get(e).then((function(n){let r=n.data,s=(new DOMParser).parseFromString(r,"text/html").querySelector(".content-wrapper .content");null!==s&&(t.insertAdjacentElement("beforeend",s),function e(t){let n=document.getElementById("editor-modal");if(null===n)return;u.default(n);let r=n.querySelector(".content-wrapper");null!==r&&r.classList.remove("content-wrapper");let s=n.querySelector("form");if(null!==s){let r="#main form";s.name&&(r='form[name="'+s.name+'"]'),s.action=t,o.default(r,()=>{e(t)}),a.default(s,l.default,(o,a)=>{if(a&&o.success)$(n).modal("hide"),i.default(o);else{let n=o,a=(new DOMParser).parseFromString(n,"text/html").querySelector(r);null!==a&&(s.parentNode.replaceChild(a,s),e(t))}})}let c=document.getElementById("editor-modal-title"),d=n.querySelector(".content-header .title");null!==d&&(c.innerHTML=d.innerHTML);let f=n.querySelector(".content-header");null!==f&&f.remove();let p=n.querySelector(".modal-nav-content"),m=n.querySelector(".modal-nav");null!==m&&null!==p?(m.innerHTML=p.innerHTML,p.remove()):null!==m&&(m.innerHTML=""),$(n).modal("show")}(e))})).catch((function(e){console.log(e)})).finally((function(){})))}}]);