<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

$app = c::app();

c::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    (new \ReflectionClass($app))->getFileName();
}
c::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();

for ($i = 0; $i < 50000; ++$i) {
    new \ReflectionClass($app);
}
c::benchPrint();
