(function () {
  $(document).ready(function () {

    /*
     * Async font loading.
     */
    $('head').append('<link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i" />');

    /*
     * Syntax highlighting via Highlight.js.
     */
    $('pre > code').not('.no-hljs, .no-highlight, .nohighlight').each(function () {
      var $this = $(this);

      if ($this.is('.lang-none, .lang-plain, .lang-text, .lang-txt, .none, .plain, .text, .txt')) {
        $this.addClass('hljs lang-none');
      } else {
        hljs.highlightBlock(this);
      }
    });

  });
})();
