<?php
namespace WebSharks\Core\Traits;

/**
 * Markdown utilities.
 *
 * @since 150424 Initial release.
 */
trait MarkdownUtils
{
    abstract protected function envHasFunction($function);
    abstract protected function &staticKey($function, $args = array());
    abstract protected function htmlTokenizeSpcsm($string, array $tokenize_only = array(), $marker = '');
    abstract protected function htmlTokensRestoreSpcsm(array $spcsm);

    /**
     * A very simple markdown parser.
     *
     * @param string $string Input string to convert.
     * @param array  $args   Any additional behavioral args.
     *
     * @return string Markdown converted to HTML markup.
     */
    protected function markdown($string, array $args = array())
    {
        if (!($string = trim((string) $string))) {
            return $string; // Not possible.
        }
        $default_args = [
            'flavor' => 'php-markdown-extra',
            // `parsedown-extra` is faster, but buggy.
            // See: <https://github.com/erusev/parsedown-extra/issues/44>
            'oembed' => false,
            'breaks' => true,
            'no_p'   => false,
        ];
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $flavor = (string) $args['flavor'];
        $oembed = (boolean) $args['oembed'];
        $breaks = (boolean) $args['breaks'];
        $no_p   = (boolean) $args['no_p'];

        if ($oembed && strpos($string, '://') !== false
                && $this->envHasFunction('wp_embed_defaults')
                && $this->envHasFunction('wp_oembed_get')) {
            $_spcsm           = $this->htmlTokenizeSpcsm($string);
            $_oembed_args     = array_merge(wp_embed_defaults(), ['discover' => false]);
            $_spcsm['string'] = preg_replace_callback('/^\s*(https?:\/\/[^\s"]+)\s*$/im', function ($m) use ($_oembed_args) {
                $oembed = wp_oembed_get($m[1], $_oembed_args);
                return $oembed ? $oembed : $m[0];
            }, $_spcsm['string']);
            $string           = $this->htmlTokensRestoreSpcsm($_spcsm);
            unset($_spcsm, $_oembed_args); // Housekeeping.
        }
        if ($flavor === 'parsedown-extra') {
            if (is_null($parsedown_extra = &$this->staticKey(__FUNCTION__, $flavor))) {
                $parsedown_extra = new \ParsedownExtra();
            }
            $parsedown_extra->setBreaksEnabled($breaks);
            $html = $parsedown_extra->text($string);
        } else {
            $flavor = 'php-markdown-extra'; // Default flavor.
            if (is_null($php_markdown_extra = &$this->staticKey(__FUNCTION__, $flavor))) {
                $php_markdown_extra                    = new \Michelf\MarkdownExtra();
                $php_markdown_extra->code_class_prefix = 'language-';
            }
            $html = $php_markdown_extra->transform($string);
        }
        if ($no_p) {
            $html = preg_replace('/^\<p\>/i', '', $html);
            $html = preg_replace('/\<\/p\>$/i', '', $html);
        }
        return $html; // Gotta love it! :-)
    }
}
