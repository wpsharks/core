<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Slug utilities.
 *
 * @since 150424 Initial release.
 */
class Slug extends Classes\AbsBase
{
    /**
     * Convert slug to name.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $slug Slug to convert to name.
     *
     * @return string Name; based on slug.
     */
    public function toName(string $slug): string
    {
        $name = $slug; // Working copy.
        $name = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $name);
        $name = $this->Utils->UcWords($name);
        $name = $this->Utils->Trim($name);

        return $name;
    }
}
