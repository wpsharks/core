<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();
c::dump(c::isSlugReserved('disney'));
c::benchPrint();
