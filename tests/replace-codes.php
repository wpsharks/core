<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo $App->Utils->ReplaceCodes('%%hello%% %%/^w**$%%', [
    'hello'     => 'hi!',
    'world.abc' => 'earth :-)',
]);
