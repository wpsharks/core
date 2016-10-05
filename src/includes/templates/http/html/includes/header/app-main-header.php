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
<div class="ui container app-main-header">
    <div class="ui stackable grid">

        <div class="eight wide column -left-side">
            <div class="ui stackable grid">
                <div class="sixteen wide column">
                    <a href="<?= $this->c::escUrl($this->c::appUrl('/')); ?>">
                        <img class="-logo" src="<?= $this->c::escUrl($this->App->Config->©brand['©logo']); ?>"
                             alt="<?= $this->c::escAttr($this->App->Config->©brand['©name']); ?>"
                             data-pin-nopin="true" />
                    </a>
                </div>
            </div>
        </div>

        <div class="eight wide right aligned large screen only column -right-side">
            &nbsp;
        </div>

    </div>
</div>
