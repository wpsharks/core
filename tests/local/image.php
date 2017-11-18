<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump(c::geoPattern($_SERVER['WEBSHARK_HOME'].'/temp/geo-pattern.svg', ['for' => 'websharks']));
c::dump(c::geoPatternThumbnail($_SERVER['WEBSHARK_HOME'].'/temp/geo-pattern-thumbnail.png', ['for' => 'websharks']));
