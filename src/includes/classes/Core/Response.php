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
     * @param Classes\App                       $App     App.
     * @param int                               $status  Status.
     * @param HeadersInterface|null             $Headers Headers.
     * @param ResponseBody|StreamInterface|null $Body    Body/stream.
     */
    public function __construct(
        Classes\App $App,
        int $status = 200,
        HeadersInterface $Headers = null,
        StreamInterface $Body = null
    ) {
        $this->App = $App;
        parent::__construct($status, $Headers, $Body);
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
        $clone = $this->withoutHeader('last-modified');

        foreach ($this->App->c::noCacheHeadersArray() as $_header => $_value) {
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
        $is_data_array = is_array($data);

        if (!isset($as_json) && $is_data_array) {
            $as_json = $this->App->c::isApi() || $this->App->c::isAjax();
        }
        if ($as_json) { // Format as JSON?
            $content_type = 'application/json';
            $data         = $is_data_array ? $data : [];
            $content      = json_encode(['success' => true] + $data, JSON_PRETTY_PRINT);
        } else {
            $content      = is_string($data) ? $data : '';
            $content_type = 'text/'.($this->App->c::isHtml($content, true) ? 'html' : 'plain');
        }
        $clone = $this->withStatus($status);
        $clone = $clone->withHeader('content-type', $content_type.'; charset=utf-8');

        $Body         = $this->App->c::createReponseBody($content);
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
        $is_data_error = $data && $this->App->c::isError($data);

        if (!isset($as_json) && $is_data_error) {
            $as_json = $this->App->c::isApi() || $this->App->c::isAjax();
        }
        if ($as_json) { // Format as JSON?
            $data         = $is_data_error ? $data : null;
            $Error        = $data ?: $this->App->c::error();
            $content_type = 'application/json';
            $content      = json_encode([
                'success' => false,
                'error'   => [
                    'code'    => $status,
                    'slug'    => $Error->slug(),
                    'message' => $Error->message(),
                ],
            ], JSON_PRETTY_PRINT);
        } else {
            $data         = is_string($data) ? $data : '';
            $content      = $data ?: $this->App->c::statusHeaderMessage($status);
            $content_type = 'text/'.($this->App->c::isHtml($content, true) ? 'html' : 'plain');
        }
        $clone = $this->withStatus($status);
        $clone = $clone->withHeader('content-type', $content_type.'; charset=utf-8');

        $Body         = $this->App->c::createReponseBody($content);
        return $clone = $clone->withBody($Body);
    }

    /**
     * Output response.
     *
     * @since 17xxxx Response utils.
     *
     * @param array $args Any behavioral args.
     */
    public function output(array $args = [])
    {
        $default_args = [
            'exit' => false,
        ];
        $args += $default_args; // Merge defaults.
        $args['exit'] = (bool) $args['exit'];

        if ($this->hasNoCache()) {
            $this->App->c::noCacheFlags();
        }
        $status   = $this->getStatusCode();
        $protocol = 'HTTP/'.$this->getProtocolVersion();

        $this->App->c::statusHeader($status, $protocol);

        foreach (array_keys($this->getHeaders()) as $_header) {
            header($_header.': '.$this->getHeaderLine($_header));
        } // unset($_header); // Housekeeping.

        $Body = $this->getBody();
        $Body->rewind(); // Rewind.

        while (!$Body->eof()) {
            echo $Body->read(262144); // 256kbs at a time.
        }
        if ($args['exit']) {
            exit; // Stop here.
        }
    }
}
