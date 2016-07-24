<?php
/**
 * Apache utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Apache utilities.
 *
 * @since 160522 Apache utilities.
 */
class Apache extends Classes\Core\Base\Core
{
    /**
     * Deny public access.
     *
     * @since 160522 Apache utilities.
     *
     * @param string $dir                 Directory to deny access in.
     * @param bool   $allow_static_access Allow static file access?
     *
     * @return int Number of bytes written to file.
     */
    public function htaccessDeny(string $dir, bool $allow_static_access = false): int
    {
        $deny = // `.htaccess` file.
            '<IfModule authz_core_module>'."\n".
            '	Require all denied'."\n".
            '</IfModule>'."\n".
            '<IfModule !authz_core_module>'."\n".
            '	deny from all'."\n".
            '</IfModule>';

        if ($allow_static_access) {
            $deny .= "\n\n".// Allow these file extensions.
            '<FilesMatch "\.(js|css|gif|jpg|png|svg|eot|woff|ttf|html|txt|md)$">'."\n".
            '	<IfModule authz_core_module>'."\n".
            '		Require all granted'."\n".
            '	</IfModule>'."\n".
            '	<IfModule !authz_core_module>'."\n".
            '		allow from all'."\n".
            '	</IfModule>'."\n".
            '</FilesMatch>';
        }
        return (int) file_put_contents($dir.'/.htaccess', $deny);
    }
}
