<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * Html anchorize utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlAnchorize extends Classes\Core\Base\Core implements Interfaces\EmailConstants, Interfaces\UrlConstants
{
    /**
     * Links in text to HTML anchors.
     *
     * @since 151121 URLs to anchors.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object HTML markup.
     */
    public function __invoke($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $Tokenizer = $this->c::tokenize($string, ['shortcodes', 'pre', 'code', 'samp', 'anchors', 'tags', 'md_fences', 'md_links']);
        $string    = &$Tokenizer->getString(); // Now get string by reference.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<url>'.$this->c::regexFrag($this::URL_REGEX_VALID).')/u', function ($m) {
            return $m['before'].'<a href="'.$this->c::escUrl($m['url']).'">'.$this->c::escHtml($m['url']).'</a>';
        }, $string); // Converts full URLs into clickable links using advanced regex.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<host>(?:www|ftp)\.'.$this::URL_REGEX_FRAG_HOST_TLD_PORT.')/u', function ($m) {
            return $m['before'].'<a href="'.$this->c::escUrl('http://'.$m['host'].'/').'">'.$this->c::escHtml($m['host']).'</a>';
        }, $string); // Converts obvious domain name references into clickable links.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<host>'.$this::URL_REGEX_FRAG_HOST.'\.(?:com|net|org)'.$this::URL_REGEX_FRAG_PORT.')/u', function ($m) {
            return $m['before'].'<a href="'.$this->c::escUrl('http://'.$m['host'].'/').'">'.$this->c::escHtml($m['host']).'</a>';
        }, $string); // Converts obvious domain name references into clickable links.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<email>'.$this->c::regexFrag($this::EMAIL_REGEX_VALID).')/u', function ($m) {
            return $m['before'].'<a href="'.$this->c::escUrl('mailto:'.$m['email']).'">'.$this->c::escHtml($m['email']).'</a>';
        }, $string); // Converts email address into clickable `mailto:` links.

        $string = $Tokenizer->restoreGetString();

        return $string;
    }
}
