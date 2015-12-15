<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Functions as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

c\benchmark_start();

for ($i = 0; $i < 500000; ++$i) {
    stripos($string);
}
c\benchmark_print();

/* ------------------------------------------------------------------------------------------------------------------ */

c\benchmark_start();

for ($i = 0; $i < 500000; ++$i) {
    mb_stripos($string);
}
c\benchmark_print();
