<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();
$App->Utils->Dump($e = $App->Utils->Rijndael256->encrypt('disney', str_repeat('x', 64)));
$App->Utils->Dump($App->Utils->Rijndael256->decrypt($e, str_repeat('x', 64)));
$App->Utils->Benchmark->stopPrint();
