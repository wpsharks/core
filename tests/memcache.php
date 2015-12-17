<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Functions as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c\benchmark_start();
c\dump(c\memcache_set('primary', 'sub', 'value'));
c\dump(c\memcache_get('primary', 'sub'));
c\benchmark_print();
