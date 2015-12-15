<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Functions as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo c\password_strength('0aA!');
