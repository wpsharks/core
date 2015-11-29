<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();

for ($i = 0; $i < 1; ++$i) {
    $App->Utils->Slug->isReserved(mt_rand().$string);
}
$App->Utils->Benchmark->stopPrint();

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();

for ($i = 0; $i < 1; ++$i) {
    $App->Utils->Slug->isReserved(mt_rand().$string);
}
$App->Utils->Benchmark->stopPrint();
