<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump(c::humanTimeDiff(strtotime('-1 second')));
c::dump(c::humanTimeDiff(strtotime('-3 seconds')));

c::dump(c::humanTimeDiff(strtotime('-1 hour')));
c::dump(c::humanTimeDiff(strtotime('-3 hours')));

c::dump(c::humanTimeDiff(strtotime('-1 week')));
c::dump(c::humanTimeDiff(strtotime('-3 weeks')));

c::dump(c::humanTimeDiff(strtotime('-1 month')));
c::dump(c::humanTimeDiff(strtotime('-3 months')));

c::dump(c::humanTimeDiff(strtotime('-1 year')));
c::dump(c::humanTimeDiff(strtotime('-3 years')));

c::dump(c::humanTimeDiff(strtotime('-1 second'), null, 'abbrev'));
c::dump(c::humanTimeDiff(strtotime('-3 seconds'), null, 'abbrev'));

c::dump(c::humanTimeDiff(strtotime('-1 hour'), null, 'abbrev'));
c::dump(c::humanTimeDiff(strtotime('-3 hours'), null, 'abbrev'));

c::dump(c::humanTimeDiff(strtotime('-1 week'), null, 'abbrev'));
c::dump(c::humanTimeDiff(strtotime('-3 weeks'), null, 'abbrev'));

c::dump(c::humanTimeDiff(strtotime('-1 month'), null, 'abbrev'));
c::dump(c::humanTimeDiff(strtotime('-3 months'), null, 'abbrev'));

c::dump(c::humanTimeDiff(strtotime('-1 year'), null, 'abbrev'));
c::dump(c::humanTimeDiff(strtotime('-3 years'), null, 'abbrev'));
