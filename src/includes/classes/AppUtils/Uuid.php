<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

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
     * UUID v3 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $address Address adentifier.
     *
     * @return string Version 3 UUID.
     */
    public function v3Dns(string $address): string
    {
        return static::v3(UuidGen::NAMESPACE_DNS, $address);
    }

    /**
     * UUID v3 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $url URL identifier.
     *
     * @return string Version 3 UUID.
     */
    public function v3Url(string $url): string
    {
        return static::v3(UuidGen::NAMESPACE_URL, $url);
    }

    /**
     * UUID v3 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $oid URL identifier.
     *
     * @return string Version 3 UUID.
     */
    public function v3Oid(string $oid): string
    {
        return static::v3(UuidGen::NAMESPACE_OID, $oid);
    }

    /**
     * UUID v3 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $x500 SSL identifier.
     *
     * @return string Version 3 UUID.
     */
    public function v3X500(string $x500): string
    {
        return static::v3(UuidGen::NAMESPACE_X500, $x500);
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

    /**
     * UUID v5 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $address Address adentifier.
     *
     * @return string Version 5 UUID.
     */
    public function v5Dns(string $address): string
    {
        return static::v5(UuidGen::NAMESPACE_DNS, $address);
    }

    /**
     * UUID v5 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $url URL identifier.
     *
     * @return string Version 5 UUID.
     */
    public function v5Url(string $url): string
    {
        return static::v5(UuidGen::NAMESPACE_URL, $url);
    }

    /**
     * UUID v5 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $oid URL identifier.
     *
     * @return string Version 5 UUID.
     */
    public function v5Oid(string $oid): string
    {
        return static::v5(UuidGen::NAMESPACE_OID, $oid);
    }

    /**
     * UUID v5 generator.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $x500 SSL identifier.
     *
     * @return string Version 5 UUID.
     */
    public function v5X500(string $x500): string
    {
        return static::v5(UuidGen::NAMESPACE_X500, $x500);
    }
}
