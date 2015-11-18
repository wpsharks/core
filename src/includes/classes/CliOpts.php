<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use GetOptionKit\Option;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionResult;
use GetOptionKit\OptionCollection;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

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
        AbsApp $App,
        OptionCollection $OptionCollection
    ) {
        parent::__construct($App);

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
        foreach ($specs as $_spec => $_data) {
            if ($_spec && is_string($_spec) && !empty($_data['desc'])) {
                $_Option = $this->OptionCollection->add($_spec, $_data['desc']);
                if (!empty($_data['type'])) {
                    $_Option->isa($_data['type']);
                }
                if (isset($_data['default'])) {
                    $_Option->defaultValue($_data['default']);
                }
            }
        } // unset($_spec, $_data, $_Option);
    }

    /**
     * Get options.
     *
     * @since 15xxxx Initial release.
     *
     * @return OptionResult Opts.
     */
    public function parse(): OptionResult
    {
        $Parser = new OptionParser($this->OptionCollection);

        return $Parser->parse($GLOBALS['argv']);
    }

    /**
     * Print option specs.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Specs as a string.
     */
    public function specs(): string
    {
        $Printer = new ConsoleOptionPrinter();

        return $Printer->render($this->OptionCollection);
    }
}
