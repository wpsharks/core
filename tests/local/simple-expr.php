<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$callback = function (string $test_fragment) {
    return '_(\''.str_replace("'", "\\'", $test_fragment).'\')';
};

/* ------------------------------------------------------------------------------------------------------------------ */

echo str_repeat('-  -  ', 10)."\n\n";
echo 'Valid Syntax:'."\n\n";

c::benchStart();

echo '• '.c::simplePhpExpr('a AND b', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) OR c', $callback)."\n";
echo '• '.c::simplePhpExpr('!(a AND b) OR c', $callback)."\n";
echo '• '.c::simplePhpExpr('c OR (a AND b)', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND(b) or !', $callback)."\n";
echo '• '.c::simplePhpExpr('(a)AND(b) or !', $callback)."\n";
echo '• '.c::simplePhpExpr('(a)AND(b) or(!)', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b or !', $callback)."\n";
echo '• '.c::simplePhpExpr('!=! OR a AND b', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR =!=', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b or ANDOR', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) OR (c and d)', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b)or(c and d)', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b)   or    (c and d)', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b )  or (c and (d OR e ))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b ) or (c and (d OR e == "true"))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b != yo ) or (c and (d OR e == true))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b !== yo ) or (c and (d OR e == NULL))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b == hi ) or (c and (d OR e == 1))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b == hello ) or (c and (d OR e >= 1))', $callback)."\n";
echo '• '.c::simplePhpExpr('( !a AND b == hello ) or (c and (d OR e >= 1))', $callback)."\n";
echo '• '.c::simplePhpExpr('( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1))))', $callback)."\n";
echo '• '.c::simplePhpExpr('( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)))) and (d OR e >= 1)))) and (d OR e >= 1)))) and (d OR e >= 1))))', $callback)."\n";

c::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

echo str_repeat('-  -  ', 10)."\n\n";
echo 'Benchmarking simple expression X 100:';

c::benchStart();
for ($i = 0; $i < 100; ++$i) {
    c::simplePhpExpr('(a AND b) or (c and (d OR e == "true"))', $callback);
} c::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

echo str_repeat('-  -  ', 10)."\n\n";
echo 'Benchmarking expensive simple expression X 100:';

c::benchStart();
for ($i = 0; $i < 100; ++$i) {
    c::simplePhpExpr('( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (( !a AND b == hello ) or (c and (d OR e >= 1 && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)) && ( !a AND b == hello ) or (c and (d OR e >= 1)))) and (d OR e >= 1)))) and (d OR e >= 1)))) and (d OR e >= 1))))', $callback);
} c::benchPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

echo str_repeat('-  -  ', 10)."\n\n";
echo 'Invalid Syntax:'."\n\n";

c::benchStart();

echo '• '.c::simplePhpExpr('(', $callback)."\n";
echo '• '.c::simplePhpExpr(')', $callback)."\n";
echo '• '.c::simplePhpExpr('()', $callback)."\n";
echo '• '.c::simplePhpExpr('a b', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b and', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) OR (c d)', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) - or (c and d)', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) - OR', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR OR', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR ==', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR !=', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR >=', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR <=', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR ===', $callback)."\n";
echo '• '.c::simplePhpExpr('=== OR a AND b', $callback)."\n";
echo '• '.c::simplePhpExpr('!= OR a AND b', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR !==', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR >=', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR <=', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR <>', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR <', $callback)."\n";
echo '• '.c::simplePhpExpr('a AND b OR !=', $callback)."\n";
echo '• '.c::simplePhpExpr('!= or a AND b', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) - or -', $callback)."\n";
echo '• '.c::simplePhpExpr('(a AND b) a or b', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b ) or (c and (d OR e !))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b ) andor (c and (d OR e == "true"))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a ANDOR b != yo ) or (c and (d OR e == true))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND OR b != yo ) or (c and (d OR e == true))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND b == hi ) or (c and (d OR-AND e == 1))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND/OR b == hello ) or (c and (d OR e >= 1))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND/OR b == hello ) or (c and (d OR e >= 1)))', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND/OR b == hello ) or (c and (d OR e >= 1))()', $callback)."\n";
echo '• '.c::simplePhpExpr('( a AND/OR b == hello ) or (c and (d OR e >= 1))(', $callback)."\n";

c::benchPrint();
