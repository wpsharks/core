namespace Hash {
  let $: any;

  export class Scripts {
    jQueryScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js';
    lodashScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js';

    hljsScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js';
    hljsStyleUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css';

    hljsWpLangScriptUrl = 'https://cdn.rawgit.com/websharks/core/170421.57490/src/client-s/js/hljs/langs/wp.min.js';
    hljsTypeScriptLangScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/typescript.min.js';
    hljsScssLangScriptUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/scss.min.js';

    constructor() {
      this.loadStyles();
      this.loadScripts();
    }

    protected loadStyles() {
      this.loadStyle(this.hljsStyleUrl);
    }

    protected loadScripts() {
      this.loadScript(this.jQueryScriptUrl, () => {
        this.loadScript(this.lodashScriptUrl, () => {
          this.loadScript(this.hljsScriptUrl, () => {
            this.loadScript(this.hljsWpLangScriptUrl, () => {
              this.loadScript(this.hljsTypeScriptLangScriptUrl, () => {
                this.loadScript(this.hljsScssLangScriptUrl, () => {
                  this.onScriptsReady();
                });
              });
            });
          });
        });
      });
    }

    protected onScriptsReady() {
      $ = jQuery;

      if ($.isReady) {
        this.onDomReady();
      } else {
        $(document).on('ready', this.onDomReady.bind(this));
      }
    }

    protected onDomReady() {
      this.setupHljs();

      if (typeof window.onReady === 'function')
        onReady(); // App ready handler.
    }

    protected setupHljs() {
      let ignore = '.\\!\\~hljs, .no-hljs, .no-highlight, .nohighlight';
      let langNone = '.lang-none, .lang-plain, .lang-text, .lang-txt, .none, .plain, .text, .txt';

      $('pre > code').not(ignore).each((i: number, code: HTMLElement) => {
        let $code = $(code);
        let $pre = $code.parent();

        $pre.addClass('hljs-pre code');

        if ($code.is(langNone)) {
          $code.addClass('hljs lang-none');
        } else {
          hljs.highlightBlock(code);
        }
      });
    }

    protected loadStyle(url: string) {
      let s = document.createElement('link');
      s.href = url, s.rel = 'stylesheet';
      (<HTMLElement>document.querySelector('head')).appendChild(s);
    }

    protected loadScript(url: string, callback: Function) {
      let s = document.createElement('script');
      s.async = true, s.src = url, s.onload = callback.bind(this);
      (<HTMLElement>document.querySelector('head')).appendChild(s);
    }
  }
  new Scripts();
}
