const w: Window = window;
let cc: any = w.chopChop, $: any, _: any;

namespace Hash {
  export class App {
    // Properties.

    protected urls = {
      jQuery: 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js',
      lodash: 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js',

      codeFonts: 'https://cloud.typography.com/7715196/6490572/css/fonts.css',

      hljs: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js',
      hljsScssLang: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/scss.min.js',
      hljsTsLang: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/typescript.min.js',
      hljsTheme: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css',
    };
    protected cache: { [ key: string ]: any } = {};

    // Constructor.

    public constructor() {
      let jQueryExists = typeof w.jQuery !== 'undefined';
      cc.promise(jQueryExists ? '' : this.urls.jQuery).then(() => {
        $ = w.jQuery; // Update local reference.
        $(document).ready(this.onDomjQueryReady.bind(this));
      });
    }

    // Ready handlers.

    protected onDomjQueryReady() {
      if (this.hasCode()) {
        cc.promise(this.urls.codeFonts);
      }
      if (this.hasPreCode()) {
        cc.promise([
          this.urls.hljs,
          this.urls.hljsScssLang,
          this.urls.hljsTsLang,
          this.urls.hljsTheme,
        ]).then(this.setupHljs.bind(this));
      }
      let lodashExists = typeof w._ !== 'undefined' || typeof w.lodash !== 'undefined';
      cc.promise(lodashExists ? '' : this.urls.lodash).then(() => {
        _ = w._ || w.lodash; // Update local reference.
        this.onReady(); // Call ready handler now.
      });
    }

    protected onReady() {
      if (typeof w.onReady === 'function') {
        w.onReady(); // App ready handler.
      }
    }

    // Setup routines.

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
          w.hljs.highlightBlock(code);
        }
      });
    }

    // Detection utilities.

    protected hasCode(): boolean {
      if (typeof this.cache.hasCode !== 'undefined')
        return this.cache.hasCode;
      return this.cache.hasCode = $('code').length > 0;
    }

    protected hasPreCode(): boolean {
      if (typeof this.cache.hasPreCode !== 'undefined')
        return this.cache.hasPreCode;
      return this.cache.hasPreCode = $('pre > code').length > 0;
    }
  }
  w.app = new App();
}
