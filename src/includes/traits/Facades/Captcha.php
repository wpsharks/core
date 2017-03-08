<?php
/**
 * Captcha.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Captcha.
 *
 * @since 17xxxx
 */
trait Captcha
{
    /**
     * @since 17xxxx Captcha utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Captcha::recaptchaVerify()
     */
    public static function recaptchaVerify(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Captcha->recaptchaVerify(...$args);
    }
}
