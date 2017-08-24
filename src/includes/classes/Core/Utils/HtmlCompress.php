<?php
/**
 * Html compression utilities.
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
 * Html compression utilities.
 *
 * @since 170824.30708 Initial release.
 */
class HtmlCompress extends Classes\Core\Base\Core
{
    /**
     * Compresses HTML markup.
     *
     * @since 170824.30708 Initial release.
     *
     * @param string $html Any HTML markup.
     *
     * @return string Compressed HTML markup.
     */
    public function __invoke(string $html): string
    {
        if (!$html) {
            return $html; // Nothing to do.
        }
        $Tokenizer = $this->c::tokenize($html, [
            'if-conds',
            'pre', 'code', 'samp',
            'style', 'script',
            'textarea',
            's-attrs',
        ]);
        $html = &$Tokenizer->getString(); // By reference.

        $html = preg_replace('/\<\!\-\-.*?\-\-\>/us', '', $html);
        $html = preg_replace('/\s+/u', ' ', $html);
        $html = preg_replace('/\s+\/\>/u', '/>', $html);

        $html        = $Tokenizer->restoreGetString();
        return $html = $html ? $this->c::mbTrim($html) : $html;
    }
}
