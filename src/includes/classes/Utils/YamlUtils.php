<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * YAML utilities.
 *
 * @since 150424 Initial release.
 */
class YamlUtils extends AbsBase
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
     * YAML parser.
     *
     * @since 150424 Initial release.
     *
     * @param string $yaml Input YAML to parse.
     *
     * @return array Output array.
     */
    public function yamlParse($yaml)
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
