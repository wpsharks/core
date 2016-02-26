<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Pdo
{
    /**
     * @since 151214 Adding functions.
     */
    public static function pdoGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->Pdo->get(...$args);
    }
}
