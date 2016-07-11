<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Error.
 *
 * @since 160710 Error utils.
 */
class Error extends Classes\Core\Base\Core
{
    /**
     * Errors.
     *
     * @since 160710
     *
     * @type array[]
     */
    protected $errors;

    /**
     * Error data.
     *
     * @since 160710
     *
     * @type array
     */
    protected $error_data;

    /**
     * Class constructor.
     *
     * @since 160710 Error utils.
     *
     * @param Classes\App $App     Instance of App.
     * @param string      $slug    Error slug.
     * @param string      $message Error message.
     * @param mixed       $data    Error data.
     */
    public function __construct(Classes\App $App, string $slug, string $message, $data = null)
    {
        parent::__construct($App);

        if (!isset($slug[0])) {
            $slug = 'error'; // Must have.
        }
        $this->errors[$slug][]   = $message;
        $this->error_data[$slug] = $data;
    }

    /**
     * Get error slug.
     *
     * @since 160710 Error utils.
     *
     * @return string Error slug.
     */
    public function slug(): string
    {
        $slugs       = $this->slugs();
        return $slug = $slugs ? $slugs[0] : '';
    }

    /**
     * Get error slug.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug Error slug.
     *
     * @return string Error message.
     */
    public function message(string $slug = ''): string
    {
        if (!isset($slug[0])) {
            $slug = $this->slug();
        }
        $messages       = $this->messages($slug);
        return $message = $messages ? $messages[0] : '';
    }

    /**
     * Get error slugs.
     *
     * @since 160710 Error utils.
     *
     * @return string[] Error slugs.
     */
    public function slugs(): array
    {
        return array_keys($this->errors);
    }

    /**
     * Get error messages.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug Error slug.
     *
     * @return string[] An array of error messages.
     */
    public function messages(string $slug = ''): array
    {
        if (!isset($slug[0])) {
            $messages = []; // Initialize.

            foreach ($this->errors as $_slug => $_messages) {
                $messages = array_merge($messages, $_messages);
            } // unset($_slug, $_messages); // Housekeeping.

            return $messages;
        }
        return $this->errors[$slug] ?? [];
    }

    /**
     * Add error.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug    Error slug.
     * @param string $message Error message.
     * @param mixed  $data    Error data.
     */
    public function add(string $slug, string $message, $data = null)
    {
        if (!isset($slug[0])) {
            $slug = 'error'; // Must have.
        }
        $this->errors[$slug][]   = $message;
        $this->error_data[$slug] = $data;
    }

    /**
     * Add data.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug Error slug.
     * @param mixed  $data Error data.
     */
    public function addData(string $slug, $data)
    {
        if (!isset($slug[0])) {
            $slug = 'error'; // Must have.
        }
        $this->error_data[$slug] = $data;
    }

    /**
     * Remove error.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug Error slug.
     */
    public function remove(string $slug)
    {
        if (!isset($slug[0])) {
            $slug = 'error'; // Must have.
        }
        unset($this->errors[$slug]);
        unset($this->error_data[$slug]);
    }
}
