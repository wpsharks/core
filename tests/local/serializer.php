<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo $closure = c::serializeClosure(function (string $string) {
    return $string;
})."\n\n";
c::dump($closure = c::unserializeClosure($closure));
echo $closure('Closure serialization works.')."\n\n";

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::maybeSerialize('string')."\n";
echo c::maybeSerialize(null)."\n";
echo c::maybeSerialize(false)."\n";
echo c::maybeSerialize(true)."\n";
echo c::maybeSerialize(0)."\n";
echo c::maybeSerialize(1)."\n";
echo c::maybeSerialize(0.1)."\n";
echo c::maybeSerialize([])."\n";
echo c::maybeSerialize([1])."\n";
echo c::maybeSerialize([1, 2])."\n";
echo c::maybeSerialize((object) [])."\n";
echo c::maybeSerialize((object) [1])."\n";
echo c::maybeSerialize((object) [1, 2])."\n\n";
echo c::maybeSerialize(function () {
    return 'testing';
})."\n\n";

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump(c::maybeUnserialize(c::maybeSerialize('string')));
c::dump(c::maybeUnserialize(c::maybeSerialize(null)));
c::dump(c::maybeUnserialize(c::maybeSerialize(false)));
c::dump(c::maybeUnserialize(c::maybeSerialize(true)));
c::dump(c::maybeUnserialize(c::maybeSerialize(0)));
c::dump(c::maybeUnserialize(c::maybeSerialize(1)));
c::dump(c::maybeUnserialize(c::maybeSerialize(0.1)));
c::dump(c::maybeUnserialize(c::maybeSerialize([])));
c::dump(c::maybeUnserialize(c::maybeSerialize([1])));
c::dump(c::maybeUnserialize(c::maybeSerialize([1, 2])));
c::dump(c::maybeUnserialize(c::maybeSerialize((object) [])));
c::dump(c::maybeUnserialize(c::maybeSerialize((object) [1])));
c::dump(c::maybeUnserialize(c::maybeSerialize((object) [1, 2])));
c::dump(c::maybeUnserialize(c::maybeSerialize(function () {
    return 'testing';
})));

echo "\n\n";

/* ------------------------------------------------------------------------------------------------------------------ */

echo($s = c::maybeSerialize('string')).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(null)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(false)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(true)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(0)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(1)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(0.1)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(PHP_INT_MAX + PHP_INT_MAX)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize([])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize([1])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize([1, 2])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize((object) [])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize((object) [1])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize((object) [1, 2])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::maybeSerialize(function () {
    return 'testing';
})).' = ';
c::dump(c::isSerialized($s));

echo "\n\n";

/* ------------------------------------------------------------------------------------------------------------------ */

echo($s = c::serialize('string')).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(null)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(false)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(true)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(0)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(1)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(0.1)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(PHP_INT_MAX + PHP_INT_MAX)).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize([])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize([1])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize([1, 2])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize((object) [])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize((object) [1])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize((object) [1, 2])).' = ';
c::dump(c::isSerialized($s));

echo($s = c::serialize(function () {
    return 'testing';
})).' = ';
c::dump(c::isSerialized($s));

echo "\n\n";
