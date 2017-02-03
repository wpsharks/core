<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

// @codingStandardsIgnoreFile

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

function test_1a()
{
    return test_1b();
}
function test_1b()
{
    return test_1c();
}
function test_1c()
{
    return c::backtraceCallers();
}
c::dump(test_1a());

/* ------------------------------------------------------------------------------------------------------------------ */

function test_2a()
{
    return test_2b();
}
function test_2b()
{
    return test_2c();
}
function test_2c()
{
    return c::hasBacktraceCaller(__FUNCTION__);
}
c::dump(test_2a());

/* ------------------------------------------------------------------------------------------------------------------ */

function test_3a()
{
    return test_3b();
}
function test_3b()
{
    return test_3c();
}
function test_3c()
{
    return c::hasBacktraceCaller([
        __NAMESPACE__.'\\test_3b',
        __NAMESPACE__.'\\test_3a',
    ], 1);
}
c::dump(test_3a());
