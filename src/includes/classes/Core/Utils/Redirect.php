<?php
/**
 * Redirect utils.
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
 * Redirect utils.
 *
 * @since 170824.30708 Spin & reload.
 */
class Redirect extends Classes\Core\Base\Core
{
    /**
     * Redirect.
     *
     * @since 170824.30708 Redirect.
     *
     * @param string $to   URL (required).
     * @param array  $args Any behavioral args.
     */
    public function __invoke(string $to, array $args = [])
    {
        if (!$to) { // Must have.
            throw $this->c::issue('Empty redirect.');
        } elseif (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        $default_args = [
            'cacheable' => false,
            'status'    => 302,
            'top'       => false,
            'exit'      => true,
        ];
        $args += $default_args; // Merge defaults.

        $args['cacheable'] = (bool) $args['cacheable'];
        $args['status']    = (int) $args['status'];
        $args['top']       = (bool) $args['top'];
        $args['exit']      = (bool) $args['exit'];

        if (!$args['cacheable']) {
            $this->c::noCacheFlags();
            $this->c::noCacheHeaders();
        }
        if ($args['top']) {
            $this->c::statusHeader(200);
            echo '<!DOCTYPE html>';
            echo '<html>';
            echo '  <head>';
            echo '      <script>'; // Break frames.
            echo '          top.location.href = '.$this->c::sQuote($to).';';
            echo '      </script>';
            echo '  </head>';
            echo '</html>';
        } else {
            header('location: '.$to, true, $args['status']);
        }
        if ($args['exit']) {
            exit();
        }
    }
}
