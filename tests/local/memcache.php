<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();
c::dump(c::memcacheEnabled());
//c::dump(c::memcacheSet('primary', 'sub', 'value'));
c::dump(c::memcacheGet('primary', 'sub'));
c::dump(c::memcacheInfo());
c::benchPrint();
