<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Traits;

/**
 * Email-related constants.
 *
 * @since 150424 Initial release.
 */
interface EmailConstants
{
    /**
     * Regex matches a `user`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     *
     * @see https://en.wikipedia.org/wiki/Email_address#Local_part
     */
    const EMAIL_REGEX_FRAG_USER = // Local part of email address.
        '(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_!#$%&*+=?`{}~|\/\^\'\-])+'.// `user` (no dots).
        '(?:\.(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_!#$%&*+=?`{}~|\/\^\'\-])+)*'; // `.name` (dotted names).

    /**
     * Regex matches a `host.tld`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const EMAIL_REGEX_FRAG_HOST = UrlConstants::URL_REGEX_FRAG_HOST;

    /**
     * Regex matches a valid `user@host.tld`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex pattern for use in `preg_match()`.
     *
     * @note This can be used in MySQL by outputting the following:
     *  `echo str_replace(["'", '\\x', '\\p', '\\?'], ["\\'", '\\\\x', '\\\\p', '\\\\?'], Interfaces/EmailConstants::EMAIL_REGEX_VALID);`
     */
    const EMAIL_REGEX_VALID = '/^'.self::EMAIL_REGEX_FRAG_USER.'@'.self::EMAIL_REGEX_FRAG_HOST.'$/u';

    /**
     * Role-based blacklist patterns.
     *
     * @since 151121 Email utilities.
     *
     * @type array Role-based blacklist patterns.
     */
    const EMAIL_ROLE_BASED_USERS = [
        'abuse',
        'admin',
        'billing',
        'compliance',
        'devnull',
        'dns',
        'ftp',
        'help',
        'hostmaster',
        'inoc',
        'ispfeedback',
        'ispsupport',
        'list-request',
        'list',
        'maildaemon',
        'noc',
        'no-reply',
        'noreply',
        'null',
        'phish',
        'phishing',
        'postmaster',
        'privacy',
        'registrar',
        'root',
        'sales',
        'security',
        'spam',
        'support',
        'sysadmin',
        'tech',
        'undisclosed-recipients',
        'unsubscribe',
        'usenet',
        'uucp',
        'webmaster',
        'www',
    ];
}
