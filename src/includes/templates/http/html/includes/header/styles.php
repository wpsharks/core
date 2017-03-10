<?php
/**
 * Template.
 *
 * @author @jaswrks
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
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/semantic.min.css" />
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.7.0/styles/github.min.css" />
<link type="text/css" rel="stylesheet" href="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/css/core.min.css?v='.urlencode($this->App::VERSION))); ?>" />

<?= $this->get('http/html/includes/header/styles-append.php'); ?>

<?php if (is_file($this->App->base_dir.'/src/client-s/css/app.min.css')) : ?>
    <link type="text/css" rel="stylesheet" href="<?= $this->c::escUrl($this->c::appUrl('/client-s/css/app.min.css?v='.urlencode($this->App::VERSION))); ?>" />
<?php endif; ?>
