namespace Hash {
  export class App {
    // Properties.

    protected cache: { [ key: string ]: any } = {};
    protected resourceUrls = {
      $: 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js',
      _: 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js',

      codeFonts: 'https://cloud.typography.com/7715196/6490572/css/fonts.css',

      hljs: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js',
      hljsScssLang: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/scss.min.js',
      hljsTsLang: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/typescript.min.js',
      hljsTheme: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css',
    };

    // Constructor.

    public constructor() {
      chopChop.preloadThenLoad(window.$ ? [] : [ this.resourceUrls.$ ]).then(() => {
        $(document).ready(this.onDomjQueryReady.bind(this));
      });
    }

    // Ready handlers.

    protected onDomjQueryReady() {
      if (this.hasCode()) {
        chopChop.preloadThenLoad([ this.resourceUrls.codeFonts ]);
      }
      if (this.hasPreCode()) {
        chopChop.preloadThenLoad([
          this.resourceUrls.hljs,
          this.resourceUrls.hljsScssLang,
          this.resourceUrls.hljsTsLang,
          this.resourceUrls.hljsTheme,
        ]).then(this.setupHljs.bind(this));
      }
      chopChop.preloadThenLoad(window._ ? [] : [ this.resourceUrls._ ]).then(this.onReady.bind(this));
    }

    protected onReady() {
      if (typeof window.onReady === 'function') {
        onReady(); // App ready handler.
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
          hljs.highlightBlock(code);
        }
      });
    }

    // Detection utilities.

    protected hasCode(): boolean {
      if (typeof this.cache.hasCode !== undefined)
        return this.cache.hasCode;
      return this.cache.hasCode = $('code').length > 0;
    }

    protected hasPreCode(): boolean {
      if (typeof this.cache.hasPreCode !== undefined)
        return this.cache.hasPreCode;
      return this.cache.hasPreCode = $('pre > code').length > 0;
    }
  }
  window.app = new App();
}
