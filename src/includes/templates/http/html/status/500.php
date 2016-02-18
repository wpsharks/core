<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;

/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'Exception' => null,

    'http/html/header.php' => [
        'head' => [
            'title' => __('500 Internal Server Error'),
        ],
    ],
];
extract($this->setVars($¤defaults, $¤vars));
/*
 * Output template contents.
 */ ?>
<?= $this->get('http/html/header.php') ?>

<h1><?= __('500 Internal Server Error'); ?></h1>
<p><?= __('This error has been reported to system administrators. Very sorry! :-)'); ?></p>

<?php if ($this->App->Config->debug && $Exception) : ?>
    <pre>
        <?= c\esc_html($Exception->getMessage()) ?>
    </pre>
<?php endif; ?>

<?= $this->get('http/html/footer.php') ?>
