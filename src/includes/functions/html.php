<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_html(...$args)
{
    return $GLOBALS[App::class]->Utils->Html->is(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function balance_html_tags(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlBalance->tags(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function strip_html_attrs(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlStrip->attributes(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function text_to_html(...$args)
{
    return $GLOBALS[App::class]->Utils->Text2Html->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_to_text(...$args)
{
    return $GLOBALS[App::class]->Utils->Html2Text->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_to_rich_text(...$args)
{
    return $GLOBALS[App::class]->Utils->Html2RichText->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_to_pandoc(...$args)
{
    return $GLOBALS[App::class]->Utils->Html2Pandoc->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_anchorize(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlAnchorize->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_anchor_rels(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlAnchorRels->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_trim(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlTrim->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_ltrim(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlTrim->l(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function html_rtrim(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlTrim->r(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function normalize_html_whitespace(...$args)
{
    return $GLOBALS[App::class]->Utils->HtmlWhitespace->normalize(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function checked(...$args)
{
    return $GLOBALS[App::class]->Utils->Html->checked(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function selected(...$args)
{
    return $GLOBALS[App::class]->Utils->Html->selected(...$args);
}
