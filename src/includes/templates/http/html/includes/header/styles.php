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

?>
<?= $this->get('http/html/includes/header/styles-append.php'); ?>

<?php if (is_file($this->App->base_dir.'/src/client-s/css/app.min.css')) : ?>
    <link type="text/css" rel="stylesheet" href="<?= $this->c::escUrl($this->c::appUrl('/client-s/css/app.min.css?v='.urlencode($this->App::VERSION))); ?>" />
<?php endif; ?>
