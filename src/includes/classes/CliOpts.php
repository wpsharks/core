<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
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
class CliOpts extends AppBase
{
    /**
     * Options.
     *
     * @since 15xxxx
     *
     * @type OptionCollection
     */
    protected $OptionCollection;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        if (!c\is_cli()) {
            throw new Exception('Requires CLI mode.');
        }
        $this->OptionCollection = new OptionCollection();
    }

    /**
     * Get options.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $specs An array of option specs.
     *
     * @see https://github.com/c9s/GetOptionKit
     */
    public function add(array $specs)
    {
        foreach ($specs as $_spec => $_data) {
            if ($_spec && is_string($_spec) && !empty($_data['description'])) {
                $_Option = $this->OptionCollection->add($_spec, $_data['description']);
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
     * @param bool       $extract Extract into an array after parsing?
     * @param array|null $args    Args to parse; default is `$GLOBALS['argv']`.
     *
     * @return array|OptionResult|Option[] Associative array, or the result set of Option objs.
     */
    public function parse(bool $extract = true, array $args = null)
    {
        $Parser = new OptionParser($this->OptionCollection);

        $OptionResult = $Options = $Parser->parse($args ?? $GLOBALS['argv']);

        if (!$extract) { // This contains some additional information for each option.
            return $OptionResult = $Options; // OptionResult|Option[]
        } else {
            $options = []; // Associative array (default behavior).

            foreach ($Options as $_key => $_Option) {
                $options[str_replace('-', '_', $_key)] = $_Option->getValue();
            } // unset($_key, $_Option); // Housekeeping.

            return $options; // Suitable for `extract()` now.
        }
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
