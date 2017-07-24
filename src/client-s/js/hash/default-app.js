"use strict";
var Hash;
(function (Hash) {
    var App = (function () {
        // Constructor.
        function App() {
            this.styleResourcesToLoad = [];
            this.scriptResourcesToLoad = [];
            this.stylesToLoadAsync = [];
            this.scriptsToLoadAsync = [];
            this.styleResourcesLoading = 0;
            this.scriptResourcesLoading = 0;
            this.styleResourcesReady = false;
            this.scriptResourcesReady = false;
            this.resourcesReady = false;
            this.domContentLoaded = false;
            this.scriptResourcesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
            this.scriptResourcesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js');
            if (this.hasCode()) {
                this.styleResourcesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css');
                this.stylesToLoadAsync.push('https://cloud.typography.com/7715196/6490572/css/fonts.css');
                this.scriptResourcesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js');
                this.scriptResourcesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/typescript.min.js');
                this.scriptResourcesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/scss.min.js');
            }
            this.styleResourcesLoading = this.styleResourcesToLoad.length;
            this.scriptResourcesLoading = this.scriptResourcesToLoad.length;
            document.addEventListener('DOMContentLoaded', this.onDomContentLoaded.bind(this));
            this.loadStyles(), this.loadScripts();
        }
        // Ready handler.
        App.prototype.onDomResourcesReady = function () {
            if (this.hasCode())
                this.setupHljs();
            if (typeof window.onReady === 'function') {
                onReady(); // App ready handler.
            }
        };
        // Setup routines.
        App.prototype.setupHljs = function () {
            var ignore = '.\\!\\~hljs, .no-hljs, .no-highlight, .nohighlight';
            var langNone = '.lang-none, .lang-plain, .lang-text, .lang-txt, .none, .plain, .text, .txt';
            $('pre > code').not(ignore).each(function (i, code) {
                var $code = $(code);
                var $pre = $code.parent();
                $pre.addClass('hljs-pre code');
                if ($code.is(langNone)) {
                    $code.addClass('hljs lang-none');
                }
                else {
                    hljs.highlightBlock(code);
                }
            });
        };
        // Load utilities.
        App.prototype.loadStyle = function (url) {
            this.supportsPreload() ? this.preloadStyle(url) : this.loadStyleNow(url);
        };
        App.prototype.loadScript = function (url) {
            this.supportsPreload() ? this.preloadScript(url) : this.loadScriptNow(url);
        };
        // Detection tools.
        App.prototype.hasCode = function () {
            if (this._hasCode !== undefined)
                return this._hasCode;
            return this._hasCode = document.querySelector('code') ? true : false;
        };
        App.prototype.supportsPreload = function () {
            if (this._supportsPreload !== undefined)
                return this._supportsPreload;
            var link = document.createElement('link');
            return this._supportsPreload = link.relList && link.relList.supports && link.relList.supports('preload');
        };
        // Style loaders.
        App.prototype.loadStyles = function () {
            var _this = this;
            if (this.styleResourcesToLoad.length > 0) {
                this.styleResourcesToLoad.forEach(function (url) {
                    _this.loadStyle(url);
                });
            }
            else {
                this.onStyleResourcesReady();
            }
        };
        App.prototype.preloadStyle = function (url) {
            var _this = this;
            var l = document.createElement('link');
            l.rel = 'preload', l.as = 'style', l.href = url, l.onload = function () { return _this.loadStyleNow(url); };
            document.querySelector('head').appendChild(l);
        };
        App.prototype.loadStyleNow = function (url, async) {
            var _this = this;
            if (async === void 0) { async = false; }
            var s = document.createElement('link');
            s.href = url, s.rel = 'stylesheet', s.onload = function () { return _this.onStyleLoaded(url); };
            document.querySelector('head').appendChild(s);
        };
        App.prototype.onStyleLoaded = function (url) {
            if (this.styleIsResource(url)) {
                this.styleResourcesLoading--;
                if (this.styleResourcesLoading <= 0) {
                    this.onStyleResourcesReady();
                }
            }
        };
        App.prototype.styleIsResource = function (url) {
            return this.styleResourcesToLoad.indexOf(url) !== -1;
        };
        // Script load handlers.
        App.prototype.loadScripts = function () {
            var _this = this;
            if (this.scriptResourcesToLoad.length > 0) {
                this.scriptResourcesToLoad.forEach(function (url) {
                    _this.loadScript(url);
                });
            }
            else {
                this.onScriptResourcesReady();
            }
        };
        App.prototype.preloadScript = function (url) {
            var _this = this;
            var l = document.createElement('link');
            l.rel = 'preload', l.as = 'script', l.href = url, l.onload = function () { return _this.loadScriptNow(url); };
            document.querySelector('head').appendChild(l);
        };
        App.prototype.loadScriptNow = function (url, async) {
            var _this = this;
            if (async === void 0) { async = false; }
            var s = document.createElement('script');
            s.async = async, s.src = url, s.onload = function () { return _this.onScriptLoaded(url); };
            document.querySelector('head').appendChild(s);
        };
        App.prototype.onScriptLoaded = function (url) {
            if (this.scriptIsResource(url)) {
                this.scriptResourcesLoading--;
                if (this.scriptResourcesLoading <= 0) {
                    this.onScriptResourcesReady();
                }
            }
        };
        App.prototype.scriptIsResource = function (url) {
            return this.scriptResourcesToLoad.indexOf(url) !== -1;
        };
        // Resource ready handlers.
        App.prototype.onStyleResourcesReady = function () {
            var _this = this;
            this.styleResourcesReady = true;
            if (this.scriptResourcesReady)
                this.onResourcesReady();
            this.stylesToLoadAsync.forEach(function (url) {
                _this.loadStyleNow(url, true);
            });
        };
        App.prototype.onScriptResourcesReady = function () {
            var _this = this;
            this.scriptResourcesReady = true;
            if (this.styleResourcesReady)
                this.onResourcesReady();
            this.scriptsToLoadAsync.forEach(function (url) {
                _this.loadScriptNow(url, true);
            });
        };
        // Other ready handlers.
        App.prototype.onResourcesReady = function () {
            if (this.resourcesReady)
                return;
            this.resourcesReady = true;
            if (this.domContentLoaded) {
                this.onDomResourcesReady();
            }
        };
        App.prototype.onDomContentLoaded = function () {
            if (this.domContentLoaded)
                return;
            this.domContentLoaded = true;
            if (this.resourcesReady) {
                this.onDomResourcesReady();
            }
        };
        return App;
    }());
    Hash.App = App;
    window.app = new App();
})(Hash || (Hash = {}));
