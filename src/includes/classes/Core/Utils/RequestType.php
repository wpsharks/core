<?php
/**
 * Request types.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Request types.
 *
 * @since 160531 Request types.
 */
class RequestType extends Classes\Core\Base\Core
{
    /**
     * Request type is AJAX?
     *
     * @since 160531 Request types.
     *
     * @param bool|null Request type is AJAX?
     */
    protected $is_ajax;

    /**
     * Request type is API?
     *
     * @since 160531 Request types.
     *
     * @param bool|null Request type is API?
     */
    protected $is_api;

    /**
     * Doing a REsT action?
     *
     * @since 160531 Request types.
     *
     * @param string|null REsT action.
     */
    protected $doing_rest_action;

    /**
     * Is WordPress?
     *
     * @since 160606 Request types.
     *
     * @param bool Is WordPress?
     */
    protected $is_wordpress;

    /**
     * Class constructor.
     *
     * @since 160606 Request types.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->is_wordpress = $this->c::isWordPress();
    }

    /**
     * Request type is AJAX?
     *
     * @since 160531 Request types.
     *
     * @param bool|null $value If setting value.
     *
     * @return bool Is an AJAX request?
     */
    public function isAjax(bool $value = null): bool
    {
        if (isset($value)) {
            $this->is_ajax = $value;
        }
        if (!isset($this->is_ajax) && $this->is_wordpress) {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                $this->is_ajax = true;
            }
        }
        return (bool) $this->is_ajax;
    }

    /**
     * Request type is API?
     *
     * @since 160605 Request types.
     *
     * @param bool|null $value If setting value.
     *
     * @return bool Is an API request?
     */
    public function isApi(bool $value = null): bool
    {
        if (isset($value)) {
            $this->is_api = $value;
        }
        if (!isset($this->is_api) && $this->is_wordpress) {
            if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
                $this->is_api = true;
            } elseif (defined('REST_REQUEST') && REST_REQUEST) {
                $this->is_api = true;
            }
        }
        return (bool) $this->is_api;
    }

    /**
     * Doing a REsT action?
     *
     * @since 160531 Request types.
     *
     * @param string|null $value If setting value.
     *
     * @return string The REsT action being done.
     */
    public function doingRestAction(string $value = null): string
    {
        if (isset($value)) {
            $this->doing_rest_action = $value;
        }
        return (string) $this->doing_rest_action;
    }
}
