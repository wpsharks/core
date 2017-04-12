<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::clip('This is a really, really long sentence with a lot of characters. More than 40, that is.')."\n";
echo c::clip('This is a really, really long sentence with a lot of characters. More than 40, that is.', 40, false, ' <a href="#">more</a>')."\n";
echo c::clip('This is a really, really long sentence with a lot of characters. More than 40, that is.', 10, false, ' <a href="#">more</a>')."\n";
echo "\n";
echo c::midClip('This is a really, really long sentence with a lot of characters. More than 40, that is.')."\n";
echo c::midClip('This is a really, really long sentence with a lot of characters. More than 40, that is.', 40, ' <a href="#">more</a>')."\n";
echo c::midClip('This is a <really> &lt;em&gt;, really long sentence with a lot of characters. More than 40, that is.', 40, ' <a href="#">more</a>')."\n";
