<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump(c::email('jas+test@myinbox.ws', 'Testing™ •', '<p>Hello world!™ •</p><p>hi!</p>'));
