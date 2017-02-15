<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::benchStart();
echo c::sri('//raw.githubusercontent.com/websharks/ace-builds/fork-1.2.6/src-min-noconflict/ext-spellcheck.js');
c::benchPrint();
