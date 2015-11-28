<?php
declare (strict_types = 1);
namespace WebSharks\Core;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'Lörem ipßüm dölör ßit ämet, cönßectetüer ädipißcing elit.';

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();

for ($i = 0; $i < 1; ++$i) {
    $r = new \ReflectionClass($App);
    $r->getFileName();
}
$App->Utils->Benchmark->print();

/* ------------------------------------------------------------------------------------------------------------------ */

$App->Utils->Benchmark->start();

for ($i = 0; $i < 10000; ++$i) {
    $r = $App::VERSION;
}
$App->Utils->Benchmark->print();
