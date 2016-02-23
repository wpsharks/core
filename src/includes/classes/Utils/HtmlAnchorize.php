<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Html anchorize utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlAnchorize extends Classes\Core implements Interfaces\EmailConstants, Interfaces\UrlConstants
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
        $Tokenizer = $this->a::tokenize($string, ['shortcodes', 'pre', 'code', 'samp', 'anchors', 'tags', 'md_fences', 'md_links']);
        $string    = &$Tokenizer->getString(); // Now get string by reference.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<url>'.$this->a::regexFrag($this::URL_REGEX_VALID).')/u', function ($m) {
            return $m['before'].'<a href="'.$this->a::escUrl($m['url']).'">'.$this->a::escHtml($m['url']).'</a>';
        }, $string); // Converts full URLs into clickable links using advanced regex.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<host>(?:www|ftp)\.'.$this::URL_REGEX_FRAG_HOST_TLD_PORT.')/u', function ($m) {
            return $m['before'].'<a href="'.$this->a::escUrl('http://'.$m['host'].'/').'">'.$this->a::escHtml($m['host']).'</a>';
        }, $string); // Converts obvious domain name references into clickable links.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<host>'.$this::URL_REGEX_FRAG_HOST.'\.(?:com|net|org)'.$this::URL_REGEX_FRAG_PORT.')/u', function ($m) {
            return $m['before'].'<a href="'.$this->a::escUrl('http://'.$m['host'].'/').'">'.$this->a::escHtml($m['host']).'</a>';
        }, $string); // Converts obvious domain name references into clickable links.

        $string = preg_replace_callback('/(?<before>^|[\s<])(?<email>'.$this->a::regexFrag($this::EMAIL_REGEX_VALID).')/u', function ($m) {
            return $m['before'].'<a href="'.$this->a::escUrl('mailto:'.$m['email']).'">'.$this->a::escHtml($m['email']).'</a>';
        }, $string); // Converts email address into clickable `mailto:` links.

        $string = $Tokenizer->restoreGetString();

        return $string;
    }
}
