<?php
/**
 * Template.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

$¤defaults = [
    'Throwable' => null,

    'http/html/header.php' => [
        'head' => [
            'title' => __('500 Internal Server Error'),
        ],
    ],
];
extract($this->setVars($¤defaults, $¤vars));
?>
<?= $this->get('http/html/header.php') ?>

<h1><?= __('500 Internal Server Error'); ?></h1>
<p><?= __('This error has been reported to system administrators. Very sorry! :-)'); ?></p>

<?php if ($this->App->Config->©debug['©er_display'] && $Throwable) : ?>
    <pre><?= $this->c::escHtml($Throwable->getMessage()) ?></pre>
<?php endif; ?>

<?= $this->get('http/html/footer.php') ?>
