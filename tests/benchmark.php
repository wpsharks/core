<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as a;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

a::benchStart();

for ($i = 0; $i < 5000; ++$i) {
    stripos($string, 'a');
}
a::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

a::benchStart();

for ($i = 0; $i < 5000; ++$i) {
    mb_stripos($string, 'a');
}
a::benchPrint();
