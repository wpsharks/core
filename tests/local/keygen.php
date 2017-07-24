<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo 'License Key: '.c::licenseKey()."\n";
echo 'License Key: '.c::licenseKey()."\n";
echo 'License Key: '.c::licenseKey()."\n";
echo 'License Key: '.c::licenseKey()."\n";
echo "\n";

echo 'Public API Key: '.c::publicApiKey()."\n";
echo 'Public API Key: '.c::publicApiKey()."\n";
echo 'Public API Key: '.c::publicApiKey()."\n";
echo 'Public API Key: '.c::publicApiKey()."\n";
echo "\n";

echo 'Secret API Key: '.c::secretApiKey()."\n";
echo 'Secret API Key: '.c::secretApiKey()."\n";
echo 'Secret API Key: '.c::secretApiKey()."\n";
echo 'Secret API Key: '.c::secretApiKey()."\n";
echo "\n";

echo 'Secret Sig Key: '.c::secretSigKey()."\n";
echo 'Secret Sig Key: '.c::secretSigKey()."\n";
echo 'Secret Sig Key: '.c::secretSigKey()."\n";
echo 'Secret Sig Key: '.c::secretSigKey()."\n";
