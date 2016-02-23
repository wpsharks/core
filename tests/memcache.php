<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\AppFacades as a;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

a::benchStart();
a::dump(a::memcacheSet('primary', 'sub', 'value'));
a::dump(a::memcacheGet('primary', 'sub'));
a::benchPrint();
