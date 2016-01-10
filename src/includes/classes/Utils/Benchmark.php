<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Dicer\Di;
use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Benchmark utilities.
 *
 * @since 15xxxx Benchmarking.
 */
class Benchmark extends Classes\AppBase
{
    /**
     * Start time.
     *
     * @since 15xxxx
     *
     * @type float
     */
    protected $started = 0;

    /**
     * Stop time.
     *
     * @since 15xxxx
     *
     * @type float
     */
    protected $stopped = 0;

    /**
     * Elapsed time.
     *
     * @since 15xxxx
     *
     * @type float
     */
    protected $this_time = 0;

    /**
     * Last elapsed time.
     *
     * @since 15xxxx
     *
     * @type float
     */
    protected $last_time = 0;

    /**
     * Stats.
     *
     * @since 15xxxx
     *
     * @type string[]
     */
    protected $stats = [];

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        if (!c\is_cli()) {
            throw new Exception('Requires CLI mode.');
        }
    }

    /**
     * Start (initialize).
     *
     * @since 15xxxx Benchmarking.
     *
     * @param bool $save_last Save last time?
     */
    public function start(bool $save_last = true)
    {
        $this->stats = [];

        if ($save_last) {
            $this->last_time = $this->this_time;
        }
        $this->this_time = 0; // Init.
        $this->started   = microtime(true);
    }

    /**
     * Stop (and print).
     *
     * @since 15xxxx Benchmarking.
     *
     * @param bool $full_report Full report?
     */
    public function print(bool $full_report = false)
    {
        $this->stopped   = microtime(true);
        $this->this_time = $this->stopped - $this->started;

        if ($this->last_time) {
            $_percent_diff = c\percent_diff($this->last_time, $this->this_time, 2);
            $_percent_diff = $_percent_diff > 0 ? abs($_percent_diff).'% slower' : abs($_percent_diff).'% faster';

            $this->addStatistic('Processing Time', number_format($this->this_time, 8, '.', ''));
            $this->addStatistic('Time Comparison', $_percent_diff.' than '.number_format($this->last_time, 8, '.', '')."\n");
            unset($_percent_diff); // Housekeeping.
        } else {
            $this->addStatistic('Processing Time', number_format($this->this_time, 8, '.', '')."\n");
        }
        if ($full_report) {
            $this->addStatistic('Memory', c\bytes_to_abbr(memory_get_usage()));
            $this->addStatistic('Peak Memory', c\bytes_to_abbr(memory_get_peak_usage())."\n");

            $this->addStatistic('PHP Version', PHP_VERSION);
            $this->addStatistic('PCRE Version', PCRE_VERSION."\n");

            $this->addStatistic('Di Version', Di::VERSION);
            $this->addStatistic('Core Version', Classes\App::VERSION);
            $this->addStatistic('App Version', $this->App::VERSION."\n");

            $this->addStatistic('Locale', setlocale(LC_ALL, 0));
            $this->addStatistic('Charset', ini_get('default_charset')."\n");

            $this->addStatistic('Script Basename', basename($GLOBALS['argv'][0]));
        }
        $this->printAllStatistics(); // Print all statistics.
    }

    /**
     * Print statistics.
     *
     * @since 15xxxx Benchmarking.
     */
    protected function printAllStatistics()
    {
        $longest_label_chars = 0; // Initialize.

        foreach ($this->stats as $_label => $_value) {
            $longest_label_chars = max($longest_label_chars, mb_strlen($_label));
        }
        echo "\n\n"; // Two lines down...
        echo '- Benchmark ----------------------------------------'."\n";

        $statistics = ''; // Initialize.

        foreach ($this->stats as $_label => $_value) {
            $_label_chars     = mb_strlen($_label);
            $_alignment_chars = $longest_label_chars - $_label_chars;
            $statistics .= $_label.':    '.str_repeat(' ', $_alignment_chars).$_value."\n";
        } // unset($_label_chars, $_alignment_chars);

        echo c\mb_trim($statistics)."\n";

        echo '----------------------------------------------------'."\n\n";
    }

    /**
     * Add statistic.
     *
     * @since 15xxxx Benchmarking.
     *
     * @param string $label Label for this statistic.
     * @param string $value Value for this statistic.
     */
    protected function addStatistic(string $label, $value)
    {
        $this->stats[$label] = (string) $value;
    }
}