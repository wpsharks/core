<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\App;

require_once dirname(__FILE__, 3).'/src/includes/stub.php';

$App = new App([], [
    '©debug'    => ['©enable' => true],
    '©fs_paths' => [ // Use config. file.
        '©config_file' => dirname(__FILE__, 3).'/assets/.~config.json',
    ],
]);
