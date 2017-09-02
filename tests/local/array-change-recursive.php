<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$a = [
    'zero'  => 'a',
    'one'   => 'a',
    'two'   => ['a'],
    'three' => [
        'four'  => 'a',
        'five'  => ['a'],
        'six'   => ['seven' => 'a', 'eight' => 'a', 'nine' => 'a'],
    ],
];
$b = [
    'one'   => 'b',
    'two'   => ['b'],
    'three' => [
        'four'  => 'b',
        'five'  => ['b'],
        'six'   => ['seven' => 'b', 'eight' => 'b', 'nine' => 'b', 'ten' => 'b'],
    ],
    'eleven' => ['twelve' => 'b'],
];
echo json_encode($a, JSON_PRETTY_PRINT)."\n";
echo json_encode($b, JSON_PRETTY_PRINT)."\n";
echo json_encode(c::arrayChangeRecursive($a, $b), JSON_PRETTY_PRINT)."\n";
