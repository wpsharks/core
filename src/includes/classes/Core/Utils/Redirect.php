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
 * @since 17xxxx Spin & reload.
 */
class Redirect extends Classes\Core\Base\Core
{
    /**
     * Redirect.
     *
     * @since 17xxxx Spin & reload.
     *
     * @param string $to   URL (required).
     * @param array  $args Any behavioral args.
     */
    public function __invoke(string $to, array $args = [])
    {
        if (!$to) { // Must have a location.
            throw $this->c::issue('Empty redirect.');
            //
        } elseif (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        $default_args = [
            'cacheable' => false,
            'top'       => false,
            'exit'      => true,
        ];
        $args += $default_args; // Merge defaults.
        $args['cacheable'] = (bool) $args['cacheable'];
        $args['top']       = (bool) $args['top'];
        $args['exit']      = (bool) $args['exit'];

        if (!$args['cacheable']) {
            $this->c::noCacheFlags();
            $this->c::noCacheHeaders();
        }
        if ($args['top']) {
            echo '<!DOCTYPE html>';
            echo '<html>';
            echo '  <head>';
            echo '      <script>'; // Breaks frames.
            echo '          top.location.href = '.$this->c::sQuote($to).';';
            echo '      </script>';
            echo '  </head>';
            echo '</html>';
        } else {
            header('location: '.$to);
        }
        if ($args['exit']) {
            exit(); // Default behavior.
        }
    }
}
