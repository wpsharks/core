<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$pattern = '**{/**,}[?]{**&,}a=b{&**&,&,}e=f{&**,}';
$regex   = c::wregx($pattern, '/', true);
$matches = preg_match($regex.'i', '/hello/?this=that&a=b&c=d&e=f&g=h');

c::dump($regex)."\n";
echo $matches ? 'Match' : 'FAIL!';
