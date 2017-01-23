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

?>
<head>
    <meta charset="utf-8" />

    <meta name="robots" content="<?= $this->c::escAttr($head['robots']); ?>" />
    <meta name="generator" content="<?= $this->c::escAttr($head['generator']); ?>" />
    <meta name="viewport" content="<?= $this->c::escAttr($head['viewport']); ?>" />
    <meta http-equiv="x-ua-compatible" content="<?= $this->c::escAttr($head['x_ua_compatible']); ?>" />

    <title><?= $this->c::escHtml($head['title'].($head['site'] ? ' | '.$head['site'] : '')); ?></title>

    <meta name="author" content="<?= $this->c::escAttr($head['author']); ?>" />
    <meta name="keywords" content="<?= $this->c::escAttr(implode(',', $head['keywords'])); ?>" />
    <meta name="description" content="<?= $this->c::escAttr($head['description']); ?>" />

    <?php if ($head['shortlink']) : ?>
        <link rel="shortlink" href="<?= $this->c::escUrl($head['shortlink']); ?>" />
    <?php endif; ?>
    <link rel="canonical" href="<?= $this->c::escUrl($head['canonical']); ?>" />

    <?= $this->get('http/html/includes/header/head-favicons.php'); ?>

    <meta property="og:locale" content="<?= $this->c::escAttr($head['og']['locale']); ?>" />
    <meta property="og:type" content="<?= $this->c::escAttr($head['og']['type']); ?>" />
    <meta property="og:url" content="<?= $this->c::escUrl($head['og']['url']); ?>" />

    <meta property="og:site_name" content="<?= $this->c::escAttr($head['og']['site_name']); ?>" />
    <meta property="og:title" content="<?= $this->c::escAttr($head['og']['title']); ?>" />
    <meta property="og:description" content="<?= $this->c::escAttr($head['og']['description']); ?>" />
    <meta property="og:image" content="<?= $this->c::escUrl($head['og']['image']); ?>" />

    <?php if ($head['og']['type'] === 'profile') : ?>
        <meta property="og:profile:gender" content="<?= $this->c::escAttr($head['og']['profile:gender']); ?>" />
        <meta property="og:profile:username" content="<?= $this->c::escAttr($head['og']['profile:username']); ?>" />
        <meta property="og:profile:first_name" content="<?= $this->c::escAttr($head['og']['profile:first_name']); ?>" />
        <meta property="og:profile:last_name" content="<?= $this->c::escAttr($head['og']['profile:last_name']); ?>" />

    <?php elseif ($head['og']['type'] === 'article') : ?>
        <meta property="og:article:author" content="<?= $this->c::escAttr($head['og']['article:author']); ?>" />
        <?php foreach ($head['og']['article:tags'] as $_tag) : ?>
            <meta property="og:article:tag" content="<?= $this->c::escAttr($_tag); ?>" />
        <?php endforeach; // unset($_tag);?>
        <meta property="og:article:section" content="<?= $this->c::escAttr($head['og']['article:section']); ?>" />
        <meta property="og:article:published_time" content="<?= $this->c::escAttr($head['og']['article:published_time']); ?>" />
        <meta property="og:article:modified_time" content="<?= $this->c::escAttr($head['og']['article:modified_time']); ?>" />
    <?php endif; ?>

    <?= $this->get('http/html/includes/header/styles.php'); ?>

    <?= $this->get('http/html/includes/header/head-append.php'); ?>
</head>
