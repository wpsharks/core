(function () {

  /*
   * On DOM ready handler.
   */
  $(document).ready(function () {

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
