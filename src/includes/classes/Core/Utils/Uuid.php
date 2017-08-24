<?php
/**
 * UUID generator.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use Ramsey\Uuid\Uuid as UuidGen;

/**
 * UUID generator.
 *
 * @since 150424 Initial release.
 */
class Uuid extends Classes\Core\Base\Core
{
    /**
     * UUID v1 generator.
     *
     * @since 150424 Initial release.
     *
     * @param bool $optimize Optimize?
     *
     * @return string Version 1 UUID (32 bytes optimized).
     *                Or 36 bytes unoptimized; i.e., w/ dashes.
     */
    public function v1(bool $optimize = true): string
    {
        $uuid = UuidGen::uuid1()->toString();

        if ($optimize) {
            // See: <http://jas.xyz/1ODZilT>
            $uuid = substr($uuid, 14, 4).
                substr($uuid, 9, 4).
                substr($uuid, 0, 8).
                substr($uuid, 19, 4).
                substr($uuid, 24);
        }
        return $uuid; // Possibly optimized now.
    }

    /**
     * UUID v3 generator.
     *
     * @since 150424 Initial release.
     *
     * @param string $namespace  Namespace.
     * @param string $identifier Identifier.
     * @param bool   $optimize   Optimize?
     *
     * @return string Version 3 UUID (32 bytes optimized).
     *                Or 36 bytes unoptimized; i.e., w/ dashes.
     *
     * @internal {@link v5()} is a suggested alternative, as this version uses MD5.
     */
    public function v3(string $namespace, string $identifier, bool $optimize = true): string
    {
        switch ($namespace) {
            case 'dns':
                $namespace = UuidGen::NAMESPACE_DNS;
                break; // Stop here.

            case 'url':
                $namespace = UuidGen::NAMESPACE_URL;
                break; // Stop here.

            case 'oid':
                $namespace = UuidGen::NAMESPACE_OID;
                break; // Stop here.

            case 'x500':
                $namespace = UuidGen::NAMESPACE_X500;
                break; // Stop here.

            default:
                $this->c::issue('Invalid namespace.');
        }
        $uuid = UuidGen::uuid3($namespace, $identifier)->toString();

        return $optimize ? str_replace('-', '', $uuid) : $uuid;
    }

    /**
     * UUID v4 generator.
     *
     * @since 150424 Initial release.
     *
     * @param bool $optimize Optimize?
     *
     * @return string Version 4 UUID (32 bytes optimized).
     *                Or 36 bytes unoptimized; i.e., w/ dashes.
     */
    public function v4(bool $optimize = true): string
    {
        $uuid = UuidGen::uuid4()->toString();

        return $optimize ? str_replace('-', '', $uuid) : $uuid;
    }

    /**
     * UUID v4 generator x 2.
     *
     * @since 170824.30708 Initial release.
     *
     * @param bool $optimize Optimize?
     *
     * @return string Version 4 UUID x 2 (64 bytes optimized).
     *                Or 73 bytes unoptimized; i.e., w/ dashes + one extra.
     */
    public function v4x2(bool $optimize = true): string
    {
        $uuid1 = UuidGen::uuid4()->toString();
        $uuid2 = UuidGen::uuid4()->toString();

        return $optimize ? str_replace('-', '', $uuid1.$uuid2) : $uuid1.'-'.$uuid2;
    }

    /**
     * UUID v5 generator.
     *
     * @since 150424 Initial release.
     *
     * @param string $namespace  Namespace.
     * @param string $identifier Identifier.
     * @param bool   $optimize   Optimize?
     *
     * @return string Version 5 UUID (32 bytes optimized).
     *                Or 36 bytes unoptimized; i.e., w/ dashes.
     */
    public function v5(string $namespace, string $identifier, bool $optimize = true): string
    {
        switch ($namespace) {
            case 'dns':
                $namespace = UuidGen::NAMESPACE_DNS;
                break; // Stop here.

            case 'url':
                $namespace = UuidGen::NAMESPACE_URL;
                break; // Stop here.

            case 'oid':
                $namespace = UuidGen::NAMESPACE_OID;
                break; // Stop here.

            case 'x500':
                $namespace = UuidGen::NAMESPACE_X500;
                break; // Stop here.

            default:
                throw $this->c::issue('Invalid namespace.');
        }
        $uuid = UuidGen::uuid5($namespace, $identifier)->toString();

        return $optimize ? str_replace('-', '', $uuid) : $uuid;
    }
}
