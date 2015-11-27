<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Config->hash_ids['key'] = 'EK3Ry8Z4UUrEcRMF9RCV4zhCFqXWkt75adXUPZktkzRwdYYSsv7rK85FES2CyRHq';
echo $App->Utils->HashIds->encode(1, 2, 3);
