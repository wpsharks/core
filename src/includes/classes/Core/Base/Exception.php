<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Base;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Exception.
 *
 * @since 150424 Initial release.
 */
class Exception extends \Exception
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param string          $message  Message.
     * @param string          $slug     Exception code (slug).
     * @param \Exception|null $previous Previous exception.
     */
    public function __construct(string $message, string $slug = '', \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->code = $slug; // String code (slug).
    }
}
