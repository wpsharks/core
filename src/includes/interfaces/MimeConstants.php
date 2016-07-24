<?php
/**
 * MIME-related constants.
 *
 * @author @jaswsinc
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
 * MIME-related constants.
 *
 * @since 150424 Initial release.
 */
interface MimeConstants
{
    /**
     * UTF8 for some MIME types.
     *
     * @since 150424 Initial release.
     *
     * @var string `; charset=utf-8`
     */
    const MIME_CHARSET_UTF8 = '; charset=utf-8';

    /**
     * Known MIME types.
     *
     * @since 150424 Initial release.
     *
     * @var array Keys are file extensions.
     */
    const MIME_TYPES = [
        // Text files.
        'md'  => 'text/plain'.self::MIME_CHARSET_UTF8,
        'txt' => 'text/plain'.self::MIME_CHARSET_UTF8,

        // Log files.
        'log' => 'text/plain'.self::MIME_CHARSET_UTF8,

        // Translation files.
        'mo'  => 'application/x-gettext-translation',
        'po'  => 'text/x-gettext-translation'.self::MIME_CHARSET_UTF8,
        'pot' => 'text/x-gettext-translation'.self::MIME_CHARSET_UTF8,

        // SQL files.
        'sql'    => 'text/plain'.self::MIME_CHARSET_UTF8,
        'sqlite' => 'text/plain'.self::MIME_CHARSET_UTF8,

        // Template files.
        'tmpl' => 'text/plain'.self::MIME_CHARSET_UTF8,
        'tpl'  => 'text/plain'.self::MIME_CHARSET_UTF8,

        // Server config files.
        'admins'          => 'text/plain'.self::MIME_CHARSET_UTF8,
        'cfg'             => 'text/plain'.self::MIME_CHARSET_UTF8,
        'conf'            => 'text/plain'.self::MIME_CHARSET_UTF8,
        'htaccess'        => 'text/plain'.self::MIME_CHARSET_UTF8,
        'htaccess-apache' => 'text/plain'.self::MIME_CHARSET_UTF8,
        'htpasswd'        => 'text/plain'.self::MIME_CHARSET_UTF8,
        'ini'             => 'text/plain'.self::MIME_CHARSET_UTF8,

        // CSS/JavaScript files.
        'css'  => 'text/css'.self::MIME_CHARSET_UTF8,
        'js'   => 'application/javascript'.self::MIME_CHARSET_UTF8,
        'json' => 'application/json'.self::MIME_CHARSET_UTF8,

        // PHP scripts/files.
        'php'   => 'text/html'.self::MIME_CHARSET_UTF8,
        'phps'  => 'application/x-php-source'.self::MIME_CHARSET_UTF8,
        'x-php' => 'application/x-php-source'.self::MIME_CHARSET_UTF8,

        // ASP scripts/files.
        'asp'  => 'text/html'.self::MIME_CHARSET_UTF8,
        'aspx' => 'text/html'.self::MIME_CHARSET_UTF8,

        // Perl scripts/files.
        'cgi' => 'text/html'.self::MIME_CHARSET_UTF8,
        'pl'  => 'text/html'.self::MIME_CHARSET_UTF8,

        // HTML/XML files.
        'dtd'   => 'application/xml-dtd'.self::MIME_CHARSET_UTF8,
        'hta'   => 'application/hta'.self::MIME_CHARSET_UTF8,
        'htc'   => 'text/x-component'.self::MIME_CHARSET_UTF8,
        'htm'   => 'text/html'.self::MIME_CHARSET_UTF8,
        'html'  => 'text/html'.self::MIME_CHARSET_UTF8,
        'shtml' => 'text/html'.self::MIME_CHARSET_UTF8,
        'xhtml' => 'application/xhtml+xml'.self::MIME_CHARSET_UTF8,
        'xml'   => 'text/xml'.self::MIME_CHARSET_UTF8,
        'xsl'   => 'application/xslt+xml'.self::MIME_CHARSET_UTF8,
        'xslt'  => 'application/xslt+xml'.self::MIME_CHARSET_UTF8,
        'xsd'   => 'application/xsd+xml'.self::MIME_CHARSET_UTF8,

        // Document files.
        'csv'  => 'text/csv'.self::MIME_CHARSET_UTF8,
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'odt'  => 'application/vnd.oasis.opendocument.text',
        'pdf'  => 'application/pdf',
        'rtf'  => 'application/rtf',
        'xls'  => 'application/vnd.ms-excel',

        // Image/animation files.
        'ai'       => 'image/vnd.adobe.illustrator',
        'bmp'      => 'image/bmp',
        'eps'      => 'image/eps',
        'gif'      => 'image/gif',
        'ico'      => 'image/x-icon',
        'jpe'      => 'image/jpeg',
        'jpeg'     => 'image/jpeg',
        'jpg'      => 'image/jpeg',
        'png'      => 'image/png',
        'psd'      => 'image/vnd.adobe.photoshop',
        'pspimage' => 'image/vnd.corel.psp',
        'svg'      => 'image/svg+xml',
        'tif'      => 'image/tiff',
        'tiff'     => 'image/tiff',

        // Audio files.
        'mid'  => 'audio/midi',
        'midi' => 'audio/midi',
        'mp3'  => 'audio/mp3',
        'wav'  => 'audio/wav',
        'wma'  => 'audio/x-ms-wma',

        // Video files.
        'avi'  => 'video/avi',
        'flv'  => 'video/x-flv',
        'ogg'  => 'video/ogg',
        'ogv'  => 'video/ogg',
        'mp4'  => 'video/mp4',
        'mov'  => 'movie/quicktime',
        'mpg'  => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'qt'   => 'video/quicktime',
        'webm' => 'video/webm',
        'wmv'  => 'audio/x-ms-wmv',
        'fla'      => 'application/vnd.adobe.flash',
        'swf'      => 'application/x-shockwave-flash',
        'blend'    => 'application/x-blender',

        // Font files.
        'eot'   => 'application/vnd.ms-fontobject',
        'otf'   => 'application/x-font-otf',
        'ttf'   => 'application/x-font-ttf',
        'woff'  => 'application/x-font-woff',
        'woff2' => 'application/x-font-woff',

        // Archive files.
        'zip'  => 'application/zip',
        'tar'  => 'application/x-tar',
        'gz'   => 'application/gzip',
        'tgz'  => 'application/gzip',
        'gtar' => 'application/x-gtar',
        'rar'  => 'application/x-rar-compressed',
        'phar' => 'application/php-archive',
        '7z'   => 'application/x-7z-compressed',
        'jar'  => 'application/java-archive',
        'dmg'  => 'application/x-apple-diskimage',
        'iso'  => 'application/iso-image',

        // Other misc files.
        'bat'   => 'application/octet-stream',
        'bin'   => 'application/octet-stream',
        'class' => 'application/octet-stream',
        'com'   => 'application/octet-stream',
        'dll'   => 'application/octet-stream',
        'exe'   => 'application/octet-stream',
        'sh'    => 'application/octet-stream',
        'bash'  => 'application/octet-stream',
        'zsh'   => 'application/octet-stream',
        'so'    => 'application/octet-stream',
    ];
}
