<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\App;

require_once dirname(__FILE__, 3).'/src/includes/stub.php';

ob_start(); // Use local config file as a base.
include dirname(__FILE__, 3).'/assets/.~config.json';
${__FILE__}['instance_base'] = json_decode(ob_get_clean(), true);

$App = new App(${__FILE__}['instance_base'], [
    '©debug' => [
        '©enable'        => true,
        '©edge'          => true,
        '©log'           => true,
        '©er_enable'     => true,
        '©er_display'    => true,
        '©er_assertions' => true,
    ],
    '©fs_paths' => [ // Use config. file.
        '©logs_dir'  => dirname(__FILE__, 2).'/local/.~logs',
        '©sris_dir'  => dirname(__FILE__, 2).'/local/.~sris',
        '©cache_dir' => dirname(__FILE__, 2).'/local/.~cache',
    ],
]);
