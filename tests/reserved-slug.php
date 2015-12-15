<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Functions as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c\benchmark_start();
c\dump(c\is_slug_reserved('disney'));
c\benchmark_print();
