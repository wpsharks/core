<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::markdown('## Testing one, two, three. {.!~@+-#foo-bar-class:1 .#foo-bar-class:100% .#1 #foo-bar-id}', ['anchorize' => true]);
