<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160102 App.
 */
function array_unshift_assoc(&$array, $key, $value)
{
    return $GLOBALS[App::class]->Utils->UnshiftAssoc->__invoke($array, $key, $value);
}
