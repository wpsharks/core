<?php
namespace WebSharks\Core\Traits;

/**
 * Encryption utilities.
 *
 * @since 150424 Initial release.
 */
trait EncUtils
{
    use EncBase64Utils;
    use EncCookieUtils;
    use EncKeygenUtils;
    use EncRij256Utils;
    use EncShaUtils;
    use EncXorUtils;
}
