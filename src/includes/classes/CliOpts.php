<?php
namespace WebSharks\Core\Classes;

use GetOptionKit\Option;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionResult;
use GetOptionKit\OptionCollection;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

// ↑ See: <http://jas.xyz/1Jc60ya>

/**
 * CLI option utilities.
 *
 * @since 150424 Initial release.
 */
class CliOpts extends AbsBase
{
    protected $OptionCollection;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        OptionCollection $OptionCollection
    ) {
        parent::__construct();

        $this->OptionCollection = $OptionCollection;
    }

    /**
     * Get options.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $specs An array of option specs.
     */
    public function add(array $specs)
    {
        foreach ($specs as $_key => $_spec) {
            if (!empty($_spec['spec']) && !empty($_spec['desc'])) {
                $_Option = $this->OptionCollection->add($_spec['spec'], $_spec['desc']);
                if (!empty($_spec['type'])) {
                    $_Option->isa($_spec['type']);
                }
                if (isset($_spec['default'])) {
                    $_Option->defaultValue($_spec['default']);
                }
            }
        }
        unset($_key, $_spec, $_Option); // Housekeeping.
    }

    /**
     * Get options.
     *
     * @since 15xxxx Initial release.
     *
     * @return OptionResult|Option[] Options.
     */
    public function parse()
    {
        $parser = new OptionParser($this->OptionCollection);

        return $parser->parse($GLOBALS['argv']);
    }

    /**
     * Print option specs.
     *
     * @since 15xxxx Initial release.
     *
     * @return OptionResult|Option Options.
     */
    public function specs()
    {
        $printer = new ConsoleOptionPrinter();

        return $printer->render($this->OptionCollection);
    }
}
