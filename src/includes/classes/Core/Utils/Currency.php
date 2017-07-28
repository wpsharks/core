<?php
/**
 * Currency utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Currency utils.
 *
 * @since 17xxxx Currency utils.
 */
class Currency extends Classes\Core\Base\Core
{
    /**
     * Currency code → symbol.
     *
     * @param string $code Currency code.
     *
     * @return string Currency symbol.
     */
    public function symbol(string $code): string
    {
        $s = [ // Currency symbols.
            'AUD' => '$', // Australian Dollar.
            'BRL' => 'R$', // Brazilian Real.
            'CAD' => '$', // Canadian Dollar.
            'CZK' => 'Kč', // Czech Koruna.
            'DKK' => 'kr', // Danish Krone.
            'EUR' => '€', // Euro.
            'HKD' => '$', // Hong Kong Dollar.
            'HUF' => 'Ft', // Hungarian Forint.
            'ILS' => '₪', // Israeli New Sheqel.
            'JPY' => '¥', // Japanese Yen.
            'MYR' => 'RM', // Malaysian Ringgit.
            'MXN' => '$', // Mexican Peso.
            'NOK' => 'kr', // Norwegian Krone.
            'NZD' => '$', // New Zealand Dollar.
            'PHP' => 'Php', // Philippine Peso.
            'PLN' => 'zł', // Polish Zloty.
            'GBP' => '£', // Pound Sterling.
            'SGD' => '$', // Singapore Dollar.
            'SEK' => 'kr', // Swedish Krona.
            'CHF' => 'CHF', // Swiss Franc.
            'TWD' => 'NT$', // Taiwan New Dollar.
            'THB' => '฿', // Thai Baht.
            'USD' => '$', // U.S. Dollar.
        ];
        return $symbol = $s[mb_strtoupper($code)] ?? '$';
    }
}
