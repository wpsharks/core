<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

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
