<?php
/**
 * Tokenizer.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * Tokenizer.
 *
 * @since 151214
 */
trait Tokenizer
{
    /**
     * @since 151214 First facades.
     *
     * @param string $string   to underlying utility.
     * @param array  $tokenize to underlying utility.
     * @param array  $args     to underlying utility.
     *
     * @see Classes\Core\Tokenizer::__construct()
     */
    public static function tokenize(string $string, array $tokenize, array $args = []): Classes\Core\Tokenizer
    {
        return $GLOBALS[static::class]->Di->get(Classes\Core\Tokenizer::class, ['string' => $string, 'tokenize' => $tokenize, 'args' => $args]);
    }
}
