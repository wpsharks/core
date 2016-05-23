<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\App;

require_once dirname(__FILE__, 3).'/src/includes/stub.php';

$App = new App([], [
    '©debug' => [
        '©enable'        => true,
        '©log'           => true,
        '©er_enable'     => true,
        '©er_display'    => true,
        '©er_assertions' => true,
    ],
    '©fs_paths' => [ // Use config. file.
        '©logs_dir'    => dirname(__FILE__, 2).'/local/.~logs',
        '©cache_dir'   => dirname(__FILE__, 2).'/local/.~cache',
        '©config_file' => dirname(__FILE__, 3).'/assets/.~config.json',
    ],
]);
