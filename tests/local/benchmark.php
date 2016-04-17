<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

$app = c::app();
$ref = new \ReflectionClass($app);

c::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    mb_strpos('hello', 'h');
}
c::benchPrint();

c::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    $ref->getFileName();
}
c::benchPrint();

c::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    $ref->getName();
}
c::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    new \ReflectionClass($app);
}
c::benchPrint();
