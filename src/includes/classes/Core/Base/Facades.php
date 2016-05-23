<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Base;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * Pseudo-static facades.
 *
 * @since 160223 Initial release.
 */
abstract class Facades
{
    use Traits\Facades\Apache;
    use Traits\Facades\App;
    use Traits\Facades\Arrays;
    use Traits\Facades\Base64;
    use Traits\Facades\Benchmark;
    use Traits\Facades\Bitly;
    use Traits\Facades\Cli;
    use Traits\Facades\Clippers;
    use Traits\Facades\Cookies;
    use Traits\Facades\Dimensions;
    use Traits\Facades\Dirs;
    use Traits\Facades\Dump;
    use Traits\Facades\Email;
    use Traits\Facades\Encryption;
    use Traits\Facades\Eols;
    use Traits\Facades\Escapes;
    use Traits\Facades\Exceptions;
    use Traits\Facades\Files;
    use Traits\Facades\Gravatar;
    use Traits\Facades\Headers;
    use Traits\Facades\Html;
    use Traits\Facades\Ips;
    use Traits\Facades\Iterators;
    use Traits\Facades\Markdown;
    use Traits\Facades\Memcache;
    use Traits\Facades\Multibyte;
    use Traits\Facades\Names;
    use Traits\Facades\OEmbed;
    use Traits\Facades\Os;
    use Traits\Facades\Output;
    use Traits\Facades\Patterns;
    use Traits\Facades\Pdo;
    use Traits\Facades\Percentages;
    use Traits\Facades\Php;
    use Traits\Facades\Replacements;
    use Traits\Facades\Serializer;
    use Traits\Facades\Sha1Mods;
    use Traits\Facades\Slashes;
    use Traits\Facades\Slugs;
    use Traits\Facades\Spellcheck;
    use Traits\Facades\Templates;
    use Traits\Facades\Tokenizer;
    use Traits\Facades\Translits;
    use Traits\Facades\UrlBuilders;
    use Traits\Facades\UrlCurrent;
    use Traits\Facades\UrlParsers;
    use Traits\Facades\UrlQuerys;
    use Traits\Facades\UrlUtils;
    use Traits\Facades\Uuid64;
    use Traits\Facades\Uuids;
    use Traits\Facades\Vars;
    use Traits\Facades\Versions;
    use Traits\Facades\Webpurify;
    use Traits\Facades\WordPress;
    use Traits\Facades\Yaml;
}
