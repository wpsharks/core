namespace Hash {
  export class App {
    // Properties.

    protected _hasCode?: boolean;
    protected _supportsPreload?: boolean;

    protected stylesToLoad: string[] = [];
    protected scriptsToLoad: string[] = [];

    protected stylesToLoadAsync: string[] = [];
    protected scriptsToLoadAsync: string[] = [];

    protected stylesLoading = 0;
    protected scriptsLoading = 0;

    protected styleResourcesReady = false;
    protected scriptResourcesReady = false;

    protected resourcesReady = false;
    protected domContentLoaded = false;

    // Constructor.

    public constructor() {
      this.scriptsToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
      this.scriptsToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js');

      if (this.hasCode()) {
        this.stylesToLoadAsync.push('https://cloud.typography.com/7715196/6490572/css/fonts.css');
        this.stylesToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/codepen-embed.min.css');

        this.scriptsToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js');
        this.scriptsToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/scss.min.js');
        this.scriptsToLoad.push('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/typescript.min.js');
      }
      this.stylesLoading = this.stylesToLoad.length, this.scriptsLoading = this.scriptsToLoad.length;
      document.addEventListener('DOMContentLoaded', this.onDomContentLoaded.bind(this));
      this.loadStyles(), this.loadScripts();
    }

    // Ready handler.

    protected onDomResourcesReady() {
      if (this.hasCode()) this.setupHljs();

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

    // Load utilities.

    public loadStyle(url: string) {
      this.supportsPreload() ? this.preloadStyle(url) : this.loadStyleNow(url);
    }

    public loadScript(url: string) {
      this.supportsPreload() ? this.preloadScript(url) : this.loadScriptNow(url);
    }

    // Detection tools.

    public hasCode(): boolean {
      if (this._hasCode !== undefined)
        return this._hasCode;

      return this._hasCode = document.querySelector('code') ? true : false;
    }

    public supportsPreload(): boolean {
      if (this._supportsPreload !== undefined)
        return <boolean>this._supportsPreload;

      let link = <HTMLElement | any>document.createElement('link');
      return this._supportsPreload = link.relList && link.relList.supports && link.relList.supports('preload');
    }

    // Style loaders.

    protected loadStyles() {
      if (this.stylesToLoad.length > 0) {
        this.stylesToLoad.forEach((url) => {
          this.loadStyle(url);
        });
      } else {
        this.onStyleResourcesReady();
      }
    }

    protected preloadStyle(url: string) {
      let l = <HTMLElement | any>document.createElement('link');
      l.rel = 'preload', l.as = 'style', l.href = url, l.onload = () => this.loadStyleNow(url);
      (<HTMLElement>document.querySelector('head')).appendChild(l);
    }

    protected loadStyleNow(url: string, async: boolean = false) {
      let s = document.createElement('link');
      s.href = url, s.rel = 'stylesheet', s.onload = () => this.onStyleLoaded(url);
      (<HTMLElement>document.querySelector('head')).appendChild(s);
    }

    protected onStyleLoaded(url: string) {
      if (this.styleIsResource(url)) {
        this.stylesLoading--;
        if (this.stylesLoading <= 0) {
          this.onStyleResourcesReady();
        }
      }
    }

    protected styleIsResource(url: string) {
      return this.stylesToLoad.indexOf(url) !== -1;
    }

    // Script load handlers.

    protected loadScripts() {
      if (this.scriptsToLoad.length > 0) {
        this.scriptsToLoad.forEach((url) => {
          this.loadScript(url);
        });
      } else {
        this.onScriptResourcesReady();
      }
    }

    protected preloadScript(url: string) {
      let l = <HTMLElement | any>document.createElement('link');
      l.rel = 'preload', l.as = 'script', l.href = url, l.onload = () => this.loadScriptNow(url);
      (<HTMLElement>document.querySelector('head')).appendChild(l);
    }

    protected loadScriptNow(url: string, async: boolean = false) {
      let s = document.createElement('script');
      s.async = async, s.src = url, s.onload = () => this.onScriptLoaded(url);
      (<HTMLElement>document.querySelector('head')).appendChild(s);
    }

    protected onScriptPreloaded(url: string) {
      if (this.scriptIsResource(url)) {
        this.scriptsLoading--;
        if (this.scriptsLoading <= 0) {
          this.onScriptResourcesReady();
        }
      }
    }

    protected onScriptLoaded(url: string) {
      if (this.scriptIsResource(url)) {
        this.scriptsLoading--;
        if (this.scriptsLoading <= 0) {
          this.onScriptResourcesReady();
        }
      }
    }

    protected scriptIsResource(url: string) {
      return this.scriptsToLoad.indexOf(url) !== -1;
    }

    // Resource ready handlers.

    protected onStyleResourcesReady() {
      this.styleResourcesReady = true;

      if (this.scriptResourcesReady)
        this.onResourcesReady();

      this.stylesToLoadAsync.forEach((url) => {
        this.loadStyleNow(url, true);
      });
    }

    protected onScriptResourcesReady() {
      this.scriptResourcesReady = true;

      if (this.styleResourcesReady)
        this.onResourcesReady();

      this.scriptsToLoadAsync.forEach((url) => {
        this.loadScriptNow(url, true);
      });
    }

    // Other ready handlers.

    protected onResourcesReady() {
      if (this.resourcesReady) return;
      this.resourcesReady = true;

      if (this.domContentLoaded) {
        this.onDomResourcesReady();
      }
    }

    protected onDomContentLoaded() {
      if (this.domContentLoaded) return;
      this.domContentLoaded = true;

      if (this.resourcesReady) {
        this.onDomResourcesReady();
      }
    }
  }
  window.app = new App();
}
