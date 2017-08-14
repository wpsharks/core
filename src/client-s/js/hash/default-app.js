"use strict";
var w = window;
var cc = w.chopChop, $, _;
var Hash;
(function (Hash) {
    var App = (function () {
        // Constructor.
        function App() {
            var _this = this;
            // Properties.
            this.cache = {};
            this.urls = {
                jQuery: 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js',
                lodash: 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js',
                codeFonts: 'https://cloud.typography.com/7715196/6490572/css/fonts.css',
                hljs: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js',
                hljsScssLang: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/scss.min.js',
                hljsTsLang: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/typescript.min.js',
                hljsTheme: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css',
            };
            var jQueryExists = typeof w.$ !== 'undefined' || typeof w.jQuery !== 'undefined';
            cc.promise(jQueryExists ? [] : [this.urls.jQuery]).then(function () {
                $ = w.$ || w.jQuery; // Update local reference.
                $(document).ready(_this.onDomjQueryReady.bind(_this));
            });
        }
        // Ready handlers.
        App.prototype.onDomjQueryReady = function () {
            var _this = this;
            if (this.hasCode()) {
                cc.promise([this.urls.codeFonts]);
            }
            if (this.hasPreCode()) {
                cc.promise([
                    this.urls.hljs,
                    this.urls.hljsScssLang,
                    this.urls.hljsTsLang,
                    this.urls.hljsTheme,
                ]).then(this.setupHljs.bind(this));
            }
            var lodashExists = typeof w._ !== 'undefined' || typeof w.lodash !== 'undefined';
            cc.promise(lodashExists ? [] : [this.urls.lodash]).then(function () {
                _ = w._ || w.lodash; // Update local reference.
                _this.onReady(); // Call ready handler now.
            });
        };
        App.prototype.onReady = function () {
            if (typeof w.onReady === 'function') {
                w.onReady(); // App ready handler.
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
                    w.hljs.highlightBlock(code);
                }
            });
        };
        // Detection utilities.
        App.prototype.hasCode = function () {
            if (typeof this.cache.hasCode !== undefined)
                return this.cache.hasCode;
            return this.cache.hasCode = $('code').length > 0;
        };
        App.prototype.hasPreCode = function () {
            if (typeof this.cache.hasPreCode !== undefined)
                return this.cache.hasPreCode;
            return this.cache.hasPreCode = $('pre > code').length > 0;
        };
        return App;
    }());
    Hash.App = App;
    w.app = new App();
})(Hash || (Hash = {}));
