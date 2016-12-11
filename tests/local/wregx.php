<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$pattern = '{/**,}[?]{**&,}a=b{&**&,&}e=f{&**,}';
$regex   = c::wregx($pattern, '/', true);
$matches = preg_match($regex.'i', '/hello/?this=that&a=b&c=d&e=f&g=h');
c::dump($regex); echo($matches ? 'Match' : 'FAIL!')."\n\n";

c::dump(c::urlToWRegxUriPattern('a'));
c::dump(c::urlToWRegxUriPattern('/a'));
c::dump(c::urlToWRegxUriPattern('/'));
c::dump(c::urlToWRegxUriPattern(''));
c::dump(c::urlToWRegxUriPattern('//'));
c::dump(c::urlToWRegxUriPattern('?abc=123'));
c::dump(c::urlToWRegxUriPattern('//example.com/?abc=123'));
c::dump(c::urlToWRegxUriPattern('//example.com/a?abc=123'));
c::dump(c::urlToWRegxUriPattern('//example.com/a?abc=123&xyz=bca'));
c::dump(c::urlToWRegxUriPattern('a?abc=123&xyz=bca'));
c::dump(c::urlToWRegxUriPattern('a?abc=123&xyz=bca#fragment?xxx'));
c::dump(c::urlToWRegxUriPattern('a?abc=123&xyz=bca#fragment'));
c::dump(c::urlToWRegxUriPattern('a?abc=123&xyz=bca'));
c::dump(c::urlToWRegxUriPattern('?abc=123&xyz=bca'));
