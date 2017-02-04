ace.define('ace/theme/light', ['require', 'exports', 'module', 'ace/lib/dom'], function (require, exports, module) {
  exports.isDark = false;
  exports.cssClass = 'ace-light-theme';

  jQuery.get('http://express.js/src/client-s/js/ace/themes/light.min.css', function (css) {
    exports.cssText = css;
    var dom = require('../lib/dom');
    dom.importCssString(exports.cssText, exports.cssClass);
  });
});
