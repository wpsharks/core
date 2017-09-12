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
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js" integrity="sha384-o9KO9jVK1Q4ybtHgJCCHfgQrTRNlkT6SL3j/qMuBMlDw3MmFrgrOHCOaIMJWGgK5" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js" integrity="sha384-FwbQ7A+X0UT99MG4WBjhZHvU0lvi67zmsIYxAREyhabGDXt1x0jDiwi3xubEYDYw" crossorigin="anonymous"></script>
<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/semantic/dist/semantic.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>

<?= $this->get('http/html/includes/footer/scripts-append.php'); ?>

<?php if (is_file($this->App->base_dir.'/src/client-s/js/app.min.js')) : ?>
    <script src="<?= $this->c::escUrl($this->c::appUrl('/client-s/js/app.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>
<?php endif; ?>
