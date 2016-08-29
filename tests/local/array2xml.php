<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::arrayToXml('root', [
    'root_a'  => 'a',
    'root_b'  => 'b',
    'child_1' => [
        'child_1_a' => 'a',
        'child_1_b' => '/?a&b',
        'child_2'   => [
            'child_2_a' => 'a',
            'child_2_b' => 'b',
            0           => '<hello>',
            1           => '& <> "\'hello',
        ],
    ],
]);

echo "\n\n";

echo c::arrayToHtml('html', [
    'head' => [
        'title' => [
            1 => '& <> "\'hello',
        ],
        'base' => [
            'href' => '/?a&b',
        ],
    ],
    'body' => [
        0 => 'Hello world!',
    ],
]);
