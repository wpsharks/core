<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();

for ($i = 0; $i < 10000; ++$i) {
    mb_strtolower($string);
}
$App->Utils->Benchmark->print();

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();

for ($i = 0; $i < 10000; ++$i) {
    strtolower($string);
}
$App->Utils->Benchmark->print();
