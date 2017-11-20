<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$dir = $_SERVER['WEBSHARK_HOME'].'/temp';

// Pattern.

c::dump(c::geoPattern([
    'output_file' => $dir.'/geo-pattern.svg',
]));

// Conversions.

c::dump(c::convertImage([
    'file'          => $dir.'/geo-pattern.svg',
    'output_format' => 'jpg',
]));
c::dump(c::convertImage([
    'file'          => $dir.'/geo-pattern.svg',
    'output_format' => 'png',
]));

// Resizes.

c::dump(c::resizeImage([
    'file'          => $dir.'/geo-pattern.svg',
    'width'         => 1200, 'height' => 600,
    'output_file'   => $dir.'/geo-pattern-1200x600.png',
]));
c::dump(c::resizeImage([
    'file'          => $dir.'/geo-pattern.svg',
    'width'         => 1200, 'height' => 600, 'bestfit' => true,
    'output_file'   => $dir.'/geo-pattern-1200x600-bestfit.png',
]));
c::dump(c::resizeImage([
    'file'          => $dir.'/geo-pattern.svg',
    'width'         => 512, 'height' => 256, 'crop' => 'thumbnail',
    'output_file'   => $dir.'/geo-pattern-512x256-crop.png',
]));

// Texturizations.

c::dump(c::texturizeImage([
    'file'          => $dir.'/geo-pattern.svg',
    'width'         => 512, 'height' => 256,
    'output_file'   => $dir.'/geo-pattern-texture-512x256.png',
]));
c::dump(c::texturizeImage([
    'file'          => $dir.'/geo-pattern.svg',
    'width'         => 1200, 'height' => 600,
    'output_file'   => $dir.'/geo-pattern-texture-1200x600.png',
]));

// Compression.

c::dump(c::compressImage([
    'file'        => $dir.'/geo-pattern.svg',
    'output_file' => $dir.'/geo-pattern-compressed.svg',
]));
c::dump(c::compressImage([
    'file'        => $dir.'/geo-pattern.jpg',
    'output_file' => $dir.'/geo-pattern-compressed.jpg',
]));
c::dump(c::compressImage([
    'file'        => $dir.'/geo-pattern.png',
    'output_file' => $dir.'/geo-pattern-compressed.png',
]));
