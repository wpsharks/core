<?php
namespace WebSharks\Core\Traits;

/**
 * URL Utilities.
 *
 * @since 150424 Initial release.
 */
trait UrlUtils
{
    use UrlCurrentUtils;
    use UrlHashUtils;
    use UrlParseUtils;
    use UrlQueryUtils;
    use UrlSchemeUtils;
}
