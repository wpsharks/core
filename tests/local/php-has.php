<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump(c::canCallFunc('eval'));
c::dump(c::canCallFunc('eval'));
c::dump(c::canCallFunc('evals'));
c::dump(c::canCallFunc('evals'));
