<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::appUrl('/client-s/test.js?v=34#core')."\n";
echo c::addUrlQueryArgs(['a' => '', 'b' => '0', 'c' => null], 'https://example.com/')."\n";
echo c::removeUrlQueryArgs(['_wpnonce'], 'https://example.com/?a=1&_wpnonce=xxxxxxx')."\n";
