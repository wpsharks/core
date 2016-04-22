<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::slugToName('s2member-x')."\n";
echo c::nameToAcronym('s2Member X')."\n";
echo mb_strtolower(c::nameToAcronym('S2Member X'));
