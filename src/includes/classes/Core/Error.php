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
     * Default slug.
     *
     * @since 160710
     *
     * @type array
     */
    protected $default_slug;

    /**
     * Default message.
     *
     * @since 160710
     *
     * @type array
     */
    protected $default_message;

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
    public function __construct(Classes\App $App, string $slug = '', string $message = '', $data = null)
    {
        parent::__construct($App);

        $this->errors          = [];
        $this->error_data      = [];
        $this->default_slug    = 'error';
        $this->default_message = __('Unknown error.');

        if ($slug || $message || isset($data)) {
            $this->add($slug, $message, $data);
        }
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
        $slugs = $this->slugs();
        return $slugs[0] ?? $this->default_slug;
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
        $slug     = $slug ?: $this->slug();
        $messages = $this->messages($slug);
        return $messages[0] ?? $this->default_message;
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
        $slugs = array_keys($this->errors);
        return $slugs ?: [$this->default_slug];
    }

    /**
     * Get error messages.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug    Error slug.
     * @param bool   $by_slug All errors by slug?
     *
     * @return string[] An array of error messages.
     */
    public function messages(string $slug = '', bool $by_slug = false): array
    {
        if (!$slug) { // All messages (i.e., for all slugs)?
            //
            if ($by_slug) { // All messages keyed by slug?
                return $this->errors ?: [$this->default_slug => $this->default_message];
                //
            } else { // Merge all messages into a single array.
                $messages = []; // Initialize array.

                foreach ($this->errors as $_slug => $_messages) {
                    $messages = array_merge($messages, $_messages);
                } // unset($_slug, $_messages); // Housekeeping.

                return $messages ?: [$this->default_message];
            }
        } else { // For a specific slug.
            $messages = $this->errors[$slug] ?? [];
            return $messages ?: [$this->default_message];
        }
    }

    /**
     * Get error data.
     *
     * @since 160710 Error utils.
     *
     * @param string $slug    Error slug.
     * @param bool   $by_slug All data by slug?
     *
     * @return array|mixed|null Error data.
     */
    public function data(string $slug = '', bool $by_slug = false)
    {
        if (!$slug) { // All data (i.e., for all slugs)?
            //
            if ($by_slug) { // All data keyed by slug?
                return $this->error_data ?: [$this->default_slug => null];
                //
            } else { // Data for the first slug.
                // NOTE: Different than `messages()`.
                $slug = $this->slug(); // First slug.
                return $this->error_data[$slug] ?? null;
            }
        } else {
            return $this->error_data[$slug] ?? null;
        }
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
    public function add(string $slug, string $message = '', $data = null)
    {
        $slug = $slug ?: $this->default_slug;

        if (!$message && $slug === $this->default_slug) {
            $message = $this->default_message;
        } elseif (!$message) { // If empty, base it on slug.
            $message = mb_strtolower($this->c::slugToName($slug));
            $message = $this->c::mbUcFirst($message).'.';
        }
        $this->errors[$slug][] = $message;

        if (isset($data) || !array_key_exists($slug, $this->error_data)) {
            $this->error_data[$slug] = $data;
        }
    }

    /**
     * Remove error(s).
     *
     * @since 160710 Error utils.
     *
     * @param string $slug Error slug.
     */
    public function remove(string $slug = '')
    {
        if (!$slug) {
            $this->errors     = [];
            $this->error_data = [];
        } else {
            unset($this->errors[$slug]);
            unset($this->error_data[$slug]);
        }
    }
}
