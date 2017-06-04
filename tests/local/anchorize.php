<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = '
<p>ID: sc-d1d2d557bd0b366a907adb3dcf002e24<br />
Via: https://example.com/contact</p>

<p>Email: user@example.com<br />
Name: John Smith</p>

<p>Test</p>
';
echo c::htmlAnchorize($string);
