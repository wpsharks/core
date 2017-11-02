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
#
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\Cookies;
use Slim\Http\UploadedFile;
use Slim\Http\HeadersInterface;
#
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Request.
 *
 * @since 17xxxx
 */
class Request extends \Slim\Http\Request
{
    /**
     * App.
     *
     * @since 17xxxx
     *
     * @type Classes\App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @param Classes\App      $App            App.
     * @param string           $method         Method.
     * @param UriInterface     $Uri            URI object.
     * @param HeadersInterface $Headers        Headers instance.
     * @param array            $cookies        Array of cookies.
     * @param array            $server_params  Environment variables.
     * @param StreamInterface  $Body           A request body instance.
     * @param array            $uploaded_files Uploaded files collection.
     */
    public function __construct(
        Classes\App $App,
        string $method,
        UriInterface $Uri,
        HeadersInterface $Headers,
        array $cookies,
        array $server_params,
        StreamInterface $Body,
        array $uploaded_files = []
    ) {
        $this->App = $App;
        $method    = $method ?: null;
        parent::construct($method, $Uri, $Headers, $cookies, $server_params, $Body, $uploaded_files);
    }

    /**
     * Create from globals.
     *
     * @since 17xxxx Request object.
     *
     * @param array $globals Globals.
     *
     * @return Request Instance from globals.
     */
    public static function createFromGlobals(array $globals)
    {
        $g   = &$globals;
        $App = $g['App'] ?? null;

        if (!($App instanceof Classes\App)) {
            throw new Exception('Missing App.');
        }
        unset($g['App']); // Ditch this.

        foreach ($g as $_key => $_value) {
            if (mb_stripos((string) $_key, 'CFG_') === 0) {
                unset($g[$_key]);
            }
        } // unset($_key, $_value);

        $method         = $g['REQUEST_METHOD'] ?? '';
        $Uri            = Uri::createFromGlobals($g);
        $Headers        = Headers::createFromGlobals($g);
        $cookies        = Cookies::parseHeader($Headers->get('cookie', []));
        $server_params  = $g; // Simply a copy of globals.
        $Body           = $App->c::createRequestBody();
        $uploaded_files = UploadedFile::createFromGlobals($g);

        $Request = new static($App, $method, $Uri, $Headers, $cookies, $server_params, $Body, $uploaded_files);

        if ($method === 'POST' && in_array($Request->getMediaType(), ['application/x-www-form-urlencoded', 'multipart/form-data'])) {
            $Request = $Request->withParsedBody($_POST);
        }
        return $Request;
    }

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
        if (!in_array($this->getMediaType(), ['application/x-www-form-urlencoded', 'multipart/form-data'], true)) {
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
        } else { // Array or object — either.
            $json        = $this->getParsedBody();
            return $json = is_array($json) || is_object($json) ? $json : (object) [];
        }
    }
}
