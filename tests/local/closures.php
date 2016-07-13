<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$closure = c::serializeClosure(function (string $string) {
    echo "\n".$string."\n";
});
echo $closure."\n\n";
c::dump($closure = c::unserializeClosure($closure));
$closure('It works!');
