<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Tokenizer
{
    /**
     * @since 151214 Adding functions.
     */
    public static function tokenize(string $string, array $tokenize)
    {
        $args = ['string' => $string, 'tokenize' => $tokenize];
        return $GLOBALS[static::class]->Di->get(Classes\Core\Tokenizer::class, $args);
    }
}
