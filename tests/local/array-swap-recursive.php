<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$a = [
    'one' => [
        'two'   => 'a2',
        'three' => ['a4', 'a5', 'a6'],
        'four'  => (object) ['five' => 'a5', 'six' => 'a6', 'seven' => 'a7'],
    ],
    'two' => [
        'three' => 'a3',
        'four'  => ['a5', 'a6', 'a7'],
        'five'  => (object) ['six' => 'a6', 'seven' => 'a7', 'eight' => 'a8'],
    ],
    'three' => [
        'four'  => 'a4',
        'five'  => ['a6', 'a7', 'a8'],
        'six'   => (object) ['seven' => 'a7', 'eight' => 'a8', 'nine' => 'a9'],
    ],
];
$b = [
    'one' => [
        'two'   => '',
        'three' => [],
        'four'  => (object) [],
    ],
    'two' => [
        'three' => 'b3',
        'four'  => (object) ['b5', 'b6', 'b7'],
        'five'  => ['six' => 'b6', 'seven' => 'b7', 'eight' => 'b8'],
    ],
    'three' => [],
];
print_r(c::arraySwapRecursive($a, $b));
