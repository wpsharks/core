(function ($) {
  /*
   * Mixins.
   */
  var md5 = new Hashes.MD5();
  var base64 = new Hashes.Base64();

  var sha1 = new Hashes.SHA1();
  var sha256 = new Hashes.SHA256();
  var sha512 = new Hashes.SHA512();
  var rmd160 = new Hashes.RMD160();

  _.mixin({
    crc32: function (str) {
      return Hashes.CRC32(String(str));
    },
    md5: function (str) {
      return md5.hex(String(str));
    },
    base64: function (str) {
      return base64.hex(String(str));
    },

    sha1: function (str) {
      return sha1.hex(String(str));
    },
    sha256: function (str) {
      return sha256.hex(String(str));
    },
    sha512: function (str) {
      return sha512.hex(String(str));
    },
    rmd160: function (str) {
      return rmd160.hex(String(str));
    },

    trim: function (str, chars) {
      return _.lTrim(_.rTrim(str, chars), chars);
    },
    lTrim: function (str, chars) {
      return String(str).replace(new RegExp('^[' + (chars || '\\s') + ']+', 'g'), '');
    },
    rTrim: function (str, chars) {
      return String(str).replace(new RegExp('[' + (chars || '\\s') + ']+$', 'g'), '');
    },

    hexDec: function (hex) {
      return parseInt(String(str).replace(/[^a-f0-9]/i, ''), 16);
    },
    sha1Mod: function (str, divisor, isSha) {
      return _.hexDec(String(isSha ? str : _.sha1(str)).substr(0, 15)) % Math.max(1, Number(divisor));
    }
  });

  /*
   * On DOM ready handler.
   */
  $(document).ready(function () {
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
