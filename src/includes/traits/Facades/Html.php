<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Html
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->Html->is(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function balanceHtmlTags(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlBalance->tags(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function stripHtmlAttrs(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlStrip->attributes(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function textToHtml(...$args)
    {
        return $GLOBALS[static::class]->Utils->Text2Html->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlToText(...$args)
    {
        return $GLOBALS[static::class]->Utils->Html2Text->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlToRichText(...$args)
    {
        return $GLOBALS[static::class]->Utils->Html2RichText->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlToPandoc(...$args)
    {
        return $GLOBALS[static::class]->Utils->Html2Pandoc->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlAnchorize(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlAnchorize->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlAnchorRels(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlAnchorRels->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlTrim->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlLTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlTrim->l(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function htmlRTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlTrim->r(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function normalizeHtmlWhitespace(...$args)
    {
        return $GLOBALS[static::class]->Utils->HtmlWhitespace->normalize(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function checked(...$args)
    {
        return $GLOBALS[static::class]->Utils->Html->checked(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function selected(...$args)
    {
        return $GLOBALS[static::class]->Utils->Html->selected(...$args);
    }
}
