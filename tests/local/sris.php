<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo 'sha256-KbfTjB0WZ8vvXngdpJGY3Yp3xKk+tttbqClO11anCIU='."\n";
echo c::sri('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js');
