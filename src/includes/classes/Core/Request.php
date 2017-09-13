<?php
/**
 * Request.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core;

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
 * Request.
 *
 * @since 17xxxx
 */
class Request extends \Slim\Http\Request
{
    /**
     * Get all data.
     *
     * @since 17xxxx Request.
     *
     * @return array All data.
     */
    public function getData(): array
    {
        $form_data   = $this->getFormData();
        $query_args  = $this->getQueryArgs();
        return $data = $form_data + $query_args;
    }

    /**
     * Get query args.
     *
     * @since 17xxxx Request.
     *
     * @return array Query args.
     */
    public function getQueryArgs(): array
    {
        $args        = $this->getQueryParams();
        return $args = is_array($args) ? $args : [];
    }

    /**
     * Get form data.
     *
     * @since 17xxxx Request.
     *
     * @return array Form data.
     */
    public function getFormData(): array
    {
        if ($this->getMediaType() !== 'application/x-www-form-urlencoded') {
            return $data = []; // Not possible.
        }
        $data        = $this->getParsedBody();
        return $data = is_array($data) ? $data : [];
    }

    /**
     * Get parsed JSON body.
     *
     * @since 17xxxx Request object.
     *
     * @param string $type Expected data return type.
     *                     One of: `array`, `object`, `any`.
     *
     * @return \StdClass|array Body as object|array.
     */
    public function getJson(string $type = 'object')
    {
        if ($this->getMediaType() !== 'application/json') {
            return $json = $type === 'array' ? [] : (object) [];
        }
        if ($type === 'array') {
            $json        = $this->getParsedBody();
            return $json = is_array($json) ? $json : [];
            //
        } elseif ($type === 'object') {
            $json        = $this->getParsedBody();
            return $json = $json instanceof \StdClass ? $json : (object) [];
            //
        } else { // Array or object; implies `any`.
            $json        = $this->getParsedBody();
            return $json = is_object($json) || is_array($json) ? $json : (object) [];
        }
    }
}
