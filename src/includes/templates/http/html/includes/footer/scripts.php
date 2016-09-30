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

?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/semantic.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.7.0/highlight.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/bignumber.js/2.4.0/bignumber.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jshashes/1.0.5/hashes.min.js"></script>

<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/underscore/mixins.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>
<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/hljs/langs/wp.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>

<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/core.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>

<?php if (is_file($this->App->base_dir.'/src/client-s/js/app.min.js')) : ?>
    <script src="<?= $this->c::escUrl($this->c::appUrl('/client-s/js/app.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>
<?php endif; ?>

<?= $this->get('http/html/includes/footer/scripts-append.php'); ?>
