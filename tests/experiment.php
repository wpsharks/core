<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

class experiment
{
    public function __construct()
    {
        echo 'constructing...'."\n";
    }
    public function __invoke($x)
    {
        var_dump($x);
    }
}
var_dump(WebSharks\Core\Test\experiment(1));
