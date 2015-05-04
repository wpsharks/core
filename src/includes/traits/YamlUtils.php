<?php
namespace WebSharks\Core\Traits;

/**
 * YAML utilities.
 *
 * @since 150424 Initial release.
 */
trait YamlUtils
{
    /**
     * YAML parser.
     *
     * @since 150424 Initial release.
     *
     * @param string $yaml Input YAML to parse.
     *
     * @return array Output array.
     */
    protected function yamlParse($yaml)
    {
        if (!($yaml = (string) $yaml)) {
            return []; // Empty.
        }
        try {
            $array = \Symfony\Component\Yaml\Yaml::parse($yaml);
        } catch (\Exception $exception) {
            $array = []; // Empty.
        }
        return is_array($array) ? $array : [];
    }
}
