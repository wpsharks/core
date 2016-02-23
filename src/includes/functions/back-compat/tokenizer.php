<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;
use WebSharks\Core\Classes\Tokenizer;

/**
 * @since 151214 Adding functions.
 */
function tokenize(string $string, array $tokenize)
{
    $args = ['string' => $string, 'tokenize' => $tokenize];
    return $GLOBALS[App::class]->Di->get(Tokenizer::class, $args);
}
