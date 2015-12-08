<?php
/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'html' => [
        'lang'  => 'en-US',
        'class' => '',
    ],
    'head' => [
        'robots'    => 'noindex,follow',
        'generator' => $this->App->Config->brand['name'],
        'viewport'  => 'width=device-width, initial-scale=1.0',

        'title'       => $this->__('Untitled'),
        'site'        => $this->App->Config->brand['name'],
        'keywords'    => $this->App->Config->brand['keywords'],
        'description' => $this->App->Config->brand['description'],

        'og' => [ // See below.
            'site'        => '',
            'title'       => '',
            'description' => '',
            'url'         => '',
            'type'        => 'website',
            'image'       => $this->Utils->Url->toApp($this->App->Config->brand['screenshot'], 'current'),
        ],
        'canonical' => $this->Utils->UrlCurrent(true),
        'favicon'   => $this->Utils->Url->toApp($this->App->Config->brand['favicon'], 'current'),
        'shortlink' => '', // Defaults to canonical below.

        'styles' => [
            'v' => $this->App::VERSION,
        ],
        'extras' => '',
    ],
    'body' => [
        'class' => '',
    ],
];
extract($this->setVars($¤defaults, $¤vars));
/*
 * A few easy fallbacks.
 */
if (!$head['og']['site']) {
    $head['og']['site'] = $head['site'];
}
if (!$head['og']['title']) {
    $head['og']['title'] = $head['title'];
}
if (!$head['og']['description']) {
    $head['og']['description'] = $head['description'];
}
if (!$head['og']['url']) {
    $head['og']['url'] = $head['canonical'];
}
if (!$head['shortlink']) {
    $head['shortlink'] = $head['canonical'];
}
/*
 * Output template contents.
 */
?><!DOCTYPE html>
<html id="app" class="<?= $html['class'] ?>" lang="<?= $html['lang'] ?>">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="IE=edge" />
        <meta name="viewport" content="<?= $this->escAttr($head['viewport']) ?>" />
        <meta name="generator" content="<?= $this->escAttr($head['generator']) ?>" />
        <meta name="robots" content="<?= $this->escAttr($head['robots']) ?>" />

        <title><?= $this->escHtml($head['title'].($head['site'] ? ' | '.$head['site'] : '')) ?></title>
        <meta name="keywords" content="<?= $this->escAttr(implode(',', $head['keywords'])) ?>" />
        <meta name="description" content="<?= $this->escAttr($head['description']) ?>" />

        <meta property="og:site_name" content="<?= $this->escAttr($head['og']['site']) ?>" />
        <meta property="og:title" content="<?= $this->escAttr($head['og']['title']) ?>" />
        <meta property="og:description" content="<?= $this->escAttr($head['og']['description']) ?>" />
        <meta property="og:url" content="<?= $this->escUrl($head['og']['url']) ?>" />
        <meta property="og:image" content="<?= $this->escAttr($head['og']['image']) ?>" />
        <meta property="og:type" content="<?= $this->escAttr($head['og']['type']) ?>" />

        <link rel="canonical" href="<?= $this->escUrl($head['canonical']) ?>" />
        <link rel="shortcut icon" href="<?= $this->escUrl($head['favicon']) ?>" />
        <link rel="shortlink" href="<?= $this->escUrl($head['shortlink']) ?>" />

        <link type="text/css" rel="stylesheet" href="<?= $this->escUrl($this->Utils->UrlScheme->set('//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.6/semantic.min.css')) ?>" />
        <link type="text/css" rel="stylesheet" href="<?= $this->escUrl($this->Utils->Url->toApp('/vendor/websharks/core/src/client-s/css/core.min.css?v='.urlencode($head['styles']['v']))) ?>" />
        <link type="text/css" rel="stylesheet" href="<?= $this->escUrl($this->Utils->Url->toApp('/client-s/css/app.min.css?v='.urlencode($head['styles']['v']))) ?>" />

        <?= $head['extras'] ?>
    </head>

    <body class="<?= $body['class'] ?>">

        <?= $this->inc('http/html/header.inc.php') ?>
