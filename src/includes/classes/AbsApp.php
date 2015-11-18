<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Application abstraction.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsApp extends AbsCore
{
    /**
     * Instance.
     *
     * @since 15xxxx
     *
     * @type \stdClass
     */
    public $instance;

    /**
     * Dicer.
     *
     * @since 15xxxx
     *
     * @type AppDi
     */
    public $Di;

    /**
     * Utilities.
     *
     * @since 15xxxx
     *
     * @type AppUtils
     */
    public $Utils;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $args Instance args.
     */
    public function __construct(array $args)
    {
        parent::__construct();

        $this->instance = (object) $args;

        $this->Di = new AppDi(); // Dependency injector.
        $this->Di->addInstances([self::class => $this, $this]);

        $this->Utils = $this->Di->get(AppUtils::class);
    }
}
