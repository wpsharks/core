<?php
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
     * @param bool Request type is AJAX?
     */
    protected $is_ajax = false;

    /**
     * Request type is API?
     *
     * @since 160531 Request types.
     *
     * @param bool Request type is API?
     */
    protected $is_api = false;

    /**
     * Request type action.
     *
     * @since 160531 Request types.
     *
     * @param string Request type action.
     */
    protected $doing_action = '';

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
        if (defined('DOING_AJAX') && DOING_AJAX && $this->c::isWordPress()) {
            $this->is_ajax = true;
        }
        return $this->is_ajax;
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
        if (((defined('XMLRPC_REQUEST') && XMLRPC_REQUEST)
                    || (defined('REST_REQUEST') && REST_REQUEST))
                && $this->c::isWordPress()) {
            $this->is_api = true;
        }
        return $this->is_api;
    }

    /**
     * Doing an action? If so, which action?
     *
     * @since 160531 Request types.
     *
     * @param string|null $value If setting value.
     *
     * @return string The action being done.
     */
    public function doingAction(string $value = null): string
    {
        if (isset($value)) {
            $this->doing_action = $value;
        }
        return $this->doing_action;
    }
}
