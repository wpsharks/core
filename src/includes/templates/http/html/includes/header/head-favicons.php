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

// See: <https://realfavicongenerator.net>
// Generate & incorporate files referenced below.
// Also copy `favicon.ico` and XML files into root directory.

$_dir = $this->c::escAttr($this->App->Config->©brand['©favicons']['©dir']);
?>
<link rel="manifest" href="<?= $this->c::escUrl($this->c::appUrl($_dir.'/manifest.json', 'current')); ?>" />
<meta name="msapplication-config" content="<?= $this->c::escUrl($this->c::appUrl($_dir.'/browserconfig.xml', 'current')); ?>" />

<meta name="application-name" content="<?= $this->c::escAttr($this->App->Config->©brand['©name']); ?>" />
<meta name="apple-mobile-web-app-title" content="<?= $this->c::escAttr($this->App->Config->©brand['©name']); ?>" />
<meta name="theme-color" content="<?= $this->c::escAttr($this->App->Config->©brand['©favicons']['©theme_color']); ?>" />

<link rel="shortcut icon" href="<?= $this->c::escUrl($this->c::appUrl($_dir.'/favicon.ico', 'current')); ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="<?= $this->c::escUrl($this->c::appUrl($_dir.'/apple-touch-icon.png', 'current')); ?>" />
<link rel="icon" type="image/png" href="<?= $this->c::escUrl($this->c::appUrl($_dir.'/favicon-32x32.png', 'current')); ?>" sizes="32x32" />
<link rel="icon" type="image/png" href="<?= $this->c::escUrl($this->c::appUrl($_dir.'/favicon-16x16.png', 'current')); ?>" sizes="16x16" />
<link rel="mask-icon" href="<?= $this->c::escUrl($this->c::appUrl($_dir.'/safari-pinned-tab.svg', 'current')); ?>"
      color="<?= $this->c::escAttr($this->App->Config->©brand['©favicons']['©pinned_color']); ?>" />
