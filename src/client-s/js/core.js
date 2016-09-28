(function ($) {
  /*
   * Mixins.
   */
  var crc32 = Hashes.CRC32;
  var md5 = new Hashes.MD5();
  var base64 = new Hashes.Base64();

  var sha1 = new Hashes.SHA1();
  var sha256 = new Hashes.SHA256();
  var sha512 = new Hashes.SHA512();
  var rmd160 = new Hashes.RMD160();

  _.mixin({
    crc32: function (str) {
      return crc32(str);
    },
    md5: function (str) {
      return md5.hex(str);
    },
    base64: function (str) {
      return base64.hex(str);
    },

    sha1: function (str) {
      return sha1.hex(str);
    },
    sha256: function (str) {
      return sha256.hex(str);
    },
    sha512: function (str) {
      return sha512.hex(str);
    },
    rmd160: function (str) {
      return rmd160.hex(str);
    },

    trim: function (str, chars) {
      return _.lTrim(_.rTrim(str, chars), chars);
    },
    lTrim: function (str, chars) {
      return str.replace(new RegExp('^[' + (chars || '\\s') + ']+', 'g'), '');
    },
    rTrim: function (str, chars) {
      return str.replace(new RegExp('[' + (chars || '\\s') + ']+$', 'g'), '');
    },

    sha1Mod: function (str, divisor, isSha) {
      var sha1 = isSha ? str : _.sha1(str);
      var hex15 = '0x' + sha1.substr(0, 15);
      return new BigNumber(hex15).mod(Math.max(1, divisor)).toString(10);
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
