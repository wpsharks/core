<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$dir = $_SERVER['WEBSHARK_HOME'].'/temp';

// Pattern.

c::dump(c::identipattern([
    'output_file' => $dir.'/idp.svg',
]));

// Conversions.

c::dump(c::convertImage([
    'file'          => $dir.'/idp.svg',
    'output_format' => 'jpg',
]));
c::dump(c::convertImage([
    'file'          => $dir.'/idp.svg',
    'output_format' => 'png',
]));

// Resizes.

c::dump(c::resizeImage([
    'file'          => $dir.'/idp.svg',
    'width'         => 1200, 'height' => 600,
    'output_file'   => $dir.'/idp-1200x600.png',
]));
c::dump(c::resizeImage([
    'file'          => $dir.'/idp.svg',
    'width'         => 1200, 'height' => 600, 'bestfit' => true,
    'output_file'   => $dir.'/idp-1200x600-bestfit.png',
]));
c::dump(c::resizeImage([
    'file'          => $dir.'/idp.svg',
    'width'         => 512, 'height' => 256, 'crop' => 'thumbnail',
    'output_file'   => $dir.'/idp-512x256-crop.png',
]));

// Texturizations.

c::dump(c::texturizeImage([
    'file'          => $dir.'/idp.svg',
    'width'         => 512, 'height' => 256,
    'output_file'   => $dir.'/idp-texture-512x256.png',
]));
c::dump(c::texturizeImage([
    'file'          => $dir.'/idp.svg',
    'width'         => 1200, 'height' => 600,
    'output_file'   => $dir.'/idp-texture-1200x600.png',
]));
c::dump(c::texturizeImage([
    'file'          => $dir.'/idp.svg',
    'width'         => 512, 'height' => 256,
    'output_file'   => $dir.'/idp-texture-512x256.svg',
]));

// Compression.

c::dump(c::compressImage([
    'file'        => $dir.'/idp.svg',
    'output_file' => $dir.'/idp-compressed.svg',
]));
c::dump(c::compressImage([
    'file'        => $dir.'/idp.jpg',
    'output_file' => $dir.'/idp-compressed.jpg',
]));
c::dump(c::compressImage([
    'file'        => $dir.'/idp.png',
    'output_file' => $dir.'/idp-compressed.png',
]));
