<?php
/**
 * Template.
 *
 * @author @jaswsinc
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

if (!empty($this->vars['head']['og']['type'])) {
    $_og_type = $this->vars['head']['og']['type'];
} else {
    $_og_type = 'website'; // Default type.
}
$_current_url    = $this->c::currentUrl();
$_current_url    = $this->c::parseUrl($_current_url);
$_main_file_slug = $this->c::nameToSlug($this->mainFile());

$defaults = [
    'html' => [
        'lang'  => 'en-US',
        'class' => $_main_file_slug,
    ],
    'head' => [
        'robots'          => 'index,follow',
        'generator'       => 'websharks/core',
        'viewport'        => 'width=device-width, initial-scale=1.0',
        'x_ua_compatible' => 'IE=edge', // See: <http://bit.ly/1fJyGT3>

        'title' => '', // Filled by app.
        'site'  => $this->App->Config->©brand['©name'],

        'author'      => $this->App->Config->©brand['©author']['©name'],
        'keywords'    => $this->App->Config->©brand['©keywords'],
        'description' => $this->App->Config->©brand['©description'],

        'canonical' => $_current_url['canonical'],
        'shortlink' => '', // Filled by app.

        'og' => [ // See: <http://ogp.me/>
            'locale' => '', // See below.
            'type'   => $_og_type, // See above.
            'url'    => $_current_url['canonical'],

            'site_name'   => '', // See below.
            'title'       => '', // See below.
            'description' => '', // See below.
            'image'       => $this->c::appUrl($this->App->Config->©brand['©image']),

            'profile:gender'     => $this->App->Config->©brand['©author']['©gender'],
            'profile:username'   => $this->App->Config->©brand['©author']['©username'],
            'profile:first_name' => $_og_type === 'profile' ? $this->c::fnameIn($this->App->Config->©brand['©author']['©name']) : '',
            'profile:last_name'  => $_og_type === 'profile' ? $this->c::lnameIn($this->App->Config->©brand['©author']['©name']) : '',

            'article:author'         => '', // See below.
            'article:tags'           => '', // See below.
            'article:section'        => '', // Filled by app.
            'article:published_time' => '', // Filled by app.
            'article:modified_time'  => '', // Filled by app.
        ],
    ],
    'body' => [
        'class'           => '', // Filled by app.
        'app_main_enable' => true,
    ],
]; unset($_current_url, $_main_file_slug);

$vars = array_replace_recursive($defaults, $this->vars);

$vars['head']['og']['site_name'] = $vars['head']['og']['site_name'] ?: $vars['head']['site'];
$vars['head']['og']['locale']    = $vars['head']['og']['locale'] ?: str_replace('-', '_', $vars['html']['lang']);

$vars['head']['og']['title']       = $vars['head']['og']['title'] ?: $vars['head']['title'];
$vars['head']['og']['description'] = $vars['head']['og']['description'] ?: $vars['head']['description'];

$vars['head']['og']['article:author'] = $vars['head']['og']['article:author'] ?: $vars['head']['author'];
$vars['head']['og']['article:tags']   = $vars['head']['og']['article:tags'] ?: $vars['head']['keywords'];

extract($this->setVars($vars, $this->vars));
?>
<?= $this->get('http/html/includes/header/html.php'); ?>
    <?= $this->get('http/html/includes/header/head.php'); ?>
    <?= $this->get('http/html/includes/header/body.php'); ?>
        <?= $this->get('http/html/includes/header/app-main.php'); ?>
