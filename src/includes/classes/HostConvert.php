<?php
namespace WebSharks\Core\Classes;

/**
 * Host conversion utilities.
 *
 * @since 150424 Initial release.
 */
class HostConvert extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Converts a host name into a slug.
     *
     * @since 150424 Initial release.
     *
     * @param string $host Optional. Defaults to current host name.
     *
     * @return string Slug representation of the `$host`; i.e., w/ dashes.
     */
    public function toSlug($host = '')
    {
        $host = (string) $host;

        if (!$host && !empty($_SERVER['HTTP_HOST'])) {
            $host = (string) $_SERVER['HTTP_HOST'];
        } elseif (!$host && !empty($_SERVER['HOST_NAME'])) {
            $host = (string) $_SERVER['HOST_NAME'];
        } elseif (!$host) {
            $host = (string) php_uname('n');
        }
        if (!$host) {
            return ''; // Nothing we can do.
        }
        if (!is_null($slug = &$this->staticKey(__FUNCTION__, $host))) {
            return $slug; // Already cached this.
        }
        $slug               = strtolower($host);
        $slug               = preg_replace('/^www\./', '', $slug);
        $slug               = preg_replace('/\:[0-9]+$/', '', $slug);
        $slug               = preg_replace('/\-inc\./', '.', $slug);
        $known_domain_hacks = '/^short\.codes$/i';

        if (substr_count($slug, '.') > 1) {
            $slug = preg_replace_callback(
                '/^(?P<sub_domain>[^.]+)\.(?P<slug>.*)$/',
                function ($m) use ($known_domain_hacks) {
                    if (!preg_match($known_domain_hacks, $m['slug'])) {
                        $m['slug'] = preg_replace('/\.[a-z]+$/', '', $m['slug']);
                    }
                    return $m['slug'].'-'.$m['sub_domain'];
                },
                $slug
            );
        } elseif (!preg_match($known_domain_hacks, $slug)) {
            $slug = preg_replace('/\.[a-z]+$/', '', $slug);
        }
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $slug), '-');

        return $slug;
    }

    /**
     * Converts a host name into a variable name.
     *
     * @since 150424 Initial release.
     *
     * @param string $host Optional. Defaults to current host name.
     *
     * @return string Var representation of the `$host`; i.e., w/ underscores.
     */
    public function toVar($host = '')
    {
        return str_replace('-', '_', $this->toSlug($host));
    }
}
