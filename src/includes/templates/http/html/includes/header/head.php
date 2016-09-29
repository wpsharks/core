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

// @TODO Add article-specific OG tags.
?>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="<?= $this->c::escAttr($head['viewport']); ?>" />
    <meta name="generator" content="<?= $this->c::escAttr($head['generator']); ?>" />
    <meta name="robots" content="<?= $this->c::escAttr($head['robots']); ?>" />

    <title><?= $this->c::escHtml($head['title'].($head['site'] ? ' | '.$head['site'] : '')); ?></title>
    <meta name="keywords" content="<?= $this->c::escAttr(implode(',', $head['keywords'])); ?>" />
    <meta name="description" content="<?= $this->c::escAttr($head['description']); ?>" />

    <meta property="og:site_name" content="<?= $this->c::escAttr($head['og']['site_name']); ?>" />
    <meta property="og:locale" content="<?= $this->c::escAttr($head['og']['locale']); ?>" />

    <meta property="og:type" content="<?= $this->c::escAttr($head['og']['type']); ?>" />
    <meta property="og:url" content="<?= $this->c::escUrl($head['og']['url']); ?>" />

    <meta property="og:title" content="<?= $this->c::escAttr($head['og']['title']); ?>" />
    <meta property="og:description" content="<?= $this->c::escAttr($head['og']['description']); ?>" />

    <meta property="og:image" content="<?= $this->c::escAttr($head['og']['image']); ?>" />
    <meta property="og:image:url" content="<?= $this->c::escAttr($head['og']['image']); ?>" />
    <meta property="og:image:secure_url" content="<?= $this->c::escAttr($head['og']['image']); ?>" />

    <?php if ($head['og']['type'] === 'profile') : ?>
        <meta property="profile:gender" content="<?= $this->c::escAttr($head['og']['profile:gender']); ?>" />
        <meta property="profile:username" content="<?= $this->c::escAttr($head['og']['profile:username']); ?>" />
        <meta property="profile:first_name" content="<?= $this->c::escAttr($head['og']['profile:first_name']); ?>" />
        <meta property="profile:last_name" content="<?= $this->c::escAttr($head['og']['profile:last_name']); ?>" />
    <?php endif; ?>

    <link rel="shortcut icon" href="<?= $this->c::escUrl($head['favicon']); ?>" />

    <link rel="canonical" href="<?= $this->c::escUrl($head['canonical']); ?>" />
    <link rel="shortlink" href="<?= $this->c::escUrl($head['shortlink']); ?>" />

    <?= $this->get('http/html/includes/header/styles.php'); ?>

    <?= $this->get('http/html/includes/header/head-append.php'); ?>
</head>
