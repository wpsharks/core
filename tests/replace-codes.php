<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::fillReplacementCodes('%%hello%% %%/^w**$%%', [
    'hello'     => 'hi!',
    'world.abc' => 'earth :-)',
]);
