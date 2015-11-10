<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Dicer\Core as Di;

error_reporting(-1);
ini_set('display_errors', 'yes');

require_once dirname(dirname(__FILE__)).'/src/includes/stub.php';

$Di         = new Di();
$PwStrength = $Di->get(Classes\PwStrength::class);

echo $PwStrength('0aA!');
