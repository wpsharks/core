<?php
/**
 * Initializer.
 *
 * @since 15xxxx Initial release.
 */
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/stub.php';

if (!defined(__NAMESPACE__.'\\DEBUG')) {
    define(__NAMESPACE__.'\\DEBUG', false);
}
if (!defined(__NAMESPACE__.'\\ASSETS_DIR')) {
    define(__NAMESPACE__.'\\ASSETS_DIR', dirname(__FILE__, 3).'/assets');
}
if (!defined(__NAMESPACE__.'\\CONFIG_FILE')) {
    define(__NAMESPACE__.'\\CONFIG_FILE', dirname(__FILE__, 3).'/assets/.~config.json');
}
$GLOBALS[__NAMESPACE__] = new \WebSharks\Core\Classes\App([
    'debug'       => DEBUG,
    'assets_dir'  => ASSETS_DIR,
    'config_file' => CONFIG_FILE,
]);
