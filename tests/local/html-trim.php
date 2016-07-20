<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$html = <<<HTML
&nbsp;<br /><br><p></p><p>&nbsp;</p>

<p>Not whitespace.</p>
HTML;

echo c::htmlTrim($html, 'horizontal');
