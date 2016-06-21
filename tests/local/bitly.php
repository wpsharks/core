<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::bitlyShorten('http://php.net/manual/en/function.json-decode.php')."\n";
c::dump(c::bitlyLinkHistory());
