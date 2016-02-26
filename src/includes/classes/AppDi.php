<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Dicer\Di;

/**
 * App DI.
 *
 * @since 150424 Initial release.
 */
class AppDi extends Di
{
    /**
     * App.
     *
     * @since 150424
     *
     * @type Classes\App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App                 Instance of App.
     * @param array       $global_default_rule Default rule.
     */
    public function __construct(Classes\App $App, array $global_default_rule = [])
    {
        parent::__construct($global_default_rule);

        $this->App = $App;
    }

    /**
     * Get a class instance.
     *
     * @since 160224 Initial release.
     *
     * @param string $class_name Class path.
     * @param array  $args       Constructor args.
     * @param bool   $as_is      Class name as-is?
     *
     * @return object An object class instance.
     */
    public function get(string $class_name, array $args = [], bool $as_is = false)
    {
        return parent::get($as_is ? $class_name : $this->App->getClass($class_name), $args);
    }
}
