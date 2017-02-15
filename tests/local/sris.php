<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo 'sha256-jHqKZLWQrHz5TSDtGB1wqHlUcYHjK4XUN3IS84M9Av0='."\n";

c::benchStart();
echo c::sri('https://raw.githubusercontent.com/websharks/ace-builds/fork-1.2.6/src-min-noconflict/ext-spellcheck.js');
c::benchPrint();
