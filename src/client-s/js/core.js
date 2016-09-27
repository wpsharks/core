(function ($) {
  $(document).ready(function () {
    /*
     * Hash mixins.
     */
    _.mixin({
      crc32: Hashes.CRC32,
      md5: new Hashes.MD5(),
      base64: new Hashes.Base64(),

      sha1: new Hashes.SHA1(),
      sha256: new Hashes.SHA256(),
      sha512: new Hashes.SHA512(),
      rmd160: new Hashes.RMD160()
    });

    /*
     * Syntax highlighting via Highlight.js.
     */
    $('pre > code').not('.no-highlight, .nohighlight').each(function () {
      var $this = $(this);

      if ($this.is('.plain, .text, .txt')) {
        $this.addClass('hljs');
      } else {
        hljs.highlightBlock(this);
      }
    });
  });
})(jQuery);
