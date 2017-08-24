<?php
/**
 * Key generator.
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

/**
 * Key generator.
 *
 * @since 170824.30708 Key generator.
 */
class Keygen extends Classes\Core\Base\Core
{
    /**
     * License key.
     *
     * @since 170824.30708 Key generator.
     *
     * @return string UUID w/ 4 dashed parts (35 uppercase bytes).
     *
     * @example `CAF0F02F-DFC248C7-B4EF607C-F85C154A`
     */
    public function license(): string
    {
        $key        = mb_strtoupper($this->c::uuidV4());
        return $key = implode('-', str_split($key, 8));
    }

    /**
     * Public API key.
     *
     * @since 170824.30708 Key generator.
     *
     * @return string UUID w/ `pub_` prefix (64 lowercase bytes).
     *
     * @example `pub_104abe60992841aa920c764d5a37c33e20467398bb3a43288a1e554c607f`
     */
    public function publicApi(): string
    {
        return 'pub_'.mb_substr($this->c::uuidV4x2(), 0, -4);
    }

    /**
     * Secret API key.
     *
     * @since 170824.30708 Key generator.
     *
     * @return string UUID w/ `sec_` prefix (64 lowercase bytes).
     *
     * @example `sec_8f7d4f577838478498f0ca193f87980c529f20c9526c47ecbb5f3dcdcf48`
     */
    public function secretApi(): string
    {
        return 'sec_'.mb_substr($this->c::uuidV4x2(), 0, -4);
    }

    /**
     * Secret sig key.
     *
     * @since 170824.30708 Key generator.
     *
     * @return string UUID w/ `sig_` prefix (64 lowercase bytes).
     *
     * @example `sig_2759592dc1fc4574938e5f78e74c90abb5caad9a17d942e7857747af4b0a`
     */
    public function secretSig(): string
    {
        return 'sig_'.mb_substr($this->c::uuidV4x2(), 0, -4);
    }
}
