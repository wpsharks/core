<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';
$key    = c::encryptionKey();

/* ------------------------------------------------------------------------------------------------------------------ */

echo $key."\n\n";

echo($e = c::encrypt($string, $key))."\n";
echo c::decrypt($e, $key)."\n";
