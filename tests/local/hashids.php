<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

for ($i = 0; $i <= 100; ++$i) {
    echo c::decodeHashId(c::hashIds($i))."\n";
}
