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

$_current_url    = $this->c::currentUrl();
$_current_url    = $this->c::parseUrl($_current_url);
$_main_file_slug = $this->c::nameToSlug($this->mainFile());

$defaults = [
    'html' => [
        'lang'  => 'en-US',
        'class' => $_main_file_slug,
    ],
    'head' => [
        'robots'    => 'index,follow',
        'generator' => $this->App->Config->©brand['©name'],
        'viewport'  => 'width=device-width, initial-scale=1.0',

        'title'       => __('Untitled'),
        'site'        => $this->App->Config->©brand['©name'],
        'keywords'    => $this->App->Config->©brand['©keywords'],
        'description' => $this->App->Config->©brand['©description'],

        'og' => [ // See below.
            'site'        => '',
            'title'       => '',
            'description' => '',
            'type'        => 'website',
            'url'         => $_current_url['canonical'],
            'image'       => $this->c::appUrl($this->App->Config->©brand['©screenshot'], 'current'),
        ],
        'canonical' => $_current_url['canonical'],

        'next' => '', // See: <http://jas.xyz/1IEKvqB>
        'prev' => '', // See: <http://jas.xyz/1IEKvqB>

        'favicon'   => $this->c::appUrl($this->App->Config->©brand['©favicon'], 'current'),
        'shortlink' => $_current_url['canonical'],

        'styles' => [
            'v' => $this->App::VERSION,
        ],
    ],
    'body' => [
        'class' => $_main_file_slug,

        'app_main_enable' => true,

        'brand' => [
            'url'  => $this->c::appUrl('/'),
            'name' => $this->App->Config->©brand['©name'],
            'logo' => $this->c::appUrl($this->App->Config->©brand['©logo']),
        ],
        'header' => [
            'title' => '',
        ],
    ],
]; unset($_current_url, $_main_file_slug);

$vars                              = array_replace_recursive($defaults, $this->vars);
$vars['head']['og']['site']        = $vars['head']['og']['site'] ?: $vars['head']['site'];
$vars['head']['og']['title']       = $vars['head']['og']['title'] ?: $vars['head']['title'];
$vars['head']['og']['description'] = $vars['head']['og']['description'] ?: $vars['head']['description'];
$vars['body']['header']['title']   = $vars['body']['header']['title'] ?: $this->c::escHtml($vars['head']['title']);

extract($this->setVars($vars, $this->vars));
?>
<?= $this->get('http/html/includes/header/html-open.php'); ?>
    <?= $this->get('http/html/includes/header/html-head.php'); ?>
    <?= $this->get('http/html/includes/header/body-open.php'); ?>
        <?= $this->get('http/html/includes/header/app-main.php'); ?>
