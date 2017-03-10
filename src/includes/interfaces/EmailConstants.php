<?php
/**
 * Email-related constants.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

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
     * @var string Regex fragment for use in `preg_match()`.
     *
     * @link https://en.wikipedia.org/wiki/Email_address#Local_part
     */
    const EMAIL_REGEX_FRAG_USER = // Local part of email address.
        '(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_!#$%&*+=?`{}~|\/\^\'\-])+'.// `user` (no dots).
        '(?:\.(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_!#$%&*+=?`{}~|\/\^\'\-])+)*'; // `.name` (dotted names).

    /**
     * Regex matches a `host.tld`.
     *
     * @since 150424 Initial release.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const EMAIL_REGEX_FRAG_HOST = UrlConstants::URL_REGEX_FRAG_HOST;

    /**
     * Regex matches a valid `user@host.tld`.
     *
     * @since 150424 Initial release.
     *
     * @var string Regex pattern for use in `preg_match()`.
     *
     * @internal This can be used in MySQL by outputting the following:
     *  `echo str_replace(["'", '\\x', '\\p', '\\?'], ["\\'", '\\\\x', '\\\\p', '\\\\?'], Interfaces/EmailConstants::EMAIL_REGEX_VALID);`
     */
    const EMAIL_REGEX_VALID = '/^'.self::EMAIL_REGEX_FRAG_USER.'@'.self::EMAIL_REGEX_FRAG_HOST.'$/u';

    /**
     * Role-based regex patterns.
     *
     * @since 151121 Email utilities.
     *
     * @var array Role-based regex patterns.
     */
    const EMAIL_ROLE_BASED_REGEX_FRAGS = [
        '(list)?(un)?subscribes?',
        '(the)?office(admin)?s?',
        'administrat(or|ion)s?',
        'admins?',
        'advisors?',
        'authors?',
        'ceos?',
        'communit(y|ie)s?',
        'competes?',
        'consultants?',
        'contact(us)?',
        'contributors?',
        'customer(care|service|teacher)?s?',
        'daemons?',
        'deans?',
        'design(er)?s?',
        'dev(eloper|el|null)?s?',
        'digsites?value',
        'director(y|ie)?s?',
        'directors?',
        'dns[0-9]*',
        'downloads?',
        'editorials?',
        'editors?',
        'enq(quire|quirie|quiry)?s?',
        'errors?',
        'everyones?',
        'exec(utive)?s?',
        'experts?',
        'exports?',
        'fbls?',
        'ftps?[0-9]*',
        'investorrelations?',
        'isp(feedback|support)',
        'jobs?',
        'listmasters?',
        'listrequests?',
        'lists?',
        'mail(er|daemon)?s?',
        'masters?',
        'operations?',
        'phish(ing)?s?',
        'post(box|master)?s?',
        'principals?',
        'recruit(ing)?s?',
        'registrar?s',
        'remov(e|al)s?',
        'requests?',
        'roots?',
        'sales?',
        'school(office)?s?',
        'secretar(y|ie)?s?',
        'spam(mer)?s?',
        'sysadmins?',
        'techs?',
        'undisclosed(recipient)?s?',
        'usenets?',
        'users?',
        'webmasters?',
        'welcome',
        'www[0-9]*',
    ];

    /**
     * Role-based strings.
     *
     * @since 151121 Email utilities.
     *
     * @var array Role-based strings.
     */
    const EMAIL_ROLE_BASED_STRINGS = [
        'abuse',
        'administracion',
        'alexa',
        'all',
        'available',
        'billing',
        'bursar',
        'busdev',
        'compliance',
        'contacto',
        'coop',
        'crew',
        'data',
        'help',
        'hr',
        'info',
        'information',
        'informativo',
        'inoc',
        'marketing',
        'media',
        'noc',
        'noreply',
        'null',
        'prime',
        'privacy',
        'reception',
        'security',
        'support',
        'usenet',
        'uucp',
    ];
}
