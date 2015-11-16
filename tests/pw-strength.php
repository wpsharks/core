<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Dicer\Di;
use WebSharks\Core\Classes;

error_reporting(-1);
ini_set('display_errors', 'yes');

require_once dirname(__FILE__, 2).'/src/includes/stub.php';

$Di         = new Di();
$PwStrength = $Di->get(Classes\PwStrength::class);

echo $PwStrength('0aA!');
