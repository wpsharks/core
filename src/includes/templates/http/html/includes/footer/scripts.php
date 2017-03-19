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
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js" integrity="sha384-o9KO9jVK1Q4ybtHgJCCHfgQrTRNlkT6SL3j/qMuBMlDw3MmFrgrOHCOaIMJWGgK5" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" integrity="sha384-FZY+KSLVXVyc1qAlqH9oCx1JEOlQh6iXfw3o2n3Iy32qGjXmUPWT9I0Z9e9wxYe3" crossorigin="anonymous"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.address/1.6/jquery.address.min.js" integrity="sha384-zMTBSQGBLON8BzJvCLwjWuuA1q8Ew5clzw9A3FMXoo/AdY/oB2Q1QTOT/aHDGzfg" crossorigin="anonymous"></script>
<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/semantic/dist/semantic.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/highlight.min.js" integrity="sha384-BA64Pbom6s1cFobK6lLrxH00+tV8fV79eto4YAbT6JTQNvI8edolukUleKe2T/6L" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bignumber.js/4.0.0/bignumber.min.js" integrity="sha384-BYoEoUg64n+TS95QmgjlFumB8zSrsWsNDnmqedkKAQw2s8dhuKn/4iBfmoCztovt" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jshashes/1.0.6/hashes.min.js" integrity="sha384-nKo52NrVQhnSpXoci3mI5lfRFILLT5AE1tQ7BE//26OunGyJL7/+mYeDNLVi8zIa" crossorigin="anonymous"></script>

<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/underscore/mixins.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>
<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/hljs/langs/wp.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>
<script src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/core.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>

<?= $this->get('http/html/includes/footer/scripts-append.php'); ?>

<?php if (is_file($this->App->base_dir.'/src/client-s/js/app.min.js')) : ?>
    <script src="<?= $this->c::escUrl($this->c::appUrl('/client-s/js/app.min.js?v='.urlencode($this->App::VERSION))); ?>"></script>
<?php endif; ?>
