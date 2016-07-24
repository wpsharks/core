<?php
/**
 * Tokenizer.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Tokenizer.
 *
 * @since 151214
 */
trait Tokenizer
{
    /**
     * @since 151214 First facades.
     */
    public static function tokenize(string $string, array $tokenize, array $args = [])
    {
        return $GLOBALS[static::class]->Di->get(Classes\Core\Tokenizer::class, ['string' => $string, 'tokenize' => $tokenize, 'args' => $args]);
    }
}
