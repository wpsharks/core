"use strict";
var Hash;
(function (Hash) {
    var $;
    var Scripts = (function () {
        function Scripts() {
            this.jQueryScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js';
            this.lodashScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js';
            this.hljsScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js';
            this.hljsStyleUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css';
            this.hljsWpLangScriptUrl = 'https://cdn.rawgit.com/websharks/core/170421.57490/src/client-s/js/hljs/langs/wp.min.js';
            this.loadStyles();
            this.loadScripts();
        }
        Scripts.prototype.loadStyles = function () {
            this.loadStyle(this.hljsStyleUrl);
        };
        Scripts.prototype.loadScripts = function () {
            var _this = this;
            this.loadScript(this.jQueryScriptUrl, function () {
                _this.loadScript(_this.lodashScriptUrl, function () {
                    _this.loadScript(_this.hljsScriptUrl, function () {
                        _this.loadScript(_this.hljsWpLangScriptUrl, function () {
                            _this.onScriptsReady();
                        });
                    });
                });
            });
        };
        Scripts.prototype.onScriptsReady = function () {
            $ = jQuery;
            if ($.isReady) {
                this.onDomReady();
            }
            else {
                $(document).on('ready', this.onDomReady.bind(this));
            }
        };
        Scripts.prototype.onDomReady = function () {
            this.setupHljs();
            if (typeof window.onReady === 'function')
                onReady(); // App ready handler.
        };
        Scripts.prototype.setupHljs = function () {
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
        Scripts.prototype.loadStyle = function (url) {
            var s = document.createElement('link');
            s.href = url, s.rel = 'stylesheet';
            document.querySelector('head').appendChild(s);
        };
        Scripts.prototype.loadScript = function (url, callback) {
            var s = document.createElement('script');
            s.async = true, s.src = url, s.onload = callback.bind(this);
            document.querySelector('head').appendChild(s);
        };
        return Scripts;
    }());
    Hash.Scripts = Scripts;
    new Scripts();
})(Hash || (Hash = {}));
