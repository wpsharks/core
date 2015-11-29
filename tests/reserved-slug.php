<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();
$App->Utils->Dump($App->Utils->Slug->isReserved('disney'));
$App->Utils->Benchmark->stopPrint();
