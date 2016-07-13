<?php
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
use SuperClosure\Serializer;
use SuperClosure\Analyzer\AstAnalyzer;
use SuperClosure\Analyzer\TokenAnalyzer;

/**
 * Closure utils.
 *
 * @since 160712 Closure utils.
 */
class Closure extends Classes\Core\Base\Core
{
    /**
     * Serializer.
     *
     * @since 160712
     *
     * @type Serializer
     */
    protected $AstSerializer;

    /**
     * Serializer (faster).
     *
     * @since 160712
     *
     * @type Serializer
     */
    protected $TokenSerializer;

    /**
     * Class constructor.
     *
     * @since 160712 Closure utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->AstSerializer   = new Serializer(new AstAnalyzer());
        $this->TokenSerializer = new Serializer(new TokenAnalyzer());
        // See: <https://github.com/jeremeamia/super_closure>
    }

    /**
     * Serialize closure.
     *
     * @since 160712 Closure utils.
     *
     * @param \Closure $Closure A closure.
     * @param bool     $faster  Use faster serializer?
     *
     * @return string Serialized closure.
     */
    public function toString(\Closure $Closure, bool $faster = false): string
    {
        return $this->{$faster ? 'TokenSerializer' : 'AstSerializer'}->serialize($Closure);
    }

    /**
     * Unserialize closure.
     *
     * @since 160712 Closure utils.
     *
     * @param string $closure     A serialized closure.
     * @param bool   $used_faster Used faster serializer?
     *
     * @return \Closure Unserialized closure.
     */
    public function fromString(string $closure, bool $used_faster = false): \Closure
    {
        return $this->{$used_faster ? 'TokenSerializer' : 'AstSerializer'}->unserialize($closure);
    }
}
