<?php
/**
 * Request types.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
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
     * Is AJAX?
     *
     * @since 160531 Request types.
     *
     * @param bool|null Is AJAX?
     */
    protected $is_ajax;

    /**
     * Is API?
     *
     * @since 160531 Request types.
     *
     * @param bool|null Is API?
     */
    protected $is_api;

    /**
     * REsT action.
     *
     * @since 160531 Request types.
     *
     * @param string|null REsT action.
     */
    protected $doing_rest_action;

    /**
     * Doing actions.
     *
     * @since 170623.50532 Action utils.
     *
     * @param array Actions.
     */
    protected $doing_actions;

    /**
     * Completed actions & data.
     *
     * @since 170623.50532 Action utils.
     *
     * @param array Actions & data.
     */
    protected $did_actions;

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

        $this->doing_actions = [];
        $this->did_actions   = [];
        $this->is_wordpress  = $this->c::isWordPress();
    }

    /**
     * An AJAX request?
     *
     * @since 160531 Request types.
     *
     * @param bool|null $flag If setting flag.
     *
     * @return bool Is an AJAX request?
     */
    public function isAjax(bool $flag = null): bool
    {
        if (isset($flag)) {
            $this->is_ajax = $flag;
        }
        if (!isset($this->is_ajax) && $this->is_wordpress) {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                $this->is_ajax = true; // Via WP constants.
            }
        }
        return (bool) $this->is_ajax;
    }

    /**
     * An API request?
     *
     * @since 160605 Request types.
     *
     * @param bool|null $flag If setting flag.
     *
     * @return bool Is an API request?
     */
    public function isApi(bool $flag = null): bool
    {
        if (isset($flag)) {
            $this->is_api = $flag;
        }
        if (!isset($this->is_api) && $this->is_wordpress) {
            if ((defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) || (defined('REST_REQUEST') && REST_REQUEST)) {
                $this->is_api = true; // Via WP constants.
            }
        }
        return (bool) $this->is_api;
    }

    /**
     * Doing a REsT action?
     *
     * @since 160531 Request types.
     *
     * @param string|null $action Only to set action.
     *
     * @return string The REsT action being done.
     */
    public function doingRestAction(string $action = null): string
    {
        if (isset($action)) {
            $this->doing_rest_action = $action;
            $this->doingAction($action, true);
        }
        return (string) $this->doing_rest_action;
    }

    /**
     * Doing an action?
     *
     * @since 170623.50532 Action utils.
     *
     * @param string     $action Action to add or check.
     * @param mixed|null $data   If adding action data.
     *
     * @return bool True if doing the action.
     */
    public function doingAction(string $action = '', bool $flag = null): bool
    {
        if ($action && isset($flag)) {
            $this->doing_actions[$action] = $flag;
        }
        if ($action) {
            return $this->doing_actions[$action] ?? false;
        } else {
            return !empty($this->doing_actions);
        }
    }

    /**
     * Did (completed) an action?
     *
     * @since 170623.50532 Action utils.
     *
     * @param string     $action Action to add or check.
     * @param mixed|null $data   If adding truthy action data.
     *
     * @return mixed|null Action data, else `null`.
     */
    public function didAction(string $action = '', $data = null)
    {
        if ($action && isset($data)) {
            if (!$data) { // Empty/falsey?
                throw $this->c::issue('Data must be truthy.');
            } // Avoid falsey data, which leads to confusion.
            $this->did_actions[$action] = $data;
        }
        if ($action) {
            return $this->did_actions[$action] ?? null;
        } else {
            return !empty($this->did_actions);
        }
    }
}
