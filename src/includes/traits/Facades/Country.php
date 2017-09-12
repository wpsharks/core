<?php
/**
 * Country.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Country.
 *
 * @since 170329.13807
 */
trait Country
{
    /**
     * @since 170329.13807 Country utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Country::selectOptions()
     */
    public static function countrySelectOptions(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Country->selectOptions(...$args);
    }

    /**
     * @since 170329.13807 Country utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Country::dropdownItems()
     */
    public static function countryDropdownItems(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Country->dropdownItems(...$args);
    }
}
