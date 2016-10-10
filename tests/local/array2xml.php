<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c::arrayToXml('rss', [
    'version' => '2.0',

    'channel' => [
        'language'  => ['en-US'],
        'generator' => [''],

        'title'       => [''],
        'link'        => [''],
        'description' => [''],

        'image' => [
            'url'   => [''],
            'title' => [''],
            'link'  => [''],
        ],

        'item' => [
            'title'       => [''],
            'link'        => [''],
            'description' => [''],

            'image' => [
                'url'   => [''],
                'title' => [''],
                'link'  => [''],
            ],
            'pubDate'  => [''],
            'guid'     => [''],
            'comments' => [''],
        ],
    ],
]);
