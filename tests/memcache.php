<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\AppFacades as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();
c::dump(c::memcacheSet('primary', 'sub', 'value'));
c::dump(c::memcacheGet('primary', 'sub'));
c::benchPrint();
