<?php
namespace WebSharks\Core;

use WebSharks\Core\Classes as Classes;
use WebSharks\Dicer\Core as Dicer;

ini_set('error_reporting', -1);
ini_set('display_errors', true);

require_once dirname(dirname(__FILE__)).'/src/includes/stub.php';

$Di           = new Dicer();
$ReplaceCodes = $Di->get(Classes\ReplaceCodes::class);

$vars = [
    'hello' => 'hi!',
    'world.abc' => 'earth :-)',
];

$string = $ReplaceCodes('%%hello%% %%/^w**$%%', $vars);
echo $string;
