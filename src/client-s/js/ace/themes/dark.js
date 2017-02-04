ace.define('ace/theme/dark', ['require', 'exports', 'module', 'ace/lib/dom'], function (require, exports, module) {
  exports.isDark = true;
  exports.cssClass = 'ace-dark-theme';

  jQuery.get('http://express.js/src/client-s/js/ace/themes/dark.min.css', function (css) {
    exports.cssText = css;
    var dom = require('../lib/dom');
    dom.importCssString(exports.cssText, exports.cssClass);
  });
});
