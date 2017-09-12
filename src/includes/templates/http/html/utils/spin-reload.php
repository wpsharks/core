<?php
/**
 * Template.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

extract($this->setVars([
    'delay' => 2,
    'speed' => 1,

    'max_reloads' => 0,
    'max_time'    => 0,
    'max_message' => __('Request timed out.'),

    'top'     => false,
    'bg'      => '#fff',
    'spinner' => '',

    'url' => $this->c::currentUrl(),
]));
$time               = time(); // Time now.
$_r                 = $this->c::unslash($_REQUEST);
$_reloads           = (int) ($_r['_reloads'] ?? 0);
$_first_reload_time = (int) ($_r['_first_reload_time'] ?? 0);
$_elapsed_time      = $_first_reload_time ? $time - $_first_reload_time : 0;

if ($max_reloads && $_reloads > $max_reloads) {
    $this->c::die($max_message);
} elseif ($max_time && $_elapsed_time > $max_time) {
    $this->c::die($max_message);
}
$query_args = [
    '_reloads'           => $_reloads + 1,
    '_first_reload_time' => $_first_reload_time ?: $time,
];
$url = $this->c::addUrlQueryArgs($query_args, $url);
?>
<!DOCTYPE html>
<html>
<head>
    <title>...</title>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex,nofollow" />

    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: <?= $bg; ?>;
        }
        .spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .spinner > svg {
            width: 50%;
            max-width: 100px;
            animation: spin <?= $speed; ?>s linear infinite;
        }
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        setTimeout(function() {
            <?php if ($top) : ?>
                top.location.href = <?= $this->c::sQuote($url) ?>;
            <?php else : ?>
                location.href = <?= $this->c::sQuote($url) ?>;
            <?php endif; ?>
        }, <?= $delay * 1000; ?>);
    </script>
</head>
<body>
    <div class="spinner">
        <?php if ($spinner) : ?>
            <?= $spinner; ?>
        <?php else : ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><g fill="none" fill-rule="evenodd"><path fill="#015157" d="M2.6 5.34l-1.34-.8C2.9 1.7 5.7.08 9 .08v1.56c-2.73 0-5.12 1.5-6.4 3.7z"/><path fill="#218488" d="M2.63 12.73l-1.37.8c-1.64-2.86-1.64-6.12 0-8.98l1.35.8C2 6.4 1.66 7.67 1.66 9c0 1.35.36 2.62 1 3.7z"/><path fill="#962422" d="M9 16.4V18c-3.3 0-6.1-1.63-7.74-4.5l1.36-.78c1.28 2.2 3.66 3.7 6.38 3.7z"/><path fill="#CB3246" d="M15.38 12.72l1.36.8C15.1 16.36 12.3 17.98 9 17.98v-1.6c2.72 0 5.1-1.47 6.38-3.68z"/><path fill="#EC5E47" d="M15.38 5.32l1.36-.8c1.64 2.87 1.64 6.13 0 9l-1.36-.8c.62-1.1.98-2.35.98-3.7s-.36-2.6-.98-3.7z"/><path fill="#F19D1F" d="M15.38 5.32l1.36-.8C15.1 1.68 12.3.06 9 .06v1.58c2.72 0 5.1 1.48 6.38 3.7z"/></g></svg>
        <?php endif; ?>
    </div>
</body>
</html>
