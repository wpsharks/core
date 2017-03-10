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
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

?>
<?php if ($body['app_main_enable']) : ?>
    <?= $this->get('http/html/includes/footer/app-main-body-close.php'); ?>
    <?= $this->get('http/html/includes/footer/app-main-footer.php'); ?>
    <?= $this->get('http/html/includes/footer/app-main-append.php'); ?>
    <?= $this->get('http/html/includes/footer/app-main-close.php'); ?>
<?php endif; ?>
