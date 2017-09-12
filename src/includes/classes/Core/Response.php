<?php
/**
 * Response.
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
use Slim\Http\Body;
use Slim\Http\HeadersInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Response.
 *
 * @since 17xxxx
 */
class Response extends \Slim\Http\Response
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
     * @since 17xxxx Response utils.
     *
     * @param Classes\App           $App     App.
     * @param int                   $status  Status.
     * @param HeadersInterface|null $Headers Headers.
     * @param StreamInterface|null  $Stream  Stream.
     */
    public function __construct(Classes\App $App, int $status = 200, HeadersInterface $Headers = null, StreamInterface $Stream = null)
    {
        $this->App = $App; // Before parent contructor.
        parent::__construct($status, $Headers, $Stream);
    }

    /**
     * With no-cache headers.
     *
     * @since 17xxxx Response utils.
     *
     * @return Response w/ no-cache headers.
     */
    public function withNoCache(): self
    {
        $c = $this->App->c;

        $clone = $this->withoutHeader('last-modified');

        foreach ($c::noCacheHeadersArray() as $_header => $_value) {
            $clone = $clone->withHeader($_header, $_value);
        } // unset($_header, $_value);

        return $clone; // Response.
    }

    /**
     * Has no-cache headers?
     *
     * @since 17xxxx Response utils.
     *
     * @return bool True if has no-cache headers.
     */
    public function hasNoCache(): bool
    {
        $cache_control = $this->getHeaderLine('cache-control');
        return $cache_control && mb_stripos($cache_control, 'no-cache') !== false;
    }

    /**
     * Success response.
     *
     * @since 17xxxx Response utils.
     *
     * @param int          $status  Status.
     * @param array|string $data    Props/markup.
     * @param bool         $as_json As JSON?
     *
     * @return Response w/ success response.
     */
    public function withSuccess(int $status, $data, bool $as_json = null): self
    {
        $c             = $this->App->c;
        $is_data_array = is_array($data);

        if (!isset($as_json) && $is_data_array) {
            $as_json = $c::isApi() || $c::isAjax();
        }
        if ($as_json) { // Format as JSON?
            $content_type = 'application/json';
            $data         = $is_data_array ? $data : [];
            $content      = json_encode(['success' => true] + $data);
        } else {
            $content      = is_string($data) ? $data : '';
            $content_type = 'text/'.($c::isHtml($content, true) ? 'html' : 'plain');
        }
        $clone = $this->withStatus($status);
        $clone = $clone->withHeader('content-type', $content_type.'; charset=utf-8');

        $Body = new Body(fopen('php://temp', 'r+'));
        $Body->write($content); // Content body.

        return $clone = $clone->withBody($Body);
    }

    /**
     * Error response.
     *
     * @since 17xxxx Response utils.
     *
     * @param int          $status  Status.
     * @param Error|string $data    Error|markup.
     * @param bool         $as_json As JSON?
     *
     * @return Response w/ error response.
     */
    public function withError(int $status, $data, bool $as_json = null): self
    {
        $c             = $this->App->c;
        $is_data_error = $data && $c::isError($data);

        if (!isset($as_json) && $is_data_error) {
            $as_json = $c::isApi() || $c::isAjax();
        }
        if ($as_json) { // Format as JSON?
            $data         = $is_data_error ? $data : null;
            $Error        = $data ?: $c::error();
            $content_type = 'application/json';
            $content      = json_encode([
                'success' => false,
                'error'   => [
                    'code'    => $status,
                    'slug'    => $Error->slug(),
                    'message' => $Error->message(),
                ],
            ]);
        } else {
            $data         = is_string($data) ? $data : '';
            $content      = $data ?: $c::statusHeaderMessage($status);
            $content_type = 'text/'.($c::isHtml($content, true) ? 'html' : 'plain');
        }
        $clone = $this->withStatus($status);
        $clone = $clone->withHeader('content-type', $content_type.'; charset=utf-8');

        $Body = new Body(fopen('php://temp', 'r+'));
        $Body->write($content); // Content body.

        return $clone = $clone->withBody($Body);
    }

    /**
     * Output response.
     *
     * @since 17xxxx Response utils.
     */
    public function output()
    {
        $c = $this->App->c;

        if ($this->hasNoCache()) {
            $c::noCacheFlags();
        }
        $status   = $this->getStatusCode();
        $protocol = 'HTTP/'.$this->getProtocolVersion();

        $c::statusHeader($status, $protocol);

        foreach (array_keys($this->getHeaders()) as $_header) {
            header($_header.': '.$this->getHeaderLine($_header));
        } // unset($_header); // Housekeeping.

        $Body = $this->getBody();
        $Body->rewind(); // Rewind.

        while (!$Body->feof()) {
            echo $Body->read(262144); // 256kbs at a time.
        }
    }
}
