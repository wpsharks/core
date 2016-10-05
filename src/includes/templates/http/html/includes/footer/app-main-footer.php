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
<div class="ui container app-main-footer">
    <div class="ui section divider"></div>

    <div class="ui vertical segment">
        <div class="ui stackable divided grid">

            <div class="eight wide column -left-side">
                <p class="-copyright">
                    © <?= date('Y'); ?>
                    <?= $this->c::escHtml($this->App->Config->©brand['©name']); ?>
                    <?= __('"All Rights Reserved"'); ?>
                </p>
            </div>
            <div class="eight wide right aligned large screen only column -right-side">
                <a href="<?= $this->c::escUrl(appUrl('/')); ?>"><?= __('Home'); ?></a>
            </div>

        </div>
    </div>

</div>
