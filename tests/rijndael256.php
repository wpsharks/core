<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as a;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

a::benchStart();
a::dump($e = a::encrypt('disney', str_repeat('x', 64)));
a::dump(a::decrypt($e, str_repeat('x', 64)));
a::benchPrint();
