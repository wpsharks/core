<?php
/**
 * Captcha utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Captcha utilities.
 *
 * @since 170309.60830 Captcha utilities.
 */
class Captcha extends Classes\Core\Base\Core
{
    /**
     * Verifies Google reCAPTCHA.
     *
     * @since 170309.60830 Captcha utilities.
     *
     * @param string      $token      reCAPTCHA token from user.
     * @param string|null $ip         The user's remote IP address.
     * @param string|null $secret_key reCAPTCHA secret key.
     *
     * @return bool True if reCAPTCHA is valid.
     */
    public function recaptchaVerify(string $token, string $ip = null, string $secret_key = null): bool
    {
        if (!isset($ip)) {
            if (!$this->c::isCli()) {
                $ip = $this->c::currentIp();
            } else {
                $ip = ''; // Not possible.
            }
        } // Defaults to current IP address.

        if (!isset($secret_key)) {
            $secret_key = $this->App->Config->©recaptcha['©secret_key'];
        } // Defaults to global config value.

        $remote_args = [
            'body' => [
                'secret'   => $secret_key,
                'response' => $token,
                'remoteip' => $ip,
            ],
        ];
        $endpoint = 'https://www.google.com/recaptcha/api/siteverify';
        $response = json_decode($this->c::remoteResponse($endpoint, $remote_args)->body);

        return $is_valid = !empty($response->success);
    }
}
