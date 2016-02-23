<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * i18n utilities.
 *
 * @since 160113 Initial release.
 */
class I18n extends Classes\AppBase
{
    /**
     * `\__()` exists in WP?
     *
     * @since 160219 Enhancing i18n utils.
     *
     * @type bool `\__()` exists in WP?
     */
    protected $is__wp;

    /**
     * Class constructor.
     *
     * @since 151216 Memcached utilities.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->is__wp = c\is_wordpress() && c\can_call_func('__');
    }

    /* @codingStandardsIgnoreStart */
    /**
     * Translate a string.
     *
     * @since 150424 Initial release.
     *
     * @param string $string String to translate.
     *
     * @return string Translated string.
     */
    public function __(string ...$args): string
    { /*@codingStandardsIgnoreEnd*/
        if (!$args) {
            return ''; // Nothing to do.
        }
        if (empty($args[1]) && $this->App->Config->i18n['text_domain']) {
            $args[1] = $this->App->Config->i18n['text_domain'];
        }
        if ($this->is__wp) {
            return \__(...$args);
        }
        return $args[0] ?? '';
    }
}
