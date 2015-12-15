<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use Ramsey\Uuid\Uuid as UuidGen;

/**
 * UUID Generator.
 *
 * @since 150424 Initial release.
 */
class Uuid extends Classes\AbsBase
{
    /**
     * UUID v1 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Version 1 UUID.
     */
    public function v1(): string
    {
        return UuidGen::uuid1()->toString();
    }

    /**
     * UUID v3 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $namespace  Namespace.
     * @param string $identifier Identifier.
     *
     * @return string Version 3 UUID.
     */
    public function v3(string $namespace, string $identifier): string
    {
        return UuidGen::uuid3($namespace, $identifier)->toString();
    }

    /**
     * UUID v4 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Version 4 UUID.
     */
    public function v4(): string
    {
        return UuidGen::uuid4()->toString();
    }

    /**
     * UUID v5 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $namespace  Namespace.
     * @param string $identifier Identifier.
     *
     * @return string Version 5 UUID.
     */
    public function v5(string $namespace, string $identifier): string
    {
        return UuidGen::uuid5($namespace, $identifier)->toString();
    }
}
