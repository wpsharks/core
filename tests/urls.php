<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Functions as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c\app_url('/client-s/test.js?v=34#core');
