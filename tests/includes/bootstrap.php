<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__, 3).'/src/includes/stub.php';

$App = new \WebSharks\Core\Classes\App([
    'debug'    => true,
    'fs_paths' => [
        'config_file' => dirname(__FILE__, 3).'/src/includes/.~assets/config.json',
    ],
]);
