<?php
/**
 * OEmbed utilities.
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
#
use Embedly\Embedly;

/**
 * OEmbed utilities.
 *
 * @since 151125 OEmbed utilities.
 */
class OEmbed extends Classes\Core\Base\Core implements Interfaces\UrlConstants
{
    /**
     * Cache directory.
     *
     * @since 150424
     *
     * @var string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->cache_dir = $this->App->Config->©fs_paths['©cache_dir'].'/oembed';
    }

    /**
     * OEmbed parser.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Html markup value(s).
     */
    public function __invoke($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $args);
            } //unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (mb_strpos($string, '://') === false) {
            return $string; // Nothing to do.
        }
        $default_args = ['via' => ''];
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $via = (string) $args['via']; // Force string.

        $Tokenizer = $this->c::tokenize(
            $string,
            [
                'shortcodes',
                'pre',
                'code',
                'samp',
                'anchors',
                'md-fences',
                'md-links',
            ]
        ); // Now get string by reference.
        $string = &$Tokenizer->getString();

        switch ($via) {
            case 'embedly':
                $string = $this->viaEmbedly($string);
                break; // All done here.

            case 'wordpress':
                $string = $this->viaWordPress($string);
                break; // All done here.

            default: // Defaults.
                if ($this->c::isWordPress()) {
                    $string = $this->viaWordPress($string);
                } else { // Default outside WP.
                    $string = $this->viaEmbedly($string);
                } // See {@link viaEmbedly()} routines below.
        }
        return $string = $Tokenizer->restoreGetString();
    }

    /**
     * Via Embedly.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string $string String to parse.
     */
    protected function viaEmbedly(string $string): string
    {
        # Require a valid API key for Embedly.

        if (!$this->App->Config->©embedly['©api_key']) {
            throw $this->c::issue('Missing Embedly API key.');
        }
        # Initialize several variables.

        $tokens  = $uncached_urls  = $new_embeds  = [];
        $marker  = str_replace('.', '', uniqid('', true));
        $Embedly = new Embedly(['key' => $this->App->Config->©embedly['©api_key']]);

        # Tokenize all URLs on a line of their own.

        $string = preg_replace_callback($this::URL_REGEX_VALID.'m', function ($m) use (&$tokens, &$uncached_urls, $marker) {
            $token = '%#%oembed-token-'.$marker.'-'.(count($tokens) - 1).'%#%';
            $tokens[$token] = $uncached_urls[$m[0]] = $m[0];
            return $token; // Replaced w/ unique token.
        }, $string); // Tokenizes all possible embed URLs.

        # Replace and flag URLs already cached.

        foreach ($tokens as $_token => $_url) {
            if (!($_embed = $this->getEmbedlyCache($_url))) {
                continue; // Not cached yet.
            }
            unset($uncached_urls[$_url]); // Cached already.

            if ($_embed->type === 'error') {
                continue; // Not possible.
            } elseif (!empty($_embed->error_code)) {
                continue; // Not possible.
            }
            if (($_markup = $this->embedlyMarkup($_url, $_embed))) {
                $string = str_replace($_token, $_markup, $string);
            }
        } // unset($_token, $_url, $_embed, $_markup); // Housekeeping.

        # Retrieve embed objects for any uncached URLs now.

        for ($_i = 1; $_i <= count($uncached_urls); $_i = $_i + 10) {
            $_urls = array_slice($uncached_urls, $_i - 1, 10);
            $_urls = array_values($_urls);

            $_args = [ // Batches of 10.
                'urls'     => $_urls,
                'secure'   => true,
                'width'    => 800,
                'maxwidth' => 800,
                'wmode'    => 'transparent',
            ]; // See: <http://embed.ly/docs/api/embed/arguments>

            foreach ($Embedly->oembed($_args) as $_key => $_embed) {
                $new_embeds[$_urls[$_key]] = $_embed;
            } // unset($_key, $_embed); // Housekeeping.
        } // unset($_i, $_urls, $_args); // Housekeeping.

        # Cache each of the new embeds now, and replace any
        # tokens associated w/ new embeds obtained above.

        foreach ($new_embeds as $_url => $_embed) {
            $this->setEmbedlyCache($_url, $_embed);

            if ($_embed->type === 'error') {
                continue; // Not possible.
            } elseif (!empty($_embed->error_code)) {
                continue; // Not possible.
            }
            if (($_markup = $this->embedlyMarkup($_url, $_embed))) {
                foreach (array_keys($tokens, $_url, true) as $_token) {
                    $string = str_replace($_token, $_markup, $string);
                } // unset($_token); // Housekeeping.
            }
        } // unset($_url, $_embed, $_markup); // Housekeeping.

        # Restore URLs that didn't trigger embeds & return.

        foreach (array_reverse($tokens, true) as $_token => $_url) {
            $string = str_replace($_token, $_url, $string);
        } // unset($_token, $_url); // Housekeeping.

        return $string;
    }

    /**
     * Get Embedly markup.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string    $url   URL the embed is for.
     * @param \StdClass $embed The embed response object.
     *
     * @return string The Embedly HTML markup.
     */
    protected function embedlyMarkup(string $url, \StdClass $embed): string
    {
        $markup = ''; // Initialize HTML markup.

        if (!$url || empty($embed->type) || empty($embed->provider_name)) {
            return $markup; // Not possible; i.e., empty string.
        }
        $provider_slug = $this->c::nameToSlug($embed->provider_name);
        $classes       = '-_embedly -_'.$embed->type.' -_'.$provider_slug;

        switch ($embed->type) { // See: <http://jas.xyz/1O7ymwT>
            case 'rich':
            case 'video':
                if (!empty($embed->html)) {
                    $markup = '<div class="'.$this->c::escAttr($classes).'">'.
                        $embed->html.
                    '</div>';
                }
                break;

            case 'photo':
                if (!empty($embed->url) && isset($embed->width, $embed->height)) {
                    $markup = '<div class="'.$this->c::escAttr($classes).'">'.
                        '<a href="'.$this->c::escUrl($url).'" title="'.$this->c::escAttr($embed->title ?? '').'">'.
                            '<img src="'.$this->c::escUrl($embed->url).'" width="'.$this->c::escAttr((string) $embed->width).'" height="'.$this->c::escAttr((string) $embed->height).'" alt="" />'.
                        '</a>'.
                    '</div>';
                }
                break;

            case 'link':
                if (!empty($embed->thumbnail_url) && isset($embed->thumbnail_width, $embed->thumbnail_height)) {
                    $markup = '<div class="'.$this->c::escAttr($classes).'">'.
                        '<a href="'.$this->c::escUrl($url).'" title="'.$this->c::escAttr($embed->title ?? '').'">'.
                            '<img src="'.$this->c::escUrl($embed->thumbnail_url).'" width="'.$this->c::escAttr((string) $embed->thumbnail_width).'" height="'.$this->c::escAttr((string) $embed->thumbnail_height).'" alt="" />'.
                        '</a>'.
                    '</div>';
                }
                break;
        }
        return $markup;
    }

    /**
     * Get Embedly cache.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string $url URL to check the cache for.
     *
     * @return \StdClass|null Cached embed code, if possible.
     */
    protected function getEmbedlyCache(string $url)
    {
        $cache_dir = $this->cache_dir.'/embedly';
        $cache_dir .= '/'.$this->c::sha1ModShardId($url);
        $cache_file = $cache_dir.'/'.sha1($url);

        if (is_file($cache_file)) {
            $embed = file_get_contents($cache_file);
            $embed = unserialize($embed);
            if ($embed instanceof \StdClass) {
                return $embed;
            }
        }
    }

    /**
     * Set Embedly cache.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string    $url   URL the embed is for.
     * @param \StdClass $embed The embed response object.
     */
    protected function setEmbedlyCache(string $url, \StdClass $embed)
    {
        $url_sha1              = sha1($url);
        $cache_dir             = $this->cache_dir.'/embedly/'.$this->c::sha1ModShardId($url_sha1, true);
        $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
        $cache_file            = $cache_dir.'/'.$url_sha1;

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        file_put_contents($cache_file, serialize($embed));
    }

    /**
     * Via WordPress.
     *
     * @since 151125 OEmbed utilities.
     *
     * @param string $string String to parse.
     */
    protected function viaWordPress(string $string): string
    {
        if (!$this->c::isWordPress()
            || !$this->c::canCallFunc('wp_oembed_get')
            || !$this->c::canCallFunc('wp_embed_defaults')) {
            throw $this->c::issue('Unable to oEmbed via WordPress.');
        }
        $oembed_args = array_merge(wp_embed_defaults(), ['discover' => false]);
        $string      = preg_replace_callback('/^\s*(https?:\/\/[^\s"]+)\s*$/uim', function ($m) use ($oembed_args) {
            $oembed = wp_oembed_get($m[1], $oembed_args);
            return $oembed ? $oembed : $m[0];
        }, $string);

        return $string;
    }
}
