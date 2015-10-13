<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

/**
 * Regex-related constants.
 *
 * @since 150424 Initial release.
 */
interface RegexConstants
{
    /**
     * Finds a double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    const REGEX_FRAG_DQ_VALUE = '(?P<open_dq>(?<!\\\\)")(?P<dq_value>(?s:\\\\.|[^\\\\"])*?)(?P<close_dq>")';

    /**
     * Finds a single quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    const REGEX_FRAG_SQ_VALUE = '(?P<open_sq>(?<!\\\\)\')(?P<sq_value>(?s:\\\\.|[^\\\\\'])*?)(?P<close_sq>\')';

    /**
     * Finds a single or double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regular expression fragment (dot matches newline inside quotes).
     */
    const REGEX_FRAG_DSQ_VALUE = '(?P<open_dsq>(?<!\\\\)["\'])(?P<dsq_value>(?s:\\\\.|(?!\\\\|(?P=open_dsq)).)*?)(?P<close_dsq>(?P=open_dsq))';
}
