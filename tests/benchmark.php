<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as a;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

$app = a::app();

a::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    (new \ReflectionClass($app))->getFileName();
}
a::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

a::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    new \ReflectionClass($app);
}
a::benchPrint();
