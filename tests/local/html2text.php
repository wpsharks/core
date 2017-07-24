<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$html = <<<HTML
<br>eot: 1-year<br>space_id: 123<br>
HTML;

echo c::htmlToText($html);
