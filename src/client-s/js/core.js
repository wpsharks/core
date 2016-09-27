(function ($) {
  $(document).ready(function () {
    /*
     * Hash globals.
     */
    window.crc32 = crc32 || Hashes.CRC32;
    window.md5 = md5 || new Hashes.MD5();
    window.base64 = base64 || new Hashes.Base64();

    window.sha1 = sha1 || new Hashes.SHA1();
    window.sha256 = sha256 || new Hashes.SHA256();
    window.sha512 = sha512 || new Hashes.SHA512();
    window.rmd160 = rmd160 || new Hashes.RMD160();

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
