<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::fillReplacementCodes('%%hello%% %%/^w**$%%', [
    'hello'     => 'hi!',
    'world.abc' => 'earth :-)',
]);
