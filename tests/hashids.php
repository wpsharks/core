<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::hashIds(1, 2, 3);
