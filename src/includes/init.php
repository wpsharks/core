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
if (!defined(__NAMESPACE__.'\\DEBUG_TEST_CONFIG')) {
    define(__NAMESPACE__.'\\DEBUG_TEST_CONFIG', false);
}
if (!defined(__NAMESPACE__.'\\ASSETS_DIR')) {
    define(__NAMESPACE__.'\\ASSETS_DIR', dirname(__FILE__, 3).'/assets');
}
$GLOBALS[__NAMESPACE__] = new \WebSharks\Core\Classes\App([
    'debug'             => DEBUG,
    'debug_test_config' => DEBUG_TEST_CONFIG,
    'assets_dir'        => ASSETS_DIR,
]);
