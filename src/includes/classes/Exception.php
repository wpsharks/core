<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Exception.
 *
 * @since 150424 Initial release.
 */
class Exception extends \Exception
{
    protected $error_code;
    protected $reason_code;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(string $message, string $code = '', Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->code = $code; // String code.

        if (mb_strpos($this->code, '.') !== false) {
            $this->error_code  = mb_strstr($this->code, '.', true);
            $this->reason_code = mb_substr(mb_strstr($this->code, '.'), 1);
        } else {
            $this->error_code = $this->reason_code = '';
        }
    }

    /**
     * Get exception code.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Exception code.
     */
    public function getErrorCode(): string
    {
        return $this->error_code;
    }

    /**
     * Get exception reason.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Exception reason.
     */
    public function getReasonCode(): string
    {
        return $this->reason_code;
    }
}
