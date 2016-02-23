<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Tokenizer
{
    /**
     * @since 151214 Adding functions.
     */
    public static function tokenize(string $string, array $tokenize)
    {
        $args = ['string' => $string, 'tokenize' => $tokenize];
        return $GLOBALS[static::class]->Di->get(Classes\Tokenizer::class, $args);
    }
}
